Myo2 
---------

# Get started

## Requirements

    - PHP >= 5.5.9
    - OpenSSL PHP Extension
    - Mbstring PHP Extension
    - Tokenizer PHP Extension
    - Composer
    - node / npm / bower

## Install

    - cp .env.example .env
    - For a dev env you need to set `APP_ENV` to `local` and `ASSETS_CONCAT` to `false`
    - composer install
    - php artisan migrate --seed
    - php artisan assets:update
    - php artisan assets:build
    - create a vhost to /public folder or type `php artisan serve` to run php build in server

# Assets management
## Folders

    resources
    ├── assets
    │   ├── bower
    │   │   └── All bower dependencies
    │   ├── css
    │   │   └── Application CSS files
    │   ├── fonts
    │   │   └── Application font files
    │   ├── img
    │   │   └── Application images
    │   ├── js
    │   │   └── Application javascript
    │   ├── less
    │   │   └── Application less files
    │   └── sass
    │       └── Application sass files
    .

For a better readability and maintainability you need to respect this folder pattern. You can create as many subfolders as you want.
/!\ After the building process all fonts will be in the same folder. You need to pay attention to name conflicts.

## Building assets

### Configuration

In the `config/assets.php` file you can define groups of assets:



    'groups' => [
        'style' => [
            \App\Libraries\Assets\Asset::CSS => [
                'resources/assets/css/folder/file.css',
                'resources/assets/bower/bootstrap/dist/css/bootstrap.css',
                'resources/assets/bower/fontawesome/css/font-awesome.css'
            ],

            \App\Libraries\Assets\Asset::LESS => [
                'resources/assets/less/file.less',
                'resources/assets/less/folder/file2.less'
            ],

            \App\Libraries\Assets\Asset::SASS => [
                'resources/assets/sass/file.scss',
                'resources/assets/sass/folder/file2.scss'
            ],

            \App\Libraries\Assets\Asset::FONT => [
                'resources/assets/bower/fontawesome/fonts/*', // only file in this level no subfolders
                'resources/assets/bower/bootstrap/fonts/*',
            ],

            \App\Libraries\Assets\Asset::IMG => [
                'resources/assets/img/folder/*'
            ]
        ],

        'javascript-core' => [
            \App\Libraries\Assets\Asset::JS => [
                'resources/assets/bower/angularjs/angular.js',
            ]
        ],

        'javascript-app' => [
            \App\Libraries\Assets\Asset::JS => [
                'resources/assets/js/*.js',
                'resources/assets/js/*/*.js',
            ]
        ]
    ]


The best practice, for the moment, is to separate style and applicative (JS) files. You can create as many groups you want but it will have an impact on production.
Each js or style group will generate a build file in production.

For the previous configuration, we have, after building, 2 js files ans 1 css file.

For a group, style files are built and concatenated in the order of the group with this priority: LESS then SASS then CSS

In the `.env` file you can change the mode of building you want by setting `ASSETS_CONCAT` to true or false;

### Development building

- less and sass are compiled to css
- css (css, less and sass compiled) are rewritten to fix path (url)
- js, images, and fonts are copied (no modification)

### Production building

- less and sass are compiled to css
- css (css, less and sass compiled) are concatenated and minified
- js are concatenated and packed
- images, and fonts are copied (no modification)

Each style and js groups have a "version" file in `storage/versions`. In this file you can see the detail of each building.

### Command line

If in your `.env` file you set `APP_ENV` to `local` assets building will be done on each request.

#### Cleaning assets

Remove all buildings, versions and temporary files.

`php artisan assets:clean`

#### Building assets

Build all groups, this command runs an asset: clean before the building

`php artisan assets:build`

#### Bower update

Update bower dependencies and remove unused local packages

`php artisan assets:update`

## Include assets in view

For a style group

    <?php
        echo Assets::style(\App\Libraries\Assets\Collection::createByGroup('style'));
    ?>

Output


     <link rel="stylesheet" type="text/css" href="/assets/css/4b2d403006d93f1b598e0499e3866b2c.css">


For a javascript group

    <?php
        echo Assets::javascript(\App\Libraries\Assets\Collection::createByGroup('javascript-core'));
        echo Assets::javascript(\App\Libraries\Assets\Collection::createByGroup('javascript-app'));
    ?>

Output:

    <script src="/assets/js/e57c943abf8160da2fc80f14f16edd16.js"></script>
    <script src="/assets/js/5e2c8c8ffefc922db1b6e56004fcfb3b.js"></script>

# Todo

- Using symlink in the copy task in dev
- Simplify call in view
- Integrate typescript compilator if needed