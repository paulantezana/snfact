let UserRoleForm = {
    currentModeForm: 'create',
    modalName: 'userRoleModalForm',

    currentForm: null,
    submitButton: null,

    loading: false,
    passCustomerId: 0,

    currentUserRoleId: 0,

    init() {
        this.currentForm = document.getElementById('userRoleForm');
        this.submitButton = document.getElementById('userRoleFormSubmit');
        this.list();
    },

    list() {
        RequestApi.fetchText('/userRole/list')
            .then(res => {
                let passPasswordContainer = document.getElementById('userRoleListContainer');
                if (passPasswordContainer) {
                    passPasswordContainer.innerHTML = res;
                }
            })
    },

    loadAuthorities(userRoleId, content) {
        this.currentUserRoleId = userRoleId;
        this.setLoading(true);
        RequestApi.fetch('/appAuthorization/byUserRoleId', {
            method: 'POST',
            body: {
                userRoleId: userRoleId || 0,
            }
        }).then(res => {
            if (res.success) {
                let rows = document.querySelectorAll('#userRoleAuthList [id*="autState"]');
                rows.forEach(item => {
                    item.checked = false;
                });

                [...res.result].forEach(item => {
                    let autState = document.querySelector(`#userRoleAuthList #autState${item.app_authorization_id}`);
                    if (autState){
                        autState.checked = true;
                    }
                });

                document.getElementById('userRoleAuthSave').classList.remove('hidden');
                document.getElementById('userRoleAuthTitle').textContent = content;
            } else {
                SnModal.error({ title: 'Algo salió mal', content: res.message });
            }
        }).finally(e => {
            this.setLoading(false);
        });
    },

    saveAuthorization(){
        if (!(this.currentUserRoleId >= 1)){
            SnModal.error({ title: 'Algo salió mal', content: 'No se indico el rol'});
            return;
        }

        let rows = document.querySelectorAll('#userRoleAuthList tbody tr');

        let enableAuth = [];
        rows.forEach(item => {
            let authId = item.dataset.id;
            let authState = item.querySelector(`#autState${authId}`);
            if (authState.checked){
                enableAuth.push(parseInt(authId));
            }
        });

        this.setLoading(true);
        RequestApi.fetch('/appAuthorization/save', {
            method: 'POST',
            body: {
                authIds: enableAuth || [],
                userRoleId: this.currentUserRoleId || 0,
            }
        }).then(res => {
            if (res.success) {
                SnMessage.success({ content: res.message });
            } else {
                SnModal.error({ title: 'Algo salió mal', content: res.message })
            }
        }).finally(e => {
            this.setLoading(false);
        })
    },

    setLoading(state) {
        this.loading = state;
        let jsUserRoleOption = document.querySelectorAll('.jsUserRoleOption');

        if (this.loading) {
            if (this.submitButton) {
                this.submitButton.setAttribute('disabled', 'disabled');
                this.submitButton.classList.add('loading');
                if (jsUserRoleOption) {
                    jsUserRoleOption.forEach(item => {
                        item.setAttribute('disabled', 'disabled');
                    });
                }
            }
        } else {
            if (this.submitButton) {
                this.submitButton.removeAttribute('disabled');
                this.submitButton.classList.remove('loading');
                if (jsUserRoleOption) {
                    jsUserRoleOption.forEach(item => {
                        item.removeAttribute('disabled');
                    });
                }
            }
        }
    },

    clearForm() {
        if (this.currentForm) {
            this.currentForm.reset();
        }
    },

    submit(event) {
        event.preventDefault();
        let userRole = {
            name: document.getElementById('userRoleFormName').value || '',
        };
        if (this.currentModeForm === 'update') {
            userRole.userRoleId = document.getElementById('userRoleFormId').value || 0;
        }

        this.setLoading(true);
        const url = this.currentModeForm === 'update' ? '/userRole/update' : '/userRole/create';

        RequestApi.fetch(url, {
            method: 'POST',
            body: userRole
        }).then(res => {
            if (res.success) {
                this.list();
                SnModal.close(this.modalName);
                SnMessage.success({ content: res.message });
            } else {
                SnModal.error({ confirm: false, title: 'Algo salió mal', content: res.message })
            }
        }).finally(e => {
            this.setLoading(false);
        })
    },

    delete(userRoleId, content = '') {
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
                RequestApi.fetch('/userRole/delete', {
                    method: 'POST',
                    body: {
                        userRoleId: userRoleId || 0
                    }
                }).then(res => {
                    if (res.success) {
                        _list();
                        SnMessage.success({ content: res.message });
                    } else {
                        SnModal.error({ title: 'Algo salió mal', content: res.message })
                    }
                }).finally(e => {
                    _setLoading(false);
                })
            }
        });
    },

    showModalCreate() {
        SnModal.open(this.modalName);
        this.clearForm();
        this.currentModeForm = 'create';
    },

    showModalUpdate(userRoleId, content) {
        this.currentModeForm = 'update';
        this.setLoading(true);

        RequestApi.fetch('/userRole/id', {
            method: 'POST',
            body: {
                userRoleId: userRoleId || 0
            }
        }).then(res => {
            if (res.success) {
                document.getElementById('userRoleFormName').value = res.result.name || '';
                document.getElementById('userRoleFormId').value = res.result.user_role_id || '';
                SnModal.open(this.modalName);
            } else {
                SnModal.error({ title: 'Algo salió mal', content: res.message })
            }
        }).finally(e => {
            this.setLoading(false);
        })
    },
};

document.addEventListener('DOMContentLoaded',()=>{
    UserRoleForm.init();
});