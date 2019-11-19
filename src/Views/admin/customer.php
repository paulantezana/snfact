<?php require_once __DIR__ . '/layout/header.php'; ?>
    <div class="SnContent">
        <?php require_once  __DIR__ . '/partials/invoiceToolbar.php'; ?>
        <div class="SnToolbar">
            <div class="SnToolbar-left">
                <i class="icon-angle-right"></i> Clientes
            </div>
            <div class="SnToolbar-right">
                <div class="SnBtn jsCustomerOption" onclick="CustomerForm.list()">
                    <i class="icon-refresh"></i>
                    Actualizar
                </div>
                <div class="SnBtn primary jsCustomerOption" onclick="CustomerForm.showModalCreate()">
                    <i class="icon-plus"></i>
                    Nuevo
                </div>
            </div>
        </div>
        <div class="SnCard">
            <div class="SnCard-body">
                <div class="SnInput-wrapper SnMb-16">
                    <input type="text" class="SnForm-input" onkeyup="CustomerForm.search(event)">
                    <span class="SnInput-suffix icon-search"></span>
                </div>
                <div id="customerTable"></div>
            </div>
        </div>
    </div>

    <script src="<?= URL_PATH ?>/assets/dist/script/customer-min.js"></script>

    <div class="SnModal-wrapper" data-modal="customerModalForm">
        <div class="SnModal">
            <div class="SnModal-close" data-modalclose="customerModalForm">
                <svg viewBox="64 64 896 896" class="" data-icon="close" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">
                    <path d="M563.8 512l262.5-312.9c4.4-5.2.7-13.1-6.1-13.1h-79.8c-4.7 0-9.2 2.1-12.3 5.7L511.6 449.8 295.1 191.7c-3-3.6-7.5-5.7-12.3-5.7H203c-6.8 0-10.5 7.9-6.1 13.1L459.4 512 196.9 824.9A7.95 7.95 0 0 0 203 838h79.8c4.7 0 9.2-2.1 12.3-5.7l216.5-258.1 216.5 258.1c3 3.6 7.5 5.7 12.3 5.7h79.8c6.8 0 10.5-7.9 6.1-13.1L563.8 512z"></path>
                </svg>
            </div>
            <div class="SnModal-header">Cliente</div>
            <div class="SnModal-body">
                <form action="" class="SnForm" id="customerForm" onsubmit="CustomerForm.submit(event)">
                    <input type="hidden" class="SnForm-input" id="customerId">
                    <div class="SnForm-item required">
                        <label for="customerIdentityDocumentCode" class="SnForm-label">Tipo de Documento de Identidad</label>
                        <select id="customerIdentityDocumentCode" class="SnForm-select">
                            <option value="">Elegir</option>
                            <?php foreach ($catIdentityDocumentTypeCode ?? [] as $row): ?>
                                <option value="<?= $row['code']?>"><?= $row['description']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="SnForm-item required">
                        <label for="customerDocumentNumber" class="SnForm-label">Documento de Identidad</label>
                        <input type="text" class="SnForm-input" id="customerDocumentNumber">
                    </div>
                    <div class="SnForm-item required">
                        <label for="customerSocialReason" class="SnForm-label">Razón social/Nombre Completo</label>
                        <input type="text" class="SnForm-input" id="customerSocialReason">
                    </div>
                    <div class="SnForm-item">
                        <label for="customerCommercialReason" class="SnForm-label">Razón comercial</label>
                        <input type="text" class="SnForm-input" id="customerCommercialReason">
                    </div>
                    <div class="SnForm-item">
                        <label for="customerFiscalAddress" class="SnForm-label">Dirección fiscal</label>
                        <input type="text" class="SnForm-input" id="customerFiscalAddress">
                    </div>
                    <div class="SnForm-item">
                        <label for="customerEmail" class="SnForm-label">Email</label>
                        <input type="email" class="SnForm-input" id="customerEmail">
                    </div>
                    <div class="SnForm-item">
                        <label for="customerTelephone" class="SnForm-label">Teléfono</label>
                        <input type="text" class="SnForm-input" id="customerTelephone">
                    </div>
                    <div class="SnForm-item">
                        <p>Estado</p>
                        <input class="SnSwitch SnSwitch-ios" id="customerState" type="checkbox">
                        <label class="SnSwitch-btn" for="customerState"></label>
                    </div>
                    <div class="SnForm-item">
                        <button type="submit" class="SnBtn primary block" id="customerFormSubmit">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
require_once __DIR__ . '/layout/footer.php'
?>