'use strict';

angular.module('app.config', [])

    .constant('appname', 'Myo2')

    .constant('version', 'v0.1.0')
    
    .constant('appdir', 'assets/app')

    .config(function($urlRouterProvider) {

        // For any unmatched url, redirect to /home
        $urlRouterProvider.otherwise('/home');

       //states are configured in each controller
    });
