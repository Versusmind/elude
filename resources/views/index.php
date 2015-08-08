<html>
<head>
    <?php
    echo Assets::style(new \App\Libraries\Assets\Collection([
        \App\Libraries\Assets\Asset::CSS  => [
            'resources/assets/css/folder/file.css',
            'resources/assets/css/file.css',
        ],
        \App\Libraries\Assets\Asset::LESS => [
            'resources/assets/less/file.less',
            'resources/assets/less/folder/file2.less',
        ]
    ]));
    ?>

    <?php
    echo Assets::javascript(new \App\Libraries\Assets\Collection([
        \App\Libraries\Assets\Asset::JS => [
            'resources/assets/bower/angularjs/angular.js',
            'resources/assets/js/file.js',
        ]
    ]));
    ?>
</head>
<body ng-app>
    Write some text in textbox:
    <input type="text" ng-model="sometext" />

    <h1>Hello {{ sometext }}</h1>
</body>
</html>