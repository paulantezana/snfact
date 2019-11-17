<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once __DIR__ . '/../../head.php'; ?>

    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/dist/css/admin.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/dist/css/nprogress.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/libraries/fonts/styles.css">

    <script src="<?= URL_PATH ?>/assets/dist/script/nprogress-min.js"></script>
    <script src="<?= URL_PATH ?>/assets/dist/script/sedna-min.js"></script>
    <script src="<?= URL_PATH ?>/assets/dist/script/conmon-min.js"></script>
</head>

<body>
    <div class="AdminLayout SnAdminL2" id="AdminLayout">
        <div class="AdminLayout-header">
            <header class="Header">
                <div class="Header-left">
                    <div id="AsideMenu-toggle"> <i class="icon-menu"></i> </div>
                </div>
                <div class="Header-right">
                    <ul class="HeaderMenu SnMenu">
                        <li>
                            <div class="Header-action">
                                <select class="SnForm-select">
                                    <option value="">Seleccionar local</option>
                                    <?php
                                        if (isset($_SESSION[SESS_LOCALS]) && isset($_SESSION[SESS_CURRENT_LOCAL])):
                                            foreach ($_SESSION[SESS_LOCALS] as $row):
                                                ?>
                                                    <option value="<?= $row['business_local_id'] ?>" <?php echo $_SESSION[SESS_CURRENT_LOCAL] === $row['business_local_id'] ? 'selected' : '' ?>>
                                                        <?= $row['short_name'] ?>
                                                    </option>
                                                <?php
                                            endforeach;
                                        endif;
                                    ?>
                                </select>
                            </div>
                        </li>
                        <li>
                            <a href="<?= URL_PATH ?>/customer" class="Header-action">
                                <i class="icon-check"></i>
                                Produción
                            </a>
                        </li>
                        <li>
                            <a href="<?= URL_PATH ?>/busines" class="Header-action">
                                <i class="icon-bell-o"></i>
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
                                    <div class="small s-text-ellipsis">
                                        <div class="User-title"><?= $_SESSION[SESS_DATA]['user_name'] ?></div>
                                        <div class="User-description"><?= $_SESSION[SESS_DATA]['email'] ?></div>
                                    </div>
                                </li>
                                <li><a href="<?= URL_PATH ?>/auth/profile"> <i class="icon-user"></i> Perfil</a></li>
                                <li><a href="<?= URL_PATH ?>/"> <i class="icon-sign-out"></i> Soporte</a></li>
                                <li><a href="<?= URL_PATH ?>/business/update"> <i class="icon-cog"></i> Configurar empresa</a></li>
                                <li><a href="<?= URL_PATH ?>/auth/logout"> <i class="icon-sign-out"></i> Cerrar sesión</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </header>
        </div>
        <div class="AdminLayout-main">