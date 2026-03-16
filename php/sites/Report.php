<?php
class Report {
    public static function load(mysqli $conn, User $user, int $pid) {
        ?>
        <!DOCTYPE html>
        <html lang="de">
            <head>
                <title>Report Post Nr. <?php echo($pid); ?></title>
                <meta charset="utf8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="stylesheet" type="text/css" href="/styles/index.css">
                <script src="/scripts/angular.min.js"></script>
            </head>
            <body class="bg-darkblue text-light">
                <div ng-app="reportapp" ng-controller="reportctrl" class="padding-middle">
                    <p class="text-success" ng-bind="info"></p>
                    <p class="text-danger" ng-bind="error"></p>

                    <p>Gib bitte eine Begründung an:</p>
                    <div class="select-wrapper">
                        <i class="fas fa-caret-down"></i>
                        <input id="pid" type="hidden" value="<?php echo($pid); ?>">
                        <select id="grund" name="grund">
                            <option value="Dieser Post ist beleidigend">Dieser Post ist beleidigend</option>
                            <option value="Dieser Post ist diskriminierend">Dieser Post ist diskriminierend</option>
                            <option value="Dieser Post enthält persönliche Informationen">Dieser Post enthält persönliche Informationen</option>
                            <option value="Dieser Post kommt von einem gehackten Useraccount">Dieser Post kommt von einem gehackten Useraccount</option>
                            <option value="Der postende User verbreitet Malware über die Storage-Funktion">Der postende User verbreitet Malware über die Storage-Funktion</option>
                            <option value="Dieser Post enthält pornografische Inhalte">Dieser Post enthält pornografische Inhalte</option>
                            <option value="Dieser Post verstößt gegen deutsches oder europäisches Recht">Dieser Post verstößt gegen deutsches oder europäisches Recht</option>
                        </select>
                    </div><br>
                    <input ng-click="report();" type="button" class="stylish-button" value="Report">
                </div>

                <script>
                    const reportApp = angular.module("reportapp", []);

                    reportApp.controller("reportctrl", ($scope, $http) => {
                        $scope.info = "";
                        $scope.error = "";

                        $scope.report = () => {
                            const grund = document.getElementById("grund").value;
                            const pid = document.getElementById("pid").value;
                            
                            $http({
                                url: "/report/comment",
                                method: "POST",
                                data: {
                                    pid: pid,
                                    grund: grund
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
            </body>
        </html>
        <?php
    }
}
?>