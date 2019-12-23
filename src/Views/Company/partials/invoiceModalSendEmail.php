<div class="SnModal-wrapper" data-modal="invoiceModalSendEmail">
    <div class="SnModal">
        <div class="SnModal-close" data-modalclose="invoiceModalSendEmail">
            <i class="icon-cross"></i>
        </div>
        <div class="SnModal-header"><i class="icon-envelop2 SnMr-2"></i> Enviar email</div>
        <div class="SnModal-body">
            <form action="" onsubmit="InvoiceSendEmail(event)">
                <input type="hidden" id="sendInvoiceId">
                <div class="SnForm-item required">
                    <label for="sendInvoiceCustomerEmail" class="SnForm-label">Email</label>
                    <div class="SnControl-wrapper">
                        <i class="icon-envelop2 SnControl-prefix"></i>
                        <input type="email" class="SnForm-control SnControl" required id="sendInvoiceCustomerEmail" placeholder="Email">
                    </div>
                </div>
                <button type="submit" class="SnBtn primary">Enviar email</button>
            </form>
        </div>
    </div>
</div>