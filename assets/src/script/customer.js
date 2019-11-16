let  CustomerForm = {
    currentModeForm : 'create',
    modalName : 'customerModalForm',

    currentForm : null,
    submitButton : null,

    loading : false,
    init() {
        this.currentForm = document.getElementById('customerForm');
        this.submitButton = document.getElementById('customerFormSubmit');
        this.list();
    },
    search(event){
        event.preventDefault();
        this.list(1,10,event.target.value);
    },
    list(page = 1, limit = 10, search = ''){
        let customerTable = document.getElementById('customerTable');
        if(customerTable){
            this.setLoading(true);
            RequestApi.fetchText(`/customer/table?limit=${limit}&page=${page}&search=${search}`,{
                method: 'GET',
            }).then(res => {
                customerTable.innerHTML = res;
            }).finally(e =>{
                this.setLoading(false);
            })
        }
    },
    setLoading(state){
        this.loading = state;
        let jsCustomerOption = document.querySelectorAll('.jsCustomerOption');
        if (this.loading){
            if(this.submitButton){
                this.submitButton.setAttribute('disabled','disabled');
                this.submitButton.classList.add('loading');
            }
            if (jsCustomerOption) {
                jsCustomerOption.forEach(item => {
                    item.setAttribute('disabled', 'disabled');
                });
            }
        } else {
            if(this.submitButton){
                this.submitButton.removeAttribute('disabled');
                this.submitButton.classList.remove('loading');
            }
            if (jsCustomerOption) {
                jsCustomerOption.forEach(item => {
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
        let customerSendData = {};
        customerSendData.fullName =  document.getElementById('customerFullName').value || '';
        customerSendData.document =  document.getElementById('customerDocument').value || '';
        customerSendData.phone =  document.getElementById('customerPhone').value || '';
        customerSendData.state =  document.getElementById('customerState').checked || false;

        if (this.currentModeForm === 'create'){
            url = '/customer/create';
        }
        if (this.currentModeForm === 'update'){
            url = '/customer/update';
            customerSendData.customerId = document.getElementById('customerId').value || 0;
        }

        RequestApi.fetch(url,{
            method: 'POST',
            body: customerSendData
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
    delete(customerId, content = '') {
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
                RequestApi.fetch('/customer/delete', {
                    method: 'POST',
                    body: {
                        customerId: customerId || 0
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

    executeUpdateNormal(customerId){
        this.currentModeForm = 'update';
        this.showModalUpdate(customerId);
    },

    showModalUpdate(customerId){
        this.clearForm();

        this.setLoading(true);
        RequestApi.fetch('/customer/id',{
            method: 'POST',
            body: {
                customerId: customerId || 0
            }
        }).then(res => {
            if (res.success){
                document.getElementById('customerFullName').value  = res.result.full_name;
                document.getElementById('customerDocument').value  = res.result.document;
                document.getElementById('customerState').checked  = res.result.state;
                document.getElementById('customerPhone').value  = res.result.phone;
                document.getElementById('customerId').value = res.result.id;
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
    CustomerForm.init();
});