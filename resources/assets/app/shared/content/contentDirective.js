'use strict';

angular.module('app')
.directive('appContent', function(appdir) {
    return {
        restrict: 'E',
        transclude: true,
        replace: true,
        scope: {
            nope: '='
        },
        controller: function($scope, $rootScope, $location) {


        },
        templateUrl: appdir + '/shared/content/content.html'
    };
});