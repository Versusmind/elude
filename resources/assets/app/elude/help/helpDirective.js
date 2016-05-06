'use strict';

angular.module('elude')
.directive('help', function(appdir) {
    return {
        restrict: 'E',
        templateUrl: appdir + '/elude/help/help.html',
        scope: {
            message: '@'
        }
    };
});
