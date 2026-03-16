app.controller("tab1Ctrl", $scope => {

    $scope.bgImage = document.querySelector("header").dataset.image;
    document.querySelector("header").style.backgroundImage = "url(" + $scope.bgImage + ")";
});