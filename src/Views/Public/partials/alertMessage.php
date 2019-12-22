<?php if (isset($message)) : ?>
    <div class="SnAlert <?php echo isset($messageType) ? $messageType : '' ?> SnMb-5"><i class="SnIcon-<?php echo isset($messageType) ? $messageType : '' ?>"></i><?php echo $message ?></div>
<?php endif; ?>