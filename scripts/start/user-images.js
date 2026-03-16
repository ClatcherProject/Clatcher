app.controller("settingsCtrl", ($scope, $http, $sce, $window, constants, showInfo) => {

    $scope.showInfo = str => {
        showInfo.show(str);
    }

    $scope.logoText = "Change";
    $scope.logoUpload = () => {
        var fd = new FormData();
        var file = document.querySelector("#user-logo").files[0];

        if(file === undefined) {
            showInfo.show("No file");
            return;
        }

        if(file.size > constants.MAX_LOGO_FILESIZE) {
            showInfo.show(`Max ${constants.MAX_LOGO_FILESIZE / (1024*1024)} MB`);
            return;
        }

        fd.append("logo", file);

        $http({
            url: "/upload/logo",
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
                            $scope.logoText = percentComplete + "%";
                        }
                    });
                }
            }
        }).then(response => {
            $scope.showInfo(response.data.info);

            $scope.logoText = "Change";
            $window.location.reload();
        }, error => {
            console.log(error);
            $scope.showInfo(error.data.info);

            $scope.logoText = "Change";
        });

        document.querySelector("#user-logo").value = "";
    };

    $scope.backgroundText = "Change";
    $scope.backgroundUpload = () => {
        var fd = new FormData();
        var file = document.querySelector("#user-background").files[0];

        if(file === undefined) {
            showInfo.show("No file");
            return;
        }

        if(file.size > constants.MAX_BACKGROUND_FILESIZE) {
            showInfo.show(`Max ${constants.MAX_BACKGROUND_FILESIZE / (1024*1024)} MB`);
            return;
        }

        fd.append("background", file);

        $http({
            url: "/upload/background",
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
                            $scope.backgroundText = percentComplete + "%";
                        }
                    });
                }
            }
        }).then(response => {
            document.body.style.backgroundImage = "url(" + response.data.info + ")";

            $scope.backgroundText = "Change";
        }, error => {
            $scope.showInfo(error.data.info);

            $scope.backgroundText = "Change";
        });

        document.querySelector("#user-background").value = "";
    };

    $scope.headerText = "Change";
    $scope.uploadHeader = () => {
        var fd = new FormData();
        var file = document.querySelector("#user-header").files[0];

        if(file === undefined) {
            showInfo.show("No file");
            return;
        }

        if(file.size > constants.MAX_HEADER_FILESIZE) {
            showInfo.show(`Max ${constants.MAX_HEADER_FILESIZE / (1024*1024)} MB`);
            return;
        }

        fd.append("header", file);

        $http({
            url: "/upload/header",
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
                            $scope.headerText = percentComplete + "%";
                        }
                    });
                }
            }
        }).then(response => {
            $scope.showInfo(response.data.info);

            $scope.headerText = "Change";
            $window.location.reload();
        }, error => {
            $scope.showInfo(error.data.info);

            $scope.headerText = "Change";
        });

        document.querySelector("#user-header").value = "";
    };

    $scope.reportedPosts = [];
    $scope.loadReportedPosts = () => {
        $http({
            url: "/reported/comments",
            method: "GET"
        }).then(response => {
            if(typeof(response.data.info) != "string") {
                response.data.info.forEach(elem => {
                    if(elem.reportbild != null) {
                        elem.ext = elem.reportbild.substring(elem.reportbild.indexOf(".")+1);
                    }
                    else {
                        elem.ext = null;
                    }

                    elem.reporttext = $sce.trustAsHtml(elem.reporttext);
                });

                $scope.reportedPosts = response.data.info;
            }
            else
                $scope.showInfo(response.data.info);
        }, error => {
            $scope.showInfo(error.status + " -> " + error.statusText);
        });
    };

    $scope.deleteComment = pid => {
        $http({
            url: "/delete/comment?pid=" + pid,
            method: "DELETE"
        }).then(response => {
            $scope.showInfo(response.data.info);
        }, error => {
            $scope.showInfo(error.data.info);
        });
    };

    $scope.deleteReportComment = pid => {
        $http({
            url: "/delete/report?pid=" + pid,
            method: "DELETE"
        }).then(response => {
            $scope.showInfo(response.data.info);
        }, error => {
            $scope.showInfo(error.data.info);
        });
    };

    $scope.deletePrivPosts = () => {
        $http({
            url: "/delete/privatethread",
            method: "DELETE"
        }).then(response => {
            $scope.showInfo(response.data);
        }, error => {
            $scope.showInfo(error.status + " -> " + error.statusText);
        });
    };

    $scope.deleteAccount = () => {
        if(confirm("Deinen Account wirklich löschen? Falls du einen Storage hast, gehen deine Dateien darin unwiederbringlich verloren!")) {
            $http({
                url: "/delete/account",
                method: "DELETE"
            }).then(response => {
                $window.location.href = "/";
            }, error => {
                $scope.showInfo(error.data.info);
            });
        }
        else {
            $scope.showInfo("Löschen abbrechen!");
        }
    };
});