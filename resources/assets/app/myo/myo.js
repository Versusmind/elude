'use strict';

angular.module('myo', [
    'ui.router',
    'ui.bootstrap',
    'pascalprecht.translate',
    'restangular'
])
.config(function($translateProvider) {

    //Configure the translation (see http://angular-translate.github.io/ for more informations)
    $translateProvider.useSanitizeValueStrategy('escape');
    $translateProvider.preferredLanguage('fr');
});
