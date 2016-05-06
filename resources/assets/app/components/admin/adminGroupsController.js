'use strict';

angular.module('app')
.config(function($stateProvider, appdir) {
    $stateProvider.state('admin-groups', {
        url: '/admin/groups',
        templateUrl: appdir + '/components/admin/adminGroups.html',
        controller: 'adminGroupsController'
    });
})
.controller('adminGroupsController', function($scope, EludeEntityUiConstants) {

    // Configure the entity (to call eludeEntityUi Directive with it)
    $scope.groupEntity = {
        title: 'Groups',
        reference: 'groups',
        fields: [
            {
                key: 'id',
                title: '#',
                type: EludeEntityUiConstants.NUMBER,
                display: true,
                edit: false
            },
            {
                key: 'created_at',
                title: 'Créé',
                type: EludeEntityUiConstants.DATE,
                display: true,
                edit: true
            },
            {
                key: 'updated_at',
                title: 'Mis à jour',
                type: EludeEntityUiConstants.DATE,
                display: true,
                edit: true
            }
        ]
    };

});