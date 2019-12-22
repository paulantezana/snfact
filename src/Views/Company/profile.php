<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="SnContent">
    <div class="SnCard">
        <div class="SnCard-body">
            <?php require_once __DIR__ . '/partials/alertMessage.php' ?>
            <div class="SnTab">
                <input type="hidden" id="userId" value="<?php echo $_SESSION[SESS_KEY] ?? 0 ?>">
                <div class="SnTab-header">
                    <div class="SnTab-title is-active">Perfil</div>
                    <div class="SnTab-title">Seguridad</div>
                </div>
                <div class="SnTab-content">
                    <div class="SnGrid m-grid-2 SnMb-5">
                        <div>
                            <strong>Perfil</strong>
                            <p>Su dirección de correo electrónico es su identidad en <?= APP_NAME ?> y se utiliza para iniciar sesión.</p>
                        </div>
                        <form action="" class="SnForm" method="post" onsubmit="ProfileUpdateProfile(event)">
                            <div class="SnForm-item required">
                                <label for="userEmail" class="SnForm-label">Email</label>
                                <div class="SnControl-wrapper">
                                    <i class="icon-envelop2 SnControl-prefix"></i>
                                    <input type="email" class="SnForm-control SnControl" required id="userEmail" placeholder="Email" value="<?= $user['email'] ?>">
                                </div>
                            </div>
                            <div class="SnForm-item required">
                                <label for="userUserName" class="SnForm-label">Nombre de usuario</label>
                                <div class="SnControl-wrapper">
                                    <i class="icon-user SnControl-prefix"></i>
                                    <input type="text" class="SnForm-control SnControl" required id="userUserName" placeholder="Nombre de usuario" value="<?= $user['user_name'] ?>">
                                </div>
                            </div>
                            <div class="SnForm-item">
                                <button type="submit" class="SnBtn primary block" name="commitUser">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="SnTab-content">
                    <div class="SnGrid m-grid-2 SnMb-5">
                        <div>
                            <strong>Password</strong>
                            <p>Cambiar su contraseña también restablecerá su clave</p>
                        </div>
                        <form action="" class="SnForm" method="post" onsubmit="ProfileUpdatePassword(event)">
                            <div class="SnForm-item required">
                                <label for="userPassword" class="SnForm-label">Contraseña</label>
                                <div class="SnControl-wrapper">
                                    <i class="icon-lock SnControl-prefix"></i>
                                    <input type="password" class="SnForm-control SnControl" id="userPassword" placeholder="Contraseña">
                                    <span class="SnControl-suffix icon-eye togglePassword"></span>
                                </div>
                            </div>
                            <div class="SnForm-item required">
                                <label for="userPasswordConfirm" class="SnForm-label">Confirmar contraseña</label>
                                <div class="SnControl-wrapper">
                                    <i class="icon-lock SnControl-prefix"></i>
                                    <input type="password" class="SnForm-control SnControl" id="userPasswordConfirm" placeholder="Confirmar contraseña">
                                    <span class="SnControl-suffix icon-eye togglePassword"></span>
                                </div>
                            </div>
                            <div class="SnForm-item">
                                <button type="submit" class="SnBtn primary block" name="commitChangePassword">Guardar</button>
                            </div>
                        </form>
                    </div>

                    <div class="SnGrid m-grid-2">
                        <div>
                            <strong>Seguridad adicional</strong>
                            <p>Una vez que hayas ingresado la contraseña, se te pedirá un código de inicio de sesión.</p>
                            <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=es_PE" target="_blank">
                                <img src="<?= URL_PATH ?>/assets/images/fa2.png" alt="" class="SnAvatar User-avatar">
                                <div class="">
                                    <h3 class="">Autenticador de Google</h3>
                                    <p class="">Recibirás un código de inicio de sesión a través de una app de autenticación.</p>
                                </div>
                            </a>
                        </div>
                        <form action="" class="SnForm" method="post">
                            <div class="SnForm-item">
                                <style>
                                    .qr{
                                        width: 250px;
                                        height: 250px;
                                        margin-right: auto;
                                        margin-left: auto;
                                    }
                                    .qr .on{
                                        background: black;
                                    }
                                </style>
                                <div style="padding: 2rem 0; background: white">
                                    <?php echo $qrCodeTable ?? '' ?>
                                </div>
                            </div>
                            <div class="Form-item">
                                <input type="hidden" name="user2faSecret" value="<?= $secret ?? '' ?>">
                            </div>
                            <div class="SnForm-item required">
                                <label for="user2faKey" class="SnForm-label">Clave</label>
                                <div class="SnControl-wrapper">
                                    <i class="icon-key SnControl-prefix"></i>
                                    <input type="text" class="SnForm-control SnControl" required id="user2faKey" placeholder="###">
                                </div>
                            </div>
                            <div class="SnForm-item">
                                <div class="SnSwitch">
                                    <input class="SnSwitch-input" type="checkbox" id="user2faKeyEnable">
                                    <label class="SnSwitch-label" for="user2faKeyEnable">Activar</label>
                                </div>
                            </div>
                            <div class="SnForm-item">
                                <button type="submit" class="SnBtn primary block" name="commit2faKey">Validar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= URL_PATH ?>/assets/script/company/profile.js"></script>

<?php require_once __DIR__ . '/layout/footer.php'; ?>