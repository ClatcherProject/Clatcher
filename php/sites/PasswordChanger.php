<?php
class PasswordChanger {
    public static function load(User $user) {
        ?>
        <!DOCTYPE html>
        <html lang="de">
            <head>
                <title>Clatcher - Change Password</title>
                <meta charset="utf8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="icon" type="image/x-icon" href="/icon.ico">
                <link rel="stylesheet" href="/styles/index.css">
                <script src="/scripts/angular.min.js"></script>
            </head>
            <body class="bg-darkblue text-light">
                <div class="padding-middle">
                    <?php if($user->isActivated() != 1): ?>
                        <p>Please confirm your E-Mail</p>
                    <?php else: ?>
                        <div ng-app="changeapp" ng-controller="changectrl">
                            <p>
                                <label>
                                    Old password: <input ng-model="oldpass" type="password" class="inputfield bg-dark" placeholder="Current password">
                                </label><br>
                                <label>
                                    New password: <input ng-model="newpass1" type="password" class="inputfield bg-dark" placeholder="New password">
                                </label><br>
                                <label>
                                    Repeat password: <input ng-model="newpass2" type="password" class="inputfield bg-dark" placeholder="New password repeat">
                                </label><br>
                                <button ng-click="changePass();" class="stylish-button">Change</button>
                                <p ng-bind="info" class="text-success"></p>
                                <p ng-bind="error" class="text-danger"></p>
                            </p>
                        </div>

                        <script>
                            const changeapp = angular.module("changeapp", []);

                            changeapp.controller("changectrl", ($scope, $http, $window) => {
                                $scope.oldpass = "";
                                $scope.newpass1 = "";
                                $scope.newpass2 = "";

                                $scope.info = "";
                                $scope.error = "";

                                $scope.changePass = () => {
                                    $http({
                                        url: "/change/password",
                                        method: "PUT",
                                        data: {
                                            oldpass: $scope.oldpass,
                                            newpass1: $scope.newpass1,
                                            newpass2: $scope.newpass2
                                        },
                                        headers: {
                                            "Content-Type": "application/json"
                                        }
                                    }).then(response => {
                                        $scope.error = "";
                                        $scope.info = response.data.info;
                                    }, error => {
                                        $scope.info = "";
                                        $scope.error = error.data.info;
                                    });
                                }
                            });
                        </script>
                    <?php endif; ?>
                </div>
            </body>
        </html>
        <?php
    }
}
?>