((w, d) => {
    const SkyGuideTemplate = `<div class="SkyGuide">
            <div class="SkyGuide-cover">{{modal-cover}}</div>
            <div class="SkyGuide-action">
                <span class="SkyGuide-btn">{{modal-map}}</span>
                <span>{{modal-title}}</span>
                <span class="SkyGuide-btn">{{modal-close}}</span>
            </div>
            <div class="SkyGuide-scroll">
                <div class="SkyGuide-header">{{modal-header}}</div>
                <div class="SkyGuide-content">{{modal-body}}</div>
            </div>
            <div class="SkyGuide-footer">
                <span class="SkyGuide-page">
                    <span class="SkyGuide-stepVal">{{step-value}}</span>
                    <span class="SkyGuide-stepTotal">{{step-total}}</span>
                </span>

                <span class="SkyGuide-btn">{{modal-prev}}</span>
                <span class="SkyGuide-btn">{{modal-next}}</span>

                <span class="SkyGuide-btn">{{modal-cancel}}</span>
                <span class="SkyGuide-btn">{{modal-start}}</span>

                <span class="SkyGuide-btn">{{modal-first}}</span>
                <span class="SkyGuide-btn">{{modal-continue}}</span>
            </div>
        </div>`;

    const SkyGuideLang = {
        lang: 'es',
        cancelTitle: 'Cancelar',
        cancelText: '×',
        hideText: 'Ocultar',
        tourMapText: '≡',
        tourMapTitle: 'Mapa del gira',
        nextTextDefault: 'Siguiente',
        prevTextDefault: 'Anterior',
        endText: 'Fin',

        modalIntroType: 'Introducción',
        modalContinueType: 'Tour sin terminar',

        contDialogBtnBegin: 'Primero',
        contDialogBtnContinue: 'Continuar',

        introDialogBtnStart: 'Iniciar',
        introDialogBtnCancel: 'Cancelar'
    };

    function getElementByContent(rootElement, text) {
        let filter = {
            acceptNode: function (node) {
                if (node.nodeType === d.TEXT_NODE && node.nodeValue.includes(text)) {
                    return NodeFilter.FILTER_ACCEPT;
                }
                return NodeFilter.FILTER_REJECT;
            }
        };
        let nodes = [];
        let walker = d.createTreeWalker(rootElement, NodeFilter.SHOW_TEXT, filter, false);
        while (walker.nextNode()) {
            nodes.push(walker.currentNode.parentNode);
        }
        return nodes;
    }

    function typeDetect(per) {
        if (typeof per == 'function') {
            return typeof per;
        }
        if (typeof per == 'boolean') {
            return typeof per;
        }
        if (typeof per == 'string') {
            return typeof per;
        }
        if (typeof per == 'number') {
            return typeof per;
        }
        if (typeof per == 'object') {
            if (Array.isArray(per)) {
                return 'array';
            } else {
                return 'object';
            }
        }
    }

    let toStringObjIndex = 0;
    function toStringObj(obj) {
        let newIndex = toStringObjIndex++;
        let objEnter = obj;
        let tempObj = {};
        tempObj[newIndex] = {};

        for (let i in objEnter) {
            if (objEnter.hasOwnProperty(i)) {
                let key1 = i;
                let val = objEnter[key1];
                //function
                if (typeDetect(val) === 'function') {
                    tempObj[newIndex][key1] = val.toString();
                }
                //array
                if (typeDetect(val) === 'array') {
                    let valEach = val;
                    for (let f = 0; f < valEach.length; f++) {
                        if (typeDetect(valEach[f]) === 'object') {
                            let objMass = valEach[f];
                            valEach[f] = toStringObj(objMass);
                        }
                    }
                    tempObj[newIndex][key1] = valEach;
                }
                //object
                if (typeDetect(val) === 'object') {
                    tempObj[newIndex][key1] = toStringObj(val);
                }
                //string, number, boolean
                if (typeDetect(val) === 'string' || typeDetect(val) === 'number' || typeDetect(val) === 'boolean') {
                    tempObj[newIndex][key1] = objEnter[key1];
                }
            }
        }
        return tempObj[newIndex];
    }

    function getLastStandardElement(elements, classNames = [], content = '') {
        let lastElement = elements[elements.length - 1];
        lastElement.innerHTML = content;
        classNames.forEach(i => lastElement.classList.add(i));
        return lastElement;
    }

    function clearRoundRect(ctx, x, y, width, height, radius) {
        if (width < 2 * radius) radius = width / 2;
        if (height < 2 * radius) radius = height / 2;
        ctx.save();
        ctx.beginPath();
        ctx.moveTo(x + radius, y);
        ctx.arcTo(x + width, y, x + width, y + height, radius);
        ctx.arcTo(x + width, y + height, x, y + height, radius);
        ctx.arcTo(x, y + height, x, y, radius);
        ctx.arcTo(x, y, x + width, y, radius);
        ctx.closePath();
        ctx.clip();
        ctx.clearRect(x - 2, y - 2, width + 4, height + 4);
        ctx.restore();
    }

    function saveState(state) {
        w.localStorage.setItem('SkyGuide', JSON.stringify(state));
    }

    function getState() {
        let skyGuideState = w.localStorage.getItem('SkyGuide');
        return skyGuideState != null ? JSON.parse(skyGuideState) : null;
    }

    w.SkyGuide = (options = null) => {
        let step,   // 1 - 2 - 3 - 4
            event,  // create || start
            view,
            data = {};

        // Set dinamic variables
        let stepHistory = [],
            delayAfterId,
            errorMessageId,
            delayBeforeId;

        const separator = 15;
        if (getState() == null && options == null) {
            return;
        }

        if (getState() != null) {
            let oldState = getState();
            step = oldState.step;
            event = oldState.event;
            data = oldState.data;
        } else {
            step = 0;
            event = 'create';
            data = options;
            // Set Default values
            data.spacing = options.spacing || 10;
        }

        // Create Canvas
        let sgCanvas = d.getElementById('SkyGuide-overlay');
        if (!sgCanvas) {
            sgCanvas = d.createElement('canvas');
            sgCanvas.id = 'SkyGuide-overlay';
            d.body.appendChild(sgCanvas);
        }
        let ctx = sgCanvas.getContext("2d");

        const setCanvasSize = () => {
            sgCanvas.height = w.innerHeight;
            sgCanvas.width = w.innerWidth;
        };

        const clearCanvas = () => {
            ctx.clearRect(0, 0, sgCanvas.width, sgCanvas.height);
            ctx.fillStyle = 'rgba(0,0,0,.4)';
            ctx.fillRect(0, 0, sgCanvas.width, sgCanvas.height);
        };
        setCanvasSize();
        clearCanvas();
        d.body.classList.add('SkyGuide-show');

        let sgModalPos = d.createElement('div');
        sgModalPos.classList.add('SkyGuide-wrapper');
        d.body.appendChild(sgModalPos);

        sgModalPos.innerHTML = SkyGuideTemplate;
        let sgModalCover = getLastStandardElement(getElementByContent(sgModalPos, '{{modal-cover}}'), ['sgModal-cover'], '');
        let sgModalTitle = getLastStandardElement(getElementByContent(sgModalPos, '{{modal-title}}'), ['sgModal-title'], '');
        let sgModalMap = getLastStandardElement(getElementByContent(sgModalPos, '{{modal-map}}'), ['sgModal-map'], SkyGuideLang.tourMapText);
        let sgModalClose = getLastStandardElement(getElementByContent(sgModalPos, '{{modal-close}}'), ['sgModal-close'], SkyGuideLang.cancelText);

        let sgModalHeader = getLastStandardElement(getElementByContent(sgModalPos, '{{modal-header}}'), ['sgModal-header'], '');
        let sgModalBody = getLastStandardElement(getElementByContent(sgModalPos, '{{modal-body}}'), ['sgModal-body'], '');

        let sgModalValue = getLastStandardElement(getElementByContent(sgModalPos, '{{step-value}}'), ['sgModal-value'], '');
        let sgModalTotal = getLastStandardElement(getElementByContent(sgModalPos, '{{step-total}}'), ['sgModal-total'], '');

        let sgModalPrev = getLastStandardElement(getElementByContent(sgModalPos, '{{modal-prev}}'), ['sgModal-prev'], SkyGuideLang.prevTextDefault);
        let sgModalNext = getLastStandardElement(getElementByContent(sgModalPos, '{{modal-next}}'), ['sgModal-next'], SkyGuideLang.nextTextDefault);

        let sgModalCancel = getLastStandardElement(getElementByContent(sgModalPos, '{{modal-cancel}}'), ['sgModal-cancel'], SkyGuideLang.introDialogBtnCancel);
        let sgModalStart = getLastStandardElement(getElementByContent(sgModalPos, '{{modal-start}}'), ['sgModal-start'], SkyGuideLang.introDialogBtnStart);

        let sgModalFirst = getLastStandardElement(getElementByContent(sgModalPos, '{{modal-first}}'), ['sgModal-first'], SkyGuideLang.contDialogBtnBegin);
        let sgModalContinue = getLastStandardElement(getElementByContent(sgModalPos, '{{modal-continue}}'), ['sgModal-continue'], SkyGuideLang.contDialogBtnContinue);

        let sgErrorMessage = d.createElement('div');
        sgModalTitle.innerHTML = data.guideTitle || '';

        function showErrorMessage(message) {
            sgErrorMessage.classList.add('sgModal-errorMessage');
            sgErrorMessage.innerHTML = message;
            sgModalPos.firstElementChild.appendChild(sgErrorMessage);
            setPositionModal(view);

            clearTimeout(errorMessageId);
            errorMessageId = setTimeout(() => {
                sgErrorMessage.remove();
                setPositionModal(view);
            }, 5000);
        }

        function checkNextFunc(targetElObj) {
            let result = true;
            if (view.checkNext && view.checkNext.func !== undefined) {
                let nextFunc = new Function('return ' + view.checkNext.func)();
                result = nextFunc(targetElObj);
                if (!result && view.checkNext.errorMessage !== undefined) {
                    showErrorMessage(view.checkNext.errorMessage)
                }
            }
            return result;
        }

        function beforeFunc(targetElObj) {
            if (view.before !== undefined) {
                let nextFunc = new Function('return ' + view.before)();
                nextFunc(targetElObj);
            }
        }

        function afterFunc(targetElObj) {
            if (view.after !== undefined) {
                let nextFunc = new Function('return ' + view.after)();
                nextFunc(targetElObj);
            }
        }

        function setEventListener(currentView) {
            if (currentView.event !== undefined) {
                const executeListener = (target, event) => {
                    let targetElem = document.querySelector(target);
                    targetElem.addEventListener(event,function (e) {
                        handleNext(e);
                    },{ once: true });
                };

                let eventType = typeDetect(currentView.event);
                if (eventType === 'string') {
                    if (currentView.target !== undefined && currentView.event !== undefined) {
                        executeListener(currentView.target, currentView.event);
                    }
                } else if (eventType === 'array') {
                    currentView.event.forEach(i => {
                        if (i.target !== undefined && i.event !== undefined) {
                            executeListener(i.target, i.event);
                        }
                    });
                }
            }
            if (currentView.abort !== undefined) {
                let eventType = typeDetect(currentView.abort);
                if (eventType === 'array') {
                    currentView.abort.forEach(i => {
                        if (i.target !== undefined && i.event !== undefined) {
                            $(i.target).on(i.event, e => {
                                destroyGuide();
                            });
                        }
                    });
                }
            }
        }

        function drawOverlay(currentView) {
            setCanvasSize();
            clearCanvas();

            [...d.querySelectorAll('.sgHighlight')].forEach(i => i.classList.remove('sgHighlight'));

            if (currentView.target) {
                let targets = currentView.target.trim().split(',');
                d.body.classList.add('SkyGuide-event');

                targets.forEach(i => {
                    let targetElement = d.querySelector(i);
                    if (targetElement) {

                        targetElement.classList.add('sgHighlight');
                        let positionInfo = targetElement.getBoundingClientRect();

                        let newPosX, newPosY, newW, newH;
                        newPosX = positionInfo.x - data.spacing;
                        newPosY = positionInfo.y - data.spacing;
                        newW = positionInfo.width + (data.spacing * 2);
                        newH = positionInfo.height + (data.spacing * 2);
                        clearRoundRect(ctx, newPosX, newPosY, newW, newH, 4);
                    }
                })
            }
        }

        function setPositionModal(currentView) {
            let guideModalInfo = sgModalPos.getBoundingClientRect();

            const setCenterView = () => {
                let newPosX = (w.innerWidth / 2) - (guideModalInfo.width / 2);
                let newPosY = (w.innerHeight / 2) - (guideModalInfo.height / 2);
                sgModalPos.style.top = `${newPosY}px`;
                sgModalPos.style.left = `${newPosX}px`;
            };

            const setClassname = position => {
                sgModalPos.classList.remove('sgT', 'sgR', 'sgB', 'sgL');
                sgModalPos.classList.add(position);
            };

            if (currentView.target) {
                let target = currentView.target.trim().split(',')[0];
                let targetElement = d.querySelector(target);
                if (targetElement) {
                    let targetElementInfo = targetElement.getBoundingClientRect();

                    // Set left position
                    let newPosX = targetElementInfo.x + targetElementInfo.width + data.spacing + separator;
                    let newPosY = targetElementInfo.y - data.spacing;
                    setClassname('sgR');

                    if (guideModalInfo.width > (w.innerWidth - newPosX)) {
                        // Set button position
                        newPosX = targetElementInfo.x - data.spacing;
                        newPosY = targetElementInfo.y + targetElementInfo.height + data.spacing + separator;
                        setClassname('sgB');

                        if (guideModalInfo.width > (w.innerWidth - newPosX)) {
                            newPosX = targetElementInfo.x - guideModalInfo.width + targetElementInfo.width + data.spacing;
                            setClassname('sgT');
                        }
                    }

                    if (guideModalInfo.height > (w.innerHeight - newPosY)) {
                        // Set top position
                        newPosY = targetElementInfo.y - guideModalInfo.height - data.spacing - separator;
                        newPosX = targetElementInfo.x - data.spacing;
                        setClassname('sgT');
                    }

                    sgModalPos.style.top = `${newPosY}px`;
                    sgModalPos.style.left = `${newPosX}px`;
                } else {
                    setCenterView();
                }
            } else {
                setCenterView();
            }
        }

        function PaintSkyGuideModal(currentView, step, total) {
            let targetLoc = location.href;
            if (currentView.loc) {
                if (targetLoc != currentView.loc) {
                    saveState({
                        step,
                        event,
                        data: toStringObj(data),
                    });
                    w.location.href = currentView.loc;
                    return;
                }
            }

            view = currentView;

            clearTimeout(delayBeforeId);
            delayBeforeId = setTimeout(() => {
                // execute before event
                beforeFunc(currentView);

                // Set content modal
                if (step === -1) {
                    sgModalHeader.innerHTML = '';
                    sgModalBody.innerHTML = '';
                    sgModalCover.innerHTML = '';
                } else {
                    sgModalHeader.innerHTML = currentView.title || '';
                    sgModalBody.innerHTML = currentView.content || '';
                    sgModalCover.innerHTML = currentView.cover ? `<img src="${currentView.cover}"/>` : '';

                    if (step === 0) {
                        sgModalPrev.classList.add('sgIsHide');
                        sgModalNext.classList.add('sgIsHide');

                        sgModalCancel.classList.remove('sgIsHide');
                        sgModalStart.classList.remove('sgIsHide');

                        sgModalFirst.classList.add('sgIsHide');
                        sgModalContinue.classList.add('sgIsHide');
                    } else {
                        sgModalValue.textContent = step;
                        sgModalTotal.textContent = total;
                        sgModalPrev.textContent = currentView.prevText || SkyGuideLang.prevTextDefault;
                        sgModalNext.textContent = currentView.nextText || SkyGuideLang.nextTextDefault;

                        if (step >= total) {
                            sgModalNext.textContent = SkyGuideLang.endText;
                        }
                        // if (step>1){
                        //     sgModalPrev.classList.remove('sgIsHide');
                        // } else {
                            sgModalPrev.classList.add('sgIsHide');
                        // }
                        if (view.event !== undefined) {
                            sgModalNext.classList.add('sgIsHide');
                        } else {
                            sgModalNext.classList.remove('sgIsHide');
                        }

                        sgModalCancel.classList.add('sgIsHide');
                        sgModalStart.classList.add('sgIsHide');

                        sgModalFirst.classList.add('sgIsHide');
                        sgModalContinue.classList.add('sgIsHide');

                        saveState({
                            step,
                            event,
                            data: toStringObj(data),
                        });

                        setEventListener(currentView);
                    }
                }

                drawOverlay(currentView);
                setPositionModal(currentView);

                // Move scroll
                [...d.querySelectorAll('.sgHighlight')].forEach(i => {
                    let targetPositionInfo = i.getBoundingClientRect();
                    w.scrollTo(targetPositionInfo.x, targetPositionInfo.y);
                });
            }, view.delayBefore || 0);
        }

        function listenerScrollW() {
            if (view) {
                setPositionModal(view);
                drawOverlay(view);
            }
        }

        function listenerResizeW() {
            if (view) {
                setPositionModal(view);
                drawOverlay(view);
            }
        }

        function destroyGuide() {
            clearTimeout(delayAfterId);
            clearTimeout(delayBeforeId);

            // sgModalClose.removeListener('click', handleCancel);
            // sgModalPrev.removeListener('click', handlePrev);
            // sgModalNext.addEventListener('click', handleNext);
            // sgModalCancel.addEventListener('click', handleCancel);
            // sgModalStart.addEventListener('click', handleStart);
            // sgModalFirst.addEventListener('click', handleFirst);
            // sgModalContinue.addEventListener('click', handleContinue);

            [...d.querySelectorAll('.sgHighlight')].forEach(i => i.classList.remove('sgHighlight'));

            d.body.classList.remove('SkyGuide-show');
            d.body.classList.remove('SkyGuide-event');

            sgCanvas.remove();
            sgModalPos.remove();
            w.localStorage.removeItem('SkyGuide');
        }

        function handleStart() {
            step = 1;
            PaintSkyGuideModal({
                ...data.steps[step - 1],
            }, step, data.steps.length);
        }

        function handleCancel() {
            destroyGuide();
        }

        function handleContinue() {
            console.log('handleContinue');
        }

        function handleFirst() {
            step = 1;
            PaintSkyGuideModal({
                ...data.steps[step - 1],
            }, step, data.steps.length);
        }

        function handlePrev() {
            step = step - 1;
            PaintSkyGuideModal({
                ...data.steps[step - 1],
            }, step, data.steps.length);
        }

        function handleNext(e = null) {
            clearTimeout(delayAfterId);
            delayAfterId = setTimeout(() => {
                if (checkNextFunc(e) === true) {
                    afterFunc(e);
                    if (data.steps.length == step) {
                        step = 0;
                        destroyGuide();
                    } else {
                        step = step + 1;
                        PaintSkyGuideModal({
                            ...data.steps[step - 1],
                        }, step, data.steps.length);
                    }
                }else {
                    setEventListener(view);
                }
            }, view.delayAfter || 0);
        }


        if (step >= 1) {
            PaintSkyGuideModal({
                ...data.steps[step - 1],
            }, step, data.steps.length);
        } else {
            if (data.intro.enable) {
                PaintSkyGuideModal({
                    ...data.intro,
                }, 0, 0);
            } else {
                handleFirst();
            }
        }


        w.addEventListener('scroll', listenerScrollW);
        w.addEventListener('resize', listenerResizeW);

        sgModalClose.addEventListener('click', handleCancel);
        sgModalPrev.addEventListener('click', handlePrev);
        sgModalNext.addEventListener('click', handleNext);
        sgModalCancel.addEventListener('click', handleCancel);
        sgModalStart.addEventListener('click', handleStart);
        sgModalFirst.addEventListener('click', handleFirst);
        sgModalContinue.addEventListener('click', handleContinue);
    };

    d.addEventListener("DOMContentLoaded", () => {
        w.SkyGuide();
    });
})(window, document);
