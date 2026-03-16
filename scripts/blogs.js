app.controller("tab1Ctrl", ($scope, $http, $sce, postActions, embed, easteregg) => {
    $scope.searchtext = "";
    $scope.blogs;
    $scope.empty = false;

    $scope.showInfo = postActions.showInfo;
    $scope.showEmbedCode = postActions.showEmbedCode;
    $scope.openReport = postActions.openReport;
    $scope.sharePublicComment = postActions.sharePublicComment;

    $scope.search = id => {

        if(id === 0) {
            $scope.blogs = undefined;
            $scope.empty = false;
        }

        $http({
            url: "/search/public/comments?id=" + id + "&text=" + $scope.searchtext,
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
                    for(var i = 0; i < response.data.length; i++) {
                        $scope.blogs[$scope.blogs.length] = response.data.info[i];
                    }
                }
            }
            else {
                $scope.empty = true;
            }
        });
    };
    $scope.search(0);

    easteregg.apriljoke();
    easteregg.halloween();
    easteregg.christmas();
    easteregg.firework();
});

app.controller("tab2Ctrl", ($scope, $http, $sce, embed, postActions) => {
    $scope.blogs;
    $scope.empty = false;

    $scope.showInfo = postActions.showInfo;
    $scope.showEmbedCode = postActions.showEmbedCode;
    $scope.openReport = postActions.openReport;
    $scope.sharePublicComment = postActions.sharePublicComment;

    $scope.loadShares = () => {

        $scope.blogs = "";

        $http({
            url: "/load/share/comments",
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

            $scope.empty = true;
            $scope.blogs = response.data.info;
        });
    };
    $scope.loadShares();
});