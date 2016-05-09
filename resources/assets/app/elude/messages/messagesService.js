'use strict';

angular.module('elude')
/**
 *  Messages for App is a service that managed messages displaying & logging
 **/
.service('Messages', function(ngNotify, $translate) {

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
            $translate(err).then(function(errTranslated) {
                ngNotify.set(errTranslated, {
                    type: 'error',
                    duration: duration || 6000
                });
            });
        },
        success: function(message, duration) {
            if (console && console.info) {
                console.info(message);
            }
            $translate(message).then(function(messageTranslated) {
            ngNotify.set(messageTranslated, {
                type: 'success',
                duration: duration || 3000
            });
        });
        },
        info: function(message, duration) {
            if (console && console.info) {
                console.info(message);
            }
            $translate(message).then(function(messageTranslated) {
            ngNotify.set(messageTranslated, {
                duration: duration || 3000
            });
        });
        }
    };
});
