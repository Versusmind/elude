'use strict';

angular.module('app')
.config(function($stateProvider, appdir) {
    $stateProvider.state('admin-users', {
        url: '/admin/users',
        templateUrl: appdir + '/components/admin/adminUsers.html',
        controller: 'adminUsersController'
    });
})
.controller('adminUsersController', function($scope, Restangular) {
    
    $scope.test = 'Yop';
    
    Restangular.all('api/v1/users').getList().then(function(accounts) {
        console.log('accounts',accounts);
    });
    
});