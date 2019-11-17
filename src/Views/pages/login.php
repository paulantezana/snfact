<?php require_once __DIR__ . '/layout/header.php'; ?>

    <div class="Login">
        <?php require_once __DIR__ . '/partials/alertMessage.php' ?>
        <form action="<?= URL_PATH ?>/auth/login" method="post" class="SnForm">
            <div class="SnForm-item required">
                <label for="username" class="SnForm-label">Nombre de usuario</label>

                <input type="text" class="SnForm-input" required id="username" name="user">
            </div>
            <div class="SnForm-item required">
                <label for="password" class="SnForm-label">Contraseña</label>
                <div class="SnInput-wrapper">
                    <input type="password" class="SnForm-input" required id="password" name="password">
                    <span class="SnInput-suffix icon-eye togglePassword"></span>
                </div>
            </div>
            <div class="SnForm-item SnFlex">
                <div>
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Recuerdame</label>
                </div>
                <a href="<?= URL_PATH ?>/forgot">¿Olvido su contraseña?</a>
            </div>
            <button type="submit" class="SnBtn block primary" name="commit">Login</button>
            <p>O <a href="<?= URL_PATH ?>/register">Registrate ahora</a></p>
        </form>
    </div>

<?php require_once __DIR__ . '/layout/footer.php' ?>