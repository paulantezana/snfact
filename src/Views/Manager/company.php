<div class="SnContent">
    <div class="SnToolbar">
        <div class="SnToolbar-left">
            <i class=" fas fa-list-ul SnMr-2"></i><strong>EMPRESAS</strong>
        </div>
        <div class="SnToolbar-right">
            <div class="SnBtn jsCompanyAction" onclick="CompanyToPrint()">
                <i class="fas fa-print"></i>
            </div>
            <div class="SnBtn jsCompanyAction" onclick="CompanyToExcel()">
                <i class="far fa-file-excel"></i>
            </div>
            <div class="SnBtn jsCompanyAction" onclick="CompanyList()">
                <i class="fas fa-sync-alt"></i>
            </div>
            <div class="SnBtn primary jsCompanyAction" onclick="CompanyShowModalCreate()">
                <i class="fas fa-plus SnMr-2"></i>Nuevo
            </div>
        </div>
    </div>
    <div class="SnCard">
        <div class="SnCard-body">
            <div class="SnControl-wrapper SnMb-5">
                <input type="text" class="SnForm-control SnControl" id="searchContent" placeholder="Buscar...">
                <span class="SnControl-suffix icon-search4"></span>
            </div>
            <div id="companyTable"></div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/manager/company.js"></script>

<div class="SnModal-wrapper" data-modal="companyModalForm">
    <div class="SnModal">
        <div class="SnModal-close" data-modalclose="companyModalForm">
            <i class="fas fa-times"></i>
        </div>
        <div class="SnModal-header"><i class="fas fa-folder-plus SnMr-2"></i>Empresa</div>
        <div class="SnModal-body">
            <form action="" class="SnForm" id="companyForm" novalidate onsubmit="CompanySubmit(event)">
                <input type="hidden" class="SnForm-control" id="companyId">
                <div class="SnForm-item required">
                    <label for="companyRuc" class="SnForm-label">RUC</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-barcode SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" required id="companyRuc" placeholder="Nombre de usuario">
                    </div>
                </div>
                <div class="SnGrid s-grid-2">
                    <div class="SnForm-item required">
                        <label for="companyEmail" class="SnForm-label">Email</label>
                        <div class="SnControl-wrapper">
                            <i class="fas fa-envelope SnControl-prefix"></i>
                            <input type="email" class="SnForm-control SnControl" required id="companyEmail" placeholder="Email">
                        </div>
                    </div>
                    <div class="SnForm-item required">
                        <label for="companyWebSite" class="SnForm-label">Sitio Web</label>
                        <div class="SnControl-wrapper">
                            <i class="fas fa-globe SnControl-prefix"></i>
                            <input type="url" class="SnForm-control SnControl" required id="companyWebSite" placeholder="Sitio Web">
                        </div>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="companyCommercialReason" class="SnForm-label">Razon comercial</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-id-card SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" required id="companyCommercialReason" placeholder="Razon comercial">
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="companyPhone" class="SnForm-label">Telefono</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-phone SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" required id="companyPhone" placeholder="Telefono">
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="companyUserName" class="SnForm-label">Nombre de usuario</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-user SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" required id="companyUserName" placeholder="Nombre de usuario">
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="companyPassword" class="SnForm-label">Contraseña</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-key SnControl-prefix"></i>
                        <input type="password" class="SnForm-control SnControl" required id="companyPassword" placeholder="Contraseña">
                        <span class="SnControl-suffix fas fa-eye togglePassword"></span>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="companyPasswordConfirm" class="SnForm-label">Confirmar contraseña</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-key SnControl-prefix"></i>
                        <input type="password" class="SnForm-control SnControl" required id="companyPasswordConfirm" placeholder="Confirmar contraseña">
                        <span class="SnControl-suffix fas fa-eye togglePassword"></span>
                    </div>
                </div>
                <div class="SnForm-item">
                    <div class="SnSwitch">
                        <input class="SnSwitch-control" type="checkbox" id="companyEnvironment">
                        <label class="SnSwitch-label" for="companyEnvironment">Producción</label>
                    </div>
                </div>
                <div class="SnForm-item">
                    <div class="SnSwitch">
                        <input class="SnSwitch-control" type="checkbox" id="companyState">
                        <label class="SnSwitch-label" for="companyState">Estado</label>
                    </div>
                </div>
                <button type="submit" class="SnBtn primary block" id="companyFormSubmit">Guardar</button>
            </form>
        </div>
    </div>
</div>

<div class="SnModal-wrapper" data-modal="companyModalLogoForm">
    <div class="SnModal">
        <div class="SnModal-close" data-modalclose="companyModalLogoForm">
            <i class="fas fa-times"></i>
        </div>
        <div class="SnModal-header"><i class="fas fa-folder-plus SnMr-2"></i>Logo</div>
        <div class="SnModal-body">
            <form action="" class="SnForm" id="companyLogoForm" novalidate onsubmit="CompanyLogoSubmit()">
                <input type="hidden" class="SnForm-control" id="companyLogoId">
                <div class="SnUpload-wrapper">
                    <input type="file" id="companyLogo" accept="image/x-png,image/jpeg,image/png,image/svg">
                    <img src="" alt="logo" id="companyLogoImg">
                </div>
                <button type="submit" class="SnBtn primary block" id="companyLogoFormSubmit">Guardar</button>
            </form>
        </div>
    </div>
</div>
