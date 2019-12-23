<?php require_once __DIR__ . '/layout/header.php'; ?>
    <div class="SnContent">
        <div class="SnToolbar">
            <div class="SnToolbar-left">
                <i class="icon-dots SnMr-2"></i> Clientes
            </div>
            <div class="SnToolbar-right">
                <div class="SnBtn jsCustomerOption" onclick="CustomerList()">
                    <i class="icon-reload-alt SnMr-2"></i> Actualizar
                </div>
                <div class="SnBtn primary jsCustomerOption" onclick="CustomerShowModalCreate()">
                    <i class="icon-plus2 SnMr-2"></i> Nuevo
                </div>
            </div>
        </div>
        <div class="SnCard">
            <div class="SnCard-body">
                <div class="SnControl-wrapper SnMb-5">
                    <input type="text" class="SnForm-control SnControl" id="searchContent" placeholder="Buscar...">
                    <span class="SnControl-suffix icon-search4"></span>
                </div>
                <div id="customerTable"></div>
            </div>
        </div>
    </div>

    <script src="<?= URL_PATH ?>/assets/script/company/customer.js"></script>

    <div class="SnModal-wrapper" data-modal="customerModalForm">
        <div class="SnModal">
            <div class="SnModal-close" data-modalclose="customerModalForm">
                <i class="icon-cross"></i>
            </div>
            <div class="SnModal-header"><i class="icon-file-plus SnMr-2"></i> Cliente</div>
            <div class="SnModal-body">
                <form action="" class="SnForm" id="customerForm" onsubmit="CustomerSubmit(event)">
                    <input type="hidden" class="SnForm-control" id="customerId">
                    <div class="SnForm-item required">
                        <label class="SnForm-label" for="customerDocumentNumber">Número de documento</label>
                        <div class="SnControl-group">
                            <div class="SnControl-wrapper">
                                <i class="icon-user SnControl-prefix"></i>
                                <input class="SnForm-control SnControl" type="text" id="customerDocumentNumber" placeholder="Número de documento Aquí!">
                            </div>
                            <div class="SnBtn primary" onclick="CustomerQueryPeruDocument()"><i class="icon-search4"></i></div>
                        </div>
                    </div>

                    <div class="SnForm-item required">
                        <label for="customerIdentityDocumentCode" class="SnForm-label">Tipo de Documento de Identidad</label>
                        <select id="customerIdentityDocumentCode" class="SnForm-control">
                            <option value="">Elegir</option>
                            <?php foreach ($catIdentityDocumentTypeCode ?? [] as $row): ?>
                                <option value="<?= $row['code']?>"><?= $row['description']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="SnForm-item required">
                        <label for="customerSocialReason" class="SnForm-label">Razón social/Nombre Completo</label>
                        <input type="text" class="SnForm-control" id="customerSocialReason">
                    </div>
                    <div class="SnForm-item">
                        <label for="customerCommercialReason" class="SnForm-label">Razón comercial</label>
                        <input type="text" class="SnForm-control" id="customerCommercialReason">
                    </div>
                    <div class="SnForm-item">
                        <label for="customerFiscalAddress" class="SnForm-label">Dirección fiscal</label>
                        <input type="text" class="SnForm-control" id="customerFiscalAddress">
                    </div>
                    <div class="SnForm-item">
                        <label for="customerEmail" class="SnForm-label">Email</label>
                        <input type="email" class="SnForm-control" id="customerEmail">
                    </div>
                    <div class="SnForm-item">
                        <label for="customerTelephone" class="SnForm-label">Teléfono</label>
                        <input type="text" class="SnForm-control" id="customerTelephone">
                    </div>
                    <div class="SnForm-item">
                        <div class="SnSwitch">
                            <input class="SnSwitch-input" type="checkbox" id="customerState">
                            <label class="SnSwitch-label" for="customerState">Estado</label>
                        </div>
                    </div>
                    <button type="submit" class="SnBtn primary block" id="customerFormSubmit">Guardar</button>
                </form>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/layout/footer.php' ?>