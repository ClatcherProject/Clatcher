app.controller("settingsCtrl", ($scope, $http, constants) => {
    
    $scope.job = document.querySelector("input[ng-model='job']").dataset.target;

    $scope.location = document.querySelector("input[ng-model='location']").dataset.target;

    if(document.querySelector("input[ng-model='birthday']").dataset.target !== "") {
        $scope.datestring = document.querySelector("input[ng-model='birthday']").dataset.target;
        $scope.day = $scope.datestring.match(/-(\d+)$/)[1];
        $scope.month = $scope.datestring.match(/-(\d+)-/)[1];
        $scope.year = $scope.datestring.match(/^(\d+)-/)[1];
        $scope.birthday = new Date($scope.year, parseInt($scope.month)-1, $scope.day);
    }

    $scope.website = document.querySelector("input[ng-model='website']").dataset.target;

    $scope.interests = document.querySelector("input[ng-model='interests']").dataset.target;

    $scope.eventtitle = document.querySelector("input[ng-model='eventtitle']").dataset.target;

    $scope.eventtext = document.querySelector("textarea[ng-model='eventtext']").dataset.target;

    $scope.updateSettings = () => {
        let fd = new FormData();

        let img = document.querySelector("#eventimage").files[0];

        let birthday = document.querySelector("input[ng-model='birthday']").value;

        if(img !== undefined && img.size > constants.MAX_EVENTIMAGE_FILESIZE) {
            document.querySelector("#settings>.modal-footer").innerHTML = `<div class="text-info">Max ${constants.MAX_EVENTIMAGE_FILESIZE / (1024*1024)} MB</div>`;
            return;
        }

        fd.append("job", $scope.job);
        fd.append("location", $scope.location);
        fd.append("birthday", birthday);
        fd.append("interests", $scope.interests);
        fd.append("website", $scope.website);
        fd.append("eventtitle", $scope.eventtitle);
        fd.append("eventimage", img);
        fd.append("eventtext", $scope.eventtext);

        document.querySelector("#settings>.modal-footer").innerHTML = "<div class=\"spinner-round\"></div>";

        $http({
            url: "/upload/usersettings",
            method: "POST",
            data: fd,
            transformRequest: angular.identiy,
            headers: {
                "Content-Type": undefined
            }
        }).then(response => {
            document.querySelector("#settings>.modal-footer").innerHTML = "<div class=\"text-info\">" + response.data.info + "</div>";
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }, error => {
            document.querySelector("#settings>.modal-footer").innerHTML = "<div class=\"text-info\">" + error.data.info + "</div>";
        });
    };
});

app.controller("tab1Ctrl", ($scope, $http, easteregg, embed) => {

    $scope.bgImage = document.querySelector("header").dataset.image;
    document.querySelector("header").style.backgroundImage = "url(" + $scope.bgImage + ")";

    $scope.openSettings = () => {
        document.querySelector("#settingsModal").checked = true;
    };

    var eventtitle = document.querySelector("#eventtitle");
    if(eventtitle !== null) {
        eventtitle.innerHTML = embed.youtube(eventtitle.innerHTML);
        eventtitle.innerHTML = embed.link(eventtitle.innerHTML);
    }

    var eventtext = document.querySelector("#eventtext");
    if(eventtext !== null) {
        eventtext.innerHTML = embed.youtube(eventtext.innerHTML);
        eventtext.innerHTML = embed.link(eventtext.innerHTML);
    }
    

    $scope.noFriends = false;
    $scope.loadFriends = id => {

        var name = document.querySelector("#friends>div").dataset.target;

        if(id === 0) {
            $scope.friends = undefined;
            $scope.noFriends = false;
        }

        $http({
            url: "/load/" + name + "/friends?fid=" + id,
            method: "GET"
        }).then(response => {
            if(response.data.info.length > 0) {
                if($scope.friends === undefined)
                    $scope.friends = response.data.info;
                else {
                    for(var i = 0; i < response.data.info.length; i++) {
                        $scope.friends[$scope.friends.length] = response.data.info[i];
                    }
                }
            }
            else {
                $scope.noFriends = true;
            }
        });
    }
    $scope.loadFriends(0);

    easteregg.apriljoke();
    easteregg.halloween();
    easteregg.christmas();
    easteregg.firework();
});

app.controller("tab2Ctrl", ($scope, $location, $http, $sce, constants, postActions, embed) => {

    $scope.showInfo = postActions.showInfo;
    $scope.openReport = postActions.openReport;
    $scope.showEmbedCode = postActions.showEmbedCode;
    $scope.sharePublicComment = postActions.sharePublicComment;

    $scope.blogs = undefined;
    $scope.empty = false;
    $scope.loadBlogs = id => {

        var name = $location.absUrl().split("/");
        name = name[name.length-1];

        if(id === 0) {
            $scope.blogs = undefined;
            $scope.empty = false;
        }

        $http({
            url: "/load/public/comments?id=" + id + "&user=" + name,
            method: "GET"
        }).then(response => {
            response.data.info.forEach(elem => {
                if(elem.postbild !== null) {
                    const binary = atob(elem.postbild);
                    const len = binary.length;
                    const bytes = new Uint8Array(len);
                    for(let i = 0; i < len; i++) {
                        bytes[i] = binary.charCodeAt(i);
                    }
                    const blob = new Blob([bytes], { type: elem.mime });
                    const videoUrl = URL.createObjectURL(blob);
                    elem.postbild = videoUrl;
                    
                    if(elem.mime === "video/mp4")
                        elem.video = true;
                    else
                        elem.video = false;
                }

                elem.posttext = embed.youtube(elem.posttext);
                elem.posttext = embed.clatcher(elem.posttext);
                elem.posttext = embed.link(elem.posttext);
                elem.posttext = $sce.trustAsHtml(elem.posttext);
            });
            
            if(response.data.info.length > 0) {
                if($scope.blogs === undefined)
                    $scope.blogs = response.data.info;
                else {
                    for(var i = 0; i < response.data.info.length; i++) {
                        $scope.blogs[$scope.blogs.length] = response.data.info[i];
                    }
                }
            }
            else {
                $scope.empty = true;
            }
        });
    }
    $scope.loadBlogs(0);

    $scope.blogtext = "";
    $scope.uploadInfo = "Post";
    $scope.postBlog = () => {
        var fd = new FormData();

        var file = document.querySelector("#blogfile").files[0];

        if($scope.blogtext === "" && file === undefined) {
            $scope.showInfo("No content");
            return;
        }

        if($scope.blogtext.length > constants.MAX_CHARACTERS) {
            $scope.showInfo(`Max ${constants.MAX_CHARACTERS} characters`);
            return;
        }

        if(file !== undefined && file.size > constants.MAX_VIDEO_FILESIZE) {
            $scope.showInfo(`Max ${constants.MAX_VIDEO_FILESIZE / (1024*1024)} MB`);
            document.querySelector("#blogfile").value = "";
            return;
        }

        fd.append("file", file);
        fd.append("text", $scope.blogtext);

        if($scope.uploadInfo === "Post") {
            $http({
                url: "/post/publiccomment",
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
                                $scope.uploadInfo = percentComplete + "%";
                            }
                        })
                    }
                }
            }).then(response => {
                window.location.reload();
            }, error => {
                $scope.showInfo(error.data.info);
            });
        }
    };

    $scope.deleteComment = blog => {
        var id = blog.postsid;
        
        $http({
            url: "/delete/comment?pid=" + id,
            method: "DELETE"
        }).then(response => {
            $scope.showInfo(response.data.info);
            document.getElementById(id).remove();
        }, error => {
            $scope.showInfo(error.data.info);
        });
    };

    $scope.stopSharing = blog => {
        var pid = blog.postsid;
        var id = blog.sharedid;
        
        $http({
            url: "/delete/share?pid=" + id,
            method: "DELETE"
        }).then(response => {
            $scope.showInfo(response.data.info);
            document.getElementById(pid).remove();
        }, error => {
            $scope.showInfo(error.data.info);
        });
    };
});