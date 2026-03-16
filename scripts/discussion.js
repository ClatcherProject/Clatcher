app.controller("tab1Ctrl", ($scope, embed, easteregg) => {

    var ugc = document.querySelector("#ugc");
    ugc.innerHTML = embed.youtube(ugc.innerHTML);
    ugc.innerHTML = embed.clatcher(ugc.innerHTML);
    ugc.innerHTML = embed.link(ugc.innerHTML);

    $scope.bgImage = document.querySelector("header").dataset.image;
    document.querySelector("header").style.backgroundImage = "url(" + $scope.bgImage + ")";

    easteregg.apriljoke();
    easteregg.halloween();
    easteregg.christmas();
    easteregg.firework();
});

app.controller("tab2Ctrl", ($scope, $http, $sce, constants, postActions) => {

    $scope.showInfo = postActions.showInfo;

    $scope.answers = undefined;
    $scope.empty = false;

    $scope.loadPublicAnswers = id => {
        var data = document.querySelector("#mainPost").dataset.target;
        var pid = data.split(", ")[0];
        var name = data.split(", ")[1];

        if(id === 0) {
            $scope.answers = undefined;
            $scope.empty = false;
        }

        $http({
            url: "/load/public/answers?user=" + name + "&pid=" + pid + "&id=" + id,
            method: "GET"
        }).then(response => {
            response.data.info.forEach(elem => {
                elem.posttext = $sce.trustAsHtml(elem.posttext);
            });

            if(response.data.info.length > 0) {
                if($scope.answers === undefined)
                    $scope.answers = response.data.info;
                else {
                    for(var i = 0; i < response.data.info.length; i++) {
                        $scope.answers[$scope.answers.lengths] = response.data.info[i];
                    }
                }
                console.log(response.data.info);
            }
            else {
                $scope.empty = true;
            }
        });
    };
    $scope.loadPublicAnswers(0);

    $scope.loadMorePublicAnswers = id => {
        var data = document.querySelector("#mainPost").dataset.target;
        var pid = data.split(", ")[0];
        var name = data.split(", ")[1];

        $http({
            url: "/load/morepublic/answers?user=" + name + "&pid=" + pid + "&id=" + id,
            method: "GET"
        }).then(response => {
            response.data.info.forEach(elem => {
                elem.posttext = $sce.trustAsHtml(elem.posttext);
            });

            if(response.data.info.length > 0) {
                for(var i = 0; i < response.data.info.length; i++) {
                    $scope.answers[$scope.answers.length] = response.data.info[i];
                }
            }
            else {
                $scope.empty = true;
            }
        });
    };

    $scope.posttext = "";
    $scope.postPublicAnswer = () => {
        if($scope.posttext.length > constants.MAX_CHARACTERS) {
            $scope.showInfo(`Max ${constants.MAX_CHARACTERS} characters`);
            return;
        }

        var pid = document.querySelector("#mainPost").dataset.target.split(", ")[0];

        var fd = new FormData();
        fd.append("text", $scope.posttext);

        $http({
            url: "/post/publicanswer?pid=" + pid,
            method: "POST",
            data: fd,
            transformRequest: angular.identiy,
            headers: {
                "Content-Type": undefined
            }
        }).then(response => {
            $scope.loadPublicAnswers(0);

            $scope.posttext = "";
        }, error => {
            $scope.showInfo(error.data.info);
        });
    }

    $scope.deleteAnswer = blog => {
        var id = blog.postsid;

        $http({
            url: "/delete/answer?aid=" + id,
            method: "DELETE"
        }).then(response => {
            $scope.showInfo(response.data.info);
            document.getElementById(id).remove();
        }, error => {
            $scope.showInfo(error.data.info);
        });
    };
});