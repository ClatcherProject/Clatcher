app.controller("postsCtrl", ($scope, $timeout, $http, $sce, bigPic, constants, emojis, showInfo, checkOnline, embed) => {

    document.querySelector("#publicemojis").addEventListener("change", event => {
        var select = event.target;
        var code = select.options[select.selectedIndex].value;

        if(code !== "_blank") {
            $scope.text += " " + code;
        }

        select.options.selectedIndex = 0;
    });

    $scope.emojis = emojis;

    $scope.showInfo = str => {
        showInfo.show(str);
    }

    $scope.showPic = pic => {
        bigPic.show(pic);
    }
    
    $scope.timer = 10 * 1000;
    var loadPostsTimeout = null;
    var timerCountTimeout = null;
    $scope.posts = undefined;
    $scope.empty = false;
    $scope.loadPosts = t => {

        if(!checkOnline.isOnline()) {
            $scope.showInfo("Du bist offline!");
        }

        var id = document.querySelectorAll("#comments .container")[document.querySelectorAll("#comments .container").length-1];

        if(id === undefined) {
            id = 0;
        }
        else {
            id = parseInt(id.id);
        }

        if(id === 0) {
            $scope.posts = undefined;
            $scope.empty = false;
        }

        $http({
            url: "/public/posts?postid=" + id,
            method: "GET"
        }).then(response => {
            
            response.data.info.forEach(elem => {
                elem.posttext = embed.youtube(elem.posttext);
                elem.posttext = embed.link(elem.posttext);
                elem.posttext = $sce.trustAsHtml(elem.posttext);
            });

            if(response.data.info.length > 0) {
                if($scope.posts === undefined)
                    $scope.posts = response.data.info;
                else {
                    for(var i = 0; i < response.data.info.length; i++) {
                        $scope.posts[$scope.posts.length] = response.data.info[i];
                    }
                }
            }
            else {
                $scope.empty = true;
            }
        }, error => {
            $scope.showInfo(error.status + " -> " + error.statusText);
        });

        $scope.timer = 10 * 1000;
        loadPostsTimeout =  $timeout($scope.loadPosts, $scope.timer);
    };
    $scope.loadPosts($scope.timer);

    $scope.removePost = id => {
        $http({
            url: "/delete/public?pid=" + id,
            method: "DELETE"
        }).then(response => {
            $scope.showInfo(response.data.info);
            $scope.posts = $scope.posts.filter(post => post.postsid != id);
        }, error => {
            $scope.showInfo(error.data.info);
        });
    }

    $scope.timerCount = () => {
        $scope.timer -= 1000;

        timerCountTimeout = $timeout($scope.timerCount, 1000);
    };
    $scope.timerCount();

    $scope.updatePosts = () => {
        if(loadPostsTimeout !== null) $timeout.cancel(loadPostsTimeout);
        if(timerCountTimeout !== null) $timeout.cancel(timerCountTimeout);

        $scope.timer = 10 * 1000;
        $scope.loadPosts($scope.timer);
        $scope.timerCount();
    };

    $scope.postsStatus = "Post";
    $scope.text = "";
    $scope.postComment = () => {
        let fd = new FormData();
        let file = document.querySelector("#file-upload").files[0];

        if($scope.text === "" && file === undefined) {
            showInfo.show("No content");
            return;
        }

        if($scope.text.length > constants.MAX_CHARACTERS) {
            showInfo.show(`Max ${constants.MAX_CHARACTERS} characters`);
            return;
        }

        if(file !== undefined && file.size > constants.MAX_POST_FILESIZE) {
            showInfo.show(`Max ${constants.MAX_POST_FILESIZE / (1024*1024)} MB`)

            $scope.text = "";
            document.querySelector("#file-upload").value = "";
            $scope.postsStatus = "Post";
            return;
        }

        if(file !== undefined) 
            fd.append("uimag", file);
        fd.append("utext", $scope.text);

        if($scope.postsStatus === "Post") {
            $http({
                url: "/post/public",
                method: "POST",
                data: fd,
                transformRequest: angular.identiy,
                headers: {
                    "Content-Type": undefined
                },
                uploadEventHandlers: {
                    progress: evt => {
                        evt.target.addEventListener("progress", e => {
                            if(e.lengthComputable) {
                                var percentComplete = Math.round(e.loaded / e.total * 100);
                                $scope.postsStatus = percentComplete + "%";
                            }
                        });
                    }
                }
            }).then(response => {
                $scope.showInfo(response.data.info);

                $scope.text = "";
                document.querySelector("#file-upload").value = "";
                $scope.postsStatus = "Post";
            }, error => {
                $scope.showInfo(error.data.info);
                
                $scope.text = "";
                document.querySelector("#file-upload").value = "";
                $scope.postsStatus = "Post";
            })
        }
    };
});