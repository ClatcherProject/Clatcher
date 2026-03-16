app.controller("loginCtrl", ($scope, $http) => {

    $scope.mail = "";
    $scope.password = "";
    $scope.info = "";

    $scope.keyLogin = () => {
        if(window.event.keyCode == 13) {
            $scope.sendlogin();
        }
    };

    $scope.clickLogin = () => {
        $scope.sendlogin();
    };

    $scope.sendlogin = () => {
        if($scope.username === "" || $scope.password === "") {
            $scope.info = "Please fill in all fields";
            return;
        }

        $http({
            url: "/clatcher/log/in",
            method: "POST",
            data: {
                umail: $scope.mail,
                upass: $scope.password
            },
            headers: {
                "Content-Type": "application/json"
            }
        }).then(response => {
            window.location.href = "/private/space";
        }, error => {
            $scope.info = error.data.info;
        });
    };
});

app.controller("registerCtrl", ($scope, $http, constants) => {

    $scope.username = "";
    $scope.mail = "";
    $scope.password = "";
    $scope.repeatedpass = "";
    $scope.info = "";

    $scope.keyRegister = () => {
        if(window.event.keyCode === 13) {
            $scope.sendRegister();
        }
    };

    $scope.clickRegister = () => {
        $scope.sendRegister();
    };

    $scope.sendRegister = () => {

        if($scope.username.length < constants.MIN_USERNAME_LENGTH || $scope.username.length > constants.MAX_USERNAME_LENGTH) {
            $scope.info = "Username length must be between 4 and 30 characters";
            return;
        }

        $http({
            url: "/clatcher/sign/up",
            method: "PUT",
            data: {
                uname: $scope.username,
                umail: $scope.mail,
                upass1: $scope.password,
                upass2: $scope.repeatedpass,
            },
            headers: {
                "Content-Type": "application/json"
            }
        }).then(response => {
            window.location.href = "/private/space";
        }, error => {
            $scope.info = error.data.info;
        });

    };
});

app.controller("tab1Ctrl", ($scope, $http, easteregg) => {

    easteregg.apriljoke();
    easteregg.halloween();
    easteregg.christmas();
    easteregg.firework();
});

app.controller("tab2Ctrl", ($scope, $window, $http, $sce, easteregg) => {

    $scope.loadUsers = () => {
        $http({
            url: "/show/user/number",
            method: "GET"
        }).then(response => {
            $scope.users = response.data.info;
        });

    };
    $scope.loadUsers();

    $scope.username = "";
    $scope.info = "";
    $scope.styles = "";

    $scope.checkOnline = () => {
        $http({
            url: "/check/signed/in",
            method: "GET"
        }).then(response => {
            if(response.data.info)
                window.location.href = "/private/space";
        });
    };
    $scope.checkOnline();

    $scope.searchUser = () => {

        easteregg.tilt($scope);
        easteregg.barellRoll($scope);
        easteregg.disco($scope);
        easteregg.matrix($scope);

        $http({
            url: "/load/usersite/information?username=" + (($scope.username === "disco") ? "" : $scope.username),
            method: "GET"
        }).then(response => {
            
            $scope.info = response.data.info.length;

            for(var i = 0; i < response.data.info.length; i++) {
                if(response.data.info[i].website != null && response.data.info[i].website != "") {
                    response.data.info[i].job = $sce.trustAsHtml(response.data.info[i].job);
                    response.data.info[i].location = $sce.trustAsHtml(response.data.info[i].location);
                }
            }
                
            $scope.userseiten = response.data.info;
        });
    }
    $scope.searchUser("");

    $scope.openUsersite = username => {
        $window.location.href = "/" + username;
    };
});