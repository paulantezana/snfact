let  CustomerState = {
    currentModeForm : 'create',
    modalName : 'customerModalForm',
    loading : false,
};
let pValidator;

function CustomerSetLoading(state){
    CustomerState.loading = state;
    let jsCustomerOption = document.querySelectorAll('.jsCustomerOption');
    let submitButton = document.getElementById('customerFormSubmit');
    if (CustomerState.loading){
        if(submitButton){
            submitButton.setAttribute('disabled','disabled');
            submitButton.classList.add('loading');
        }
        if (jsCustomerOption) {
            jsCustomerOption.forEach(item => {
                item.setAttribute('disabled', 'disabled');
            });
        }
    } else {
        if(submitButton){
            submitButton.removeAttribute('disabled');
            submitButton.classList.remove('loading');
        }
        if (jsCustomerOption) {
            jsCustomerOption.forEach(item => {
                item.removeAttribute('disabled');
            });
        }
    }
}

function CustomerList(page = 1, limit = 10, search = ''){
    let customerTable = document.getElementById('customerTable');
    if(customerTable){
        SnFreeze.freeze({selector: '#customerTable'});
        RequestApi.fetchText(`/customer/table?limit=${limit}&page=${page}&search=${search}`,{
            method: 'GET',
        }).then(res => {
            customerTable.innerHTML = res;
        }).finally(e =>{
            SnFreeze.unFreeze('#customerTable');
        })
    }
}

function CustomerQueryPeruDocument() {
    CustomerSetLoading(true);
    let customerDocumentNumber = document.getElementById('customerDocumentNumber');
    if (customerDocumentNumber){
        RequestApi.fetch('/customer/queryPeru',{
            method: 'POST',
            body: {
                documentNumber: customerDocumentNumber.value || 0
            }
        }).then(res => {
            if (res.success){
                document.getElementById('customerDocumentNumber').value = res.result.documentNumber  || '';
                document.getElementById('customerIdentityDocumentCode').value = res.result.identityDocumentCode  || '';
                document.getElementById('customerSocialReason').value = res.result.socialReason  || '';
                document.getElementById('customerCommercialReason').value = res.result.commercialReason  || '';
                document.getElementById('customerFiscalAddress').value = res.result.fiscalAddress  || '';
                document.getElementById('customerEmail').value = res.result.email || '';
                document.getElementById('customerTelephone').value = res.result.telephone || '';
                SnModal.open(CustomerState.modalName);
            }else {
                SnModal.error({ title: 'Algo salió mal', content: res.message })
            }
        }).finally(e => {
            CustomerSetLoading(false);
        })
    }
}

function CustomerClearForm(){
    let currentForm = document.getElementById('customerForm');
    let customerDocumentNumber = document.getElementById('customerDocumentNumber');
    pValidator.reset();
    if (currentForm && customerDocumentNumber){
        currentForm.reset();
        customerDocumentNumber.focus();
    }
}

function CustomerSubmit(e){
    e.preventDefault();
    if(!pValidator.validate()){
        return;
    }

    CustomerSetLoading(true);
    let url = '';
    let customerSendData = {};
    customerSendData.documentNumber =  document.getElementById('customerDocumentNumber').value || '';
    customerSendData.identityDocumentCode =  document.getElementById('customerIdentityDocumentCode').value || '';
    customerSendData.socialReason =  document.getElementById('customerSocialReason').value || '';
    customerSendData.commercialReason =  document.getElementById('customerCommercialReason').value || '';
    customerSendData.fiscalAddress =  document.getElementById('customerFiscalAddress').value || '';
    customerSendData.email =  document.getElementById('customerEmail').value || '';
    customerSendData.telephone =  document.getElementById('customerTelephone').value || '';
    customerSendData.state =  document.getElementById('customerState').checked || false;

    if (CustomerState.currentModeForm === 'create'){
        url = '/customer/create';
    }
    if (CustomerState.currentModeForm === 'update'){
        url = '/customer/update';
        customerSendData.customerId = document.getElementById('customerId').value || 0;
    }

    RequestApi.fetch(url,{
        method: 'POST',
        body: customerSendData
    }).then(res => {
        if (res.success){
            SnModal.close(CustomerState.modalName);
            SnMessage.success({ content: res.message });
            CustomerList();
        } else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e =>{
        CustomerSetLoading(false);
    })
}

function CustomerDelete(customerId, content = '') {
    SnModal.confirm({
        title: '¿Estás seguro de eliminar este registro?',
        content: content,
        okText: 'Si',
        okType: 'error',
        cancelText: 'No',
        onOk() {
            CustomerSetLoading(true);
            RequestApi.fetch('/customer/delete', {
                method: 'POST',
                body: {
                    customerId: customerId || 0
                }
            }).then(res => {
                if (res.success) {
                    SnMessage.success({ content: res.message });
                    CustomerList();
                } else {
                    SnModal.error({ title: 'Algo salió mal', content: res.message })
                }
            }).finally(e => {
                CustomerSetLoading(false);
            })
        }
    });
}

function CustomerShowModalCreate(){
    CustomerState.modalType = 'create';
    CustomerClearForm();
    SnModal.open(CustomerState.modalName);
    document.getElementById('customerState').checked = true;
}

function CustomerShowModalUpdate(customerId){
    CustomerState.currentModeForm = 'update';
    CustomerClearForm();

    CustomerSetLoading(true);
    RequestApi.fetch('/customer/id',{
        method: 'POST',
        body: {
            customerId: customerId || 0
        }
    }).then(res => {
        if (res.success){
            document.getElementById('customerDocumentNumber').value = res.result.document_number;
            document.getElementById('customerIdentityDocumentCode').value = res.result.identity_document_code;
            document.getElementById('customerSocialReason').value = res.result.social_reason;
            document.getElementById('customerCommercialReason').value = res.result.commercial_reason;
            document.getElementById('customerFiscalAddress').value = res.result.fiscal_address;
            document.getElementById('customerEmail').value = res.result.email;
            document.getElementById('customerTelephone').value = res.result.telephone;
            document.getElementById('customerState').checked = res.result.state == '0' ? false : true;
            document.getElementById('customerId').value = res.result.customer_id;
            SnModal.open(CustomerState.modalName);
        }else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e => {
        CustomerSetLoading(false);
    })
}

function CustomerToExcel(){
    let dataTable = document.getElementById('customerCurrentTable');
    if(dataTable){
        window.open('data:application/vnd.ms-excel,' + encodeURIComponent(dataTable.outerHTML));
    }
}

function CustomerToPrint(){
    printArea('customerCurrentTable');
}

document.addEventListener('DOMContentLoaded',()=>{
    pValidator = new Pristine(document.getElementById('customerForm'));

    document.getElementById('searchContent').addEventListener('input',e=>{
        CustomerList(1,10,e.target.value);
    });

    CustomerList();
});