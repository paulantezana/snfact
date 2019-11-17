let  CategoryForm = {
    currentModeForm : 'create',
    modalName : 'categoryModalForm',

    currentForm : null,
    submitButton : null,

    loading : false,
    init() {
        this.currentForm = document.getElementById('categoryForm');
        this.submitButton = document.getElementById('categoryFormSubmit');
        this.list();
    },
    search(event){
        event.preventDefault();
        this.list(1,10,event.target.value);
    },
    list(page = 1, limit = 10, search = ''){
        let categoryTable = document.getElementById('categoryTable');
        if(categoryTable){
            this.setLoading(true);
            RequestApi.fetchText(`/category/table?limit=${limit}&page=${page}&search=${search}`,{
                method: 'GET',
            }).then(res => {
                categoryTable.innerHTML = res;
            }).finally(e =>{
                this.setLoading(false);
            })
        }
    },
    setLoading(state){
        this.loading = state;
        let jsCategoryOption = document.querySelectorAll('.jsCategoryOption');
        if (this.loading){
            if(this.submitButton){
                this.submitButton.setAttribute('disabled','disabled');
                this.submitButton.classList.add('loading');
            }
            if (jsCategoryOption) {
                jsCategoryOption.forEach(item => {
                    item.setAttribute('disabled', 'disabled');
                });
            }
        } else {
            if(this.submitButton){
                this.submitButton.removeAttribute('disabled');
                this.submitButton.classList.remove('loading');
            }
            if (jsCategoryOption) {
                jsCategoryOption.forEach(item => {
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
        let categorySendData = {};
        categorySendData.name =  document.getElementById('categoryName').value || '';
        categorySendData.description =  document.getElementById('categoryDescription').value || '';
        categorySendData.state =  document.getElementById('categoryState').checked || false;
        categorySendData.parentId =  0;

        if (this.currentModeForm === 'create'){
            url = '/category/create';
        }
        if (this.currentModeForm === 'update'){
            url = '/category/update';
            categorySendData.categoryId = document.getElementById('categoryId').value || 0;
        }

        RequestApi.fetch(url,{
            method: 'POST',
            body: categorySendData
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
    delete(categoryId, content = '') {
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
                RequestApi.fetch('/category/delete', {
                    method: 'POST',
                    body: {
                        categoryId: categoryId || 0
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

    executeUpdateNormal(categoryId){
        this.currentModeForm = 'update';
        this.showModalUpdate(categoryId);
    },

    showModalUpdate(categoryId){
        this.clearForm();

        this.setLoading(true);
        RequestApi.fetch('/category/id',{
            method: 'POST',
            body: {
                categoryId: categoryId || 0
            }
        }).then(res => {
            if (res.success){
                document.getElementById('categoryName').value  = res.result.name;
                document.getElementById('categoryDescription').value  = res.result.description;
                document.getElementById('categoryState').checked = res.result.state;
                document.getElementById('categoryId').value = res.result.category_id;
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
    CategoryForm.init();
});