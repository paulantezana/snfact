<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once __DIR__ . '/../../Helpes/head.php'; ?>
        <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/public.css">
        <link rel="stylesheet" href="<?= URL_PATH ?>/assets/libraries/fonts/style.css">
    </head>
    <body>
    <div class="BasicLayout" id="BasicLayout">
        <div class="BasicLayout-header">
            <div class="WelcomeHome">
                <div class="WelcomeHome-title">Biemvenido a</div>
                <div class="WelcomeHome-subTitle">SnFact</div>
                <p class="WelcomeHome-description">Facturacion electronica </p>
                <div class="WelcomeHome-actions">
                    <div class="SnBtn white">Mas informacion</div>
                </div>
            </div>
        </div>
        <div class="BasicLayout-main">
            <h1 class="Branding">
                <a href="<?php echo URL_PATH ?>" class="Branding-header">
                    <img src="<?php echo URL_PATH ?>/assets/images/icon/Icon-144.png" alt="" class="Branding-img">
                    <div> <?php echo APP_NAME ?> </div>
                </a>
                <div class="Branding-description">Facturación electronica</div>
            </h1>
            <div class="BasicLayout-mainContent">