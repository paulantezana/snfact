<div class="SnContent">
    <div class="SnToolbar">
        <div class="SnToolbar-left">
            <i class=" icon-equalizer SnMr-2"></i> <strong>CLIENTES</strong>
        </div>
        <div class="SnToolbar-right">
            <div class="SnBtn jsCustomerAction" onclick="CustomerToPrint()">
                <i class="icon-printer"></i>
            </div>
            <div class="SnBtn jsCustomerAction" onclick="CustomerToExcel()">
                <i class="icon-file-excel"></i>
            </div>
            <div class="SnBtn jsCustomerAction" onclick="CustomerList()">
                <i class="icon-reload-alt"></i>
            </div>
            <div class="SnBtn primary jsCustomerAction" onclick="CustomerShowModalCreate()">
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
            <form action="" class="SnForm" novalidate id="customerForm" onsubmit="CustomerSubmit(event)">
                <input type="hidden" class="SnForm-control" id="customerId">
                <div class="SnForm-item required">
                    <label class="SnForm-label" for="customerDocumentNumber">Número de documento</label>
                    <div class="SnControl-group">
                        <div class="SnControl-wrapper">
                            <i class="icon-user SnControl-prefix"></i>
                            <input class="SnForm-control SnControl" type="text" id="customerDocumentNumber" placeholder="Número de documento Aquí!" required>
                        </div>
                        <div class="SnBtn primary" onclick="CustomerQueryPeruDocument()"><i class="icon-search4"></i></div>
                    </div>
                </div>

                <div class="SnForm-item required">
                    <label for="customerIdentityDocumentCode" class="SnForm-label">Tipo de Documento de Identidad</label>
                    <select id="customerIdentityDocumentCode" class="SnForm-control" required>
                        <option value="">Elegir</option>
                        <?php foreach ($catIdentityDocumentTypeCode ?? [] as $row): ?>
                            <option value="<?= $row['code']?>"><?= $row['description']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="SnForm-item required">
                    <label for="customerSocialReason" class="SnForm-label">Razón social/Nombre Completo</label>
                    <div class="SnControl-wrapper">
                        <i class="icon-user SnControl-prefix"></i>
                        <input class="SnForm-control SnControl" type="text" id="customerSocialReason" required>
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="customerCommercialReason" class="SnForm-label">Razón comercial</label>
                    <div class="SnControl-wrapper">
                        <i class="icon-vcard SnControl-prefix"></i>
                        <input class="SnForm-control SnControl" type="text" id="customerCommercialReason">
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="customerFiscalAddress" class="SnForm-label">Dirección fiscal</label>
                    <div class="SnControl-wrapper">
                        <i class="icon-home2 SnControl-prefix"></i>
                        <input class="SnForm-control SnControl" type="text" id="customerFiscalAddress">
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="customerEmail" class="SnForm-label">Email</label>
                    <div class="SnControl-wrapper">
                        <i class="icon-envelop2 SnControl-prefix"></i>
                        <input class="SnForm-control SnControl" type="email" id="customerEmail">
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="customerTelephone" class="SnForm-label">Teléfono</label>
                    <div class="SnControl-wrapper">
                        <i class="icon-file-text2 SnControl-prefix"></i>
                        <input class="SnForm-control SnControl" type="text" id="customerTelephone">
                    </div>
                </div>
                <div class="SnForm-item">
                    <div class="SnSwitch">
                        <input class="SnSwitch-control" type="checkbox" id="customerState">
                        <label class="SnSwitch-label" for="customerState">Estado</label>
                    </div>
                </div>
                <button type="submit" class="SnBtn primary block" id="customerFormSubmit">Guardar</button>
            </form>
        </div>
    </div>
</div>