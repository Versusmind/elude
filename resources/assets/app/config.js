'use strict';

angular.module('app.config', [])

    .constant('appname', 'Myo2')

    .constant('version', 'v0.1.0')
    
    .constant('appdir', 'assets/app')
    
    .constant('apiUrl', '/api/v1') //you can set the whole URL here : e.g http://example.com/api/v1

    .config(function($urlRouterProvider) {

        // For any unmatched url, redirect to /home
        $urlRouterProvider.otherwise('/home');

       //states are configured in each controller
    });
