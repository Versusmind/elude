'use strict';

angular.module('app.config', [])

    .constant('appname', 'Myo2')

    .constant('version', 'v0.1.0')
    
    .constant('appdir', 'assets/app')

    .config(function($stateProvider, $urlRouterProvider, appdir) {

        // For any unmatched url, redirect to /
        $urlRouterProvider.otherwise('/');

        // Now set up the states
        $stateProvider
            .state('home', {
                url: '/',
                templateUrl: appdir + '/components/home/home.html',
                controller: 'homeController'
            })

           /* .state('alerts', {
                url: '/alerts',
                templateUrl: 'alert.html',
                controller: 'AlertDemoCtrl'
            })

            .state('accordion', {
                url: '/accordion',
                templateUrl: templateDir + 'accordion.html',
                controller: 'AccordionDemoCtrl'
            })*/
    });
