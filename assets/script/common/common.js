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

    static fetch(path, options) {
        NProgress.start();
        const newOptions = RequestApi.setHeaders({ ...options }); // format

        return fetch(Service.apiPath + path, newOptions)
            .then(response => {
                return response.json(); // Return response
            }).catch(err => {
                console.warn(err);
                return err;
            }).finally(e => {
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

let SnSelectStore = [];

let SnSelect = function (options, action = 'create') {
    if (action === 'set') {
        let tElementNode = document.querySelector(options.elem);
        if (!tElementNode) {
            console.warn(options.elem + ' Not found');
            return;
        }

        if (tElementNode.dataset.ui == undefined) {
            return;
        }

        let ui = tElementNode.dataset.ui;
        let dataElement = SnSelectStore.find(item => item.ui == ui);
        if (!dataElement) {
            console.warn(dataElement + ' Not found');
            return;
        }

        let current = dataElement.data.find(item => item.value == options.value);
        if (!current) {
            let option = document.createElement('option');
            option.textContent = options.text;
            option.value = options.value;
            tElementNode.appendChild(option);
            SnSelectStore = SnSelectStore.map(item => {
                let newData = [
                    ...item.data,
                    {
                        value: options.value,
                        text: options.text,
                        source: 'local'
                    }
                ];
                return { ...item, data: newData }
            });
        }

        tElementNode.value = options.value;
        dataElement.button.textContent = options.text;
    } else if (action === 'reset') {

    } else if (action === 'create') {
        let tElementNodes = document.querySelectorAll(options.elem);
        console.log(tElementNodes);
        tElementNodes.forEach((elem, ui) => {
            elem.setAttribute('data-ui', ui);

            let selectDropClass = 'SnSelect-drop',
                optgroupClass = 'SnSelect-optgroup',
                selectedClass = 'is-selected',
                openClass = 'is-open',
                selectOpgroups = elem.getElementsByTagName('optgroup');

            // creating the pseudo-select container
            let selectContainer = document.createElement('div');
            selectContainer.className = 'SnSelect-wrapper';

            // creating the always visible main button
            let button = document.createElement('button');
            button.className = selectDropClass;

            // Creating Serach input
            let seaWrapper = document.createElement('div');
            let seaInput = document.createElement('input');
            let seaIcon = document.createElement('span');
            seaWrapper.className = 'SnSelect-search SnControl-wrapper';
            seaInput.className = 'SnForm-control SnControl';
            seaIcon.className = 'SnControl-prefix icon-search4';
            seaWrapper.appendChild(seaIcon);
            seaWrapper.appendChild(seaInput);

            // creating the UL
            let listContent = document.createElement('div');
            listContent.className = 'SnSelect-content';

            var ul = document.createElement('ul');
            ul.className = 'SnSelect-list';

            // dealing with optgroups
            // if (selectOpgroups.length) {
            // for (var i = 0; i < selectOpgroups.length; i++) {
            //     var div = document.createElement('div');
            //     div.innerText = selectOpgroups[i].label;
            //     div.classList.add(optgroupClass);

            //     ul.appendChild(div);
            //     generateOptions(selectOpgroups[i].getElementsByTagName('option'));
            // }
            // } else {
            buildOptions(elem.options);
            // }

            // appending the button and the list
            selectContainer.appendChild(button);
            if(options.data != undefined){
                listContent.appendChild(seaWrapper);
            }
            listContent.appendChild(ul);
            selectContainer.appendChild(listContent);

            // pseudo-select is ready - append it and hide the original
            elem.parentNode.insertBefore(selectContainer, elem);
            elem.style.display = 'none';

            function buildOptions(options) {
                let data = [];
                for (var i = 0; i < options.length; i++) {
                    data.push({
                        text: options[i].textContent,
                        value: options[i].value,
                        source: 'local'
                    });
                }
                SnSelectStore.push({ ui, data, button });
                paintList(data);
            }

            function paintList(data = []) {
                ul.innerHTML = '';
                if (typeof data == 'object') {
                    data.forEach((item, index) => {
                        let li = document.createElement('li');

                        li.innerText = item.text || '';
                        li.setAttribute('data-value', item.value);
                        li.setAttribute('data-index', index++);

                        if (elem.options[elem.selectedIndex].value === item.value) {
                            li.classList.add(selectedClass);
                            button.textContent = item.text;
                        }

                        ul.appendChild(li);
                    });
                }
            }

            function dataPaint(data) {
                if (typeof data == 'object') {
                    data.forEach(item => {
                        let match = false;
                        for (var i = 0; i < elem.options.length; i++) {
                            if (item.value == elem.options[i].value) {
                                match = true;
                            }
                        }

                        if (!match) {
                            let option = document.createElement('option');
                            option.textContent = item.text;
                            option.value = item.value;
                            elem.appendChild(option);
                        }
                    });

                    for (var i = 0; i < elem.options.length; i++) {
                        let match = false;
                        for (let y = 0; y < data.length; y++) {
                            if (elem.options[i].value == data[y].value) {
                                match = true;
                            }
                        }
                        if (!match) {
                            elem.options[i].remove();
                        }
                    }

                    paintList(data);
                }
            }


            function onClick(e) {
                e.preventDefault();

                var t = e.target; // || e.srcElement; - uncomment for IE8

                if (t.className === selectDropClass) {
                    toggle();
                }

                if (t.tagName === 'LI') {
                    selectContainer.querySelector('.' + selectDropClass).innerText = t.innerText;
                    elem.options.selectedIndex = t.getAttribute('data-index');

                    //trigger 'change' event
                    var evt = new CustomEvent('change');
                    elem.dispatchEvent(evt);

                    // highlight the selected
                    for (var i = 0; i < elem.options.length; i++) {
                        ul.querySelectorAll('li')[i].classList.remove(selectedClass);
                    }
                    t.classList.add(selectedClass);

                    close();
                }
            }

            function toggle() {
                listContent.classList.toggle(openClass);
            }

            function open() {
                listContent.classList.add(openClass);
            }

            function close() {
                listContent.classList.remove(openClass);
            }

            // Listeners
            selectContainer.addEventListener('click', onClick);

            seaInput.addEventListener('input', function (e) {
                e.preventDefault();
                if (options.data) {
                    if (typeof options.data == 'function') {
                        options.data(seaInput.value, dataPaint);
                    }
                }
            });

            document.addEventListener('click', function (e) {
                if (!selectContainer.contains(e.target)) close();
            });
        });
    } else {
        console.warn('Not found');
    }
};

const SnDropdown = () => {
    let SnDropdowns = document.querySelectorAll('.SnDropdown');
    SnDropdowns.forEach((item,index)=>{
        let toogleElem = item.querySelector('.SnDropdown-toggle');
        let listElem = toogleElem.nextElementSibling
        toogleElem.addEventListener('click',()=>{
            listElem.classList.toggle('show');
        });
    });
}