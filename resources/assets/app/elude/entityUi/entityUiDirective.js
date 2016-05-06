'use strict';

angular.module('elude')
/**
 *  Define constants for eludeEntityUi
 **/
.constant('EludeEntityUiConstants', {
    TEXT: 0,
    NUMBER: 1,
    DATE: 2
})
/**
 *  eludeEntityUi is a directive that generate a complete UI (Title, Search, Dynamic Table, Edition popup, Pagination, ...)
 *  use it to quickly generate an "admin-style" view for your model
 *
 *          WORK IN PROGRESS ...
 **/
.directive('eludeEntityUi', function(appdir) {
    return {
        restrict: 'E',
        scope: {
            'entity' : '=',
            'itemsByPage': '=?'
        },
        controller: function($scope, Restangular, Api, Modal, Alert, Messages) {

            if (!$scope.entity) {
                console.error('[eludeEntityUi] Your entity definition object is missing !');
                return;
            }

            if (!$scope.entity.fields) {
                console.error('[eludeEntityUi] Your entity definition object does not contain any fields.',
                    $scope.entity);
                return;
            }

            // Defaults
            if (!$scope.itemsByPage) {
                $scope.itemsByPage = 10;
            }

            //Turns actions into columns for elude-table
            var actions = [];

            var edit = function(item) {
                return $scope.openModal(item);
            };

            var remove = function(item) {
                Alert.confirm('You are about to delete the item, do you wish to continue ?', function(ok) {
                    if (ok) {
                        alert('TODO');
                        Messages.success('Item deleted.');
                    }
                });
            };

            _.each($scope.entity.actions, function(action) {
                console.log('action', action);

                switch (action) {
                    case 'edit':
                        actions.push({
                            icon: 'pencil',
                            callback: edit,
                            classes: 'btn-primary'
                        });
                        break;
                    case 'delete':
                        actions.push({
                            icon: 'trash',
                            callback: remove,
                            classes: 'btn-danger'
                        });
                        break;
                    default:
                        actions.push(action);
                }
                $scope.entity.fields.push(action);
            });
            if (actions.length) {
                $scope.entity.fields.push({
                    title: 'Actions',
                    display: true,
                    edit: false,
                    sort: false,
                    search: false,
                    type: 'actions',
                    actions: actions
                });
            }

            //prepare headers for elude-table
            $scope.headers = _.filter($scope.entity.fields, function(field) {
                return field.display;
            });

            //prepare content for elude-table
            Api.paginate($scope.entity.reference, 1 /* requested page number */, $scope.itemsByPage /* Number of items by page */).then(function(items) {
                $scope.rows = items;
            });

            /**
             * openModal()
             *  open a new or edit modal (depending on the param passed by the caller)
             **/
            $scope.openModal = function(item) {
                $scope.item = item;
                $scope.isUpdate = !!item;

                Modal.show(
                    $scope.isUpdate ? $scope.editTitle : $scope.newTitle,
                    'elude/entityUi/entityUiEditModal.html',
                    $scope.item,
                    'lg',
                    [
                        {title : 'Cancel', classname: 'btn-danger', action: '@dismiss'},
                        {title : 'Save', classname: 'btn-success', action: function(item) {
                            console.log('item on save');
                        }},
                    ]
                );

            };

        },
        templateUrl: appdir + '/elude/entityUi/entityUi.html'
    };
});
