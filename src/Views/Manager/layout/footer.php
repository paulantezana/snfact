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
            <?php $asideMenu = $_SESSION[SESS_MENU] ?? []; ?>
            <ul class="AsideMenu" id="AsideMenu">
                <li>
                    <a href="<?= URL_PATH ?>/"> <i class="icon-home"></i> <span>Inicio </span> </a>
                </li>

                <li>
                    <a href="<?= URL_PATH ?>/summary"> <i class="icon-make-group"></i> <span>Resumenes </span> </a>
                </li>

            </ul>
            <div class="AsideFooter">
                <div class="SnSwitch">
                    <input class="SnSwitch-input" type="checkbox" id="themeMode" >
                    <label class="SnSwitch-label" for="themeMode"></label>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="<?= URL_PATH ?>/assets/script/common/app.js"></script>
</body>

</html>