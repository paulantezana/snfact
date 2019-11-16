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
                        <li><a href="">Lista comprobantes</a></li>
                        <li><a href="">Emitir factura</a></li>
                        <li><a href="">Emitir boleta</a></li>
                        <li><a href="">Emitir nota crédito</a></li>
                        <li>
                            <a href="">Emitir nota débito</a>
                            <ul>
                                <li><a href="">Venta diaria</a></li>
                                <li><a href="">Venta diaria</a></li>
                                <li><a href="">Venta diaria</a></li>
                                <li>
                                    <a href="">Venta diaria</a>
                                    <ul>
                                        <li><a href="">Lista comprobantes</a></li>
                                        <li><a href="">Emitir factura</a></li>
                                        <li><a href="">Emitir boleta</a></li>
                                        <li><a href="">Emitir nota crédito</a></li>
                                        <li>
                                            <a href="">Emitir nota débito</a>
                                            <ul>
                                                <li><a href="">Venta diaria</a></li>
                                                <li><a href="">Venta diaria</a></li>
                                                <li><a href="">Venta diaria</a></li>
                                                <li>
                                                    <a href="">Venta diaria</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="<?= URL_PATH ?>/user"> <i class="icon-ioxhost"></i> <span>Anulaciones </span> </a>
                </li>
                <li>
                    <a href="<?= URL_PATH ?>/user"> <i class="icon-clone"></i> <span>Resumenes </span> </a>
                </li>
                <li>
                    <a href="<?= URL_PATH ?>/invoice"> <i class="icon-cube"></i> <span>Mantenimiento </span> </a>
                    <ul>
                        <li><a href="">Categorias</a></li>
                        <li><a href="">Productos</a></li>
                        <li><a href="">Clientes</a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?= URL_PATH ?>/customer"> <i class="icon-user"></i> <span>Usuarios </span> </a>
                </li>
                <li>
                    <a href="<?= URL_PATH ?>/invoice"> <i class="icon-pie-chart"></i> <span>Reportes </span> </a>
                    <ul>
                        <li><a href="">Venta diaria</a></li>
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
                    <a href="<?= URL_PATH ?>/invoice"> <i class="icon-book"></i> <span>Manual </span> </a>
                    <ul>
                        <li><a href="">Manual API</a></li>
                        <li><a href="">Manual core</a></li>
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