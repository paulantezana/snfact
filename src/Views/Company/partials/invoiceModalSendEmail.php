<div class="SnModal-wrapper" data-modal="invoiceModalSendEmail">
    <div class="SnModal">
        <div class="SnModal-close" data-modalclose="invoiceModalSendEmail">
            <i class="fas fa-times"></i>
        </div>
        <div class="SnModal-header"><i class="fas fa-inbox SnMr-2"></i> Enviar Correo del Cliente</div>
        <div class="SnModal-body">
            <form action="" onsubmit="invoiceSendEmail()">
                <input type="hidden" id="sendInvoiceId">
                <div class="SnForm-item required">
                    <label for="sendInvoiceCustomerEmail" class="SnForm-label">Email</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-envelope SnControl-prefix"></i>
                        <input type="email" class="SnForm-control SnControl" required id="sendInvoiceCustomerEmail" placeholder="Email">
                    </div>
                </div>
                <button type="submit" class="SnBtn primary block"><i class="fas fa-paper-plane SnMr-2"></i>Enviar</button>
            </form>
        </div>
    </div>
</div>
