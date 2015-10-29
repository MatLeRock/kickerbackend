angular.module('tutorialApp').controller('NewUserController', function($scope, $http) {

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


