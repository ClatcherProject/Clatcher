app.controller("webradioCtrl", ($scope, $http, $timeout) => {
    $scope.currentServer = "";
    $scope.servers = [
        {
            name: "ANTENNE BAYERN",
            url: "https://stream.antenne.de/antenne/stream/mp3",
            codec: "audio/mp3"
        },
        {
            name: "1LIVE",
            url: "https://wdr-1live-live.icecast.wdr.de/wdr/1live/live/mp3/128/stream.mp3",
            codec: "audio/mp3"
        },
        {
            name: "Radio Gong",
            url: "https://gong.info/gonglivemp3",
            codec: "audio/mp3"
        },
        {
            name: "Rock Antenne",
            url: "https://stream.rockantenne.de/rockantenne/stream/mp3",
            codec: "audio/mp3"
        },
        {
            name: "Nightride",
            url: "https://stream.nightride.fm/nightride.m4a",
            codec: "audio/m4a"
        }
    ]

    document.querySelector("#radioselector").addEventListener("change", event => {
        var url = event.target.options[event.target.selectedIndex].value;

        if(url !== "_blank") {
            $scope.currentServer = url;
        }
        else {
            $scope.currentServer = "";
        }
    });
});