<html>
<head>
    <?php
    echo Assets::style(\App\Libraries\Assets\Collection::createByGroup('style'));
    ?>

    <?php
    echo Assets::javascript(\App\Libraries\Assets\Collection::createByGroup('javascript-core'));
    echo Assets::javascript(\App\Libraries\Assets\Collection::createByGroup('javascript-app'));
    ?>
</head>
<body ng-app>
    Write some text in textbox:
    <input type="text" ng-model="sometext" />

    <h1>Hello {{ sometext }}</h1>
</body>
</html>