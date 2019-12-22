<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once __DIR__ . '/../../Helpes/head.php'; ?>

    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/company.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/nprogress.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/libraries/fonts/style.css">

    <script src="<?= URL_PATH ?>/assets/script/common/nprogress.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/common/common.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/common/sedna.js"></script>
</head>

<body>
    <div class="AdminLayout SnAdminL2" id="AdminLayout">
        <div class="AdminLayout-header">
            <header class="Header">
                <div class="Header-left">
                    <div id="AsideMenu-toggle"> <i class="icon-list2"></i> </div>
                </div>
                <div class="Header-right">
                    <ul class="HeaderMenu SnMenu">
                        <li>
                            <a href="<?= URL_PATH ?>/busines" class="Header-action">
                                <i class="icon-bell3"></i>
                            </a>
                        </li>
                        <li>
                            <div class="HeaderMenu-profile Header-action">
                                <div class="SnAvatar">
                                    <img src="<?= URL_PATH ?>/assets/images/logo.png" alt="avatar">
                                </div>
                            </div>
                            <ul>
                                <li class="User-item">
                                    <a href="<?= URL_PATH ?>/auth/profile" class="SnAvatar">
                                        <img src="<?= URL_PATH ?>/assets/images/logo.png" alt="avatar">
                                    </a>
                                    <div class="">
                                        <div class="User-title" id="userTitleInfo"></div>
                                        <div class="User-description" id="userDescriptionInfo"></div>
                                    </div>
                                </li>
                                <li><a href="<?= URL_PATH ?>/auth/profile"> <i class="icon-user-plus SnMr-2"></i> Perfil</a></li>
                                <li><a href="<?= URL_PATH ?>/"> <i class="icon-help SnMr-2"></i> Soporte</a></li>
                                <li><a href="<?= URL_PATH ?>/business/update"> <i class="icon-cog SnMr-2"></i> Configurar empresa</a></li>
                                <li><a href="<?= URL_PATH ?>/auth/logout"> <i class="icon-switch2 SnMr-2"></i> Cerrar sesión</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </header>
        </div>
        <div class="AdminLayout-main">
