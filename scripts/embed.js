app.controller("mainCtrl", $scope => {

    $scope.embedYoutubeVideo = () => {
        var ugc = document.querySelector("#ugc");
        ugc.innerHTML = ugc.innerHTML.replaceAll(/(https:\/\/(www\.)?youtube\.com\/watch\?v=(.+))/g, "$1 <a href=\"javascript:void(0);\" onclick=\"embedVideo(this, '$3')\">Embed</a>");
        ugc.innerHTML = ugc.innerHTML.replaceAll(/(https:\/\/(www\.)?youtu\.be\/(.+))/g, "$1 <a href=\"javascript:void(0);\" onclick=\"embedVideo(this, '$3')\">Embed</a>");
    }
    $scope.embedYoutubeVideo();

    $scope.openDiscussion = (pid, name) => {
        open("/answer/" + pid + "/" + name, "", "");
    };
});
