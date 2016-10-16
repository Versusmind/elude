'use strict';

angular.module('myo')
.config(function($translateProvider) {

    $translateProvider.translations('fr', {
        'Home': 'Accueil',
        'Logout': 'Déconnexion',
        'Users': 'Utilisateurs',
        'Groups': 'Groupes'
    });

});