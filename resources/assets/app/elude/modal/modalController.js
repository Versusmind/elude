'use strict';

angular.module('elude')
.controller('modalCtrl', function($scope, $uibModalInstance, title, templateUrl, obj, actions) {
    $scope.title = title;
    $scope.templateUrl = templateUrl;
    $scope.obj = obj;
    $scope.actions = actions;
    

    $scope.cancel = function() {
        $uibModalInstance.dismiss('cancel');
        $('.btn').blur();
    };

    $scope.onAction = function(action) {
        if (action.action == '@dismiss') {
            return $scope.cancel();
        }
        else if (_.isFunction(action.action)) {
            action.action($scope.obj);
        }
    };

});
