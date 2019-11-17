</div>
<div class="AdminLayout-aside">
    <div id="AsideMenu-wrapper" class="AsideMenu-wrapper">
        <div class="AsideMenu-container">
            <div class="AsideHeader">
                <div class="Branding">
                    <a href="<?= URL_PATH ?>" class="Branding-link">
                        <img src="<?= URL_PATH ?>/assets/images/logo.png" alt="Logo" class="Branding-img">
                        <span class="Branding-name"><?= APP_NAME ?></span>
                    </a>
                </div>
            </div>
            <ul class="AsideMenu" id="AsideMenu">
                <li>
                    <a href="<?= URL_PATH ?>/dashboard"> <i class="icon-home"></i> <span>Inicio </span> </a>
                </li>
                <li>
                    <a href="<?= URL_PATH ?>/invoice"> <i class="icon-rocket"></i> <span>Comprobantes </span> </a>
                    <ul>
                        <li><a href="<?= URL_PATH ?>/invoice">Lista comprobantes</a></li>
                        <li><a href="<?= URL_PATH ?>/invoice/newInvoice">Emitir factura</a></li>
                        <li><a href="<?= URL_PATH ?>/invoice/newInvoiceTiket">Emitir boleta</a></li>
                        <li><a href="<?= URL_PATH ?>/invoice/newInvoiceNC">Emitir nota crédito</a></li>
                        <li><a href="<?= URL_PATH ?>/invoice/newInvoiceND">Emitir nota débito</a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?= URL_PATH ?>/voided"> <i class="icon-ioxhost"></i> <span>Anulaciones </span> </a>
                </li>
                <li>
                    <a href="<?= URL_PATH ?>/summary"> <i class="icon-clone"></i> <span>Resumenes </span> </a>
                </li>
                <li>
                    <a href="#"> <i class="icon-cube"></i> <span>Mantenimiento </span> </a>
                    <ul>
                        <li><a href="<?= URL_PATH ?>/category">Categorias</a></li>
                        <li><a href="<?= URL_PATH ?>/product">Productos</a></li>
                        <li><a href="<?= URL_PATH ?>/customer">Clientes</a></li>
                        <li><a href="<?= URL_PATH ?>/user">Usuarios</a></li>
                        <li><a href="<?= URL_PATH ?>/userRole">Roles</a></li>
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
                        <li><a href="">Empresa</a></li>
                        <li><a href="">Sucursales</a></li>
                        <li><a href="">API</a></li>
                        <li><a href="">Cuentas bancarias</a></li>
                        <li><a href="">BackUp</a></li>
                        <li><a href="">General</a></li>
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
        </div>
    </div>
</div>
</div>
<script src="<?= URL_PATH ?>/assets/dist/script/admin-min.js"></script>
<script src="<?= URL_PATH ?>/assets/dist/script/themingApp-min.js"></script>
</body>

</html>