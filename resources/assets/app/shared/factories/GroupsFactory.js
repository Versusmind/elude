'use strict';

angular.module('app')
.factory('Groups', function(Api) {
    return Api.service('groups');
});
