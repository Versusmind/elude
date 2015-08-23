angular.module('app.config.router', [])
    .config(function($stateProvider, $urlRouterProvider) {

        // For any unmatched url, redirect to /
        $urlRouterProvider.otherwise("/");

        var templateDir = 'assets/app/';

        // Now set up the states
        $stateProvider
            .state('home', {
                url: "/",
                templateUrl: templateDir+"home.html"
            })

            .state('alerts', {
                url: "/alerts",
                templateUrl: templateDir+"alert.html",
                controller: 'AlertDemoCtrl'
            })

            .state('accordion', {
                url: "/accordion",
                templateUrl: templateDir+"accordion.html",
                controller: 'AccordionDemoCtrl'
            })
            });
