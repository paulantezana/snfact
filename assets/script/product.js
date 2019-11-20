let  ProductForm = {
    currentModeForm : 'create',
    modalName : 'productModalForm',

    currentForm : null,
    submitButton : null,

    loading : false,
    init() {
        this.currentForm = document.getElementById('productForm');
        this.submitButton = document.getElementById('productFormSubmit');
        this.list();
    },
    search(event){
        event.preventDefault();
        this.list(1,10,event.target.value);
    },
    list(page = 1, limit = 10, search = ''){
        let productTable = document.getElementById('productTable');
        if(productTable){
            this.setLoading(true);
            RequestApi.fetchText(`/product/table?limit=${limit}&page=${page}&search=${search}`,{
                method: 'GET',
            }).then(res => {
                productTable.innerHTML = res;
            }).finally(e =>{
                this.setLoading(false);
            })
        }
    },
    setLoading(state){
        this.loading = state;
        let jsProductOption = document.querySelectorAll('.jsProductOption');
        if (this.loading){
            if(this.submitButton){
                this.submitButton.setAttribute('disabled','disabled');
                this.submitButton.classList.add('loading');
            }
            if (jsProductOption) {
                jsProductOption.forEach(item => {
                    item.setAttribute('disabled', 'disabled');
                });
            }
        } else {
            if(this.submitButton){
                this.submitButton.removeAttribute('disabled');
                this.submitButton.classList.remove('loading');
            }
            if (jsProductOption) {
                jsProductOption.forEach(item => {
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
        let productSendData = {};
        productSendData.categoryId =  document.getElementById('productCategoryId').value || '';
        productSendData.description =  document.getElementById('productDescription').value || '';
        productSendData.unitPrice =  document.getElementById('productUnitPrice').value || 0;
        productSendData.unitValue =  document.getElementById('productUnitValue').value || 0;
        productSendData.productKey=  document.getElementById('productProductKey').value || '';
        productSendData.productCode=  document.getElementById('productProductCode').value || '';
        productSendData.unitMeasureCode=  document.getElementById('productUnitMeasureCode').value || '';
        productSendData.affectationCode=  document.getElementById('productAffectationCode').value || '';
        productSendData.systemIscCode=  document.getElementById('productSystemIscCode').value || '';
        productSendData.isc=  document.getElementById('productIsc').value || '';
        productSendData.state=  document.getElementById('productState').checked || false;

        if (this.currentModeForm === 'create'){
            url = '/product/create';
        }
        if (this.currentModeForm === 'update'){
            url = '/product/update';
            productSendData.productId = document.getElementById('productId').value || 0;
        }

        RequestApi.fetch(url,{
            method: 'POST',
            body: productSendData
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
    delete(productId, content = '') {
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
                RequestApi.fetch('/product/delete', {
                    method: 'POST',
                    body: {
                        productId: productId || 0
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

    executeUpdateNormal(productId){
        this.currentModeForm = 'update';
        this.showModalUpdate(productId);
    },

    showModalUpdate(productId){
        this.clearForm();

        this.setLoading(true);
        RequestApi.fetch('/product/id',{
            method: 'POST',
            body: {
                productId: productId || 0
            }
        }).then(res => {
            if (res.success){
                document.getElementById('productCategoryId').value = res.result.category_id;
                document.getElementById('productDescription').value = res.result.description;
                document.getElementById('productUnitPrice').value = res.result.unit_price;
                document.getElementById('productUnitValue').value = res.result.unit_value;
                document.getElementById('productProductKey').value = res.result.product_key;
                document.getElementById('productProductCode').value = res.result.product_code;
                document.getElementById('productUnitMeasureCode').value = res.result.unit_measure_code;
                document.getElementById('productAffectationCode').value = res.result.affectation_code;
                document.getElementById('productSystemIscCode').value = res.result.system_isc_code;
                document.getElementById('productIsc').value = res.result.isc;
                document.getElementById('productState').checked = res.result.state == '0' ? false : true;
                document.getElementById('productId').value = res.result.product_id;
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
    ProductForm.init();
});