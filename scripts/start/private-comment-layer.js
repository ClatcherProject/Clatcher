app.controller("privpostsCtrl", ($scope, $timeout, $http, $sce, constants, emojis, transferPrivPosts, bigPic, showInfo, checkOnline, embed) => {

    document.querySelector("#privateemojis").addEventListener("change", event => {
        var select = event.target;
        var code = select.options[select.selectedIndex].value;

        if(code !== "_blank") {
            $scope.text += " " + code;
        }

        select.selectedIndex = 0;
    });

    $scope.emojis = emojis;

    $scope.showInfo = str => {
        showInfo.show(str);
    }

    $scope.showPic = pic => {
        bigPic.show(pic);
    }

    $scope.timer = 10 * 1000;
    var loadPrivatePostsTimeout = null;
    var timerCountTimeout = null;
    $scope.posts = undefined;
    $scope.empty = false;
    $scope.loadPrivatePosts = t => {

        if(!checkOnline.isOnline()) {
            $scope.showInfo("Du bist offline!");
        }
        
        if(transferPrivPosts.getNeu()) {
            $scope.posts = "";
            transferPrivPosts.notNeu();
        }

        var pid = document.querySelectorAll("#privatecomments .container")[document.querySelectorAll("#privatecomments .container").length-1];

        if(pid == undefined) {
            pid = 0;
        }
        else {
            pid = pid.id;
        }

        if(pid === 0) {
            $scope.posts = undefined;
            $scope.empty = false;
        }

        var uid = document.querySelector("#privatecomments").classList[0];

        $http({
            url: "/private/posts?userid=" + uid + "&postid=" + pid,
            method: "GET"
        }).then(response => {
            response.data.info.forEach(elem => {
                elem.posttext = embed.youtube(elem.posttext);
                elem.posttext = embed.link(elem.posttext);
                elem.posttext = $sce.trustAsHtml(elem.posttext);
            });

            if(response.data.info.length > 0) {
                if($scope.posts === undefined) {
                    $scope.posts = response.data.info;
                }
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
            $scope.showInfo(error.data.info);
        });

        $scope.timer = 10 * 1000;
        loadPrivatePostsTimeout = $timeout($scope.loadPrivatePosts, $scope.timer);
    };
    $scope.loadPrivatePosts($scope.timer);

    $scope.removePost = id => {
        $http({
            url: "/delete/private?pid=" + id,
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

    $scope.updatePrivatePosts = () => {
        if(loadPrivatePostsTimeout !== null) $timeout.cancel(loadPrivatePostsTimeout);
        if(timerCountTimeout !== null) $timeout.cancel(timerCountTimeout);

        $scope.timer = 10 * 1000;
        $scope.loadPrivatePosts($scope.timer);
        $scope.timerCount();
    };

    $scope.postsStatus = "Post";
    $scope.text = "";
    $scope.postComment = () => {
        var fd = new FormData();
        var file = document.querySelector("#private-file-upload").files[0];

        if($scope.text === "" && file === undefined) {
            showInfo.show("No content");
            return;
        }

        if($scope.text.length > constants.MAX_CHARACTERS) {
            showInfo.show(`Max ${constants.MAX_CHARACTERS} characters`);
            return;
        }

        if(file !== undefined && file.size > constants.MAX_POST_FILESIZE) {
            showInfo.show(`Max ${constants.MAX_POST_FILESIZE / (1024*1024)} MB`);

            $scope.text = "";
            document.querySelector("#private-file-upload").value = "";
            $scope.postsStatus = "Post";
            return;
        }

        var uid = document.querySelector("#privatecomments").classList[0];

        if(file !== undefined) 
            fd.append("uimag", file);
        fd.append("utext", $scope.text);
        fd.append("uid", uid);

        if($scope.postsStatus === "Post") {
            $http({
                url: "/post/private",
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
                console.log(response);
                $scope.showInfo(response.data.info);

                $scope.text = "";
                document.querySelector("#private-file-upload").value = "";
                $scope.postsStatus = "Post";
            }, error => {
                $scope.showInfo(error.data.info);

                $scope.text = "";
                document.querySelector("#private-file-upload").value = "";
                $scope.postsStatus = "Post";
            });
        }
    };
});