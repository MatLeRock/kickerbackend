var app = angular.module('tutorialApp', ['ngRoute']);



app.factory('Team1', function() {
    var team1 = [];
    return {
        getTeam: function() {
            return team1;
        },
        addMember: function(member, event) {
            var row = $(event.target).parent().parent();
            row.hide();
            member.rowElement = row;
            team1.push(member);
        },
        removeMember: function(index) {
            if (index > -1) {
                var member = team1.splice(index, 1)[0];
                $(member.rowElement).show();
            }
        },
        reset: function() {
            for (var i = 0; i < team1.length; i++) {
                if (team1[i].rowElement) {
                    $(team1[i].rowElement).show();
                }
            }
            team1 = [];
        }
    };
});

app.factory('Team2', function() {
    var team2 = [];
    return {
        getTeam: function() {
            return team2;
        },
        addMember: function(member, event) {
            var row = $(event.target).parent().parent();
            row.hide();
            member.rowElement = row;
            team2.push(member);
        },
        removeMember: function(index) {
            if (index > -1) {
                var member = team2.splice(index, 1)[0];
                $(member.rowElement).show();
            }
        },
        reset: function() {
            for (var i = 0; i < team2.length; i++) {
                if (team2[i].rowElement) {
                    $(team2[i].rowElement).show();
                }
            }
            team2 = [];
        }
    };
});


app.factory('Games', function() {
    var games = [];
    return {
        getGames: function() {
            return games;
        },
        setGames: function(gamesdata) {
            games = gamesdata;
        }
    };
});


app.controller('UsersCtrl', function($scope, $http) {
    $http.get('/user').then(function(userListResponse) {
        $scope.users = userListResponse.data.data;
    });
});

app.controller('GamesCtrl', function($scope, $http, Games) {
    $scope.loading = true;
    $scope.games = Games;
    var params = {};
    params.index = 0;
    params.size = 10;

    var resp = $http.get('/game', {"params": params});
    window.console.info(resp);

    resp.then(function(gamesListResponse) {
        $scope.loading = false;
        $scope.games.setGames(gamesListResponse.data.data);
        window.console.info($scope.games.getGames());
    });
});

app.controller('GamesPagerCtrl', function($scope, $http, Games) {

    $scope.games = Games;
$scope.currentIndex = 0;
$scope.currentSize = 10;
    $scope.next = function() {
        window.console.info($scope);
            $scope.currentIndex++;

        var params = {};
        params.index = $scope.currentIndex;
        params.size = $scope.currentSize;


        var resp = $http.get('/game', {"params": params});
        window.console.info(resp);

        resp.then(function(gamesListResponse) {
            $scope.loading = false;
            $scope.games.setGames(gamesListResponse.data.data);
            window.console.info($scope.games.getGames());
            
        });
    };

    $scope.prev = function() { $scope.currentIndex--;
        var params = {};
        params.index = $scope.currentIndex;
        params.size = $scope.currentSize;
       
        var resp = $http.get('/game', {"params": params});
        window.console.info(resp);

        resp.then(function(gamesListResponse) {
            $scope.loading = false;
            $scope.games.setGames(gamesListResponse.data.data);
            window.console.info($scope.games.getGames());
            
        });
    }
});


app.controller('TeamTableCtrl', function($scope, $http) {
    $scope.loading = true;
    $scope.$on('ngRepeatFinished', function(ngRepeatFinishedEvent) {
        jQuery(".name").popover({
            html: true,
            trigger: 'hover',
            content: '<img src="/img/zf2-logo.png" />'
        });
    });


    $http.get('/games-table').then(function(teamTableResponse) {
        $scope.loading = false;
        $scope.teamsTable = teamTableResponse.data.data;

    });
});

app.controller('UserTableCtrl', function($scope, $http) {
    $scope.loading = true;
    $scope.$on('ngRepeatFinished', function(ngRepeatFinishedEvent) {
        jQuery(".name").popover({
            html: true,
            trigger: 'hover',
            content: '<img src="/img/zf2-logo.png" />'
        });
    });


    $http.get('/users-table').then(function(usersTableResponse) {
        $scope.loading = false;
        $scope.usersTable = usersTableResponse.data.data;

    });
});

app.directive('onFinishRender', function($timeout) {
    return {
        restrict: 'A',
        link: function(scope, element, attr) {
            if (scope.$last === true) {
                $timeout(function() {
                    scope.$emit('ngRepeatFinished');
                });
            }
        }
    }
});


app.config(function($routeProvider) {
    $routeProvider
            .when('/index', {templateUrl: 'tpls/index.html', controller: "MyCtrl1"})
            .when('/users', {templateUrl: 'tpls/users.html', controller: "MyCtrl1"})
            .when('/new-game', {templateUrl: 'tpls/new-game.html', controller: "MyCtrl1"})
            .when('/new-user', {templateUrl: 'tpls/new-user.html', controller: "MyCtrl1"})
            .when('/about', {template: 'Über unsere Pizzeria', controller: "MyCtrl1"})
            .otherwise({redirectTo: '/index'});


});

app.controller('MyCtrl1', function($scope) {

    $scope.$on('$locationChangeStart', function(event, next, current) {
//        window.console.info(current);
    });
});



app.controller('NewUserController', function($scope, $http) {

    $scope.save = function(user) {
        var res = $http.post('/user', user);
        res.success(function(data, status, headers, config) {
            $scope.message = data;
        });
        res.error(function(data, status, headers, config) {
            alert("failure message: " + JSON.stringify({data: data}));
        });
    };
});

app.controller('NewGameController', function($scope, $http, $route, Team1, Team2) {
    $scope.team1 = Team1;
    $scope.team2 = Team2;

    $scope.save = function(game) {
        if (typeof game == "undefined") {
            $scope.message = "Result missing!";
            return;
        }
        game.team1 = Team1.getTeam();
        game.team2 = Team2.getTeam();
        if (!(game.team1.length > 0) || !(game.team1.length > 0)) {
            $scope.message = "Some members missing!";
            return;
        }

        var res = $http.post('/game', game);
        res.success(function(data, status, headers, config) {
            jQuery("#myModal").trigger("click.dismiss.modal");
            jQuery("#myModal").on('hidden.bs.modal', function() {
                $route.reload();
            });
//            
            $scope.message = "Spitze!";
            $scope.game = {};
            $scope.team1.reset();
            $scope.team2.reset();

        });
        res.error(function(data, status, headers, config) {
            $scope.message = "Kagge!";
            $scope.team1.reset();
            $scope.team2.reset();
        });
    };

    $scope.$on("$destroy", function() {
        $scope.team1.reset();
        $scope.team2.reset();
    });
});

app.controller('UserPickerController', function($scope, $http, Team1, Team2) {

    $scope.team1 = Team1;
    $scope.team2 = Team2;
    $scope.loading = true;
    $http.get('/user').then(function(userListResponse) {
        $scope.loading = false;
        $scope.users = userListResponse.data.data;
    });
});

app.directive('loading', function() {
    return {
        restrict: 'E',
        replace: true,
        template: '<div class="loading"><img width=100 height=100 src="img/loader.gif"/>LOADING...</div>',
        link: function(scope, element, attr) {
            scope.$watch('loading', function(val) {
                if (val)
                    $(element).show();
                else
                    $(element).hide();
            });
        }
    }
});




