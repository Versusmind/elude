<html ng-app="myo">
<head>
    <?php
    echo Assets::style(\App\Libraries\Assets\Collection::createByGroup('style'));
    echo Assets::javascript(\App\Libraries\Assets\Collection::createByGroup('javascript-core'));
    echo Assets::javascript(\App\Libraries\Assets\Collection::createByGroup('javascript-app'));
    ?>
</head>

<body>

    <div ui-view></div>

    <a href="https://angular-ui.github.io/bootstrap/">More widgets here</a>

</body>
</html>