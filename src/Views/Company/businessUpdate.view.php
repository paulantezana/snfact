<div class="SnContent">
    <div class="SnCard">
        <div class="SnCard-body">
            <?php require_once __DIR__ . '/../partials/alertMessage.php' ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="businessId" value="<?= $parameter['business']['business_id'] ?>" name="business[business_id]">
                <div class="SnGrid m-grid-3">
                    <div class="SnForm-item required">
                        <label for="businessRuc" class="SnForm-label">RUC</label>
                        <div class="SnControl-wrapper">
                            <i class="icon-barcode2 SnControl-prefix"></i>
                            <input type="text" class="SnForm-control SnControl" value="<?= $parameter['business']['ruc'] ?>" name="business[ruc]" id="businessRuc" required>
                        </div>
                    </div>
                    <div class="SnForm-item required">
                        <label for="businessSocialReason" class="SnForm-label">Rasón social</label>
                        <div class="SnControl-wrapper">
                            <i class="icon-vcard SnControl-prefix"></i>
                            <input type="text" class="SnForm-control SnControl" value="<?= $parameter['business']['social_reason'] ?>" name="business[social_reason]" id="businessSocialReason" required>
                        </div>
                    </div>
                    <div class="SnForm-item required">
                        <label for="businessCommercialReason" class="SnForm-label">Rasón comercial</label>
                        <div class="SnControl-wrapper">
                            <i class="icon-home4 SnControl-prefix"></i>
                            <input type="text" class="SnForm-control SnControl" value="<?= $parameter['business']['commercial_reason'] ?>" name="business[commercial_reason]" id="businessCommercialReason" required>
                        </div>
                    </div>
                    <div class="SnForm-item required">
                        <label for="businessEmail" class="SnForm-label">Email</label>
                        <div class="SnControl-wrapper">
                            <i class="icon-envelop2 SnControl-prefix"></i>
                            <input type="text" class="SnForm-control SnControl" value="<?= $parameter['business']['email'] ?>" name="business[email]" id="businessEmail" required>
                        </div>
                    </div>
                    <div class="SnForm-item required">
                        <label for="businessPhone" class="SnForm-label">Telefono</label>
                        <div class="SnControl-wrapper">
                            <i class="icon-phone2 SnControl-prefix"></i>
                            <input type="text" class="SnForm-control SnControl" value="<?= $parameter['business']['phone'] ?>" name="business[phone]" id="businessPhone" required>
                        </div>
                    </div>
                    <div class="SnForm-item required">
                        <label for="businessWebSite" class="SnForm-label">Sitio web</label>
                        <div class="SnControl-wrapper">
                            <i class="icon-sphere SnControl-prefix"></i>
                            <input type="text" class="SnForm-control SnControl" value="<?= $parameter['business']['web_site'] ?>" name="business[web_site]" id="businessWebSite" required>
                        </div>
                    </div>
                </div>
                <?php if ($parameter['business']['logo']): ?>
                    <div class="Form-item SnMt-5 SnMb-5">
                        <img src="<?php echo URL_PATH . '/' .  ($parameter['business']['logo'] ?? '') ?>" alt="logo emisor electronico"
                            style="width: 320px; height: 80px; background: #F5F5F5; display: block;">
                    </div>
                <?php endif; ?>
                <div class="SnForm-item">
                    <label class="SnForm-label" for="businessLogo">Logotipo en formato .JPG para Facturas (320px por 80px) menos de 20 KB </label>
                    <input type="file" class="SnForm-control" name="businessLogo" id="businessLogo"  accept="image/png,image/jpeg,image/jpg">
                </div>
                <button type="submit" class="SnBtn primary block" name="businessCommit">Guardar</button>
            </form>
        </div>
    </div>
</div>