<!DOCTYPE html>
<html lang="es">

    <head>
        <?php require_once __DIR__ . '/head.php'; ?>
        <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/public.css">
        <link rel="stylesheet" href="<?= URL_PATH ?>/assets/libraries/fonts/style.css">
    </head>

    <body>
        <div class="BasicLayout" id="BasicLayout">
            <div class="BasicLayout-header">
                <h1 class="Branding">
                    <a href="<?php echo URL_PATH ?>" class="Branding-header">
                        <img src="<?php echo URL_PATH ?>/assets/images/icon/Icon-144.png" alt="" class="Branding-img">
                        <div> <?php echo APP_NAME ?> </div>
                    </a>
                    <div class="Branding-description">Facturación electronica</div>
                </h1>
            </div>
            <div class="BasicLayout-main">

                <div class="BasicLayout-mainContent">
                    <?php echo $content ?>
                </div>
                <!-- Copyright  © <?php // date('Y') 
                                    ?> <?php // APP_AUTHOR 
                                                        ?> -->
            </div>
        </div>
        <script src="<?= URL_PATH ?>/assets/script/public/public.js"></script>
    </body>

</html>