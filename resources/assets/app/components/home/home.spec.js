/**
 * Created by arnaud on 26/09/2016.
 */
'use strict';

describe('Test home page', function() {
    it('should display app name', function() {
        // login to angular app
        loginThenDo(function(){

            // test app name is not empty
            element(by.binding('appname')).getText().then(function (text) {
                expect(text.length).toBeGreaterThan(0);
            });


            // test helloworld message is present
            element(by.css('.container')).getText().then(function(text){
                expect(text).toContain("Page d'accueil");
            });
        });
    });
});
