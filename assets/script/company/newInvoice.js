let NewInvoiceState = {
    currentModeForm : 'create',
    modalName : 'customerModalForm',
    loading : false,
    includeIgv: false,
    igvPercentage: 0.18,
};
let pValidator;

function getDocumentNumber(body){
    RequestApi.fetch('/invoice/getNextDocumentNumber',{
        method: 'POST',
        body: body
    }).then(res => {
        if (res.success){
            let invoiceNumber = document.getElementById('invoiceNumber');
            if (invoiceNumber){
                invoiceNumber.value = res.result.number;
            }
        } else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e =>{
    })
}

function setCurrencySymbol(){
    let invoiceCurrencyCode = document.getElementById('invoiceCurrencyCode');
    document.querySelectorAll('.jsCurrencySymbol').forEach(item => {
        item.textContent = invoiceCurrencyCode.options[invoiceCurrencyCode.options.selectedIndex].dataset.symbol;
    });
}

function calcTotal(){
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
    let invoiceGlobalDiscountPercentage = document.getElementById('invoiceGlobalDiscountPercentage').value;

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
        // if (affectationItemInput[index].value === '10'){
        //     sumFreeTotalItem += parseFloat(totalValueItemInput[index].value || 0)
        // }
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
}

function calcItem(uniqueId){
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

    if (NewInvoiceState.includeIgv) {
        unitPrice = document.getElementById(`invoiceItemUnitPrice${uniqueId}`).value || 0;

        if (affectationIgvInput.value === "10") {
            if (taxIsc > 0) {
                unitValue = unitPrice / (1 + NewInvoiceState.igvPercentage);
                unitValue = calcISC(true);
                isc = calcISC();
                totalValue = (quantity * unitValue) - discount;
                totalBaseIgv = totalValue + isc;
                igv = totalBaseIgv * NewInvoiceState.igvPercentage;
                total = totalValue + igv;
            } else {
                unitValue = unitPrice / (1 + NewInvoiceState.igvPercentage);
                totalValue = (quantity * unitValue) - discount;
                totalBaseIgv = totalValue;
                igv = totalBaseIgv * NewInvoiceState.igvPercentage;
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

        document.getElementById(`invoiceItemUnitValue${uniqueId}`).setAttribute('readonly','true');
        document.getElementById(`invoiceItemUnitPrice${uniqueId}`).removeAttribute('readonly');

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
                unitPrice = (unitValue + isc) * (1 + NewInvoiceState.igvPercentage);
                isc = calcISC();
                totalValue = (quantity * unitValue) - discount;
                totalBaseIgv = totalValue + isc;
                igv = totalBaseIgv * NewInvoiceState.igvPercentage;
                total = totalValue + igv + isc;
            } else {
                unitPrice = unitValue * (1 + NewInvoiceState.igvPercentage);
                totalValue = (quantity * unitValue) - discount;
                totalBaseIgv = totalValue;
                igv = totalValue * NewInvoiceState.igvPercentage;
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
        document.getElementById(`invoiceItemUnitPrice${uniqueId}`).setAttribute('readonly','true');
        document.getElementById(`invoiceItemUnitValue${uniqueId}`).removeAttribute('readonly');

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

    let invoicePlasticBagTaxEnabled = document.getElementById(`invoiceItemPlasticBagTaxEnabled${uniqueId}`).checked;
    let invoicePlasticQuantity = document.getElementById(`invoiceItemPlasticQuantity${uniqueId}`);
    let invoicePlasticBagTax = document.getElementById(`invoiceItemPlasticBagTax${uniqueId}`);

    if (invoicePlasticBagTaxEnabled) {
        let invoiceDateOfIssue = document.getElementById('invoiceDateOfIssue').value;
        let currentYear = parseFloat(invoiceDateOfIssue.split('-')[0]);
        let plasticBagTaxed = currentYear > 2023 ? APP.ICBPERYears[2023] : APP.ICBPERYears[currentYear] * quantityInput.value;
        invoicePlasticQuantity.value = quantityInput.value;
        invoicePlasticBagTax.value = roundCurrency(plasticBagTaxed);
    } else {
        invoicePlasticBagTax.value = 0.00;
    }

    calcTotal();
}

function calcAllItem() {
    let table = document.getElementById('invoiceItemTableBody');
    [...table.children].forEach(row => {
        let uniqueId = row.dataset.uniqueid;
        calcItem(uniqueId);
    });
}

function setListenersTotalById(list){
    [...list].forEach(element => {
        let elementListener = document.getElementById(`${element}`);
        if (elementListener){
            elementListener.addEventListener('change',()=>calcTotal());
            elementListener.addEventListener('keyup',()=>calcTotal());
            elementListener.addEventListener('paste',()=>calcTotal());
        }
    })
}

function setListenersItemById(list, uniqueId){
    [...list].forEach(element => {
        let elementListener = document.getElementById(`${element}`);
        if (elementListener){
            elementListener.addEventListener('change',()=>calcItem(uniqueId));
            elementListener.addEventListener('keyup',()=>calcItem(uniqueId));
            elementListener.addEventListener('paste',()=>calcItem(uniqueId));
        }
    })
}

function executeItem(uniqueId){
    SnLiveList({
        elem: `#invoiceItemProductSearch${uniqueId}`,
        data: (search, callback) => {
            if (search.length < 2) {
                callback('Escriba almenos 2 caracteres');
                return;
            }

            callback('Cargando...');

            RequestApi.fetch('/product/search', {
                method: 'POST',
                body: { search: search }
            }).then(res => {
                if (res.success) {
                    let data = res.result.map(item => ({ ...item, text: item.description, value: item.code }));
                    callback(data);
                } else {
                    callback(false);
                }
            });
        },
        onSelect: (target, data) => {
            document.getElementById(`invoiceItemAffectationCode${uniqueId}`).value = data.affectation_code;
            document.getElementById(`invoiceItemUnitMeasure${uniqueId}`).value = data.unit_measure_code;
            document.getElementById(`invoiceItemProductCode${uniqueId}`).value = data.product_code;
            document.getElementById(`invoiceItemDescription${uniqueId}`).value = data.description;
            document.getElementById(`invoiceItemUnitPrice${uniqueId}`).value = data.unit_price;
            document.getElementById(`invoiceItemUnitValue${uniqueId}`).value = data.unit_value;
            document.getElementById(`invoiceItemQuantity${uniqueId}`).value = 1;
            document.getElementById(`invoiceItemDescriptionText${uniqueId}`).textContent =  data.description;
            document.getElementById(`invoiceItemPlasticBagTaxEnabled${uniqueId}`).checked = data.bag_tax == '1';
            calcItem(uniqueId);
            SnCollapse.open(`invoiceProductData${uniqueId}`);
        }
    });

    setListenersItemById([
        `invoiceItemUnitPrice${uniqueId}`,
        `invoiceItemUnitValue${uniqueId}`,
        `invoiceItemIscTax1${uniqueId}`,
        `invoiceItemDiscount${uniqueId}`,
        `invoiceItemAffectationCode${uniqueId}`,
        `invoiceItemPlasticBagTaxEnabled${uniqueId}`,
    ], uniqueId );

    let invoiceItemDescription = document.getElementById(`invoiceItemDescription${uniqueId}`);
    invoiceItemDescription.addEventListener('change',e => {
        document.getElementById(`invoiceItemDescriptionText${uniqueId}`).textContent =  invoiceItemDescription.value;
    });
    invoiceItemDescription.addEventListener('keyup',e => {
        document.getElementById(`invoiceItemDescriptionText${uniqueId}`).textContent =  invoiceItemDescription.value;
    });

    // Quantity Control START
    let itemQuantityText = document.getElementById(`invoiceItemQuantityText${uniqueId}`);
    let itemQuantityRemove = document.getElementById(`invoiceItemQuantityRemove${uniqueId}`);
    itemQuantityRemove.addEventListener('click',e => {
        let quantityVal = parseFloat(itemQuantityText.value);
        if(quantityVal > 1){
            itemQuantityText.value = quantityVal-1;
            document.getElementById(`invoiceItemQuantity${uniqueId}`).value = itemQuantityText.value;
            calcItem(uniqueId);
        }
    });

    let itemQuantityAdd = document.getElementById(`invoiceItemQuantityAdd${uniqueId}`);
    itemQuantityAdd.addEventListener('click',e => {
        let quantityVal = parseFloat(itemQuantityText.value);
        itemQuantityText.value = quantityVal+1;
        document.getElementById(`invoiceItemQuantity${uniqueId}`).value = itemQuantityText.value;
        calcItem(uniqueId);
    });

    let itemQuantity = document.getElementById(`invoiceItemQuantity${uniqueId}`);
    itemQuantity.addEventListener('input',e=>{
        itemQuantityText.value = itemQuantity.value;
        calcItem(uniqueId);
    });
    itemQuantityText.addEventListener('input',e=>{
        itemQuantity.value = itemQuantityText.value;
        calcItem(uniqueId);
    });
    // Quantity Control END

    setCurrencySymbol();
}

function openItemModal(uniqueId){
    SnModal.open(`invoiceItemModal${uniqueId}`);
}

function closeItemModal(uniqueId){
    SnModal.close(`invoiceItemModal${uniqueId}`);
}

function removeItem(uniqueId){
    let elem = document.getElementById(`invoiceItem${uniqueId}`);
    if (elem){
        elem.parentNode.removeChild(elem);
        calcTotal();
    }
}

function addItem(){
    let uniqueId = generateUniqueId();
    let addInvoiceItem = document.getElementById('addInvoiceItem');
    if (addInvoiceItem){
        let itemTemplate = addInvoiceItem.dataset.itemtemplate;
        itemTemplate = eval('`' + itemTemplate + '`');
        let invoiceItemTableBody = document.getElementById('invoiceItemTableBody');
        if (invoiceItemTableBody){
            invoiceItemTableBody.insertAdjacentHTML('beforeend',itemTemplate);
            openItemModal(`${uniqueId}`);
            executeItem(uniqueId);
        }
    }
    invoiceValidator();
}

function invoiceSubmit(){
    event.preventDefault();
    if(!pValidator.validate()){
        SnModal.error({ title: 'Algo salió mal', content: 'Complete los campos requeridos' })
        return;
    }

    let invoice = {};
    invoice.customer = {};
    invoice.invoiceUpdate = {};
    invoice.guide = {};
    invoice.detraction = {};
    invoice.item = [];

    invoice.dateOfIssue = document.getElementById('invoiceDateOfIssue').value;
    invoice.dateOfDue = document.getElementById('invoiceDateOfDue').value;
    invoice.serie = document.getElementById('invoiceSerie').value;
    invoice.number = document.getElementById('invoiceNumber').value;
    invoice.observation = document.getElementById('invoiceObservation').value;
    invoice.changeType = document.getElementById('invoiceChangeType').value;
    invoice.documentCode = document.getElementById('invoiceDocumentCode').value;
    invoice.currencyCode = document.getElementById('invoiceCurrencyCode').value;
    invoice.operationCode = document.getElementById('invoiceOperationCode').value;
    invoice.totalPrepayment = document.getElementById('invoiceTotalPrepayment').value;
    invoice.totalFree = document.getElementById('invoiceTotalFree').value;
    invoice.totalExport = document.getElementById('invoiceTotalExport').value;
    invoice.totalOtherCharger = document.getElementById('invoiceTotalOtherCharger').value;
    invoice.totalDiscount = document.getElementById('invoiceTotalDiscount').value;
    invoice.totalExonerated = document.getElementById('invoiceTotalExonerated').value;
    invoice.totalUnaffected = document.getElementById('invoiceTotalUnaffected').value;
    invoice.totalTaxed = document.getElementById('invoiceTotalTaxed').value;
    invoice.totalIgv = document.getElementById('invoiceTotalIgv').value;
    invoice.totalBaseIsc = document.getElementById('invoiceTotalBaseIsc').value;
    invoice.totalIsc = document.getElementById('invoiceTotalIsc').value;
    // invoice.totalValue = document.getElementById('invosssiceTotalssssssssssss').value;
    invoice.totalPlasticBagTax = document.getElementById('invoiceTotalPlasticBagTax').value;
    invoice.total = document.getElementById('invoiceTotal').value;
    invoice.globalDiscountPercentage = document.getElementById('invoiceGlobalDiscountPercentage').value;
    invoice.purchaseOrder = document.getElementById('invoicePurchaseOrder').value;
    invoice.vehiclePlate = document.getElementById('invoiceVehiclePlate').value;
    invoice.term = document.getElementById('invoiceTerm').value;
    // invoice.percentageIgv = document.getElementById('invoiceTotalIsc').value;
    invoice.pdfFormat = document.getElementById('invoicePdfFormat').value;

    // Customer
    invoice.customer.documentNumber = document.getElementById('invoiceCustomerDocumentNumber').value;
    invoice.customer.documentCode = document.getElementById('invoiceCustomerDocumentCode').value;
    invoice.customer.socialReason = document.getElementById('invoiceCustomerSocialReason').value;
    invoice.customer.address = document.getElementById('invoiceCustomerAddress').value;
    invoice.customer.email = document.getElementById('invoiceCustomerEmail').value;
    invoice.customer.sendEmail = document.getElementById('invoiceCustomerSendEmail').checked;
    invoice.customer.telephone = '';

    // Guide
    invoice.guide.transferCode = document.getElementById('guideTransferCode').value;
    invoice.guide.transportCode = document.getElementById('guideTransportCode').value;
    invoice.guide.transferStartDate = document.getElementById('guideTransferStartDate').value;
    invoice.guide.totalGrossWeight = document.getElementById('guideTotalGrossWeight').value;
    invoice.guide.carrierDocumentCode = document.getElementById('guideCarrierDocumentCode').value;
    invoice.guide.carrierDocumentNumber = document.getElementById('guideCarrierDocumentNumber').value;
    invoice.guide.carrierDenomination = document.getElementById('guideCarrierDenomination').value;
    invoice.guide.carrierPlateNumber = document.getElementById('guideCarrierPlateNumber').value;
    invoice.guide.driverDocumentCode = document.getElementById('guideDriverDocumentCode').value;
    invoice.guide.driverDocumentNumber = document.getElementById('guideDriverDocumentNumber').value;
    invoice.guide.driverFullName = document.getElementById('guideDriverFullName').value;
    invoice.guide.locationStartingCode = document.getElementById('guideLocationStartingCode').value;
    invoice.guide.addressStartingPoint = document.getElementById('guideAddressStartingPoint').value;
    invoice.guide.locationArrivalCode = document.getElementById('guideLocationExitCode').value;
    invoice.guide.addressArrivalPoint = document.getElementById('guideAddressArrivalPoint').value;
    invoice.guideEnabled = false;

    // Detraction
    invoice.detraction.referralValue  = document.getElementById('detractionReferralValue').value;
    invoice.detraction.effectiveLoad  = document.getElementById('detractionEffectiveLoad').value;
    invoice.detraction.usefulLoad  = document.getElementById('detractionUsefulLoad').value;
    invoice.detraction.travelDetail  = document.getElementById('detractionTravelDetail').value;
    invoice.detraction.subjectCode  = document.getElementById('invoiceSubjectDetractionCode').value;
    invoice.detraction.percentage  = document.getElementById('invoiceDetractionPercentage').value;
    invoice.detraction.locationStartingCode  = document.getElementById('detractionLocationStartingCode').value;
    invoice.detraction.addressStartingPoint  = document.getElementById('detractionAddressStartingPoint').value;
    invoice.detraction.locationArrivalCode  = document.getElementById('detractionLocationArrivalCode').value;
    invoice.detraction.addressArrivalPoint  = document.getElementById('detractionAddressArrivalPoint').value;
    invoice.detraction.boatRegistration  = document.getElementById('detractionBoatRegistration').value;
    invoice.detraction.boatName  = document.getElementById('detractionBoatName').value;
    invoice.detraction.speciesSold = document.getElementById('detractionSpeciesSold').value;
    invoice.detraction.deliveryAddress  = document.getElementById('detractionDeliveryAddress').value;
    invoice.detraction.deliveryDate  = document.getElementById('detractionDeliveryDate').value;
    invoice.detraction.quantity  = document.getElementById('detractionQuantity').value;
    invoice.detractionEnabled = false;

    // Invoice Credit and debit note
    invoice.invoiceUpdate.invoiceId = document.getElementById('invoiceId').value || 0;
    invoice.invoiceUpdate.serie = document.getElementById('invoiceSerieUpdate').value || '';
    invoice.invoiceUpdate.number = document.getElementById('invoiceNumberUpdate').value || '';
    invoice.invoiceUpdate.creditDebitId = document.getElementById('invoiceCreditDebitId').value || '';

    let table = document.getElementById('invoiceItemTableBody');
    invoice.item = [...table.children].map((row,index)=>{
        let uniqueId = row.dataset.uniqueid;
        let invoiceItem = {};

        invoiceItem.productCode = document.getElementById(`invoiceItemProductCode${uniqueId}`).value;
        invoiceItem.unitMeasure = document.getElementById(`invoiceItemUnitMeasure${uniqueId}`).value;
        invoiceItem.description = document.getElementById(`invoiceItemDescription${uniqueId}`).value;
        invoiceItem.quantity = document.getElementById(`invoiceItemQuantity${uniqueId}`).value;
        invoiceItem.unitValue = document.getElementById(`invoiceItemUnitValue${uniqueId}`).value;
        invoiceItem.unitPrice = document.getElementById(`invoiceItemUnitPrice${uniqueId}`).value;
        invoiceItem.discount = document.getElementById(`invoiceItemDiscount${uniqueId}`).value;
        invoiceItem.affectationCode = document.getElementById(`invoiceItemAffectationCode${uniqueId}`).value;
        invoiceItem.totalBaseIgv = document.getElementById(`invoiceItemTotalBaseIgv${uniqueId}`).value;
        invoiceItem.igv = document.getElementById(`invoiceItemIgv${uniqueId}`).value;
        invoiceItem.iscSystem = document.getElementById(`invoiceItemIscSystem${uniqueId}`).value;
        invoiceItem.totalBaseIsc = document.getElementById(`invoiceItemTotalBaseIsc${uniqueId}`).value;
        invoiceItem.iscTax = document.getElementById(`invoiceItemIscTax${uniqueId}`).value;
        invoiceItem.isc = document.getElementById(`invoiceItemIsc${uniqueId}`).value;
        invoiceItem.totalValue = document.getElementById(`invoiceItemTotalValue${uniqueId}`).value;
        invoiceItem.total = document.getElementById(`invoiceItemTotal${uniqueId}`).value;
        invoiceItem.quantityPlasticBag = document.getElementById(`invoiceItemPlasticQuantity${uniqueId}`).value;
        invoiceItem.plasticBagTax = document.getElementById(`invoiceItemPlasticBagTax${uniqueId}`).value;

        return invoiceItem;
    });

    SnModal.confirm({
        title: 'Necesitamos de tu Confirmación\n',
        content: 'Se creará el documento electrónico con los siguientes datos!',
        okText: 'Si, Adelante!',
        cancelText: 'Cancelar',
        onOk() {
            SnFreeze.freeze({selector: '#invoiceFormTemplateContainer'});
            RequestApi.fetch('/invoice/createInvoice',{
                method: 'POST',
                body: invoice,
            }).then(res => {
                if (res.success){
                    let messagePrint = '';
                    let messageType = 'info';
                    if(res.sunat.success){
                        messageType = 'success';
                        messagePrint = res.sunat.message;

                        let InvoiceConfirmWhatsapp = document.getElementById('InvoiceConfirmWhatsapp');
                        let InvoiceConfirmPrint = document.getElementById('InvoiceConfirmPrint');
                        let InvoiceConfirmEmail = document.getElementById('InvoiceConfirmEmail');

                        InvoiceConfirmWhatsapp.setAttribute('href','https://api.whatsapp.com/send?text=' + encodeURI(res.sunat.message + ' \n' + (location.origin + APP.path + res.sunat.result.pdf_url)));
                        InvoiceConfirmPrint.setAttribute('onclick',`DocumentPrinter.showModal('${res.sunat.result.pdf_url}', false)`);
                        InvoiceConfirmEmail.setAttribute('onclick',`invoiceSendEmailOpenModal('${res.result.invoiceId}', '')`);
                    } else {
                        messageType = 'warning';
                        messagePrint = res.sunat.message;
                    }

                    let invoiceConfirmAlert = document.getElementById('invoiceConfirmAlert');
                    let InvoiceConfirmTitle = document.getElementById('InvoiceConfirmTitle');
                    invoiceConfirmAlert.classList.add(messageType);
                    invoiceConfirmAlert.innerHTML = messagePrint;
                    InvoiceConfirmTitle.innerHTML = res.message;

                    SnModal.open('invoiceConfirmModal');
                } else {
                    SnModal.error({ title: 'Algo salió mal', content: res.message })
                }
            }).finally(e => {
                SnFreeze.unFreeze('#invoiceFormTemplateContainer');
            });
        },
    });
}

function invoiceSendEmailOpenModal(invoiceId, customerEmail){
    SnModal.open('invoiceModalSendEmail');
    let sendInvoiceId = document.getElementById('sendInvoiceId');
    let sendInvoiceCustomerEmail = document.getElementById('sendInvoiceCustomerEmail');
    if (sendInvoiceId && sendInvoiceCustomerEmail){
        sendInvoiceId.value = invoiceId;
        sendInvoiceCustomerEmail.value = customerEmail;
    }
}

function newInvoice(){
    let invoiceForm = document.getElementById('invoiceForm');
    if (invoiceForm){
        invoiceForm.reset();
    }
    let invoiceItemTableBody = document.getElementById('invoiceItemTableBody');
    if (invoiceItemTableBody){
        invoiceItemTableBody.innerHTML = '';
    }

    let invoiceDocumentInput = document.getElementById('invoiceDocumentCode');
    let invoiceSerie = document.getElementById('invoiceSerie');
    if (invoiceSerie && invoiceDocumentInput){
        getDocumentNumber({
            documentCode: invoiceDocumentInput.value,
            serie: invoiceSerie.value,
        });
    }
    calcTotal();
    invoiceValidator();

    SnModal.close('invoiceConfirmModal');
}

function invoiceValidator(){
    if(pValidator){
        pValidator.destroy();
    }
    pValidator = new Pristine(document.getElementById('invoiceForm'));
}

document.addEventListener('DOMContentLoaded',()=>{
    invoiceValidator();
    let invoiceCurrencyInput = document.getElementById('invoiceCurrencyCode');
    let invoiceDocumentInput = document.getElementById('invoiceDocumentCode');

    // Set Currency Symbol
    setCurrencySymbol();
    invoiceCurrencyInput.addEventListener('change',e =>{
        setCurrencySymbol();
    });

    setListenersTotalById([
        'invoiceTotalDiscountPercentage',
        'invoiceTotalOtherCharger',
    ]);

    let includeIgv = document.getElementById('includeIgv');
    includeIgv.addEventListener('change',e =>{
        NewInvoiceState.includeIgv = includeIgv.checked;
        calcAllItem();
    });

    let invoiceSerie = document.getElementById('invoiceSerie');
    if (invoiceSerie && invoiceDocumentInput){
        getDocumentNumber({
            documentCode: invoiceDocumentInput.value,
            serie: invoiceSerie.value,
        });
        invoiceSerie.addEventListener('change',()=>{
            getDocumentNumber({
                documentCode: invoiceDocumentInput.value,
                serie: invoiceSerie.value,
            });
        });
        invoiceDocumentInput.addEventListener('change',()=>{
            getDocumentNumber({
                documentCode: invoiceDocumentInput.value,
                serie: invoiceSerie.value,
            });
        });
    }

    SnLiveList({
        elem: '#invoiceCustomerDocumentNumber',
        data: (search, callback) => {
            if (search.length < 7) {
                callback('Escriba almenos 7 caracteres');
                return;
            }

            callback('Cargando...');

            RequestApi.fetch('/customer/queryInnerDataAndPeru', {
                method: 'POST',
                body: { documentNumber: search }
            }).then(res => {
                if (res.success) {
                    let data = res.result.map(item => ({ ...item, text: item.socialReason, value: item.documentNumber }));
                    callback(data);
                } else {
                    callback(res.message);
                }
            });
        },
        onSelect: (target, data) => {
            document.getElementById('invoiceCustomerDocumentCode').value = data.identityDocumentCode;
            document.getElementById('invoiceCustomerSocialReason').value = data.socialReason;
            document.getElementById('invoiceCustomerAddress').value = data.fiscalAddress;
            document.getElementById('invoiceCustomerEmail').value = data.email;
        }
    });
});
