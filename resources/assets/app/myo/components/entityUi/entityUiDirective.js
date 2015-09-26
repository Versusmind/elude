'use strict';

angular.module('myo')
/**
 *  Define constants for myoEntityUi
 **/
.constant('MyoEntityUiConstants', {
    TEXT: 0,
    NUMBER: 1,
    DATE: 2
})
/**
 *  myoEntityUi is a directive that generate a complete UI (Title, Search, Dynamic Table, Edition popup, Pagination, ...)
 *  use it to quickly generate an "admin-style" view for your model
 **/
.directive('myoEntityUi', function(appdir) {
    return {
        restrict: 'E',
        scope: {
            'entity' : '='
        },
        controller: function($scope, Restangular) {
            console.log('$scope.entity', $scope.entity);

            if (!$scope.entity) {
                console.error('[myoEntityUi] Your entity definition object is missing !');
                return;
            }

            if (!$scope.entity.fields) {
                console.error('[myoEntityUi] Your entity definition object does not contain any fields.',
                    $scope.entity);
                return;
            }

            //prepare headers for myo-table
            $scope.headers = _.filter($scope.entity.fields, function(field) {
                return field.display;
            });

            //prepare content for myo-table
            Restangular.all('api/v1/' + $scope.entity.reference).getList().then(function(items) {
                $scope.rows = items;
            });

        },
        templateUrl: appdir + '/myo/components/entityUi/entityUi.html'
    };
});
