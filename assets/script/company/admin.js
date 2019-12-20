SnMenu({
    menuId: 'AsideMenu',
    toggleButtonID: 'AsideMenu-toggle',
    toggleClass: 'AsideMenu-is-show',
    contextId: 'AdminLayout',
    parentClose: true,
    menuCloseID: 'AsideMenu-wrapper',
});

document.addEventListener('DOMContentLoaded',()=>{
    RequestApi.fetch('/admin/getGlobalInfo').then(res => {
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
                        RequestApi.fetch('/admin/setCurrentLocal',{
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