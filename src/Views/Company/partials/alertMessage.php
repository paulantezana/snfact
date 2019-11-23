<?php if (isset($message) ? $message : '') : ?>
    <div class="SnAlert <?= isset($messageType) ? $messageType : '' ?> SnMb-32"><i class="icon-<?= isset($messageType) ? $messageType : '' ?>"></i><?php echo $message ?></div>
<?php endif; ?>