            </div>
            <div class="AdminLayout-aside">
                <div id="AsideMenu-wrapper" class="AsideMenu-wrapper">
                    <div class="AsideMenu-container">
                        <div class="AsideHeader">
                            <div class="Branding">
                                <a href="<?= URL_PATH ?>" class="Branding-link">
                                    <img src="<?= URL_PATH ?>/assets/images/icon/Icon-144.png" alt="Logo" class="Branding-img">
                                    <span class="Branding-name"><?= APP_NAME ?></span>
                                </a>
                            </div>
                        </div>
                        <?php $asideMenu = $_SESSION[SESS_MENU] ?? []; ?>
                        <ul class="AsideMenu" id="AsideMenu">
                            <?php if (ArrayFindIndexByColumn($asideMenu,'module','escritorio')): ?>
                                <li>
                                    <a href="<?= URL_PATH ?>/"> <i class="icon-home"></i> <span>Inicio </span> </a>
                                </li>
                            <?php endif; ?>
                            <li>
                                <a href="<?= URL_PATH ?>/voided"> <i class="icon-file-text"></i> <span>Cotizaciones </span> </a>
                            </li>
                            <li>
                                <a href="<?= URL_PATH ?>/invoice"> <i class="icon-rocket"></i> <span>Comprobantes </span> </a>
                                <ul>
                                    <li><a href="<?= URL_PATH ?>/invoice">Lista comprobantes</a></li>
                                    <li><a href="<?= URL_PATH ?>/invoice/newInvoice?documentCode=01">Emitir factura</a></li>
                                    <li><a href="<?= URL_PATH ?>/invoice/newInvoice?documentCode=03">Emitir boleta</a></li>
                                    <li><a href="<?= URL_PATH ?>/invoice/newInvoice?documentCode=07">Emitir nota crédito</a></li>
                                    <li><a href="<?= URL_PATH ?>/invoice/newInvoice?documentCode=08">Emitir nota débito</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="<?= URL_PATH ?>/voided"> <i class="icon-book"></i> <span>Guias de remisión </span> </a>
                            </li>
                            <li>
                                <a href="<?= URL_PATH ?>/invoice"> <i class=" icon-stack"></i> <span>Contingencias </span> </a>
                                <ul>
                                    <li><a href="<?= URL_PATH ?>/invoice">Lista comprobantes[c]</a></li>
                                    <li><a href="<?= URL_PATH ?>/invoice/newInvoice?documentCode=01">Emitir factura[c]</a></li>
                                    <li><a href="<?= URL_PATH ?>/invoice/newInvoice?documentCode=03">Emitir boleta[c]</a></li>
                                    <li><a href="<?= URL_PATH ?>/invoice/newInvoice?documentCode=07">Emitir nota crédito[c]</a></li>
                                    <li><a href="<?= URL_PATH ?>/invoice/newInvoice?documentCode=08">Emitir nota débito[c]</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="<?= URL_PATH ?>/voided"> <i class="icon-warning"></i> <span>Anulaciones </span> </a>
                            </li>
                            <li>
                                <a href="<?= URL_PATH ?>/summary"> <i class="icon-make-group"></i> <span>Resumenes </span> </a>
                            </li>
                            <li>
                                <a href="#"> <i class="icon-cube"></i> <span>Mantenimiento </span> </a>
                                <ul>
                                    <?php if (ArrayFindIndexByColumn($asideMenu,'module','categoria')): ?>
                                        <li><a href="<?= URL_PATH ?>/category">Categorias</a></li>
                                    <?php endif; ?>
                                    <?php if (ArrayFindIndexByColumn($asideMenu,'module','producto')): ?>
                                        <li><a href="<?= URL_PATH ?>/product">Productos</a></li>
                                    <?php endif; ?>
                                    <?php if (ArrayFindIndexByColumn($asideMenu,'module','cliente')): ?>
                                        <li><a href="<?= URL_PATH ?>/customer">Clientes</a></li>
                                    <?php endif; ?>
                                    <?php if (ArrayFindIndexByColumn($asideMenu,'module','usuario')): ?>
                                        <li><a href="<?= URL_PATH ?>/user">Usuarios</a></li>
                                    <?php endif; ?>
                                    <?php if (ArrayFindIndexByColumn($asideMenu,'module','rol')): ?>
                                        <li><a href="<?= URL_PATH ?>/userRole">Roles</a></li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                            <li>
                                <a href="<?= URL_PATH ?>/report"> <i class="icon-pie-chart"></i> <span>Reportes </span> </a>
                                <ul>
                                    <li><a href="<?= URL_PATH ?>/report/sale">Venta diaria</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="<?= URL_PATH ?>/invoice"> <i class="icon-cog"></i> <span>Configuración </span> </a>
                                <ul>
                                    <li><a href="<?= URL_PATH ?>/business/update">Empresa</a></li>
                                    <li><a href="<?= URL_PATH ?>/businessLocal">Sucursales</a></li>
                                    <li><a href="<?= URL_PATH ?>/business/api">API</a></li>
            <!--                        <li><a href="--><?//= URL_PATH ?><!--/business/update">Cuentas bancarias</a></li>-->
            <!--                        <li><a href="--><?//= URL_PATH ?><!--/business/update">BackUp</a></li>-->
            <!--                        <li><a href="--><?//= URL_PATH ?><!--/business/update">General</a></li>-->
                                </ul>
                            </li>
                            <li>
                                <a href="<?= URL_PATH ?>/documentation"> <i class="icon-book"></i> <span>Manual </span> </a>
                                <ul>
                                    <li><a href="<?= URL_PATH ?>/documentation/api">Manual API</a></li>
                                    <li><a href="<?= URL_PATH ?>/documentation/core">Manual core</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class="AsideFooter">
                            <div class="SnSwitch">
                                <input class="SnSwitch-control" type="checkbox" id="themeMode" >
                                <label class="SnSwitch-label" for="themeMode"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?= URL_PATH ?>/assets/script/common/admin.js"></script>
        <script src="<?= URL_PATH ?>/assets/script/company/company.js"></script>
    </body>
</html>