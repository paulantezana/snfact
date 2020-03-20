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
                <button type="submit" class="SnBtn primary block" id="sendInvoiceSubmit"><i class="fas fa-paper-plane SnMr-2"></i>Enviar</button>
            </form>
        </div>
    </div>
</div>
<script>
  function invoiceSendEmail(){
    event.preventDefault();
    let sendInvoiceId = document.getElementById('sendInvoiceId');
    let sendInvoiceCustomerEmail = document.getElementById('sendInvoiceCustomerEmail');
    let sendInvoiceSubmit = document.getElementById('sendInvoiceSubmit');
    if (sendInvoiceId && sendInvoiceCustomerEmail && sendInvoiceSubmit){
      sendInvoiceSubmit.classList.add('loading');
      sendInvoiceSubmit.setAttribute('disabled','disabled');
      RequestApi.fetch(`/invoice/sendEmail`,{
        method: 'POST',
        body: {
          invoiceId: sendInvoiceId.value,
          invoiceCustomerEmail: sendInvoiceCustomerEmail.value,
        }
      }).then(res => {
        if (res.success){
          SnModal.close('invoiceModalSendEmail');
          SnMessage.success({ content: res.message });
          invoiceList();
        } else {
          SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
      }).finally(e => {
        sendInvoiceSubmit.classList.remove('loading');
        sendInvoiceSubmit.removeAttribute('disabled');
      })
    }else {
      SnModal.error({ title: 'Algo salió mal', content: 'Elementos no encontrados' })
    }
  }
</script>
