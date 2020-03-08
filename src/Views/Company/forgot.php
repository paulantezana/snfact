<div class="Login">
    <?php require_once __DIR__ . '/../partials/alertMessage.php' ?>
    <p>Ingresa tu correo electrónico para buscar tu cuenta</p>
    <form action="" method="post" class="SnForm">
        <div class="SnForm-item required">
            <label for="email" class="SnForm-label">Email</label>
            <div class="SnControl-wrapper">
                <i class="icon-envelop2 SnControl-prefix"></i>
                <input type="email" class="SnForm-control SnControl" required id="email" name="email" placeholder="Email">
            </div>
        </div>
        <button type="submit" class="SnBtn block primary SnMb-5" name="commit">Buscar</button>
        <a href="<?= URL_PATH ?>/publicCompany/login" class="SnBtn block">Login</a>
    </form>
</div>