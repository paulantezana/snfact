<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once __DIR__ . '/layout/head.php'; ?>
        <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/frontPage.css">
    </head>
    <body>
        <div class="FrontPage">
            <div class="FrontPage-header">
                <div class="Slide">
                    <img src="<?= URL_PATH ?>/assets/images/banner.jpg" class="Slide-bg" alt="slide-bg"/>
                    <div class="Container Slide-data">
                        <h1 class="Slide-title">Sn Fact</h1>
                        <p class="Slide-text">Facuración electrónica</p>
                        <!-- <div class="Slide-countdown Countdown">
                            <div class="Countdown-title">Lanzamiento en: </div>
                            <div class="Countdown-time">
                                <div>
                                    <div class="Countdown-smalltext">M</div>
                                    <span class="Countdown-months"></span>
                                </div>
                                <div>
                                    <div class="Countdown-smalltext">D</div>
                                    <span class="Countdown-days"></span>
                                </div>
                                <div>
                                    <div class="Countdown-smalltext">H</div>
                                    <span class="Countdown-hours"></span>
                                </div>
                                <div>
                                    <div class="Countdown-smalltext">mm</div>
                                    <span class="Countdown-minutes"></span>
                                </div>
                                <div>
                                    <span class="Countdown-seconds"></span>
                                    <div class="Countdown-smalltext">ss</div>
                                </div>
                            </div>
                        </div> -->
                        <div class="Slide-action">
                            <a class="SnBtn warning lg" href="<?=  URL_PATH . '/publicCompany/login' ?>">INGRESAR AL SISTEMA</a>
                        </div>
                        <img
                            src="<?= URL_PATH ?>/assets/images/dashboard.jpg"
                            alt="Interfas del sistema"
                            class="Slide-app"
                        />
                    </div>
                </div>
            </div>
            <div class="FrontPage-main">

            </div>
        </div>
    </body>
</html>