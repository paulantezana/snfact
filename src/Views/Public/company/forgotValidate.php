<?php require_once __DIR__ . '/../layout/header.php'; ?>
<div class="Login">
    <?php require_once __DIR__ . '/../partials/alertMessage.php' ?>
    <?php if (($contentType === 'validateToken' && $messageType === 'success') || ($contentType === 'changePassword'  && $messageType === 'error')):  ?>
        <form action="<?= URL_PATH . '/forgot/validate' ?>" method="POST">
            <input type="hidden" name="userId" id="userId" value="<?php echo $user['user_id'] ?? ''; ?>">
            <div class="SnForm-item required">
                <label for="password" class="SnForm-label">Contraseña</label>
                <div class="SnControl-wrapper">
                    <i class="icon-lock SnControl-prefix"></i>
                    <input type="password" class="SnForm-control SnControl" required id="password" name="password" placeholder="Contraseña">
                    <span class="SnControl-suffix icon-eye togglePassword"></span>
                </div>
            </div>

            <div class="SnForm-item required">
                <label for="confirmPassword" class="SnForm-label">Confirmar Contraseña</label>
                <div class="SnControl-wrapper">
                    <i class="icon-lock SnControl-prefix"></i>
                    <input type="password" class="SnForm-control SnControl" required id="confirmPassword" name="confirmPassword" placeholder="Contraseña">
                    <span class="SnControl-suffix icon-eye togglePassword"></span>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="SnBtn block primary" name="commit">Cambiar contraseña</button>
            </div>
        </form>
    <?php endif; ?>
    <a href="<?= URL_PATH ?>/login" class="SnBtn block">Login</a>
</div>
<?php require_once __DIR__ . '/../layout/footer.php' ?>