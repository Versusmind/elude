'use strict';

angular.module('elude')

/**
 *  elude-table directive
 *  
 *  
 **/

.directive('eludeTable', function(appdir) {
    return {
        restrict: 'E',
        scope: {
            headers: '=', //table columns
            rows: '=', //table content from ajax call (array of objects)
            search: '=?',
            itemsByPage: '=?' //number of items by page
        },
        controller: function($scope) {

            //set default items by page
            if (!$scope.itemsByPage) {
                $scope.itemsByPage = 10;
            }

            /**
          *   getValue() - Get value from pattern:
          *    key  <- return the value for the key
          *    [EMPTY]  <- return the full object
          *    [FUNCTION]()  <- return the result of the function (called with the original object as a parameter)
          *    key.subkey.subsubkey <- return the value in obj[key][subkey][subsubkey]
          *    in:ObjectKey:ValueToSearch   <- return true if the value is present in the array obj[ObjectKey]
          *    inCollection:ObjectKey:ValueToSearch   <- return true if the value is present in the collection obj[ObjectKey]
          *    firstKey+ +secondKey   <- return concatenation of values for keys (if they exist, otherwise it uses the string itself)
          **/
            $scope.getValue = function(obj, key) {
                //if the key is not defined
                if (!key) {
                    return obj;
                }
                //if this is a function
                else if (_.isFunction(key)) {
                    return key(obj);
                }
                //Search for recursive
                else if (key.indexOf('.') > -1) {
                    var keys = key.split('.');
                    var newKey = keys.shift();
                    if (obj[newKey]) {
                        return $scope.getValue(obj[newKey], keys.join('.'));
                    }
                    return null;
                }
                //search for special selector
                else if (key.indexOf(':') > -1) {
                    var parts = key.split(':');
                    var selector = parts[0];
                    var innerKey = parts[1];
                    var value = parts[2];

                    switch (selector) {
                        case 'in':
                            return _.indexOf(obj[innerKey], value) > -1;
                        case 'inCollection':
                            return !!_.find(obj[innerKey], value);
                        default: 
                            return "Unkown selector for key: " + key;
                    }
                } else if (key.indexOf('+') > -1) {
                    var parts = key.split('+');
                    var res = '';
                    _.each(parts, function(part) {
                        if (part && obj[part]) {
                            res += obj[part];
                        } else {
                            res += part;
                        }
                    });
                    return res;
                } else {
                    return obj[key];
                }
            };

        },
        templateUrl: appdir + '/elude/table/table.html'
    };
})
