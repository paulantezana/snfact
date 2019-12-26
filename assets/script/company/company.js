let DocumentPrinter = {
    print(){
        let frm = document.querySelector('#documentPrinterIframe iframe');
        if (frm){
            frm.contentWindow.print();
        }
    },
    showModal(url, showPrinter = false){
        if (!showPrinter){
            SnModal.open('documentModal');
        }
        let salePrintModalIContent = document.getElementById('documentPrinterIframe');
        let documentPrinterOpenBrowser = document.getElementById('documentPrinterOpenBrowser');
        if (documentPrinterOpenBrowser && salePrintModalIContent) {
            salePrintModalIContent.innerHTML = '';
            salePrintModalIContent.innerHTML = `<iframe src="${Service.path}/${url}" width="100%" height="600" frameborder="0" id="documentPrinterIframe"></iframe>`;
            documentPrinterOpenBrowser.setAttribute('href',`${Service.path}/${url}`);
            if (showPrinter){
                this.print();
            }
        }
    }
};

document.addEventListener('DOMContentLoaded',()=>{
    RequestApi.fetch('/company/getGlobalInfo').then(res => {
        if (res.success){
            let businessEnvironmentInfo = document.getElementById('businessEnvironmentInfo');
            if (businessEnvironmentInfo && res.result.business !== undefined){
                if (res.result.business.environment){
                    businessEnvironmentInfo.innerHTML = '<i class="icon-check SnMr-2"></i> Produción';
                    businessEnvironmentInfo.classList.add('production');
                }else {
                    businessEnvironmentInfo.innerHTML = '<i class="icon-blocked SnMr-2"></i> Demo';
                    businessEnvironmentInfo.classList.add('demo');
                }
            }

            let businessCurrentLocalInfo = document.getElementById('businessCurrentLocalInfo');
            if (businessCurrentLocalInfo && res.result.businessLocals){
                let businessLocalsOptions = '<option value="">Seleccionar local</option>';
                [...res.result.businessLocals].forEach(item => {
                    let defaultLocal = parseInt(item.businessLocalId) === parseInt(res.result.currentLocal) ? 'selected' :'';
                    businessLocalsOptions += `<option value="${item.businessLocalId}" ${defaultLocal}>${item.shortName}</option>`;
                });
                businessCurrentLocalInfo.innerHTML = businessLocalsOptions;
                businessCurrentLocalInfo.addEventListener('change',()=>{
                    if (businessCurrentLocalInfo.value !== ''){
                        RequestApi.fetch('/company/setCurrentLocal',{
                            method: 'POST',
                            body: {
                                businessLocalId: businessCurrentLocalInfo.value
                            }
                        }).then(res => {
                            if (res.success){
                                location.reload();
                            } else {
                                SnModal.error({ title: 'Algo salió mal', content: res.message });
                            }
                        });
                    }
                });
            }

            if (res.result.user){
                let userTitleInfo = document.getElementById('userTitleInfo');
                let userDescriptionInfo = document.getElementById('userDescriptionInfo');
                if (userTitleInfo){
                    userTitleInfo.textContent = res.result.user.userName;
                }
                if (userDescriptionInfo){
                    userDescriptionInfo.textContent = res.result.user.email;
                }
            }
        }else {
            SnModal.error({ title: 'Algo salió mal', content: res.message });
        }
    });
});