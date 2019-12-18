let BusinessLocalState = {
    modalType : 'create',
    modalName : 'businessLocalModalForm',
    loading : false,
};

function BusinessLocalSetLoading(state){
    BusinessLocalState.loading = state;
    let jsBusinessLocalAction = document.querySelectorAll('.jsBusinessLocalAction');
    let submitButton = document.getElementById('businessLocalFormSubmit');
    if (BusinessLocalState.loading){
        if(submitButton){
            submitButton.setAttribute('disabled','disabled');
            submitButton.classList.add('loading');
        }
        if (jsBusinessLocalAction) {
            jsBusinessLocalAction.forEach(item => {
                item.setAttribute('disabled', 'disabled');
            });
        }
    } else {
        if(submitButton){
            submitButton.removeAttribute('disabled');
            submitButton.classList.remove('loading');
        }
        if (jsBusinessLocalAction) {
            jsBusinessLocalAction.forEach(item => {
                item.removeAttribute('disabled');
            });
        }
    }
}

function BusinessLocalList(page = 1, limit = 10, search = ''){
    let businessLocalTable = document.getElementById('businessLocalTable');
    if(businessLocalTable){
        SnFreeze.freeze({selector: '#businessLocalTable'});
        RequestApi.fetchText(`/businessLocal/table?limit=${limit}&page=${page}&search=${search}`,{
            method: 'GET',
        }).then(res => {
            businessLocalTable.innerHTML = res;
        }).finally(e =>{
            SnFreeze.unFreeze('#businessLocalTable');
        })
    }
}

function BusinessLocalClearForm(){
    let currentForm = document.getElementById('businessLocalForm');
    if (currentForm){
        currentForm.reset();
    }
}

function BusinessLocalSubmit(event){
    event.preventDefault();
    BusinessLocalSetLoading(true);

    let url = '';
    if (BusinessLocalState.modalType === 'create'){
        url = '/businessLocal/create';
    }
    if (BusinessLocalState.modalType === 'update'){
        url = '/businessLocal/update';
    }

    let businessLocalForm = document.getElementById('businessLocalForm');
    RequestApi.fetch(url,{
        method: 'POST',
        body: new FormData(businessLocalForm),
    }).then(res => {
        if (res.success){
            SnModal.close(BusinessLocalState.modalName);
            SnMessage.success({ content: res.message });
            BusinessLocalList();
        } else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e =>{
        BusinessLocalSetLoading(false);
    })
}

function BusinessLocalDelete(businessLocalId, content = '') {
    SnModal.confirm({
        title: '¿Estás seguro de eliminar este registro?',
        content: content,
        okText: 'Si',
        okType: 'error',
        cancelText: 'No',
        onOk() {
            BusinessLocalSetLoading(true);
            RequestApi.fetch('/businessLocal/delete', {
                method: 'POST',
                body: {
                    businessLocalId: businessLocalId || 0
                }
            }).then(res => {
                if (res.success) {
                    SnMessage.success({ content: res.message });
                    BusinessLocalList();
                } else {
                    SnModal.error({ title: 'Algo salió mal', content: res.message })
                }
            }).finally(e => {
                BusinessLocalSetLoading(false);
            })
        }
    });
}

function BusinessLocalShowModalCreate(){
    BusinessLocalState.modalType = 'create';
    BusinessLocalClearForm();
    SnModal.open(BusinessLocalState.modalName);
}

function BusinessLocalShowModalUpdate(businessLocalId){
    BusinessLocalState.modalType = 'update';
    BusinessLocalClearForm();

    BusinessLocalSetLoading(true);
    RequestApi.fetch('/businessLocal/id',{
        method: 'POST',
        body: {
            businessLocalId: businessLocalId || 0
        }
    }).then(res => {
        if (res.success){
            document.getElementById('businessLocalSunatCode').value = res.result.sunat_code;
            document.getElementById('businessLocalShortName').value = res.result.short_name;
            document.getElementById('businessLocalLocationCode').value = res.result.location_code;
            document.getElementById('businessLocalAddress').value = res.result.address;
            document.getElementById('businessLocalDescription').value = res.result.description;
            document.getElementById('businessLocalPdfInvoiceSize').value = res.result.pdf_invoice_size;
            document.getElementById('businessLocalPdfHeader').value = res.result.pdf_header;
            document.getElementById('businessLocalId').value = res.result.business_local_id;
            SnModal.open(BusinessLocalState.modalName);
        }else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e => {
        BusinessLocalSetLoading(false);
    })
}



function BusinessLocalSerieAddItem(){
    console.log('hola');
    let uniqueId = generateUniqueId();
    let businessLocalAddItem = document.getElementById('businessLocalAddItem');
    let tableBody = document.getElementById('businessLocalSeriesTableBody');
    console.log(tableBody);
    if (tableBody){
        let itemTemplate = businessLocalAddItem.dataset.itemtemplate;
        itemTemplate = eval('`' + itemTemplate + '`');
        console.log(itemTemplate);
        tableBody.insertAdjacentHTML('beforeend',itemTemplate);
    }
}

function BusinessLocalSerieRemoveItem(uniqueId){
    let elem = document.getElementById(`businessLocalItem${uniqueId}`);
    if (elem){
        elem.parentNode.removeChild(elem);
    }
}

document.addEventListener('DOMContentLoaded',()=>{
    document.getElementById('searchContent').addEventListener('input',e=>{
        BusinessLocalList(1,10,e.target.value);
    });



    BusinessLocalList();
});