let SnLiveList = option => {
    let tElementNodes = document.querySelectorAll(option.target);
    let loading = false;

    tElementNodes.forEach(targetElement => {
        let parentNode = targetElement.parentNode;

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

        targetElement.addEventListener('input',  async e => {
            e.preventDefault();

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

let Invoice = {
    invoiceItemTableBody: null,
    invoiceCurrencyInput: null,
    invoiceDocumentInput: null,
    includeIgv: false,
    igvPercentage: 0.18,

    init(){
        this.invoiceItemTableBody = document.getElementById('invoiceItemTableBody');
        this.invoiceCurrencyInput = document.getElementById('invoiceCurrencyCode');
        this.invoiceDocumentInput = document.getElementById('invoiceDocumentCode');

        this.invoiceCurrencyInput.addEventListener('change',e =>{
            document.querySelectorAll('.jsCurrencySymbol').forEach(item => {
                item.textContent = this.invoiceCurrencyInput.options[this.invoiceCurrencyInput.options.selectedIndex].dataset.symbol;
            });
        });

        let includeIgv = document.getElementById('includeIgv');
        includeIgv.addEventListener('change',e =>{
            this.includeIgv = includeIgv.checked;
        });
    },

    calcTotal(){
        let totalItemInput = [...document.querySelectorAll('jsInvoiceItemTotal')];
        let discountItemInput = [...document.querySelectorAll('.jsInvoiceItemDiscount')];
        let totalValueItemInput = [...document.querySelectorAll('.jsInvoiceItemTotalValue')];
        let affectationItemInput = [...document.querySelectorAll('.jsInvoiceItemAffectationCode')];
        let igvItemInput = [...document.querySelectorAll('.jsInvoiceItemIgv')];
        let iscItemInput = [...document.querySelectorAll('.jsInvoiceItemIsc')];

        let prepaymentRegulationItemInput = [...document.querySelectorAll('.jsPrepaymentRegulationItem')];

        // let iscItem = [...$('.JsInvoiceIscItem')];
        let plasticBagTaxInput = [...document.querySelectorAll('.jsInvoicePlasticBagTax')];

        // let invoiceSaleCreditNoteCode = $('#invoiceSaleCreditNoteCode');
        let invoiceGlobalDiscountPercentage = document.getElementById('invoiceTotalDiscountPercentage').value;

        // CALC prepayment
        // let sumPrepaymentTotalItem = 0;
        // $.each(totalValueItemInput, function(t) {
        //     if($(prepaymentRegulationItemInput[t]).is(':checked')){
        //         sumPrepaymentTotalItem += parseFloat($(totalValueItemInput[t]).val() || 0)
        //     }
        // });
        // $('#invoiceTotalPrepayment').val(roundCurrency(sumPrepaymentTotalItem));
        // $('#invoiceTotalPrepaymentText').html(roundCurrency(sumPrepaymentTotalItem));

        // CALC Exonerated
        let sumExoneratedTotalItem = 0;
        let sumExoneratedPrepaymentTotalItem = 0;
        totalValueItemInput.forEach((item, index) => {
            if (affectationItemInput[index].value === '20'){
                sumExoneratedTotalItem += parseFloat(totalValueItemInput[index].value || 0)
            }
        });
        let exoneratedDiscount = invoiceGlobalDiscountPercentage > 0 ? ( sumExoneratedTotalItem * invoiceGlobalDiscountPercentage / 100) : 0;
        document.getElementById('invoiceTotalExonerated').value = roundCurrency(sumExoneratedTotalItem - exoneratedDiscount - sumExoneratedPrepaymentTotalItem);
        document.getElementById('invoiceTotalExoneratedText').textContent = roundCurrency(sumExoneratedTotalItem - exoneratedDiscount - sumExoneratedPrepaymentTotalItem);


        // CALC Unaffected
        let sumUnaffectedTotalItem = 0;
        let sumUnaffectedPrepaymentTotalItem = 0;
        totalValueItemInput.forEach((item, index) => {
            if (affectationItemInput[index].value === '20'){
                sumUnaffectedTotalItem += parseFloat(totalValueItemInput[index].value || 0)
            }
        });
        let unaffectedDiscount = invoiceGlobalDiscountPercentage > 0 ? ( sumUnaffectedTotalItem * invoiceGlobalDiscountPercentage / 100) : 0;
        document.getElementById('invoiceTotalUnaffected').value = roundCurrency(sumUnaffectedTotalItem - unaffectedDiscount - sumUnaffectedPrepaymentTotalItem);
        document.getElementById('invoiceTotalUnaffectedText').textContent = roundCurrency(sumUnaffectedTotalItem - unaffectedDiscount - sumUnaffectedPrepaymentTotalItem);

        // CALC export
        let sumExportTotalItem = 0;
        let sumExportPrepaymentTotalItem = 0;
        totalValueItemInput.forEach((item, index) => {
            if (affectationItemInput[index].value === '20'){
                sumExportTotalItem += parseFloat(totalValueItemInput[index].value || 0)
            }
        });
        let exportDiscount = invoiceGlobalDiscountPercentage > 0 ? ( sumExportTotalItem * invoiceGlobalDiscountPercentage / 100) : 0;
        document.getElementById('invoiceTotalUnaffected').value = roundCurrency(sumExportTotalItem - exportDiscount - sumExportPrepaymentTotalItem);
        document.getElementById('invoiceTotalUnaffectedText').textContent = roundCurrency(sumExportTotalItem - exportDiscount - sumExportPrepaymentTotalItem);

        // CALC Taxed
        let sumTaxedTotalItem = 0;
        let sumTaxedPrepaymentTotalItem = 0;
        totalValueItemInput.forEach((item, index) => {
            if (affectationItemInput[index].value === '10'){
                sumTaxedTotalItem += parseFloat(totalValueItemInput[index].value || 0)
            }
        });
        let taxedDiscount = invoiceGlobalDiscountPercentage > 0 ? ( sumTaxedTotalItem * invoiceGlobalDiscountPercentage / 100) : 0;
        let invoiceTotalTaxed = roundCurrency(sumTaxedTotalItem - taxedDiscount - sumTaxedPrepaymentTotalItem);
        document.getElementById('invoiceTotalTaxed').value = invoiceTotalTaxed;
        document.getElementById('invoiceTotalTaxedText').textContent = invoiceTotalTaxed;

        // CALC Discounts
        let sumDiscountItem = 0;
        discountItemInput.forEach((item, index) => {
            sumDiscountItem += parseFloat(discountItemInput[index].value || 0);
        });
        let totalDiscount = exoneratedDiscount + unaffectedDiscount + taxedDiscount;

        document.getElementById('invoiceTotalDiscount').value = roundCurrency(sumDiscountItem + totalDiscount);
        document.getElementById('invoiceTotalDiscountText').textContent = roundCurrency(sumDiscountItem + totalDiscount);
        // $('#invoiceTotalDiscount').val(RoundCurrency(totalDiscount));
        // document.getElementById('invoiceGlobalDiscount').value = roundCurrency(totalDiscount);
        // document.getElementById('invoiceGlobalDiscountText').textContent = roundCurrency(totalDiscount);

        // CALC ISC
        let sumIscItem = 0;
        iscItemInput.forEach((item, index) => {
            sumIscItem += parseFloat(iscItemInput[index].value || 0);
        });
        document.getElementById('invoiceTotalIsc').value = roundCurrency(sumIscItem);
        document.getElementById('invoiceTotalIscText').textContent = roundCurrency(sumIscItem);

        // CALC IGV
        let sumIgvItem = 0;
        let sumIgvPrepaymentItem = 0;
        igvItemInput.forEach((item, index) => {
            sumIgvItem += parseFloat(igvItemInput[index].value || 0);
        });

        if(invoiceGlobalDiscountPercentage > 0){
            document.getElementById('invoiceTotalIgv').value = roundCurrency((sumTaxedTotalItem - taxedDiscount) * this.igvPercentage);
            document.getElementById('invoiceTotalIgvText').textContent = roundCurrency((sumTaxedTotalItem - taxedDiscount) * this.igvPercentage);
        }else{
            document.getElementById('invoiceTotalIgv').value = roundCurrency(sumIgvItem - sumIgvPrepaymentItem);
            document.getElementById('invoiceTotalIgvText').textContent = roundCurrency(sumIgvItem - sumIgvPrepaymentItem);
        }


        // CALC Free
        let sumFreeTotalItem = 0;
        let sumFreePrepaymentTotalItem = 0;
        totalValueItemInput.forEach((item, index) => {
            switch (affectationItemInput[index].value) {
                case "11":
                case "12":
                case "13":
                case "14":
                case "15":
                case "16":
                case "31":
                case "32":
                case "33":
                case "34":
                case "35":
                case "36":
                    sumFreeTotalItem += parseFloat(totalValueItemInput[index].value || 0);
                    break;
                default:
                    break;
            }
            if (affectationItemInput[index].value === '10'){
                sumFreeTotalItem += parseFloat(totalValueItemInput[index].value || 0)
            }
        });
        let invoiceTotalFree = roundCurrency(sumFreeTotalItem - sumFreePrepaymentTotalItem);
        document.getElementById('invoiceTotalFree').value = invoiceTotalFree;
        document.getElementById('invoiceTotalFreeText').textContent = invoiceTotalFree;

        // CALC PLASTIC
        let plasticBagTax = 0;
        plasticBagTaxInput.forEach((item, index) => {
            plasticBagTax += parseFloat(plasticBagTaxInput[index].value || 0);
        });
        document.getElementById('invoiceTotalPlasticBagTax').value = roundCurrency(plasticBagTax);
        document.getElementById('invoiceTotalPlasticBagTaxText').textContent = roundCurrency(plasticBagTax);

        // CALC TOTALS
        let invoiceTotals = document.querySelectorAll('.jsInvoiceTotals'),
            sumInvoiceTotals = 0;
        invoiceTotals.forEach((item, index) => {
            sumInvoiceTotals += parseFloat(invoiceTotals[index].value || 0);
        });

        let invoiceTotalCharge = document.getElementById('invoiceTotalOtherCharger').value || 0;

        let invoiceTotal = 0;
        // if (!(invoiceSaleCreditNoteCode.val() === '03' && invoiceDocumentCode.val() === '07')){
            invoiceTotal = parseFloat(sumInvoiceTotals) + parseFloat(invoiceTotalCharge) + parseFloat(plasticBagTax);
        // }
        document.getElementById('invoiceTotal').value = roundCurrency(invoiceTotal);
        document.getElementById('invoiceTotalText').textContent = roundCurrency(invoiceTotal);


        document.getElementById('invoiceTotalPrepaymentRow').classList.toggle('SnHide',!(document.getElementById('invoiceTotalPrepayment').value > 0 ));
        document.getElementById('invoiceTotalExoneratedRow').classList.toggle('SnHide',!(document.getElementById('invoiceTotalExonerated').value > 0 ));
        document.getElementById('invoiceTotalUnaffectedRow').classList.toggle('SnHide',!(document.getElementById('invoiceTotalUnaffected').value > 0 ));
        document.getElementById('invoiceTotalExportRow').classList.toggle('SnHide',!(document.getElementById('invoiceTotalExport').value > 0 ));
        document.getElementById('invoiceTotalIscRow').classList.toggle('SnHide',!(document.getElementById('invoiceTotalIsc').value > 0 ));
        document.getElementById('invoiceTotalFreeRow').classList.toggle('SnHide',!(document.getElementById('invoiceTotalFree').value > 0 ));
        document.getElementById('invoiceTotalPlasticBagTaxRow').classList.toggle('SnHide',!(document.getElementById('invoiceTotalPlasticBagTax').value > 0 ));
    },

    calcItem(uniqueId){
        let affectationIgvInput = document.getElementById(`invoiceItemAffectationCode${uniqueId}`);
        let taxIscInput = document.getElementById(`invoiceItemIscTax${uniqueId}`);
        let systemIscCodeInput = document.getElementById(`invoiceItemIscSystem${uniqueId}`);
        let quantityInput = document.getElementById(`invoiceItemQuantity${uniqueId}`);
        let discountInput = document.getElementById(`invoiceItemDiscount${uniqueId}`);

        let total,
            totalBaseIgv,
            totalBaseIsc,
            igv,
            unitPrice,
            unitValue,
            totalValue;

        let quantity = quantityInput.value || 0,
            discount = discountInput.value || 0;

        let taxIsc = taxIscInput.value || 0,
            systemIscCode = systemIscCodeInput.value || 0,
            isc = 0;

        const calcISC = (includeIsc = false) => {
            switch (systemIscCode) {
                case "01":
                    if (includeIsc) {
                        return unitValue / (1 + (taxIsc / 100));
                    } else {
                        totalBaseIsc = unitValue * quantity;
                        return totalBaseIsc * (taxIsc / 100);
                    }
                case "02":
                    if (includeIsc) {
                        return unitValue / taxIsc;
                    } else {
                        totalBaseIsc = quantity;
                        return totalBaseIsc * taxIsc;
                    }
                case "03":
                    if (includeIsc) {
                        return unitValue / (1 + (taxIsc / 100));
                    } else {
                        totalBaseIsc = unitValue * quantity;
                        return totalBaseIsc * (taxIsc / 100);
                    }
                default:
                    return 0;
            }
        };

        if (this.includeIgv) {
            unitPrice = document.getElementById(`invoiceItemUnitPrice${uniqueId}`).value || 0;

            if (affectationIgvInput.value === "10") {
                if (taxIsc > 0) {
                    unitValue = unitPrice / (1 + this.igvPercentage);
                    unitValue = calcISC(true);
                    isc = calcISC();
                    totalValue = (quantity * unitValue) - discount;
                    totalBaseIgv = totalValue + isc;
                    igv = totalBaseIgv * this.igvPercentage;
                    total = totalValue + igv;
                } else {
                    unitValue = unitPrice / (1 + this.igvPercentage);
                    totalValue = (quantity * unitValue) - discount;
                    totalBaseIgv = totalValue;
                    igv = totalBaseIgv * this.igvPercentage;
                    total = totalValue + igv;
                }
            } else {
                if (taxIsc > 0) {
                    unitValue = unitPrice;
                    unitValue = calcISC(true);
                    isc = calcISC();
                    totalValue = (quantity * unitValue) - discount;
                    totalBaseIgv = totalValue + isc;
                    igv = 0;
                    total = totalValue + igv;
                } else {
                    unitValue = unitPrice;
                    totalValue = (quantity * unitValue) - discount;
                    totalBaseIgv = totalValue;
                    igv = 0;
                    total = totalValue + igv;
                }
            }

            document.getElementById(`invoiceItemUnitValue${uniqueId}`).value = unitValue;
            document.getElementById(`invoiceItemTotalValue${uniqueId}`).value = roundCurrency(totalValue);
            document.getElementById(`invoiceItemTotalBaseIsc${uniqueId}`).value = totalBaseIsc;
            document.getElementById(`invoiceItemIsc${uniqueId}`).value = isc;
            document.getElementById(`invoiceItemTotalBaseIgv${uniqueId}`).value = totalBaseIgv;
            document.getElementById(`invoiceItemIgv${uniqueId}`).value = igv;
            document.getElementById(`invoiceItemTotal${uniqueId}`).value = roundCurrency(total);

            document.getElementById(`invoiceItemTotalValueDecimal${uniqueId}`).value = totalValue;
            document.getElementById(`invoiceItemTotalDecimal${uniqueId}`).value = total;

            document.getElementById(`invoiceItemUnitPriceText${uniqueId}`).innerHTML = roundCurrency(unitPrice);
            document.getElementById(`invoiceItemTotalValueText${uniqueId}`).innerHTML = roundCurrency(totalValue);
            document.getElementById(`invoiceItemTotalText${uniqueId}`).innerHTML = roundCurrency(total);
        } else {
            unitValue = document.getElementById(`invoiceItemUnitValue${uniqueId}`).value || 0;

            if (affectationIgvInput.value === "10") {
                if (taxIsc > 0) {
                    unitPrice = (unitValue + isc) * (1 + this.igvPercentage);
                    isc = calcISC();
                    totalValue = (quantity * unitValue) - discount;
                    totalBaseIgv = totalValue + isc;
                    igv = totalBaseIgv * this.igvPercentage;
                    total = totalValue + igv + isc;
                } else {
                    unitPrice = unitValue * (1 + this.igvPercentage);
                    totalValue = (quantity * unitValue) - discount;
                    totalBaseIgv = totalValue;
                    igv = totalValue * this.igvPercentage;
                    total = totalValue + igv;
                }
            } else {
                if (taxIsc > 0) {
                    unitPrice = unitValue + isc;
                    isc = calcISC();
                    totalValue = (quantity * unitValue) - discount;
                    totalBaseIgv = totalValue;
                    igv = 0;
                    total = totalValue + igv + isc;
                } else {
                    unitPrice = unitValue;
                    totalValue = (quantity * unitValue) - discount;
                    totalBaseIgv = totalValue;
                    igv = 0;
                    total = totalValue + igv;
                }
            }
            document.getElementById(`invoiceItemUnitPrice${uniqueId}`).value = unitPrice;
            document.getElementById(`invoiceItemTotalValue${uniqueId}`).value = roundCurrency(totalValue);
            document.getElementById(`invoiceItemTotalBaseIsc${uniqueId}`).value = totalBaseIsc;
            document.getElementById(`invoiceItemIsc${uniqueId}`).value = isc;
            document.getElementById(`invoiceItemTotalBaseIgv${uniqueId}`).value = totalBaseIgv;
            document.getElementById(`invoiceItemIgv${uniqueId}`).value = igv;
            document.getElementById(`invoiceItemTotal${uniqueId}`).value = roundCurrency(total);

            document.getElementById(`invoiceItemTotalValueDecimal${uniqueId}`).value = totalValue;
            document.getElementById(`invoiceItemTotalDecimal${uniqueId}`).value = total;

            document.getElementById(`invoiceItemUnitPriceText${uniqueId}`).innerHTML = roundCurrency(unitPrice);
            document.getElementById(`invoiceItemTotalValueText${uniqueId}`).innerHTML = roundCurrency(totalValue);
            document.getElementById(`invoiceItemTotalText${uniqueId}`).innerHTML = roundCurrency(total);
        }

        // if ($(`#plasticBagTaxEnabled${uniqueId}`).is(':checked')) {
        //     let quantity = $(`#quantity${uniqueId}`).val() || 0;
        //     let invoiceDateOfIssue = invoiceDateOfIssueInput.val(),
        //         currentYear = parseFloat(invoiceDateOfIssue.split('-')[0]);
        //     let plasticBagTaxed = currentYear > 2023 ? ICBPERYears[2023] : ICBPERYears[currentYear] * quantity;
        //     $(`#plasticBagTax${uniqueId}`).val(RoundCurrency(plasticBagTaxed));
        // } else {
        //     $(`#plasticBagTax${uniqueId}`).val(RoundCurrency(0));
        // }

        this.calcTotal();
    },

    setListeners(list, uniqueId){
        [...list].forEach(itemId => {
            let elementListener = document.getElementById(itemId);
            if (elementListener){
                elementListener.addEventListener('change',()=>this.calcItem(uniqueId));
                elementListener.addEventListener('keyup',()=>this.calcItem(uniqueId));
                elementListener.addEventListener('paste',()=>this.calcItem(uniqueId));
            }
        })
    },

    executeItem(uniqueId){
        SnLiveList({
            target: `#invoiceItemProductSearch${uniqueId}`,
            data: {
                src: async target => {
                    const response = await RequestApi.fetch('/product/search',{
                        method: 'POST',
                        body: { search: target.value }
                    });
                    return response.success ? response.result : [];
                },
                keys: {
                    id: 'product_id',
                    text: 'description',
                }
            },
            onSelect: (target, data) => {
                document.getElementById(`invoiceItemAffectationCode${uniqueId}`).value = data.affectation_code;
                document.getElementById(`invoiceItemUnitMeasure${uniqueId}`).value = data.unit_measure_code;
                document.getElementById(`invoiceItemDescription${uniqueId}`).value = data.description;
                document.getElementById(`invoiceItemUnitPrice${uniqueId}`).value = data.unit_price;
                document.getElementById(`invoiceItemUnitValue${uniqueId}`).value = data.unit_value;
                document.getElementById(`invoiceItemQuantity${uniqueId}`).value = 1;

                document.getElementById(`invoiceItemDescriptionText${uniqueId}`).textContent =  data.description;
                this.calcItem(uniqueId);
                SnCollapse.open(`invoiceProductData${uniqueId}`);
            }
        });

        this.setListeners([
            `invoiceItemQuantity${uniqueId}`,
            `invoiceItemUnitPrice${uniqueId}`,
            `invoiceItemUnitValue${uniqueId}`,
            `invoiceItemIscTax1${uniqueId}`,
            `invoiceItemDiscount${uniqueId}`,
            `invoiceItemAffectationCode${uniqueId}`,
        ], uniqueId );

        let invoiceItemDescription = document.getElementById(`invoiceItemDescription${uniqueId}`);
        invoiceItemDescription.addEventListener('change',e => {
            document.getElementById(`invoiceItemDescriptionText${uniqueId}`).textContent =  invoiceItemDescription.value;
        });
        invoiceItemDescription.addEventListener('keyup',e => {
            document.getElementById(`invoiceItemDescriptionText${uniqueId}`).textContent =  invoiceItemDescription.value;
        });
    },

    openItemModal(uniqueId){
        SnModal.open(`invoiceItemModal${uniqueId}`);
    },

    closeItemModal(uniqueId){
        SnModal.close(`invoiceItemModal${uniqueId}`);
    },

    removeItem(uniqueId){
        let elem = document.getElementById(`invoiceItem${uniqueId}`);
        if (elem){
            elem.parentNode.removeChild(elem);
            this.calcItem();
        }
    },

    addItem(){
        let uniqueId = generateUniqueId();

        let addInvoiceItem = document.getElementById('addInvoiceItem');
        if (addInvoiceItem){
            let itemTemplate = addInvoiceItem.dataset.itemtemplate;
            itemTemplate = eval('`' + itemTemplate + '`');
            if (this.invoiceItemTableBody){
                this.invoiceItemTableBody.innerHTML = this.invoiceItemTableBody.innerHTML + itemTemplate;
                this.openItemModal(`${uniqueId}`);
                this.executeItem(uniqueId);
            }
        }
    },

    submit(event){
        event.preventDefault();
        //let _setLoading = this.setLoading();

        SnModal.confirm({
            title: 'Necesitamos de tu Confirmación\n',
            content: 'Se creará el documento electrónico con los siguientes datos!',
            okText: 'Si, Adelante!',
            cancelText: 'Cancelar',
            onOk() {
                let invoiceForm = document.getElementById('invoiceForm');
                RequestApi.fetch('/invoice/createF',{
                    method: 'POST',
                    body: new FormData(invoiceForm),
                }).then(res => {
                    if (res.success){
                        SnModal.confirm({
                            title: 'Proceso Completo',
                            content: res.message,
                            okText: 'Ver Lista Documentos >',
                            cancelText: 'Realizar Otra Venta!',
                            onOk() {

                            },
                            onCancel() {
                                console.log('onCancel')
                            }
                        });
                    } else {
                        SnModal.error({ title: 'Algo salió mal', content: res.message })
                    }
                }).finally(e => {
                    _setLoading(false);
                });
            },
        });
    }
};

document.addEventListener('DOMContentLoaded',()=>{
    Invoice.init();
});