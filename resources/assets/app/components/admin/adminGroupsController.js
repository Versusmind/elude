'use strict';

angular.module('app')
.config(function($stateProvider, appdir) {
    $stateProvider.state('admin-groups', {
        url: '/admin/groups',
        templateUrl: appdir + '/components/admin/adminGroups.html',
        controller: 'adminGroupsController'
    });
})
.controller('adminGroupsController', function($scope, MyoEntityUiConstants) {

    // Configure the entity (to call myoEntityUi Directive with it)
    $scope.groupEntity = {
        title: 'Groups',
        reference: 'groups',
        fields: [
            {
                key: 'id',
                title: '#',
                type: MyoEntityUiConstants.NUMBER,
                display: true,
                edit: false
            },
            {
                key: 'created_at',
                title: 'Créé',
                type: MyoEntityUiConstants.DATE,
                display: true,
                edit: true
            },
            {
                key: 'updated_at',
                title: 'Mis à jour',
                type: MyoEntityUiConstants.DATE,
                display: true,
                edit: true
            }
        ]
    };

});