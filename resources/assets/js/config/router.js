angular.module('myo.config.router', [])
    .config(function($stateProvider, $urlRouterProvider) {

        // For any unmatched url, redirect to /
        $urlRouterProvider.otherwise("/");
        //
        // Now set up the states
        $stateProvider
            .state('home', {
                url: "/",
                templateUrl: "/assets/templates/home.html"
            })

            .state('alerts', {
                url: "/alerts",
                templateUrl: "/assets/templates/alert.html",
                controller: 'AlertDemoCtrl'
            })

            .state('accordion', {
                url: "/accordion",
                templateUrl: "/assets/templates/accordion.html",
                controller: 'AccordionDemoCtrl'
            })

            .state('error404', {
                url: "/404",
                templateUrl: "/assets/templates/errors/404.html"
            })
            ;
    })