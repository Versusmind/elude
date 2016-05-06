'use strict';

angular.module('elude')
.directive('modal', function(appdir) {
    return {
        restrict: 'A',
        scope: {
            title: '=',
            tmp: '=',
            obj: '=',
            size: '=',
            onSave: '&',
            onCancel: '&',
            actions: '='
        },
        link: function(scope, element, attr, ctrl) {
            element.bind('click', function(evt) {
                scope.$apply(function() {
                    ctrl.open(scope.size ? scope.size : '');
                });
            });
        },
        controller: function($scope, $modal, Modal) {
            if (!$scope.tmp) {
                $scope.tmp = appdir + '/elude/modal/modal-default.html';
            } else {
                $scope.tmp = '/' + $scope.tmp;
            }

            this.open = function(size) {
                Modal.show($scope.title, $scope.tmp, $scope.obj, size);
            };

        }
    };
});
