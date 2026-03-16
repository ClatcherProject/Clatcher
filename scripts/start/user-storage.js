app.controller("storageCtrl", ($scope, $http, constants, bigPic, showInfo) => {

    $scope.showInfo = str => {
        showInfo.show(str);
    }

    $scope.showPic = pic => {
        bigPic.show(pic);
    }

    $scope.uploadInfo = "Upload";
    $scope.uploadYourFile = () => {
        var fd = new FormData();
        var file = document.querySelector("#storagefile").files[0];

        if(file === undefined) {
            showInfo.show("No file");
            return;
        }

        if(file.size > constants.MAX_STORAGE_FILESIZE) {
            showInfo.show(`Max ${constants.MAX_STORAGE_FILESIZE / (1024*1024)} MB`);

            document.querySelector("#storagefile").value = "";
            $scope.uploadInfo = "Upload";
            return;
        }

        fd.append("ufile", file);

        if($scope.uploadInfo === "Upload") {
            $http({
                url: "/file/upload",
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
                        });
                    }
                }
            }).then(response => {
                $scope.showInfo(response.data.info);

                document.querySelector("#storagefile").value = "";
                $scope.uploadInfo = "Upload";
            }, error => {
                console.log(error);
                $scope.showInfo(error.data.info);

                document.querySelector("#storagefile").value = "";
                $scope.uploadInfo = "Upload";
            });
        }
    }
    
    $scope.files = [];
    $scope.searchfilter = "";
    $scope.loadFiles = () => {
        $http({
            url: "/user/files",
            method: "GET"
        }).then(response => {
            $scope.files = response.data.info;
        }, error => {
            $scope.showInfo(error.data.info);
        });
    };

    $scope.hiddenFiles = () => {
        $scope.files = [];
    };

    $scope.filename = null;
    $scope.ext = null;
    $scope.path = null;
    $scope.file = "";
    $scope.clearFile = () => {
        $scope.filename = null;
        $scope.ext = null;
        $scope.path = null;
        $scope.file = "";
    }

    $scope.getFile = (name, sid) => {
        $scope.clearFile();

        let pos = name.lastIndexOf(".");
        let path = "/show/file?sid=" + sid;
        $scope.ext = name.substr(pos+1, name.length);
        $scope.filename = name;

        document.getElementsByClassName("fileloader")[0].style.display = "block";
        if($scope.ext == "mp3" || $scope.ext == "ogg" || $scope.ext == "opus") {
            $scope.showInfo(`Audio ${$scope.filename} is loading...`);
            fetch(path)
            .then(response => response.blob())
            .then(blob => { 
                $scope.path = URL.createObjectURL(blob);
                document.getElementsByClassName("fileloader")[0].style.display = "none";
            });

            $scope.file = "audio";
            var player = document.querySelector("#audioplayer");
            if(player !== null) player.load();
        }
        else if($scope.ext == "mp4" || $scope.ext == "webm") {
            $scope.showInfo(`Video ${$scope.filename} is loading...`);
            fetch(path)
            .then(response => response.blob())
            .then(blob => {
                $scope.path = URL.createObjectURL(blob);
                document.getElementsByClassName("fileloader")[0].style.display = "none";
            });

            $scope.file = "video";
            var player = document.querySelector("#videoplayer");
            if(player !== null) player.load();
        }
        else if($scope.ext == "png" || $scope.ext == "jpg" || $scope.ext == "jpeg" || $scope.ext == "gif") {
            $scope.showInfo(`Image ${$scope.filename} is loading...`);
            fetch(path)
            .then(response => response.blob())
            .then(blob => {
                $scope.path = URL.createObjectURL(blob);
                document.getElementsByClassName("fileloader")[0].style.display = "none";
            });

            $scope.file = "image";
        }
        else {
            $scope.file = "file";
            $scope.path = path;
            $scope.showInfo("Shows file \"" + $scope.filename + "\"");
            document.getElementsByClassName("fileloader")[0].style.display = "none";
        }
    };

    $scope.downloadFile = () => {
        const blobUrl = document.getElementById("download-btn").getAttribute("data-bloburl");
        const filename = document.getElementById("download-btn").getAttribute("data-filename");

        const link = document.createElement("a");
        link.href = blobUrl;
        link.download = filename;
        link.click();
        URL.revokeObjectURL(blobUrl);
    };

    $scope.shareFile = (name, sid) => {
        open(`/share/file?uid=${name}&sid=${sid}`, "", "width=500,height=800");
    };

    $scope.removeFile = sid => {
        if(confirm("Wirklich löschen?")) {
            $http({
                url: "/remove/file",
                method: "DELETE",
                data: {
                    sid: sid
                },
                headers: {
                    "Content-Type": "application/json"
                }
            }).then(response => {
                $scope.showInfo(response.data.info);
            }, error => {
                $scope.showInfo(error.data.info);
            });
        }
    };
});