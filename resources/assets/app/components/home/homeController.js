'use strict';

angular.module('app')
.config(function($stateProvider, appdir) {
    $stateProvider.state('home', {
        url: '/home',
        templateUrl: appdir + '/components/home/home.html',
        controller: 'homeController'
    });
})

.controller('homeController', function($scope, $log) {
    $log = $log.getInstance('homeController');
    $log.debug('Debug message');
    $log.info('Info message');
    $log.error('Error message');
});