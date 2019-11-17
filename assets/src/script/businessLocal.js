let  BusinessLocalForm = {
    currentModeForm : 'create',
    modalName : 'businessLocalModalForm',

    currentForm : null,
    submitButton : null,

    loading : false,
    init() {
        this.currentForm = document.getElementById('businessLocalForm');
        this.submitButton = document.getElementById('businessLocalFormSubmit');
        this.list();
    },
    search(event){
        event.preventDefault();
        this.list(1,10,event.target.value);
    },
    list(page = 1, limit = 10, search = ''){
        let businessLocalTable = document.getElementById('businessLocalTable');
        if(businessLocalTable){
            this.setLoading(true);
            RequestApi.fetchText(`/businessLocal/table?limit=${limit}&page=${page}&search=${search}`,{
                method: 'GET',
            }).then(res => {
                businessLocalTable.innerHTML = res;
            }).finally(e =>{
                this.setLoading(false);
            })
        }
    },
    setLoading(state){
        this.loading = state;
        let jsBusinessLocalOption = document.querySelectorAll('.jsBusinessLocalOption');
        if (this.loading){
            if(this.submitButton){
                this.submitButton.setAttribute('disabled','disabled');
                this.submitButton.classList.add('loading');
            }
            if (jsBusinessLocalOption) {
                jsBusinessLocalOption.forEach(item => {
                    item.setAttribute('disabled', 'disabled');
                });
            }
        } else {
            if(this.submitButton){
                this.submitButton.removeAttribute('disabled');
                this.submitButton.classList.remove('loading');
            }
            if (jsBusinessLocalOption) {
                jsBusinessLocalOption.forEach(item => {
                    item.removeAttribute('disabled');
                });
            }
        }
    },

    clearForm(){
        if (this.currentForm){
            this.currentForm.reset();
        }
    },

    submit(event){
        event.preventDefault();
        this.setLoading(true);

        let url = '';
        let businessLocalSendData = {};
        businessLocalSendData.documentNumber =  document.getElementById('businessLocalDocumentNumber').value || '';
        businessLocalSendData.identityDocumentCode =  document.getElementById('businessLocalIdentityDocumentCode').value || '';
        businessLocalSendData.socialReason =  document.getElementById('businessLocalSocialReason').value || '';
        businessLocalSendData.commercialReason =  document.getElementById('businessLocalCommercialReason').value || '';
        businessLocalSendData.fiscalAddress =  document.getElementById('businessLocalFiscalAddress').value || '';
        businessLocalSendData.email =  document.getElementById('businessLocalEmail').value || '';
        businessLocalSendData.telephone =  document.getElementById('businessLocalTelephone').value || '';

        if (this.currentModeForm === 'create'){
            url = '/businessLocal/create';
        }
        if (this.currentModeForm === 'update'){
            url = '/businessLocal/update';
            businessLocalSendData.businessLocalId = document.getElementById('businessLocalId').value || 0;
        }

        RequestApi.fetch(url,{
            method: 'POST',
            body: businessLocalSendData
        }).then(res => {
            if (res.success){
                SnModal.close(this.modalName);
                SnMessage.success({ content: res.message });
                this.list();
            } else {
                SnModal.error({ title: 'Algo salió mal', content: res.message })
            }
        }).finally(e =>{
            this.setLoading(false);
        })
    },
    delete(businessLocalId, content = '') {
        let _setLoading = this.setLoading;
        let _list = this.list;

        SnModal.confirm({
            title: '¿Estás seguro de eliminar este registro?',
            content: content,
            okText: 'Si',
            okType: 'error',
            cancelText: 'No',
            onOk() {
                _setLoading(true);
                RequestApi.fetch('/businessLocal/delete', {
                    method: 'POST',
                    body: {
                        businessLocalId: businessLocalId || 0
                    }
                }).then(res => {
                    if (res.success) {
                        SnMessage.success({ content: res.message });
                        _list();
                    } else {
                        SnModal.error({ title: 'Algo salió mal', content: res.message })
                    }
                }).finally(e => {
                    _setLoading(false);
                })
            }
        });
    },

    showModalCreate(){
        this.currentModeForm = 'create';
        this.clearForm();
        SnModal.open(this.modalName);
    },

    executeUpdateNormal(businessLocalId){
        this.currentModeForm = 'update';
        this.showModalUpdate(businessLocalId);
    },

    showModalUpdate(businessLocalId){
        this.clearForm();

        this.setLoading(true);
        RequestApi.fetch('/businessLocal/id',{
            method: 'POST',
            body: {
                businessLocalId: businessLocalId || 0
            }
        }).then(res => {
            if (res.success){
                document.getElementById('businessLocalDocumentNumber').value = res.result.document_number;
                document.getElementById('businessLocalIdentityDocumentCode').value = res.result.identity_document_code;
                document.getElementById('businessLocalSocialReason').value = res.result.social_reason;
                document.getElementById('businessLocalCommercialReason').value = res.result.commercial_reason;
                document.getElementById('businessLocalFiscalAddress').value = res.result.fiscal_address;
                document.getElementById('businessLocalEmail').value = res.result.email;
                document.getElementById('businessLocalTelephone').value = res.result.telephone;
                document.getElementById('businessLocalId').value = res.result.businessLocal_id;
                SnModal.open(this.modalName);
            }else {
                SnModal.error({ title: 'Algo salió mal', content: res.message })
            }
        }).finally(e => {
            this.setLoading(false);
        })
    }
};

document.addEventListener('DOMContentLoaded',()=>{
    BusinessLocalForm.init();
});