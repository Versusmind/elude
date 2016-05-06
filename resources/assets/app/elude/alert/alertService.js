'use strict';

angular.module('elude')
/**
 *  Material is a helper for the list of SAP Materials
 **/
.service('Alert', function() {

    return {
        error: function(message, title, confirmText) {
            swal({
                title: title || 'Erreur',
                text: message,
                type: 'warning',
                confirmButtonColor: '#DD6B55',
                confirmButtonText: confirmText || 'OK'
            });
        },
        success : function(message, title, callback) {
            swal({
                title: title,
                text: message,
                type: 'success'
            }, function() {
                if (callback) {
                    callback();
                }
            });
        },
        confirm: function(message, callback, title) {
            swal({
              title: title || 'Are you sure ?',
              text: message,
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#DD6B55',
              confirmButtonText: 'Oui',
              cancelButtonText: 'Non',
              closeOnConfirm: true,
              closeOnCancel: true},
            function(isConfirm) {
                if (callback) {
                    callback(isConfirm);
                }
            });
        },
        prompt : function(title, message, placeholder, callback) {

            swal({
              title: title,
              text: message,
              type: 'input',
              showCancelButton: true,
              closeOnConfirm: true,
              animation: 'slide-from-top',
              inputPlaceholder: placeholder || 'Write something.'},
            function(inputValue) {
                if (inputValue === false) {
                    return false;
                }
                if (callback) {
                    callback(inputValue);
                }
            });

        }
    };
});
