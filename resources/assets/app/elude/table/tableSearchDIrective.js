'use strict';

angular.module('elude')
.directive('stSearchCustom', function($timeout, $parse) {
    return {
        require: '^stTable',
        link: function(scope, element, attr, ctrl) { //@TODO: gérer le champ "search" dans la définition des headers
            var tableCtrl = ctrl;
            var promise = null;
            var throttle = 1;

            attr.$observe('stSearch', function(newValue, oldValue) {
                var input = element[0].value;
                if (newValue !== oldValue && input) {
                    ctrl.tableState().search = {};
                    tableCtrl.search(input, newValue);
                }
            });

            //table state -> view
            scope.$watch(function() {
                return ctrl.tableState().search;
            }, function(newValue, oldValue) {
                var predicateExpression = attr.stSearch || '$';
                if (newValue.predicateObject &&
                    $parse(predicateExpression)(newValue.predicateObject) !== element[0].value) {
                    element[0].value = $parse(predicateExpression)(newValue.predicateObject) || '';
                }
            }, true);

            // view -> table state
            scope.$watch('search', function() {

                if (promise !== null) {
                    $timeout.cancel(promise);
                }

                promise = $timeout(function() {
                    tableCtrl.search(scope.search, attr.stSearch || '');
                    promise = null;
                }, throttle);
            });
        }
    };
});
