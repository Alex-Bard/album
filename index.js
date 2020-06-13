var app = angular.module('index', []);
app.controller('filesArea', function($scope,$http) {
    //$scope.getPhotos = function() {
        $http.get("files.php")
            .then(function (response) {
                $scope.photos = response.data;
                console.log(response.data);
            });

        $scope.lastName = "Doe";
    //}
});