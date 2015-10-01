'use strict';

/**
 *  Myo2 Angular module.
 **/
angular.module('myo', [

    /**
     *  All the dependencies are handled by bower.
     *  To add a new lib here:
     *  1. bower install --save restangular
     *  2. Add a line in config/assets.php for the PHP to include your file in the html
     *  3. Add a line down there.
     **/

    'ui.router',  //Allows to register routes for your app
    'ui.bootstrap', //Integrate bootstrap components to AngularJS
    'pascalprecht.translate', //Handle app content translation
    'restangular', //Handles API calls
    'smart-table', //Generate custom, searchable, filterable, paginated table based on data
    'angular-loading-bar' //Automagically show spinner when ajax calls are made (https://github.com/chieffancypants/angular-loading-bar)

])
.config(function($translateProvider, cfpLoadingBarProvider) {

    //Configure the translation (see http://angular-translate.github.io/ for more informations)
    $translateProvider.useSanitizeValueStrategy('escape');
    $translateProvider.preferredLanguage('fr');

    //Configure the loading bar
    cfpLoadingBarProvider.includeSpinner = false;
});
