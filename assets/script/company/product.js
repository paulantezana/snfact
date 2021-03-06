let ProductState = {
    modalType: 'create',
    modalName: 'productModalForm',
    categoryModalName: 'categoryModalForm',
    loading: false,
    slimProductCode: null,
};
let pValidator;
let cValidator;

function ProductSetLoading(state) {
    ProductState.loading = state;
    let jsCategoryAction = document.querySelectorAll('.jsCategoryAction');
    let submitButton = document.getElementById('productFormSubmit');
    if (ProductState.loading) {
        if (submitButton) {
            submitButton.setAttribute('disabled', 'disabled');
            submitButton.classList.add('loading');
        }
        if (jsCategoryAction) {
            jsCategoryAction.forEach(item => {
                item.setAttribute('disabled', 'disabled');
            });
        }
    } else {
        if (submitButton) {
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

function ProductList(page = 1, limit = 10, search = '') {
    let productTable = document.getElementById('productTable');
    if (productTable) {
        SnFreeze.freeze({ selector: '#productTable' });
        RequestApi.fetchText(`/product/table?limit=${limit}&page=${page}&search=${search}`, {
            method: 'GET',
        }).then(res => {
            productTable.innerHTML = res;
        }).finally(e => {
            SnFreeze.unFreeze('#productTable');
        })
    }
}

function ProductClearForm() {
    let currentForm = document.getElementById('productForm');
    let productProductKey = document.getElementById('productProductKey');
    pValidator.reset();
    if (currentForm && productProductKey) {
        currentForm.reset();
        productProductKey.focus();
    }
    ProductState.slimProductCode.set('');
}

function ProductSubmit(e) {
    e.preventDefault();
    if(!pValidator.validate()){
        return;
    }

    ProductSetLoading(true);
    let url = '';
    let productSendData = {};
    productSendData.categoryId = document.getElementById('productCategoryId').value;
    productSendData.description = document.getElementById('productDescription').value;
    productSendData.unitPrice = document.getElementById('productUnitPrice').value || 0;
    productSendData.unitValue = document.getElementById('productUnitValue').value || 0;
    productSendData.productKey = document.getElementById('productProductKey').value;
    productSendData.productCode = document.getElementById('productProductCode').value;
    productSendData.unitMeasureCode = document.getElementById('productUnitMeasureCode').value;
    productSendData.affectationCode = document.getElementById('productAffectationCode').value;
    productSendData.systemIscCode = document.getElementById('productSystemIscCode').value;
    productSendData.isc = document.getElementById('productIsc').value;
    productSendData.state = document.getElementById('productState').checked || false;
    productSendData.bagTax = document.getElementById('productBagTax').checked || false;

    if (ProductState.modalType === 'create') {
        url = '/product/create';
    }
    if (ProductState.modalType === 'update') {
        url = '/product/update';
        productSendData.productId = document.getElementById('productId').value || 0;
    }

    RequestApi.fetch(url, {
        method: 'POST',
        body: productSendData
    }).then(res => {
        if (res.success) {
            SnModal.close(ProductState.modalName);
            SnMessage.success({ content: res.message });
            ProductList();
        } else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e => {
        ProductSetLoading(false);
    })
}

function ProductDelete(productId, content = '') {
    SnModal.confirm({
        title: '¿Estás seguro de eliminar este registro?',
        content: content,
        okText: 'Si',
        okType: 'error',
        cancelText: 'No',
        onOk() {
            ProductSetLoading(true);
            RequestApi.fetch('/product/delete', {
                method: 'POST',
                body: {
                    productId: productId || 0
                }
            }).then(res => {
                if (res.success) {
                    SnMessage.success({ content: res.message });
                    ProductList();
                } else {
                    SnModal.error({ title: 'Algo salió mal', content: res.message })
                }
            }).finally(e => {
                ProductSetLoading(false);
            })
        }
    });
}

function ProductShowModalCreate() {
    ProductState.modalType = 'create';
    ProductClearForm();
    SnModal.open(ProductState.modalName);
    document.getElementById('productState').checked = true;
}

function ProductShowModalUpdate(productId) {
    ProductState.modalType = 'update';
    ProductClearForm();

    ProductSetLoading(true);
    RequestApi.fetch('/product/id', {
        method: 'POST',
        body: {
            productId: productId || 0
        }
    }).then(res => {
        if (res.success) {
            document.getElementById('productCategoryId').value = res.result.category_id;
            document.getElementById('productDescription').value = res.result.description;
            document.getElementById('productUnitPrice').value = res.result.unit_price;
            document.getElementById('productUnitValue').value = res.result.unit_value;
            document.getElementById('productProductKey').value = res.result.product_key;
            document.getElementById('productUnitMeasureCode').value = res.result.unit_measure_code;
            document.getElementById('productAffectationCode').value = res.result.affectation_code;
            document.getElementById('productSystemIscCode').value = res.result.system_isc_code;
            document.getElementById('productIsc').value = res.result.isc;
            document.getElementById('productState').checked = res.result.state == '0' ? false : true;
            document.getElementById('productBagTax').checked = res.result.bag_tax == '0' ? false : true;
            document.getElementById('productId').value = res.result.product_id;

            ProductState.slimProductCode.setData([
                {
                    text: res.result.product_code_description,
                    value: res.result.product_code,
                },
            ]);
            ProductState.slimProductCode.set(res.result.product_code);

            SnModal.open(ProductState.modalName);
        } else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e => {
        ProductSetLoading(false);
    })
}

function CategoryShowModalCreate() {
    let currentForm = document.getElementById('categoryForm');
    let categoryName = document.getElementById('categoryName');
    cValidator.reset();
    if (currentForm && categoryName) {
        currentForm.reset();
        categoryName.focus();
    }
    SnModal.open(ProductState.categoryModalName);
    document.getElementById('categoryState').checked = true;
}

function CategorySubmit(e) {
    e.preventDefault();
    if(!cValidator.validate()){
        return;
    }

    ProductSetLoading(true);

    let categorySendData = {};
    categorySendData.name = document.getElementById('categoryName').value;
    categorySendData.description = document.getElementById('categoryDescription').value;
    categorySendData.state = document.getElementById('categoryState').checked || false;
    categorySendData.parentId = 0;

    RequestApi.fetch('/category/create', {
        method: 'POST',
        body: categorySendData
    }).then(res => {
        if (res.success) {
            SnModal.close(ProductState.categoryModalName);
            SnMessage.success({ content: res.message });
            let productCategoryId = document.getElementById('productCategoryId');
            if (productCategoryId) {
                productCategoryId.insertAdjacentHTML('beforeend', `<option value="${res.result.category_id}">${res.result.name}</option>`);
                productCategoryId.value = res.result.category_id;
            }
        } else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e => {
        ProductSetLoading(false);
    })
}

function ProductToExcel(){
    let dataTable = document.getElementById('productCurrentTable');
    if(dataTable){
        window.open('data:application/vnd.ms-excel,' + encodeURIComponent(dataTable.outerHTML));
    }
}

function ProductToPrint(){
    printArea('productCurrentTable');
}

document.addEventListener('DOMContentLoaded', () => {
    pValidator = new Pristine(document.getElementById('productForm'));
    cValidator = new Pristine(document.getElementById('categoryForm'));

    document.getElementById('productSearch').addEventListener('input', e => {
        ProductList(1, 10, e.target.value);
    });

    ProductList();

    ProductState.slimProductCode = new SlimSelect({
        select: '#productProductCode',
        searchingText: 'Buscando...',
        // addToBody: true,
        ajax: function (search, callback) {
            if (search.length < 2) {
                callback('Escriba almenos 2 caracteres');
                return
            }
            RequestApi.fetch('/product/searchProductCode',{
                method: 'POST',
                body: { search: search }
            }).then(res=>{
                if (res.success){
                    let data = res.result.map(item=>({ text: item.description, value: item.code }));
                    callback(data);
                } else {
                    callback(false);
                }
            }).catch(err=>{
                callback(false);
            })
        }
    });

    let productUnitPrice = document.getElementById('productUnitPrice');
    let productUnitValue = document.getElementById('productUnitValue');
    if(productUnitPrice && productUnitValue){
        productUnitPrice.addEventListener('input',()=>{
            productUnitValue.value = roundCurrency(productUnitPrice.value / ( 1 + APP.igvPercentage));
        });
        productUnitValue.addEventListener('input',()=>{
            productUnitPrice.value = roundCurrency(productUnitValue.value * (1 + APP.igvPercentage)) ;
        });
    }
});
