function invoiceList(page = 1, limit = 10, search = '', filter){
    let productTable = document.getElementById('invoiceTable');
    if(productTable){
        SnFreeze.freeze({selector: '#invoiceTable'});
        RequestApi.fetchText(`/invoice/table?limit=${limit}&page=${page}&search=${search}`,{
            method: 'POST',
            body: { filter }
        }).then(res => {
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
    let filterCustomerId = document.getElementById('filterCustomerId');
    if (filterStartDate && filterEndDate && filterCustomerId){
        invoiceList(1,10,'',{
            startDate: filterStartDate.value,
            endDate: filterEndDate.value,
            customerId: filterCustomerId.value,
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

function invoiceSendEmail(){
    event.preventDefault();
    let sendInvoiceId = document.getElementById('sendInvoiceId');
    let sendInvoiceCustomerEmail = document.getElementById('sendInvoiceCustomerEmail');
    console.log(sendInvoiceCustomerEmail);
    if (sendInvoiceId && sendInvoiceCustomerEmail){
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
            } else {
                SnModal.error({ title: 'Algo salió mal', content: res.message })
            }
        }).finally(e =>{
            // SnFreeze.unFreeze('#invoiceTable');
        })
    }
}

function invoiceValidateDocument(invoiceId){
  console.log(invoiceId);
}

document.addEventListener('DOMContentLoaded',()=>{
    invoiceList();

    let filterStartDate = document.getElementById('filterStartDate');
    if (filterStartDate){
        filterStartDate.addEventListener('input',()=>{
            invoiceFilter();
        });
    }

    let filterEndDate = document.getElementById('filterEndDate');
    if (filterEndDate){
        filterEndDate.addEventListener('input',()=>{
            invoiceFilter();
        });
    }

    let filterCustomerId = document.getElementById('filterCustomerId');
    if (filterCustomerId){
        filterCustomerId.addEventListener('input',()=>{
            invoiceFilter();
        });
    }
});
