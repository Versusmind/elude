'use strict';

angular.module('elude')
/**
 *  handle error when fetching translations
 *  -> because for some reasons angular-moment does not use the key as a default value when the translation is not found
 **/
.factory('customTranslationHandler', function() {
    return function(translationID, uses) {
        return translationID;
    };
});

