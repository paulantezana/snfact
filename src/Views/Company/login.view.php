<div class="Login">
    <?php require_once __DIR__ . '/../partials/alertMessage.php' ?>
    <form action="" method="post" class="SnForm">
        <div class="SnForm-item required">
            <label for="username" class="SnForm-label">Nombre de usuario</label>
            <div class="SnControl-wrapper">
                <i class="fas fa-user SnControl-prefix"></i>
                <input type="text" class="SnForm-control SnControl" required id="username" name="user" placeholder="Nombre de usuario">
            </div>
        </div>
        <div class="SnForm-item required">
            <label for="password" class="SnForm-label">Contraseña</label>
            <div class="SnControl-wrapper">
                <i class="fas fa-lock SnControl-prefix"></i>
                <input type="password" class="SnForm-control SnControl" required id="password" name="password" placeholder="Contraseña">
                <span class="SnControl-suffix fas fa-eye togglePassword"></span>
            </div>
        </div>
        <button type="submit" class="SnBtn block primary" name="commit">Login</button>
        <p style="text-align: center">
          <span>¿No tienes una cuenta? <a href="<?= URL_PATH ?>/publicCompany/register"> Registrate ahora</a></span>
          <a href="<?= URL_PATH ?>/publicCompany/forgot"> ¿Olvido su contraseña?</a>
        </p>
    </form>
</div>
