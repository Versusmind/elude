exports.config = {
  // The address of a running selenium server.
  seleniumAddress: 'http://localhost:4444/wd/hub',

  // Spec patterns are relative to the location of this config.
  specs: [
    'resources/assets/app/**/*.spec.js'
  ],


  capabilities: {
    'browserName': 'chrome',
    'chromeOptions': {'args': ['--disable-extensions']}
  },

  onPrepare: function(){
    global.isAngularSite = function(flag){
      browser.ignoreSynchronization = !flag;
    };

    global.loginThenDo = function (callback, username, password) {
      isAngularSite(false);
      browser.get('/auth/login');

      element(by.id('username')).sendKeys(username||'user');
      element(by.id('password')).sendKeys(password||'user');
      element(by.id('signin')).click();

      browser.wait(function() {
        return browser.driver.isElementPresent(by.xpath('//*[@id="navbar"]/ul[1]/li[2]/a'))
      }).then(function() {
        isAngularSite(true);
        callback();
      });
    }
  },

  // A base URL for your application under test. Calls to protractor.get()
  // with relative paths will be prepended with this.
  baseUrl: 'http://localhost:8000',

  jasmineNodeOpts: {
    onComplete: null,
    isVerbose: false,
    showColors: true,
    includeStackTrace: true,
    defaultTimeoutInterval: 10000
  }
};
