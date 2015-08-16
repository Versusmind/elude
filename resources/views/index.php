<html ng-app="myo">
<head>
    <?php
        echo Assets::style(\App\Libraries\Assets\Collection::createByGroup('style'));
    ?>
</head>

<body>

    <div ui-view></div>

    <a href="https://angular-ui.github.io/bootstrap/">More widgets here</a>

    <?php
        echo Assets::javascript(\App\Libraries\Assets\Collection::createByGroup('javascript-core'));
        echo Assets::javascript(\App\Libraries\Assets\Collection::createByGroup('javascript-app'));
    ?>
</body>
</html>