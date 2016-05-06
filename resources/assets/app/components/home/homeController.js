'use strict';

angular.module('app')
.config(function($stateProvider, appdir) {
    $stateProvider.state('home', {
        url: '/home',
        templateUrl: appdir + '/components/home/home.html',
        controller: 'homeController'
    });
})
.controller('homeController', function($scope) {

    $scope.test = 'Yop';

});
