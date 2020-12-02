<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?=$page_title;?>
    </title>
    <base href="<?=$CFG["base_url"];?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="assets/bs2/css/bootstrap-combined.min.css">
    <link rel="stylesheet" href="assets/bs2/css/font-awesome.css">
    <link rel="stylesheet" href="assets/bs2/css/custom.css">

    <script src="assets/bs2/js/jquery.js"></script>
    <script src="assets/bs2/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
</head>

<body>
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">

                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <a href="index" class="brand">TODO</a>

                <div class="nav-collapse collapse">
                    <ul class="nav pull-right">
                        <?php if ($USER):?>
                            <li>
                                <?=$USER->login;?>
                            </li>
                            <li><a href="user/logout">Log out</a></li>
                        <?php else:?>
                            <li><a href="user/form/login">Log In</a></li>
                        <?php endif;?>
                    </ul>
                </div>

            </div>
            <div class="navbar-overlay"></div>
        </div>
    </div>


    <div class="container-fluid">

        <?php if (!empty($msg)):?>
            <article class="flash_msg">
                <div class="alert alert-info">
                    <?=$msg;?>
                </div>
            </div>
        <?php endif;?>

        <?php if (!empty($content_template_file)):?>
            <?php include $content_template_file;?>
        <?php endif;?>

    </div>
</body>

</html>
