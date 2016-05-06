'use strict';

angular.module('elude')
.factory('Modal', function(appdir, $uibModal) {
    return {
        show : function(title, tmp, obj, size, actions) {
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: appdir + '/elude/modal/modal.html',
                controller: 'modalCtrl',
                size: (size ? size : ''), // can be 'lg' or 'sm'
                resolve: {
                    title: function() {
                        return title;
                    },
                    templateUrl: function() {
                        return appdir + '/' + tmp;
                    },
                    obj: function() {
                        return obj;
                    },
                    actions: function() {
                        return actions;
                    }
                }
            });
            modalInstance.result.then(function(res) {
            }, function() {
                //console.info('Modal dismissed at: ' + new Date());
            });
        }
    };
});
