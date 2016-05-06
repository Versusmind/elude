'use strict';

angular.module('app')
.config(function($stateProvider, appdir) {
    $stateProvider.state('admin-users', {
        url: '/admin/users',
        permission: 'admin',
        templateUrl: appdir + '/components/admin/adminUsers.html',
        controller: 'adminUsersController'
    });
})
.controller('adminUsersController', function($scope, EludeEntityUiConstants) {

    // Configure the entity (to call eludeEntityUi Directive with it)
    $scope.userEntity = {
        title: 'Users',
        newTitle: 'New user',
        editTitle: 'Edit user',
        reference: 'users',
        help: 'A user is an app user.',
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
                key: 'username',
                title: 'Login',
                type: EludeEntityUiConstants.TEXT,
                display: true,
                edit: true,
                sort: true,
                search: true
            },
            {
                key: 'email',
                title: 'Email',
                type: EludeEntityUiConstants.TEXT,
                display: true,
                edit: true,
                sort: true,
                search: true
            },
            {
                key: 'created_at',
                title: 'Créé',
                type: EludeEntityUiConstants.DATE,
                display: true,
                edit: true,
                sort: true,
                search: false
            },
            {
                key: 'updated_at',
                title: 'Mis à jour',
                type: EludeEntityUiConstants.DATE,
                display: true,
                edit: true,
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
