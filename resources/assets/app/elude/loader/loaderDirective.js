'use strict';

angular.module('elude')
.directive('loader', function(appdir) {
    return {
        restrict: 'E',
        templateUrl: appdir + '/elude/loader/loader.html'
    };
});
