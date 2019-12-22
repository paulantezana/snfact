<?php if (isset($message) ? $message : '') : ?>
    <div class="SnAlert <?= isset($messageType) ? $messageType : '' ?> SnMb-5"><i class="SnIcon-<?= isset($messageType) ? $messageType : '' ?>"></i><?php echo $message ?></div>
<?php endif; ?>