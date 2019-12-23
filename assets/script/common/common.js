const Service = {
    path: '/snfact',
    apiPath: '/snfact',
};

class RequestApi {
    static setHeaders(options) {
        if (options.method === 'POST' || options.method === 'PUT' || options.method === 'DELETE') {
            if (!(options.body instanceof FormData)) {
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
            } else {
                options.headers = {
                    Accept: 'application/json',
                    ...options.headers,
                };
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

const generateUniqueId = () => {
    let length = 6;
    let timestamp = + new Date;

    let _getRandomInt = function( min, max ) {
        return Math.floor( Math.random() * ( max - min + 1 ) ) + min;
    };

    let ts = timestamp.toString();
    let parts = ts.split( "" ).reverse();
    let id = "";
    for( let i = 0; i < length; ++i ) {
        let index = _getRandomInt( 0, parts.length - 1 );
        id += parts[index];
    }
    return id;
};

const roundCurrency = (num, decimals = 2) => {
    let number = parseFloat(num);
    number = Math.round(number * Math.pow(10, decimals)) / Math.pow(10, decimals);
    return  number.toFixed(decimals);
};

const validateInputIsNumber = (input) => {
    let regex = /[^0-9.,]/g;
    input.value = input.value.replace(regex, '');
    return regex.test(input.value);
};

const validateRUC = ruc => {
    if (isNaN(ruc)){
        return false;
    }

    if (!(ruc >= 1e10 && ruc < 11e9
        || ruc >= 15e9 && ruc < 18e9
        || ruc >= 2e10 && ruc < 21e9))
        return false;

    let sum;
    let i = 0;
    for (sum = -(ruc%10<2); i<11; i++, ruc = ruc/10|0){
        sum += (ruc % 10) * (i % 7 + (i/7|0) + 1);
    }

    return sum % 11 === 0
};

const validateDNI = dni => {
    return /^[0-9]{8}$/.test(dni);
};

const validateEmail = email => {
    return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email);
};

const groupBy = function(data, key) {
    return data.reduce((a, b) => {
        (a[b[key]] = a[b[key]] || []).push(b);
        return a;
    }, {});
};

let SnLiveList = option => {
    let tElementNodes = document.querySelectorAll(option.target);
    let loading = false;

    tElementNodes.forEach(targetElement => {
        let parentNode = targetElement.parentNode;
        let nodeLocalName = targetElement.localName;

        let listContainer = document.createElement('ul');
        listContainer.classList.add('SnLiveList');
        if (!parentNode.querySelector('.SnLiveList')) {
            parentNode.appendChild(listContainer);
        }

        const paintElement = async (event, targetElement) => {
            listContainer.innerHTML = '';
            if (option.data && typeof option.data.src === 'function'){
                let response = await option.data.src(event.target);
                [...response].forEach(item => {
                    let listItem = document.createElement('li');
                    listItem.classList.add('SnLiveList-item');

                    let dataKeys = option.data.keys;
                    if (dataKeys){
                        listItem.innerHTML = `<div>${item[dataKeys.text]}</div><div>${item[dataKeys.text]}</div>`;
                    }

                    listItem.addEventListener('click', e => {
                        listContainer.innerHTML = '';
                        if (option.onSelect && typeof option.onSelect === 'function'){
                            option.onSelect(e, item);
                            if (dataKeys){
                                targetElement.value = item[dataKeys.text];
                            }
                        }
                    });
                    listContainer.appendChild(listItem);
                });
                loading = false;
            }
        };
        // console.log(nodeLocalName);
        targetElement.addEventListener('change',  async e => {
            e.preventDefault();
            console.log(nodeLocalName);
            let targetElementInfo = targetElement.getBoundingClientRect();
            listContainer.style.top = (targetElementInfo.height - 1) + 'px';
            listContainer.style.width = targetElementInfo.width + 'px';

            if (!loading){
                targetElement.classList.add('loading');
                await paintElement(e,targetElement);
            } else {
                targetElement.classList.remove('loading');
            }
        });
    });
};