app.controller("userlayerCtrl", ($scope, $http, transferPrivPosts, showInfo) => {

    $scope.showInfo = str => {
        showInfo.show(str);
    }

    $scope.searchUser = () => {
        if(window.event.keyCode >= 33 || window.event.keyCode <= 126) {
            var username = document.querySelector("#searchuserfield").value;

            if(username != "") {
                $http({
                    url: "/load/user?uname=" + username,
                    method: "GET"
                }).then(response => {
                    $scope.users = response.data.info;
                }, error => {
                    $scope.showInfo(error.status + " -> " + error.statusText);
                });
            }
            else {
                $scope.users = "";
            }
        }
    };

    $scope.loadUserThread = username => {
        transferPrivPosts.isNeu();

        $http({
            url: "/load/userthread?uname=" + username,
            method: "GET"
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

    $scope.sendAnfrage = id => {
        $http({
            url: "/send/request",
            method: "PUT",
            data: {
                uid: id
            },
            headers: {
                "Content-Type": "application/json"
            }
        }).then(response => {
            $scope.showInfo(response.data.info);
        }, error => {
            $scope.showInfo(error.data.info);
        });
    };

    $scope.toggleOP = uid => {
        $http({
            url: "/toggle/op?uid=" + uid,
            method: "POST"
        }).then(response => {
            $scope.showInfo(response.data.info);
        });
    }

    $scope.toggleAdmin = uid => {
        $http({
            url: "/toggle/admin?uid=" + uid,
            method: "POST"
        }).then(response => {
            $scope.showInfo(response.data.info);
        });
    }

    $scope.toggleBan = uid => {
        $http({
            url: "/toggle/ban?uid=" + uid,
            method: "POST"
        }).then(response => {
            $scope.showInfo(response.data.info);
        })
    }

    $scope.loadFriends = id => {
        var name = document.querySelector("#greeting").innerText;
        name = name.substr(name.indexOf(" ")+1, name.length);

        $http({
            url: `/load/${name}/friends?fid=${id}`,
            method: "GET"
        }).then(response => {

            if(id === 0) {
                var friends = [];
            }
            else {
                var friends = $scope.friends;
            }
            
            if(response.data.info.length > 0) {
                for(var x in response.data.info) {
                    friends.push(response.data.info[x]);
                }
                $scope.friends = friends;

            }
            else {
                if(document.querySelector("#more-friends").innerHTML !== "") {
                    document.querySelector("#more-friends").innerHTML = "";
                }
                else {
                    document.querySelector("#more-friends").innerHTML = "<p><i>Keine Freunde</i></p>";
                }
            }
        }, error => {
            $scope.showInfo(error.status + " -> " + error.statusText);
        });
    };
    $scope.loadFriends(0);

    // $scope.openShareWindow = uid => {
    //     open("/share/file?uid=" + uid, "", "height=500,width=800");
    // };

    $scope.removeFriend = name => {

        if(confirm("Diesen User aus deiner Freundesliste entfernen?")) {
            $http({
                url: "/" + name + "/removefriend",
                method: "DELETE"
            }).then(response => {
                $scope.showInfo(response.data.info);

                for(var i in $scope.friends) {
                    if($scope.friends[i].username === name) {
                        $scope.friends.splice(i, 1);
                        break;
                    }
                }
            }, error => {
                $scope.showInfo(error.data.info);
            });
        }
    };

    $scope.loadRequests = id => {
        $http({
            url: "/" + id + "/requests",
            method: "GET"
        }).then(response => {
            
            if(id === 0) {
                var anfragen = [];
            }
            else {
                var anfragen = $scope.anfragen;
            }

            if(response.data.info.length > 0) {
                for(var x in response.data.info) {
                    anfragen.push(response.data.info[x]);
                }
                $scope.anfragen = anfragen;
            }
            else {
                if(document.querySelector("#more-requests").innerHTML !== "") {
                    document.querySelector("#more-requests").innerHTML = "";
                }
                else {
                    document.querySelector("#more-requests").innerHTML = "<p><i>Keine Anfragen</i></p>";
                }
            }
        }, error => {
            $scope.showInfo(error.status + " -> " + error.statusText);
        });
    };
    $scope.loadRequests(0);

    $scope.acceptRequest = id => {
        $http({
            url: "/accept/request",
            method: "PUT",
            data: {
                uid: id
            },
            headers: {
                "Content-Type": "application/json"
            }
        }).then(response => {
            $scope.showInfo(response.data.info);
            
            for(var i in $scope.anfragen) {
                if(id === $scope.anfragen[i].userid) {
                    $scope.anfragen.splice(i, 1);
                }
            }
        }, error => {
            $scope.showInfo(error.data.info);
        });
    };

    $scope.refuseRequest = id => {
        $http({
            url: "/refuse/request",
            method: "PUT",
            data: {
                uid: id
            },
            headers: {
                "Content-Type": "application/json"
            }
        }).then(response => {
            $scope.showInfo(response.data.info);
            
            for(var i in $scope.anfragen) {
                if(id === $scope.anfragen[i].userid) {
                    $scope.anfragen.splice(i, 1);
                }
            }
        }, error => {
            $scope.showInfo(error.status + " -> " + error.statusText);
        });
    }
});