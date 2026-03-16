app.controller("gamesCtrl", ($scope, $http, showInfo) => {

    $scope.showInfo = str => {
        showInfo.show(str);
    }

    document.querySelector("#gameselector").addEventListener("change", event => {
        var select = event.target;
        var index = parseInt(select.options[select.selectedIndex].value);
        var current_game = document.querySelector("#current-game");

        switch(index) {
            case 0:
                current_game.innerHTML = "";
                break;
            case 1:

                current_game.innerHTML = `
                    <iframe style="border: none;" src="/Games/Mario/game.html" width="640" height="480"></iframe>
                `;

                break;
            default:
                $scope.showInfo("Ungültige Eingabe!");
        }
    });
});