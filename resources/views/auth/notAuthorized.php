<html>
<head>
    <?php
    echo Assets::style(\App\Libraries\Assets\Collection::createByGroup('style'));
    ?>
    <style>
        body {
            background: url('http://farm3.staticflickr.com/2832/12303719364_c25cecdc28_b.jpg') fixed;
            background-size: cover;
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row" style="margin-top: 10%; text-align: center">
        <div class="col-md-4 col-md-offset-4">
            <div class="alert alert-danger" role="alert">
                Vous n'avez pas accés à cette fonctionnalité.
                <br/>
                <br/>
                <a href="/" class="btn btn-default">
                    Accueil
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
