'use strict';

angular.module('app')
.config(function($stateProvider, appdir) {
    $stateProvider.state('admin-users', {
        url: '/admin/users',
        templateUrl: appdir + '/components/admin/adminUsers.html',
        controller: 'adminUsersController'
    });
})
.controller('adminUsersController', function($scope, MyoEntityUiConstants) {

    // Configure the entity (to call myoEntityUi Directive with it)
    $scope.userEntity = {
        title: 'Users',
        reference: 'users',
        fields: [
            {
                key: 'id',
                title: '#',
                type: MyoEntityUiConstants.NUMBER,
                display: true,
                edit: false
            },
            {
                key: 'username',
                title: 'Login',
                type: MyoEntityUiConstants.TEXT,
                display: true,
                edit: true
            },
            {
                key: 'email',
                title: 'Email',
                type: MyoEntityUiConstants.TEXT,
                display: true,
                edit: true
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