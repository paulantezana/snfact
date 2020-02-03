let CategoryState = {
    modalType: 'create',
    modalName : 'categoryModalForm',
    loading : false,
};
let pValidator;

function CategorySetLoading(state){
    CategoryState.loading = state;
    let jsCategoryAction = document.querySelectorAll('.jsCategoryAction');
    let submitButton = document.getElementById('categoryFormSubmit');
    if (CategoryState.loading){
        if(submitButton){
            submitButton.setAttribute('disabled','disabled');
            submitButton.classList.add('loading');
        }
        if (jsCategoryAction) {
            jsCategoryAction.forEach(item => {
                item.setAttribute('disabled', 'disabled');
            });
        }
    } else {
        if(submitButton){
            submitButton.removeAttribute('disabled');
            submitButton.classList.remove('loading');
        }
        if (jsCategoryAction) {
            jsCategoryAction.forEach(item => {
                item.removeAttribute('disabled');
            });
        }
    }
}

function CategoryList(page = 1, limit = 10, search = ''){
    let categoryTable = document.getElementById('categoryTable');
    if(categoryTable){
        SnFreeze.freeze({selector: '#categoryTable'});
        RequestApi.fetchText(`/category/table?limit=${limit}&page=${page}&search=${search}`,{
            method: 'GET',
        }).then(res => {
            categoryTable.innerHTML = res;
        }).finally(e =>{
            SnFreeze.unFreeze('#categoryTable');
        })
    }
}

function CategoryClearForm(){
    let currentForm = document.getElementById('categoryForm');
    let categoryName = document.getElementById('categoryName');
    pValidator.reset();
    if (currentForm && categoryName){
        currentForm.reset();
        categoryName.focus();
    }
}

function CategorySubmit(e){
    e.preventDefault();
    if(!pValidator.validate()){
        return;
    }

    CategorySetLoading(true);
    let url = '';
    let categorySendData = {};
    categorySendData.name =  document.getElementById('categoryName').value || '';
    categorySendData.description =  document.getElementById('categoryDescription').value || '';
    categorySendData.state =  document.getElementById('categoryState').checked || false;
    categorySendData.parentId =  0;

    if (CategoryState.modalType === 'create'){
        url = '/category/create';
    }
    if (CategoryState.modalType === 'update'){
        url = '/category/update';
        categorySendData.categoryId = document.getElementById('categoryId').value || 0;
    }

    RequestApi.fetch(url,{
        method: 'POST',
        body: categorySendData
    }).then(res => {
        if (res.success){
            SnModal.close(CategoryState.modalName);
            SnMessage.success({ content: res.message });
            CategoryList();
        } else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e =>{
        CategorySetLoading(false);
    })
}

function CategoryDelete(categoryId, content = ''){
    SnModal.confirm({
        title: '¿Estás seguro de eliminar este registro?',
        content: content,
        okText: 'Si',
        okType: 'error',
        cancelText: 'No',
        onOk() {
            CategorySetLoading(true);
            RequestApi.fetch('/category/delete', {
                method: 'POST',
                body: {
                    categoryId: categoryId || 0
                }
            }).then(res => {
                if (res.success) {
                    SnMessage.success({ content: res.message });
                    CategoryList();
                } else {
                    SnModal.error({ title: 'Algo salió mal', content: res.message })
                }
            }).finally(e => {
                CategorySetLoading(false);
            })
        }
    });
}

function CategoryShowModalCreate(){
    CategoryState.modalType = 'create';
    SnModal.open(CategoryState.modalName);
    CategoryClearForm();
}

function CategoryShowModalUpdate(categoryId){
    CategoryState.modalType = 'update';
    CategorySetLoading(true);
    CategoryClearForm();

    RequestApi.fetch('/category/id',{
        method: 'POST',
        body: {
            categoryId: categoryId || 0
        }
    }).then(res => {
        if (res.success){
            document.getElementById('categoryName').value  = res.result.name;
            document.getElementById('categoryDescription').value  = res.result.description;
            document.getElementById('categoryState').checked = res.result.state == '0' ? false : true;
            document.getElementById('categoryId').value = res.result.category_id;
            SnModal.open(CategoryState.modalName);
        }else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e => {
        CategorySetLoading(false);
    })
}

function CategoryToExcel(){
    let dataTable = document.getElementById('categoryCurrentTable');
    if(dataTable){
        window.open('data:application/vnd.ms-excel,' + encodeURIComponent(dataTable.outerHTML));
    }
}

function CategoryToPrint(){
    printArea('categoryCurrentTable');
}

document.addEventListener('DOMContentLoaded',()=>{
    pValidator = new Pristine(document.getElementById('categoryForm'));

    document.getElementById('searchContent').addEventListener('input',e=>{
        CategoryList(1,10,e.target.value);
    });

    CategoryList();
});