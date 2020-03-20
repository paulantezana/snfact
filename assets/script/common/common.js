const Service = {
    path: '/snfact',
};

class RequestApi {
    static setHeaders(options) {
        if (options.method === 'POST' || options.method === 'PUT' || options.method === 'DELETE' || options.method === 'GET') {
            if (!(options.body instanceof FormData)) {
                if (options.contentType === 'formData') {
                    let formData = new FormData();
                    let bodyKeys = Object.keys(options.body);
                    bodyKeys.forEach(item => {
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

    static fetch(path, options, responseType = 'json') {
        NProgress.start();
        const newOptions = RequestApi.setHeaders({ ...options }); // format

        return fetch(Service.path + path, newOptions)
            .then(response => {
                if (responseType === 'json'){
                  return response.json();
                } else {
                  return response.text();
                }
            }).catch(err => {
                console.warn(err);
                return err;
            }).finally(e => {
                NProgress.done();
            })
    }

    static fetchText(path, options) {
        const url = Service.path + path; // uri request

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

const generateUniqueId = (length = 6) => {
    let timestamp = + new Date;

    let _getRandomInt = function (min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    };

    let ts = timestamp.toString();
    let parts = ts.split("").reverse();
    let id = "";
    for (let i = 0; i < length; ++i) {
        let index = _getRandomInt(0, parts.length - 1);
        id += parts[index];
    }
    return id;
};

const roundCurrency = (num, decimals = 2) => {
    let number = parseFloat(num);
    number = Math.round(number * Math.pow(10, decimals)) / Math.pow(10, decimals);
    return number.toFixed(decimals);
};

const validateRUC = ruc => {
    if (isNaN(ruc)) {
        return false;
    }

    if (!(ruc >= 1e10 && ruc < 11e9
        || ruc >= 15e9 && ruc < 18e9
        || ruc >= 2e10 && ruc < 21e9))
        return false;

    let sum;
    let i = 0;
    for (sum = -(ruc % 10 < 2); i < 11; i++ , ruc = ruc / 10 | 0) {
        sum += (ruc % 10) * (i % 7 + (i / 7 | 0) + 1);
    }

    return sum % 11 === 0
};

const groupBy = function (data, key) {
    return data.reduce((a, b) => {
        (a[b[key]] = a[b[key]] || []).push(b);
        return a;
    }, {});
};

const printArea = function(idElem){
    let dataTable = document.getElementById(idElem);
    if(dataTable){
        var content = dataTable.outerHTML;
        var mywindow = window.open('', 'Print', 'height=600,width=800');

        mywindow.document.write('<html><head><title>Print</title>');
        mywindow.document.write('</head><body >');
        mywindow.document.write(content);
        mywindow.document.write('</body></html>');

        mywindow.document.close();
        mywindow.focus()
        mywindow.print();
    }
}

let SnLiveList = options => {
    let tElementNodes = document.querySelectorAll(options.elem);

    tElementNodes.forEach(tElementNode => {
        let ul = document.createElement('ul');
        ul.classList.add('SnLiveList');
        let currentData = [];

        const itemOnClick = (e) => {
            e.preventDefault();
            let t = e.target;

            if (t.tagName === 'LI') {
                let index = t.getAttribute('data-index');
                let type = t.getAttribute('data-type');
                if(type == 'data'){
                    options.onSelect(e, currentData[index]);
                }
            }

            ul.remove();
        }

        const renderContainer = () => {
            let parentNode = tElementNode.parentNode;
            if (!parentNode.querySelector('.SnLiveList')) {
                parentNode.appendChild(ul);
                parentNode.addEventListener('click',itemOnClick);
            }

            setPositionContainer();
        }

        const setPositionContainer = () => {
            let tElementNodeInfo = tElementNode.getBoundingClientRect();
            ul.style.top = tElementNodeInfo.height + 'px';
            ul.style.width = tElementNodeInfo.width + 'px';
        }

        const dataPaint = data => {
            if (typeof data === 'object') {
                ul.innerHTML = '';
                data.forEach((item, index) => {
                    let li = `<li data-type="data" data-index="${index++}" class="SnLiveList-item">${item.text}</li>`;
                    ul.insertAdjacentHTML('beforeend', li);
                });
                currentData = data;
                renderContainer();
            } else if (typeof data === 'string'){
                ul.innerHTML = `<li data-type="alert" class="SnLiveList-item alert">${data}</li>`;
                currentData = [];
                renderContainer();
            }
        }

        tElementNode.addEventListener('input', function (e) {
            e.preventDefault();
            if (options.data) {
                if (typeof options.data == 'function') {
                    options.data(tElementNode.value, dataPaint);
                }
            }
        });
    });
};

function SnDropdown(){
    let SnDropdowns = document.querySelectorAll('.SnDropdown');
    SnDropdowns.forEach((item,index)=>{
        if(item.getAttribute('tabindex') === null){
            item.setAttribute('tabindex',generateUniqueId());
            let toogleElem = item.querySelector('.SnDropdown-toggle');
            let listElem = toogleElem.nextElementSibling;
            toogleElem.addEventListener('click',()=>{
                listElem.classList.toggle('show');
            });
        }
    });
}

document.addEventListener('DOMContentLoaded',()=>{
    SnDropdown();
});
