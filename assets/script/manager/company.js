let CompanyState = {
    modalType: 'create',
    modalName : 'companyModalForm',
    loading : false,
};
let pValidator;

function CompanySetLoading(state){
    CompanyState.loading = state;
    let jsCompanyAction = document.querySelectorAll('.jsCompanyAction');
    let submitButton = document.getElementById('companyFormSubmit');
    if (CompanyState.loading){
        if(submitButton){
            submitButton.setAttribute('disabled','disabled');
            submitButton.classList.add('loading');
        }
        if (jsCompanyAction) {
            jsCompanyAction.forEach(item => {
                item.setAttribute('disabled', 'disabled');
            });
        }
    } else {
        if(submitButton){
            submitButton.removeAttribute('disabled');
            submitButton.classList.remove('loading');
        }
        if (jsCompanyAction) {
            jsCompanyAction.forEach(item => {
                item.removeAttribute('disabled');
            });
        }
    }
}

function CompanyList(page = 1, limit = 10, search = ''){
    let companyTable = document.getElementById('companyTable');
    if(companyTable){
        SnFreeze.freeze({selector: '#companyTable'});
        RequestApi.fetchText(`/company/table?limit=${limit}&page=${page}&search=${search}`,{
            method: 'GET',
        }).then(res => {
            companyTable.innerHTML = res;
        }).finally(e =>{
            SnFreeze.unFreeze('#companyTable');
        })
    }
}

function CompanyClearForm(){
    let currentForm = document.getElementById('companyForm');
    let companyRuc = document.getElementById('companyRuc');
    pValidator.reset();
    if (currentForm && companyRuc){
        currentForm.reset();
        companyRuc.focus();
    }
}

function CompanySubmit(e){
    e.preventDefault();
    if(!pValidator.validate()){
        return;
    }

    CompanySetLoading(true);
    let url = '';
    let companySendData = {};
    companySendData.ruc =  document.getElementById('companyRuc').value;
    companySendData.email =  document.getElementById('companyEmail').value;
    companySendData.commercialReason =  document.getElementById('companyCommercialReason').value;
    companySendData.webSite =  document.getElementById('companyWebSite').value;
    companySendData.phone =  document.getElementById('companyPhone').value;
    companySendData.environment =  document.getElementById('companyEnvironment').checked || false;
    companySendData.state =  document.getElementById('companyState').checked || false;
    
    if (CompanyState.modalType === 'create'){
        url = '/company/create';
        companySendData.userName =  document.getElementById('companyUserName').value;
        companySendData.password =  document.getElementById('companyPassword').value;
        companySendData.confirmPassword =  document.getElementById('companyPasswordConfirm').value;
    }
    if (CompanyState.modalType === 'update'){
        url = '/company/update';
        companySendData.companyId = document.getElementById('companyId').value || 0;
    }

    RequestApi.fetch(url,{
        method: 'POST',
        body: companySendData
    }).then(res => {
        if (res.success){
            SnModal.close(CompanyState.modalName);
            SnMessage.success({ content: res.message });
            CompanyList();
        } else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e =>{
        CompanySetLoading(false);
    })
}

function CompanyShowModalCreate(){
    CompanyState.modalType = 'create';
    SnModal.open(CompanyState.modalName);
    prepareModalCompany(CompanyState.modalType);
    document.getElementById('companyState').checked = true;
}

function CompanyShowModalUpdate(companyId){
    CompanyState.modalType = 'update';
    CompanySetLoading(true);
    prepareModalCompany(CompanyState.modalType);

    RequestApi.fetch('/company/id',{
        method: 'POST',
        body: {
            companyId: companyId || 0
        }
    }).then(res => {
        if (res.success){
            document.getElementById('companyRuc').value = res.result.ruc;
            document.getElementById('companyEmail').value = res.result.email;
            document.getElementById('companyCommercialReason').value = res.result.commercial_reason;
            document.getElementById('companyWebSite').value = res.result.web_site;
            document.getElementById('companyPhone').value = res.result.phone;
            document.getElementById('companyEnvironment').checked = res.result.environment == '1';
            document.getElementById('companyState').checked = res.result.state == '1';
            document.getElementById('companyId').value = res.result.business_id;
            SnModal.open(CompanyState.modalName);
        }else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e => {
        CompanySetLoading(false);
    })
}


function prepareModalCompany(mode = ''){
    CompanyClearForm();
    pValidator.destroy();

    if (mode === 'update'){
        document.getElementById('companyPassword').parentElement.parentElement.classList.add('hidden');
        document.getElementById('companyPasswordConfirm').parentElement.parentElement.classList.add('hidden');
        document.getElementById('companyUserName').parentElement.parentElement.classList.add('hidden');
        
        document.getElementById('companyUserName').removeAttribute('required');
        document.getElementById('companyPassword').removeAttribute('required');
        document.getElementById('companyPasswordConfirm').removeAttribute('required');
    } else if(mode === 'create') {
        document.getElementById('companyUserName').parentElement.parentElement.classList.remove('hidden');
        document.getElementById('companyPassword').parentElement.parentElement.classList.remove('hidden');
        document.getElementById('companyPasswordConfirm').parentElement.parentElement.classList.remove('hidden');
        
        document.getElementById('companyUserName').setAttribute('required',true);
        document.getElementById('companyPassword').setAttribute('required',true);
        document.getElementById('companyPasswordConfirm').setAttribute('required',true);

        document.getElementById('companyState').checked = true;
    }

    pValidator = new Pristine(document.getElementById('companyForm'));
}

function CompanyToExcel(){
    let dataTable = document.getElementById('companyCurrentTable');
    if(dataTable){
        window.open('data:application/vnd.ms-excel,' + encodeURIComponent(dataTable.outerHTML));
    }
}

function CompanyToPrint(){
    printArea('companyCurrentTable');
}


function CompanyShowModalLogo(companyId){
    CompanySetLoading(true);
    RequestApi.fetch('/company/id',{
        method: 'POST',
        body: {
            companyId: companyId || 0
        }
    }).then(res => {
        if (res.success){
            SnModal.open('companyModalLogoForm');
            document.getElementById('companyLogoId').value = res.result.business_id;
        }else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e => {
        CompanySetLoading(false);
    })
}

function CompanyLogoSubmit(){
    event.preventDefault();
    CompanySetLoading(true);

    let companySendData = {};

    RequestApi.fetch('/company/ss',{
        method: 'POST',
        body: companySendData
    }).then(res => {
        if (res.success){
            SnModal.close('companyModalLogoForm');
            SnMessage.success({ content: res.message });
            CompanyList();
        } else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e =>{
        CompanySetLoading(false);
    })
}

document.addEventListener('DOMContentLoaded',()=>{
    pValidator = new Pristine(document.getElementById('companyForm'));

    document.getElementById('searchContent').addEventListener('input',e=>{
        CompanyList(1,10,e.target.value);
    });

    CompanyList();
});