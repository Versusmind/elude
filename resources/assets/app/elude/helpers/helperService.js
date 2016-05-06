'use strict';

angular.module('elude')
/**
 *  General Helper for handling errors and do common stuff that can be functionizable
 **/
.service('Helper', function(Messages) {

    return {
        error: function(error, messagesPerCode) {

            var displayableError = error;

            if (error && error.reason) {
                displayableError = error.reason;
                if (messagesPerCode && error.error && messagesPerCode[error.error]) {
                    displayableError = messagesPerCode[error.error];
                }
            }
            if (displayableError) {
                console.error('Error:', error);
                Messages.error(displayableError);
            }
        }
    };
});
