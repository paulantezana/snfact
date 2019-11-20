let  UserForm = {
    currentModeForm : 'create',
    modalName : 'userModalForm',

    currentForm : null,
    submitButton : null,

    loading : false,
    init() {
        this.currentForm = document.getElementById('userForm');
        this.submitButton = document.getElementById('userFormSubmit');
        this.list();
    },
    search(event){
        event.preventDefault();
        this.list(1,10,event.target.value);
    },
    list(page = 1, limit = 10, search = ''){
        let customerTable = document.getElementById('userTable');
        if(customerTable){
            this.setLoading(true);
            RequestApi.fetchText(`/user/table?limit=${limit}&page=${page}&search=${search}`,{
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
        let jsUserOption = document.querySelectorAll('.jsUserOption');

        if (this.loading){
            if(this.submitButton){
                this.submitButton.setAttribute('disabled','disabled');
                this.submitButton.classList.add('loading');
                if (jsUserOption) {
                    jsUserOption.forEach(item => {
                        item.setAttribute('disabled', 'disabled');
                    });
                }
            }
        } else {
            if(this.submitButton){
                this.submitButton.removeAttribute('disabled');
                this.submitButton.classList.remove('loading');
                if (jsUserOption) {
                    jsUserOption.forEach(item => {
                        item.removeAttribute('disabled');
                    });
                }
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
        let userSendData = {};
        userSendData.password =  document.getElementById('userPassword').value || '';
        userSendData.passwordConfirm =  document.getElementById('userPasswordConfirm').value || '';
        userSendData.email =  document.getElementById('userEmail').value || '';
        userSendData.userName =  document.getElementById('userUserName').value || '';
        userSendData.state =  document.getElementById('userState').checked || false;
        userSendData.userRoleId =  document.getElementById('userUserRoleId').value || '';

        if (this.currentModeForm === 'create'){
            url = '/user/create';
        }
        if (this.currentModeForm === 'update'){
            url = '/user/update';
            userSendData.userId = document.getElementById('userId').value || 0;
        }
        if (this.currentModeForm === 'updatePassword'){
            url = '/user/updatePassword';
            userSendData = {
                password :  document.getElementById('userPassword').value || '',
                passwordConfirm :  document.getElementById('userPasswordConfirm').value || '',
                userId : document.getElementById('userId').value || 0,
            }
        }

        RequestApi.fetch(url,{
            method: 'POST',
            body: userSendData
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
    delete(userId, content = '') {
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
                RequestApi.fetch('/user/delete', {
                    method: 'POST',
                    body: {
                        userId: userId || 0
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
        this.showModalMode('create');
        SnModal.open(this.modalName);
    },

    showModalMode(mode = ''){
        document.getElementById('userEmail').parentElement.classList.remove('hidden');
        document.getElementById('userUserName').parentElement.classList.remove('hidden');
        document.getElementById('userState').parentElement.classList.remove('hidden');
        document.getElementById('userUserRoleId').parentElement.classList.remove('hidden');
        document.getElementById('userPassword').parentElement.parentElement.classList.remove('hidden');
        document.getElementById('userPasswordConfirm').parentElement.parentElement.classList.remove('hidden');

        if (mode === 'normal'){
            document.getElementById('userPassword').parentElement.parentElement.classList.add('hidden');
            document.getElementById('userPasswordConfirm').parentElement.parentElement.classList.add('hidden');
        } else if(mode === 'password') {
            document.getElementById('userEmail').parentElement.classList.add('hidden');
            document.getElementById('userUserName').parentElement.classList.add('hidden');
            document.getElementById('userState').parentElement.classList.add('hidden');
            document.getElementById('userUserRoleId').parentElement.classList.add('hidden');
        } else if(mode === 'create') {
            document.getElementById('userState').checked = true;
        }
    },

    executeUpdateNormal(userId){
        this.showModalMode('normal');
        this.currentModeForm = 'update';
        this.showModalUpdate(userId);
    },

    executeUpdatePassword(userId){
        this.showModalMode('password');
        this.currentModeForm = 'updatePassword';
        this.showModalUpdate(userId);
    },

    showModalUpdate(userId){
        this.clearForm();

        this.setLoading(true);
        RequestApi.fetch('/user/id',{
            method: 'POST',
            body: {
                userId: userId || 0
            }
        }).then(res => {
            if (res.success){
                document.getElementById('userEmail').value  = res.result.email;
                document.getElementById('userUserName').value  = res.result.user_name;
                document.getElementById('userState').checked  = res.result.state == '0' ? false : true;
                document.getElementById('userUserRoleId').value  = res.result.user_role_id;
                document.getElementById('userId').value = res.result.user_id;
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
    UserForm.init();
});