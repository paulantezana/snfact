function InvoiceList(page = 1, limit = 10, search = '', filter){
    let productTable = document.getElementById('invoiceTable');
    if(productTable){
        SnFreeze.freeze({selector: '#invoiceTable'});
        RequestApi.fetchText(`/invoice/table?limit=${limit}&page=${page}&search=${search}`,{
            method: 'POST',
            body: { filter }
        }).then(res => {
            productTable.innerHTML = res;
        }).finally(e =>{
            SnFreeze.unFreeze('#invoiceTable');
        })
    }
}

function InvoiceFilter(){
    let filterStartDate = document.getElementById('filterStartDate');
    let filterEndDate = document.getElementById('filterEndDate');
    let filterCustomerId = document.getElementById('filterCustomerId');
    if (filterStartDate && filterEndDate && filterCustomerId){
        InvoiceList(1,10,'',{
            startDate: filterStartDate.value,
            endDate: filterEndDate.value,
            customerId: filterCustomerId.value,
        });
    }
}

document.addEventListener('DOMContentLoaded',()=>{
    InvoiceList();

    let filterStartDate = document.getElementById('filterStartDate');
    if (filterStartDate){
        filterStartDate.addEventListener('input',()=>{
            InvoiceFilter();
        });
    }

    let filterEndDate = document.getElementById('filterEndDate');
    if (filterEndDate){
        filterEndDate.addEventListener('input',()=>{
            InvoiceFilter();
        });
    }

    let filterCustomerId = document.getElementById('filterCustomerId');
    if (filterCustomerId){
        filterCustomerId.addEventListener('input',()=>{
            InvoiceFilter();
        });
    }
});