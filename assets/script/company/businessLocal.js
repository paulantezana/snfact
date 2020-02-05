let BusinessLocalState = {
    modalType : 'create',
    modalName : 'businessLocalModalForm',
    loading : false,
};
let pValidator;

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
    pValidator.reset();
    if (currentForm){
        currentForm.reset();
    }
    let tableBody = document.getElementById('businessLocalSeriesTableBody');
    if (tableBody){
        tableBody.innerHTML = '';
    }
}

function BusinessLocalSubmit(e){
    e.preventDefault();
    if(!pValidator.validate()){
        return;
    }

    BusinessLocalSetLoading(true);

    let url = '';
    if (BusinessLocalState.modalType === 'create'){
        url = '/businessLocal/create';
    }
    if (BusinessLocalState.modalType === 'update'){
        url = '/businessLocal/update';
    }

    let localSendData = {};
    localSendData.id = document.getElementById('businessLocalId').value || 0;
    localSendData.sunatCode = document.getElementById('businessLocalSunatCode').value || '';
    localSendData.shortName = document.getElementById('businessLocalShortName').value || '';
    localSendData.locationCode = document.getElementById('businessLocalLocationCode').value || '';
    localSendData.address = document.getElementById('businessLocalAddress').value || '';
    localSendData.description = document.getElementById('businessLocalDescription').value || '';
    localSendData.pdfInvoiceSize = document.getElementById('businessLocalPdfInvoiceSize').value || '';
    localSendData.pdfHeader = document.getElementById('businessLocalPdfHeader').value || '';
    localSendData.state = document.getElementById('businessLocalState').checked || false;

    let table = document.getElementById('businessLocalSeriesTableBody');
    localSendData.item = [...table.children].map((row,index)=>{
        let uniqueId = row.dataset.uniqueid;
        let documentCode = document.getElementById(`documentCode${uniqueId}`);
        let businessSerieId = document.getElementById(`businessSerieId${uniqueId}`);
        let serie = document.getElementById(`serie${uniqueId}`);
        let contingency = document.getElementById(`contingency${uniqueId}`);
        return { 
            documentCode: documentCode.value || 0,
            businessSerieId: businessSerieId.value || 0,
            serie: serie.value || '',
            contingency: contingency.checked || 0
        };
    });

    RequestApi.fetch(url,{
        method: 'POST',
        body: localSendData,
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

    BusinessLocalSerieAddItem(0,'01','FPP1',0);
    BusinessLocalSerieAddItem(0,'07','FPP1',0);
    BusinessLocalSerieAddItem(0,'08','FPP1',0);
    BusinessLocalSerieAddItem(0,'03','BPP1',0);
    BusinessLocalSerieAddItem(0,'07','BPP1',0);
    BusinessLocalSerieAddItem(0,'08','BPP1',0);
    BusinessLocalSerieAddItem(0,'09','T001',0);

    BusinessLocalSetValidator();
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
            document.getElementById('businessLocalState').checked = res.result.state == 1;
            document.getElementById('businessLocalId').value = res.result.business_local_id;

            [...res.result.item].forEach(item => {
                BusinessLocalSerieAddItem(item.business_serie_id, item.document_code, item.serie, item.contingency);
            });
            BusinessLocalSetValidator();

            SnModal.open(BusinessLocalState.modalName);
        }else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e => {
        BusinessLocalSetLoading(false);
    })
}

function BusinessLocalSerieAddItem(businessSerieId, documentCode, serie, contingency = 0){
    let uniqueId = generateUniqueId();
    let businessLocalAddItem = document.getElementById('businessLocalAddItem');
    let tableBody = document.getElementById('businessLocalSeriesTableBody');
    if (tableBody){
        let itemTemplate = businessLocalAddItem.dataset.itemtemplate;
        itemTemplate = eval('`' + itemTemplate + '`');
        tableBody.insertAdjacentHTML('beforeend',itemTemplate);

        let businessSerieIdItem = document.getElementById(`businessSerieId${uniqueId}`);
        let documentCodeItem = document.getElementById(`documentCode${uniqueId}`);
        let serieItem = document.getElementById(`serie${uniqueId}`);
        let contingencyItem = document.getElementById(`contingency${uniqueId}`);
        businessSerieIdItem.value = businessSerieId;
        documentCodeItem.value = documentCode;
        serieItem.value = serie;
        contingencyItem.checked = contingency == 1;
    }
}

function BusinessLocalSerieRemoveItem(uniqueId){
    let elem = document.getElementById(`businessLocalItem${uniqueId}`);
    if (elem){
        elem.parentNode.removeChild(elem);
    }
}

function BusinessLocalSetValidator(){
    if(pValidator){
        pValidator.destroy();
    }
    pValidator = new Pristine(document.getElementById('businessLocalForm'));
}

document.addEventListener('DOMContentLoaded',()=>{
    BusinessLocalSetValidator();

    document.getElementById('searchContent').addEventListener('input',e=>{
        BusinessLocalList(1,10,e.target.value);
    });

    BusinessLocalList();
});