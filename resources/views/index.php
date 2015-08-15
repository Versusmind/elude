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

    <h2>Fontawesome</h2>
    <i class="fa fa-camera-retro fa-lg"></i> fa-lg
    <i class="fa fa-camera-retro fa-2x"></i> fa-2x
    <i class="fa fa-camera-retro fa-3x"></i> fa-3x
    <i class="fa fa-camera-retro fa-4x"></i> fa-4x
    <i class="fa fa-camera-retro fa-5x"></i> fa-5x

    <h2>Bootstrap</h2>
    <i class="glyphicon glyphicon-eur"></i>
    <i class="glyphicon glyphicon-asterisk"></i>
</body>
</html>