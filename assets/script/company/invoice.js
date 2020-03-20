function invoiceList(page = 1, limit = 10, filter = {}){
    let productTable = document.getElementById('invoiceTable');
    if(productTable){
        SnFreeze.freeze({selector: '#invoiceTable'});
        RequestApi.fetch('/invoice/table',{
            method: 'POST',
            body: { filter, limit, page }
        },'text').then(res => {
            productTable.innerHTML = res;
        }).finally(e =>{
            SnDropdown();
            SnFreeze.unFreeze('#invoiceTable');
        })
    }
}

function invoiceFilter(){
    let filterStartDate = document.getElementById('filterStartDate');
    let filterEndDate = document.getElementById('filterEndDate');
    let filterInvoiceId = document.getElementById('filterInvoiceId');
    let filterDocumentCode = document.getElementById('filterDocumentCode');
    if (filterStartDate && filterEndDate && filterInvoiceId && filterDocumentCode){
        invoiceList(1,10,{
            startDate: filterStartDate.value,
            endDate: filterEndDate.value,
            invoiceId: filterInvoiceId.value,
            documentCode: filterDocumentCode.value,
        });
    }
}

function invoiceSendEmailOpenModal(invoiceId, customerEmail){
    SnModal.open('invoiceModalSendEmail');
    let sendInvoiceId = document.getElementById('sendInvoiceId');
    let sendInvoiceCustomerEmail = document.getElementById('sendInvoiceCustomerEmail');
    if (sendInvoiceId && sendInvoiceCustomerEmail){
        sendInvoiceId.value = invoiceId;
        sendInvoiceCustomerEmail.value = customerEmail;
    }
}

function invoiceResend(invoiceId){
    RequestApi.fetch(`/invoice/resend`,{
        method: 'POST',
        body: { invoiceId }
    }).then(res => {
        if (res.success){
            // SnModal.close('invoiceModalSendEmail');
            // SnMessage.success({ content: res.message });
        } else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e =>{
        // SnFreeze.unFreeze('#invoiceTable');
    })
}

function invoiceValidateDocument(invoiceId){
  console.log(invoiceId);
}

document.addEventListener('DOMContentLoaded',()=>{
    invoiceList();

    new SlimSelect({
    select: '#filterInvoiceId',
    searchingText: 'Buscando...',
    // addToBody: true,
    ajax: function (search, callback) {
      if (search.length < 2) {
        callback('Escriba almenos 2 caracteres');
        return
      }
      RequestApi.fetch('/invoice/searchBySerieNumber',{
        method: 'POST',
        body: { search }
      }).then(res=>{
        if (res.success){
          let data = res.result.map(item=>({ text: item.serie, value: item.invoice_id }));
          callback(data);
        } else {
          callback(false);
        }
      }).catch(err=>{
        callback(false);
      })
    }
  });
});
