'use strict';

angular.module('elude')
/**
 *  Messages for App is a service that managed messages displaying & logging
 **/
.service('Messages', function(ngNotify) {

    /**
     *  Configure ng-notify object
     *  See https://github.com/matowens/ng-notify for more infos
     **/
    /* ngNotify.config({
        theme: 'pure',
        position: 'bottom',
        duration: 3000,
        type: 'info',
        sticky: false,
        button: true,
        html: false
    });*/

    return {
        error: function(err, duration) {
            err = err || 'An error occured.';
            if (console && console.error) {
                console.error(err);
            }
            ngNotify.set(err, {
                type: 'error',
                duration: duration || 6000
            });
        },
        success: function(message, duration) {
            if (console && console.info) {
                console.info(message);
            }
            ngNotify.set(message, {
                duration: duration || 3000
            });
        },
        info: function(message, duration) {
            if (console && console.info) {
                console.info(message);
            }
            ngNotify.set(message, {
                duration: duration || 3000
            });
        }
    };
});
