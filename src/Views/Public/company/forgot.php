<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="Login">
    <?php require_once __DIR__ . '/../partials/alertMessage.php' ?>
    <p>Ingresa tu correo electrónico para buscar tu cuenta</p>
    <form action="" method="post" class="SnForm">
        <div class="SnForm-item required">
            <label for="email" class="SnForm-label">Email</label>
            <div class="SnControl-wrapper">
                <i class="icon-email-streamline SnControl-prefix"></i>
                <input type="email" class="SnForm-control SnControl" required id="email" name="email" placeholder="Email">
            </div>
        </div>
        <button type="submit" class="SnBtn block primary SnMb-5" name="commit">Buscar</button>
        <a href="<?= URL_PATH ?>/login" class="SnBtn block">Login</a>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php' ?>