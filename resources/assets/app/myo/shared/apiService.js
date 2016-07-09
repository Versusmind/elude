'use strict';

angular.module('myo')
/**
 *  Api for Myo is a wrapper for Restangular ressources
 *
 *  see https://github.com/mgonto/restangular for more infos about the returned data
 *
 *  Example of use :     Api.getList('users').then(function(users){
 *                           angular.forEach(users, function(user){
 *                                   ....
 *                          });
 *                       });
 *
 *                      will trigger :    GET /api/v1/users
 *
 **/
.service('Api', function(Restangular, apiConfig, $http) {

    var Api = Restangular,
        cfg = apiConfig;

    /*************************************
     *   Project configuration for       *
     *            Restangular            *
     *************************************/

    //configure the base URL of the api if necessary
    // (this is configurable by changing the "apiUrl" constant in your config file
    if (cfg.url) {
        Api.setBaseUrl(cfg.url);
    }

    function updateToken(data) {
        if (cfg.token && cfg.token.type) {
            if (data && data.access_token) {
                cfg.token.access = data.access_token;
                cfg.token.refresh = data.refresh_token;
            }

            Api.setDefaultHeaders({
                'Authorization': cfg.token.type + ' ' + cfg.token.access,
                'Accept': 'application/json'
            });
            return true;
        }
        return false;
    }
    updateToken();

    function refreshAccesstoken() {
        return Api.all('oauth/access_token').post({
            'refresh_token': cfg.token.refresh,
            'grant_type': 'refresh_token',
            'client_id': cfg.client.id,
            'client_secret': cfg.client.secret
        });
    }

    //set up a catcher for Myo WS errors (acccess token expired, and such)
    Api.setErrorInterceptor(function(response, deferred, responseHandler) {
        switch (response.status) {
            case 401:
                refreshAccesstoken().then(function(tokenData) {
                    //Save the new tokens
                    if (tokenData &&  updateToken(tokenData)) {
                        //update current request headers
                        angular.forEach(Api.defaultHeaders, function(header, headerKey) {
                            response.config.headers[headerKey] = header;
                        });
                        // Repeat the request and then call the handlers the usual way.
                        $http(response.config).then(responseHandler, deferred.reject);
                    } else {
                        deferred.reject();
                    }
                });
                return false; // error handled
            case 500:

                return false; // error handled
        }
        return true; // error not handled
    });

    /*************************************
     *   Standard configuration for      *
     *            Restangular            *
     *************************************/

    Api.setParentless(true);

    /*************************************
     *   Surcharge Restangular object    *
     *   with homemade methods           *
     *************************************/

    /**
     *  Api.getList(route, params [Optionnal]) is equivalent as "Api.all(route,params).getList()"
     *  returns a Promise
     **/
    Api.getList = function(route, params) {
        return this.all(route, params).getList();
    }.bind(Restangular);

    /**
     *  Api.paginate(route, page [Optionnal], itemsByPage [Optionnal], params [Optionnal])
     *  returns a Promise
     **/
    Api.paginate = function(route, page, itemsByPage, params) {
        params = params || {};

        params.paginate = 1;
        params.nbByPage = itemsByPage || 30;
        params.page  = page || 1;

        return this.getList(route, params);
    };

    //returns a Restangular clone !
    //All the doc for Restangular is usable on this object !
    return Api;
});
