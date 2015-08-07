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
            'resources/assets/js/file.js',
        ]
    ]));
    ?>
</head>
<body>
    Hello
</body>
</html>