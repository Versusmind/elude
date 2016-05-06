'use strict';

angular.module('elude').config(function($translateProvider) {
    $translateProvider.translations('fr', {

        //General
        'Home': 'Accueil',
        'Logout': 'Déconnexion',
        'Users': 'Utilisateurs',
        'Groups': 'Groupes',
        'Cancel': 'Annuler',
        'Save': 'Enregistrer',
        'Search': 'Rechercher',

        //Administration
        'New user': 'Nouvel utilisateur',
        'Edit user': 'Modifier l\'utilisateur',

    });
});
