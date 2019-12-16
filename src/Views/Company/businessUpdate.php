<?php require_once __DIR__ . '/layout/header.php'; ?>
    <div class="SnContent">
        <div class="SnCard">
            <?php  if(isset($business)): ?>
                <div class="SnCard-body">
                    <?php require_once __DIR__ . '/partials/alertMessage.php' ?>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="businessId" value="<?= $business['business_id'] ?>" name="business[business_id]">
                        <div class="SnGrid m-3">
                            <div class="SnForm-item required">
                                <label for="businessRuc" class="SnForm-label">RUC</label>
                                <input type="text" class="SnForm-control" value="<?= $business['ruc'] ?>" name="business[ruc]" id="businessRuc" required>
                            </div>
                            <div class="SnForm-item required">
                                <label for="businessSocialReason" class="SnForm-label">Rasón social</label>
                                <input type="text" class="SnForm-control" value="<?= $business['social_reason'] ?>" name="business[social_reason]" id="businessSocialReason" required>
                            </div>
                            <div class="SnForm-item required">
                                <label for="businessCommercialReason" class="SnForm-label">Rasón comercial</label>
                                <input type="text" class="SnForm-control" value="<?= $business['commercial_reason'] ?>" name="business[commercial_reason]" id="businessCommercialReason" required>
                            </div>
                        </div>
                        <div class="SnGrid m-3">
                            <div class="SnForm-item required">
                                <label for="businessEmail" class="SnForm-label">Email</label>
                                <input type="text" class="SnForm-control" value="<?= $business['email'] ?>" name="business[email]" id="businessEmail" required>
                            </div>
                            <div class="SnForm-item required">
                                <label for="businessPhone" class="SnForm-label">Telefono</label>
                                <input type="text" class="SnForm-control" value="<?= $business['phone'] ?>" name="business[phone]" id="businessPhone" required>
                            </div>
                            <div class="SnForm-item required">
                                <label for="businessWebSite" class="SnForm-label">Sitio web</label>
                                <input type="text" class="SnForm-control" value="<?= $business['web_site'] ?>" name="business[web_site]" id="businessWebSite" required>
                            </div>
                        </div>
                        <img
                                src="<?php echo URL_PATH . '/' .  ($business['logo'] ?? '') ?>" alt="logo emisor electronico"
                                style="width: 320px; height: 80px; background: #F5F5F5; display: block;"
                        >
                        <div class="SnForm-item">
                            <label class="SnForm-label" for="businessLogo">Logotipo en formato .JPG para Facturas (320px por 80px) menos de 20 KB </label>
                            <input type="file" class="SnForm-control" name="businessLogo" id="businessLogo"  accept="image/png,image/jpeg,image/jpg">
                        </div>
                        <div class="SnForm-item">
                            <button type="submit" class="SnBtn primary block" name="businessCommit">Guardar</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>