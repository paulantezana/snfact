<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="Login">
    <?php require_once __DIR__ . '/partials/alertMessage.php' ?>
    <p>Ingresa tu correo electrónico para buscar tu cuenta</p>
    <form action="<?= URL_PATH ?>/auth/forgot" method="post" class="SnForm">
        <div class="SnForm-item required">
            <label for="email" class="SnForm-label">Email</label>
            <input type="email" class="SnForm-input" required id="email" name="email">
        </div>
        <button type="submit" class="SnBtn block primary SnMb-16" name="commit">Buscar</button>
        <a href="<?= URL_PATH ?>" class="SnBtn block">Login</a>
    </form>
</div>

<?php require_once __DIR__ . '/layout/footer.php' ?>