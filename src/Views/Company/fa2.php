<?php require_once __DIR__ . '/layout/header.php'; ?>
<div class="SnContent">
    <div class="SnCard">
        <div class="SnCard-body">
            <?php require_once __DIR__ . '/partials/alertMessage.php' ?>
            <div>
                <div class="SnAlert warning">
                    <i class="icon-warning"></i>
                    Por motivos de seguridad le recomendamos que active la autenticación de dos factores. Caso contrario su cuenta expirara en 10 sesiones.
                </div>
                <h1>Seguridad adicional</h1>
                <p>Una vez que hayas ingresado la contraseña, se te pedirá un código de inicio de sesión.</p>
                <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=es_PE" target="_blank"  class="User-item">
                    <img src="<?= URL_PATH ?>/assets/images/fa2.png" alt="" class="SnAvatar User-avatar">
                    <div class="User-data">
                        <h3 class="User-title">Autenticador de Google</h3>
                        <p class="User-description">Recibirás un código de inicio de sesión a través de una app de autenticación.</p>
                    </div>
                </a>
            </div>
            <form action="" class="SnForm" method="post">
                <input type="hidden" class="SnForm-control" name="userId">
                <div class="Form-item">
                    <div style="text-align: center; padding: 3rem 0">
                        <img src="<?= URL_PATH ?>/admin/auth/renderQrCode?data=<?= $secret ?? '' ?>" alt="QrCode" style="max-width: 200px">
                    </div>
                    <input type="hidden" name="user2faSecret" value="<?= $secret ?? '' ?>">
                </div>
                <div class="SnForm-item">
                    <label for="user2faKey" class="SnForm-label">Clave</label>
                    <input type="text" class="SnForm-control" name="user2faKey" id="user2faKey">
                </div>
                <div class="SnForm-item">
                    <button type="submit" class="SnBtn primary block" name="commit2faKey">Validar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>
