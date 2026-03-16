app.controller("mailconfirmCtrl", ($scope, $http, $timeout, showInfo) => {

    $scope.activateCode = true;
    $scope.code = "";
    $scope.actCode = "";
    $scope.sendcode = () => {
        $http({
            url: "/send/activatecode",
            method: "PUT"
        }).then(response => {
            $scope.activateCode = true;
            $scope.actCode = response.data.info.split("").reverse().join("");
        }, error => {
            showInfo.show(error.data.info);
        });
    };
    $scope.sendcode();

    $scope.valid = code => {
        if(window.event.keyCode === 13) {
            $http({
                url: "/activate/email?ucode=" + code,
                method: "PUT"
            }).then(response => {
                showInfo.show(response.data.info);

                $scope.activateCode = false;

                $timeout(() => { window.location.reload(); }, 3000);
            }, error => {
                showInfo.show(error.data.info);
            });
        }
    }
});

app.controller("userInfoCtrl", ($scope, $http, $timeout, $window, transferPrivPosts, showInfo) => {

    $scope.showInfo = str => {
        showInfo.show(str);
    }

    document.querySelector("#themeselector").addEventListener("change", event => {
        var select = event.target;
        var choice = select.options[select.selectedIndex].value;

        if(choice === "light") {
            document.querySelectorAll("[class*='clatcher-darktheme']").forEach(elem => {
                elem.classList.remove("clatcher-darktheme");
                elem.classList.add("clatcher-lighttheme");
            })
        }
        else if(choice === "dark") {
            document.querySelectorAll("[class*='clatcher-lighttheme']").forEach(elem => {
                elem.classList.remove("clatcher-lighttheme");
                elem.classList.add("clatcher-darktheme");
            })
        }
    });

    $scope.loadBackground = () => {
        $http({
            url: "/load/background",
            method: "GET"
        }).then(response => {
            if(response.data != "")
                document.body.style.backgroundImage = "url(" + response.data.info + ")";
            else
                document.body.style.backgroundImage = "url('/pics/background.png')";
        }, error => {
            $scope.showInfo(error.status + " -> " + error.statusText);
        });
    };
    $scope.loadBackground();

    $scope.activateStorage = () => {
        $http({
            url: "/activate/storage",
            method: "PUT"
        }).then(response => {
            $scope.showInfo(response.data.info);

            $timeout(function() { location.reload(); }, 3000);
        }, error => {
            $scope.showInfo(error.data.info);
        });
    };

    $scope.activatePublicSite = () => {
        $http({
            url: "/activate/publicsite",
            method: "POST"
        }).then(response => {
            $scope.showInfo(response.data.info);
            $timeout(function() { location.reload(); }, 3000);
        }, error => {
            $scope.showInfo(error.data.info);
        });
    };

    $scope.changePass = () => {
        open("/change/window", "", "resizeable=0,width=850,height=400");
    };

    $scope.loadUserThread = name => {
        transferPrivPosts.isNeu();
    
        $http({
            url: "/load/userthread?uname=" + name,
            method: "GET",
        }).then(response => {
    
                document.querySelector("#privatecomments").classList = [response.data.info[0].userid];
    
                if(response.data.info[0].userheader != null) {
                    document.querySelector("#privateheader").src = response.data.info[0].userheader;
                    document.querySelector("#privateheader").classList.remove("invisible");
                }
                else {
                    document.querySelector("#privateheader").classList.add("invisible");
                }
    
                document.querySelector("#private-comment-layer-title").innerText = "Private User Posts | " + response.data.info[0].username;
        }, error => {
            $scope.showInfo(error.status + " -> " + error.statusText);
        });
    };

    $scope.logout = () => {

        $http({
            url: "/log/out",
            method: "DELETE"
        }).then(response => {
            $scope.showInfo(response.data.info);
            $timeout(() => { window.location.href = "/"; }, 3000);
        }, error => {
            $scope.showInfo(error.data.info);
        });
    };
});