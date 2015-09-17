<html ng-app="app">
<head>
    <base href="<?php echo env('SUBFOLDER_INSTALLATION', '/') ?>">
    <?php
        echo Assets::style('style');
    ?>

    <meta name="access_token" content="<?php echo Session::get('oauth.access_token')?>">
    <meta name="refresh_token" content="<?php echo Session::get('oauth.refresh_token')?>">
    <meta name="token_type" content="<?php echo Session::get('oauth.token_type')?>">
</head>

<body>
    <div>Welcome <?php echo Auth::user()->username ?></div>

    <div ui-view></div>

    <a href="https://angular-ui.github.io/bootstrap/">More widgets here</a>

    <?php
        echo Assets::javascript('javascript-core');
        echo Assets::javascript('javascript-app');
    ?>
</body>
</html>