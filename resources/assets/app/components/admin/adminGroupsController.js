'use strict';

angular.module('app')
.config(function($stateProvider, appdir) {
    $stateProvider.state('admin-groups', {
        url: '/admin/groups',
        templateUrl: appdir + '/components/admin/adminGroups.html',
        controller: 'adminGroupsController'
    });
})
.controller('adminGroupsController', function($scope, EludeEntityUiConstants, Groups) {

    // Configure the entity (to call eludeEntityUi Directive with it)
    $scope.groupEntity = {
        title: 'Groups',
        newTitle: 'New group',
        editTitle: 'Edit group',
        factory: Groups,
        help: 'A group is a user collection.',
        fields: [
            {
                key: 'id',
                title: '#',
                type: EludeEntityUiConstants.NUMBER,
                display: true,
                edit: false,
                sort: true,
                search: false
            },
            {
                key: 'name',
                title: 'Name',
                type: EludeEntityUiConstants.TEXT,
                display: true,
                edit: true,
                sort: true,
                search: true
            },
            {
                key: 'created_at',
                title: 'Created',
                type: EludeEntityUiConstants.DATE,
                display: true,
                edit: false,
                sort: true,
                search: false
            },
            {
                key: 'updated_at',
                title: 'Updated',
                type: EludeEntityUiConstants.DATE,
                display: true,
                edit: false,
                sort: true,
                search: false
            }
        ],
        actions: [
            'edit',
            'delete'
        ]
    };

});
