const Service = {
    path: '/snfact',
    apiPath: '/snfact',
};

class RequestApi {
    static setHeaders(options) {
        if (options.method === 'POST' || options.method === 'PUT' || options.method === 'DELETE') {
            if (options.contentType === 'formData') {
                let formData = new FormData();
                let bodyKeys = Object.keys(options.body);
                bodyKeys.forEach(item =>{
                    formData.append(`${item}`, options.body[item]);
                });
                options.body = formData;
            } else {
                options.headers = {
                    Accept: 'application/json',
                    'Content-Type': 'application/json; charset=utf-8',
                    ...options.headers,
                };
                options.body = JSON.stringify(options.body);
            }
        }
        return options;
    };

    static fetch(path, options) {
        NProgress.start();
        const newOptions = RequestApi.setHeaders({...options}); // format

        return fetch(Service.apiPath + path, newOptions)
            .then(response => {
                return response.json(); // Return response
            }).catch(err => {
                console.warn(err);
                return err;
            }).finally(e=>{
                NProgress.done();
            })
    }

    static fetchText(path, options) {
        const url = Service.apiPath + path; // uri request

        NProgress.start();
        return fetch(url, options)
            .then(response => {
                return response.text();
            }).catch(err => {
                console.warn(err);
                return err;
            }).finally(e => {
                NProgress.done();
            })
    }
}
