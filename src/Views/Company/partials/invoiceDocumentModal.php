<div class="SnModal-wrapper" data-modal="documentModal">
    <div class="SnModal" style="max-width: 90vw; top: 50px;">
        <div class="SnModal-close" data-modalclose="documentModal">
            <i class="fas fa-times"></i>
        </div>
        <div class="SnModal-header" style="display: flex; align-items: center">
          <i class="fas fa-file-pdf SnMr-2"></i>Imprimir
          <div class="SnBtn-group SnMl-5">
              <a href="" class="SnBtn primary" target="_blank" id="documentPrinterOpenBrowser">Abrir en navegador</a>
              <div class="SnBtn" onclick="DocumentPrinter.print()">Imprimir</div>
          </div>
        </div>
        <div id="documentPrinterIframe"></div>
    </div>
</div>
