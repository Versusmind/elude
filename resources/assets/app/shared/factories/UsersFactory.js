'use strict';

angular.module('app')
.factory('Users', function(Api) {
    return Api.service('users');
});
