'use strict';

angular.module('app')
.directive('appHeader', function(appdir, appname) {
    return {
        restrict: 'E',

        scope: {
            'appname' : '='
        },
        controller: function($scope, $rootScope, $location) {

            $scope.appname = appname;

            $scope.menuLeft = {
                'Accueil': '#/home'
            };

            $scope.menuRight = {
                'Administration': {
                    link: '#/admin/users',
                    subItems: {
                        'Utilisateurs': '#/admin/users',
                        'Groupes': '#/admin/groups'
                    }
                },
                'Déconnexion': '/auth/logout'
            };

        },
        templateUrl: appdir + '/shared/header/header.html'
    };
});
