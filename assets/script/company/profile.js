function ProfileUpdateProfile(event){
    event.preventDefault();
    let userSendData = {};
    userSendData.userId =  document.getElementById('userId').value;
    userSendData.email =  document.getElementById('userEmail').value;
    userSendData.userName =  document.getElementById('userUserName').value;

    RequestApi.fetch('/user/updateProfile',{
        method: 'POST',
        body: userSendData
    }).then(res => {
        if (res.success){
            SnMessage.success({ content: res.message });
        } else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    });
}

function ProfileUpdatePassword(event){
    event.preventDefault();
    let userSendData = {};
    userSendData.userId =  document.getElementById('userId').value;
    userSendData.password =  document.getElementById('userPassword').value;
    userSendData.passwordConfirm =  document.getElementById('userPasswordConfirm').value;

    RequestApi.fetch('/user/updatePassword',{
        method: 'POST',
        body: userSendData
    }).then(res => {
        if (res.success){
            SnMessage.success({ content: res.message });
            document.getElementById('userPassword').value = '';
            document.getElementById('userPasswordConfirm').value = '';
        } else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    });
}

function ProfileUpdate2fa(){
    event.preventDefault();
    let userSendData = {};
    userSendData.userId =  document.getElementById('userId').value;
    userSendData.user2faKey =  document.getElementById('user2faKey').value;
    userSendData.user2faSecret =  document.getElementById('user2faSecret').value;
    userSendData.user2faKeyEnable =  document.getElementById('user2faKeyEnable').checked;

    RequestApi.fetch('/user/update2fa',{
        method: 'POST',
        body: userSendData
    }).then(res => {
        if (res.success){
            SnMessage.success({ content: res.message });
        } else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    });
}