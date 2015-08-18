'use strict';

angular.module('app.config', [])

    .constant('version', 'v0.1.0')

    .config(function($stateProvider, $urlRouterProvider) {

        // For any unmatched url, redirect to /
        $urlRouterProvider.otherwise('/');

        //directoy where are stored the templates
        var templateDir = '/assets/templates/';

        // Now set up the states
        $stateProvider
            .state('home', {
                url: '/',
                templateUrl: templateDir + 'home.html'
            })

            .state('alerts', {
                url: '/alerts',
                templateUrl: templateDir + 'alert.html',
                controller: 'AlertDemoCtrl'
            })

            .state('accordion', {
                url: '/accordion',
                templateUrl: templateDir + 'accordion.html',
                controller: 'AccordionDemoCtrl'
            })
    });
