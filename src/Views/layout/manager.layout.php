<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once __DIR__ . '/head.php'; ?>

        <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/manager.css">
        <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/slimselect.css">
        <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/nprogress.css">

        <script src="<?= URL_PATH ?>/assets/script/common/theme.js"></script>
        <script src="<?= URL_PATH ?>/assets/script/common/pristine.min.js"></script>
        <script src="<?= URL_PATH ?>/assets/script/common/nprogress.js"></script>
        <script src="<?= URL_PATH ?>/assets/script/common/common.js"></script>
        <script src="<?= URL_PATH ?>/assets/script/common/sedna.js"></script>
    </head>
    <body>
        <div class="AdminLayout SnAdminL2" id="AdminLayout">
            <div class="AdminLayout-header">
                <header class="Header">
                    <div class="Header-left">
                        <div id="AsideMenu-toggle"><i class="fas fa-bars"></i></div>
                    </div>
                    <div class="Header-right">
                        <ul class="HeaderMenu">
                            <li>
                                <a href="<?= URL_PATH ?>/busines" class="Header-action">
                                    <i class="far fa-bell"></i>
                                </a>
                            </li>
                            <li>
                                <div class="HeaderMenu-profile Header-action">
                                    <div class="SnAvatar">
                                        <img src="<?= URL_PATH ?>/assets/images/icon/Icon-144.png" alt="avatar">
                                    </div>
                                </div>
                                <ul>
                                    <li class="User-item">
                                        <a href="<?= URL_PATH ?>/user/profile" class="SnAvatar">
                                            <img src="<?= URL_PATH ?>/assets/images/icon/Icon-144.png" alt="avatar">
                                        </a>
                                        <div class="">
                                            <div class="User-title" id="userTitleInfo"></div>
                                            <div class="User-description" id="userDescriptionInfo"></div>
                                        </div>
                                    </li>
                                    <li class="SnPt-2"><a href="<?= URL_PATH ?>/user/profile"><i class="fas fa-user SnMr-2"></i>Perfil</a></li>
                                    <li><a href="<?= URL_PATH ?>/manager/help"><i class="fas fa-life-ring SnMr-2"></i>Soporte</a></li>
                                    <li class="SnPb-2"><a href="<?= URL_PATH ?>/business/update"><i class="fas fa-cog SnMr-2"></i>Configurar empresa</a></li>
                                    <li class="divider"></li>
                                    <li class="SnPt-2 SnPb-2"><a href="<?= URL_PATH ?>/manager/logout"><i class="fas fa-sign-out-alt SnMr-2"></i>Cerrar sesión</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </header>
            </div>
            <div class="AdminLayout-main">
                <?php echo $content ?>
            </div>
            <div class="AdminLayout-aside">
                <div id="AsideMenu-wrapper" class="AsideMenu-wrapper">
                    <div class="AsideMenu-container">
                        <div class="AsideHeader">
                            <div class="Branding">
                                <a href="<?= URL_PATH ?>" class="Branding-link">
                                    <img src="<?= URL_PATH ?>/assets/images/icon/Icon-144.png" alt="Logo" class="Branding-img">
                                    <span class="Branding-name"><?= APP_NAME ?></span>
                                </a>
                            </div>
                        </div>
                        <ul class="AsideMenu" id="AsideMenu">
                            <li>
                                <a href="<?= URL_PATH ?>/"><i class="fas fa-tachometer-alt AsideMenu-icon"></i><span>Inicio </span> </a>
                            </li>
                            <li>
                                <a href="<?= URL_PATH ?>/company"><i class="fas fa-building AsideMenu-icon"></i><span>Empresas </span> </a>
                            </li>
                        </ul>
                        <div class="AsideFooter">
                            <div class="SnSwitch">
                                <input class="SnSwitch-control" type="checkbox" id="themeMode" >
                                <label class="SnSwitch-label" for="themeMode"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?= URL_PATH ?>/assets/script/common/admin.js"></script>
        <script src="<?= URL_PATH ?>/assets/script/manager/manager.js"></script>
        <script src="<?= URL_PATH ?>/assets/script/common/fontawesome.min.js"></script>
    </body>
</html>
