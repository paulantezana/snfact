<div class="SnModal-wrapper" data-modal="documentModal">
    <div class="SnModal" style="max-width: 90vw; top: 50px;">
        <div class="SnModal-close" data-modalclose="documentModal">
            <i class="icon-cross"></i>
        </div>
        <div class="SnModal-header"><i class="icon-file-pdf SnMr-2"></i>Imprimir</div>
        <div class="SnModal-body">
            <div class="SnMb-3 SnBtn-group">
                <a href="" class="SnBtn primary" target="_blank" id="documentPrinterOpenBrowser">Abrir en navegador</a>
                <div class="SnBtn" onclick="DocumentPrinter.print()">Imprimir</div>
            </div>
            <div id="documentPrinterIframe"></div>
        </div>
    </div>
</div>