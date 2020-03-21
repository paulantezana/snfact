<?php

require_once MODEL_PATH . '/Invoice.php';
require_once MODEL_PATH . '/InvoiceItem.php';
require_once MODEL_PATH . '/Business.php';
require_once MODEL_PATH . '/Catalogue/CatSystemIscTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatPerceptionTypeCode.php';

require_once __DIR__ . '/DocumentManager/DocumentManager.php';
require_once __DIR__ . '/BillingManager/BillingManager.php';
require_once __DIR__ . '/SendManager/EmailManager.php';

class BuildInvoice
{
    protected $connection;
    private $invoiceModel;
    private $invoiceItemModel;
    private $businessModel;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->invoiceModel = new Invoice($this->connection);
        $this->invoiceItemModel = new InvoiceItem($this->connection);
        $this->businessModel = new Business($this->connection);
    }

    public function GeneratePdf(array $documentData ) {
        $business = $documentData['business'];
        $invoiceData =  $documentData['invoice'];
        $invoiceDetail =  $documentData['invoiceDetail'];

        $business = array_merge($business,[
            'address' => 'AV. HUASCAR NRO. 224 DPTO. 303',
            'region' => 'CUSCO',
            'province' => 'CUSCO',
            'district' => 'WANCHAQ',
        ]);

        $invoice['headerContact'] = 'Teléfono: 084601425 | Celular: 979706609 | www.skynetcusco.com | info@skynetcusco.com';
        $invoice['documentType'] = $invoiceData['document_type_code_description'];
        $invoice['documentCode'] = $invoiceData['document_code'];
        $invoice['serie'] = $invoiceData['serie'];
        $invoice['correlative'] = $invoiceData['number'];
        $invoice['vehiclePlate'] = $invoiceData['vehicle_plate'];
        $invoice['term'] = $invoiceData['term'];
        $invoice['purchaseOrder'] = $invoiceData['purchase_order'];
        $invoice['observation'] = $invoiceData['observation'];
        $invoice['logo'] = $business['logo'];

        $invoice['businessRuc'] = $business['ruc'];
        $invoice['businessSocialReason'] = $business['social_reason'];
        $invoice['businessCommercialReason'] = $business['social_reason'];
        $invoice['businessAddress'] = $business['address'];
        $invoice['businessLocation'] = $business['district'] . ' ' . $business['province'] . ' ' . $business['region'];

        $invoice['customerDocumentNumber'] = $invoiceData['customer_document_number'];
        $invoice['customerDocumentCode'] = $invoiceData['customer_document_number'];
        $invoice['customerSocialReason'] = $invoiceData['customer_social_reason'];
        $invoice['customerFiscalAddress'] = $invoiceData['customer_fiscal_address'];
        $invoice['digestValue'] = $invoiceData['digestValue'];
        $invoice['dateOfIssue'] = $invoiceData['date_of_issue'];
        $invoice['dateOfDue'] = $invoiceData['date_of_due'];
        $invoice['currencySymbol'] = $invoiceData['currency_type_code_symbol'];
        $invoice['currencyDescription'] = $invoiceData['currency_type_code_description'];
        $invoice['totalDiscount'] = $invoiceData['total_discount'];
        $invoice['totalPrepayment'] = $invoiceData['total_prepayment'];
        $invoice['totalExonerated'] = $invoiceData['total_exonerated'];
        $invoice['totalUnaffected'] = $invoiceData['total_unaffected'];
        $invoice['totalTaxed'] = $invoiceData['total_taxed'];
        $invoice['totalIsc'] = $invoiceData['total_isc'];
        $invoice['totalIgv'] = $invoiceData['total_igv'];
        $invoice['totalFree'] = $invoiceData['total_free'];
        $invoice['totalCharge'] = $invoiceData['total_charge'];
        $invoice['totalPlasticBagTax'] = $invoiceData['total_plastic_bag_tax'];
        $invoice['total'] = $invoiceData['total'];
        $invoice['totalInWord'] = NumberToLetter::StringFormat((int)$invoiceData['total']) . ' ' .$invoiceData['currency_type_code_description'];
        $invoice['percentageIgv'] = $invoiceData['percentage_igv'];

        $invoice['perceptionCode'] = $invoiceData['perception_code'];
        $invoice['perceptionPercentage'] = $invoiceData['perception_percentage'];
        $invoice['perceptionAmount'] = $invoiceData['perception_amount'];
        $invoice['perceptionBase'] = $invoiceData['perception_base'];
        $invoice['totalWithPerception'] = $invoiceData['total_with_perception'];

        $invoice['guide'] = json_decode($invoiceData['guide'],true);
        $invoice['itemList'] = [];

        // Detraction
        $invoice['detractionCode'] = '';
        $invoice['detractionPercentage'] = '';
        $invoice['detractionAmount'] = '';

        $invoice['detractionLocationStartPoint'] = '';
        $invoice['detractionLocationEndPoint'] = '';
        $invoice['detractionReferralValue'] = '';
        $invoice['detractionEffectiveLoad'] = '';
        $invoice['detractionUsefulLoad'] = '';
        $invoice['detractionTravelDetail'] = '';

        $invoice['detractionBoatRegistration'] = '';
        $invoice['detractionBoatName'] = '';
        $invoice['detractionSpeciesKind'] = '';
        $invoice['detractionDeliveryAddress'] = '';
        $invoice['detractionQuantity'] = '';
        $invoice['detractionDeliveryDate'] = '';

        // Referral guide
        $invoice['whitGuide'] = $invoiceData['whit_guide'];
        $invoice['transferCode'] = $invoiceData['transfer_code'];
        $invoice['transportCode'] = $invoiceData['transport_code'];
        $invoice['totalGrossWeight'] = $invoiceData['total_gross_weight'];
        $invoice['carrierDenomination'] = "{$invoiceData['carrier_document_code']} - {$invoiceData['carrier_document_number']} - {$invoiceData['carrier_denomination']}";
        $invoice['carrierPlateNumber'] = $invoiceData['carrier_plate_number'];
        $invoice['driverDenomination'] = "{$invoiceData['driver_document_code']} - {$invoiceData['driver_document_number']} - {$invoiceData['driver_full_name']}";
        $invoice['locationEndPoint'] = "{$invoiceData['location_arrival_code']} - {$invoiceData['address_arrival_point']}";
        $invoice['locationStartPoint'] = "{$invoiceData['location_starting_code']} - {$invoiceData['address_starting_point']}";

        foreach ($invoiceDetail as $row){
            $item['discount'] = $row['discount'];
            $item['quantity'] = $row['quantity'];
            $item['unitMeasureCode'] = $row['unit_measure'];
            $item['productCode'] = $row['product_code'];
            $item['productDescription'] = $row['description'];
            $item['unitValue'] = $row['unit_value'];
            $item['unitPrice'] = $row['unit_price'];
            $item['total'] = $row['total'];
            array_push($invoice['itemList'], $item);
        }

        $documentManager = new DocumentManager();
        $resPdf = $documentManager->Invoice($invoice,$invoiceData['pdf_format'] !== '' ? $invoiceData['pdf_format'] : 'A4',$business['environment']);

        if ($resPdf->success){
            $this->invoiceModel->updateInvoiceSunatByInvoiceId($invoiceData['invoice_id'],[
                'pdf_url'=> $resPdf->pdfPath
            ]);
        }
        return $resPdf;
    }

    public function GenerateXML(array $documentData, $userReferId) {
        $res = new Result();
        $res->digestValue = '';
        $res->xmlPath = '';
        $res->cdrPath = '';

        $business = $documentData['business'];
        $invoiceData =  $documentData['invoice'];
        $invoiceDetail =  $documentData['invoiceDetail'];

        $invoiceDetail = array_map(function ($item) use ($invoiceData, $business){
            $discountBase = $item['total_value'] + $item['discount'];
            $discountPercentage = 0;
            if ($item['discount'] > 0){
                $discountPercentage = ($item['discount'] * 100) / $discountBase;
                $discountPercentage = $discountPercentage / 100;
            }

            // Percentage IGV
            $ac = $item['affectation_code'];
            if ($ac == '20' || $ac == '30' || $ac == '31' || $ac == '32' || $ac == '33' || $ac == '34' || $ac == '35' || $ac == '36'){
                $percentageIgv = 0;
            } else {
                $percentageIgv = $invoiceData['percentage_igv'];
            }

            // Total base igv
            if ($ac == '11' || $ac == '12' || $ac == '13' || $ac == '14' || $ac == '15' || $ac == '16'){
                $percentageIgvDecimal = $invoiceData['percentage_igv'] / 100;
                $item['total_base_igv'] = $item['total_value'] / (1 + $percentageIgvDecimal);
                $item['igv'] = $item['total_base_igv'] * $percentageIgvDecimal;
                $item['total'] = $item['igv'] + $item['total_value'];
            }

            $item['total_taxed'] = $item['igv'] + $item['isc'] + $item['other_taxed'];

            // Unit value
            if ($ac == '11' || $ac == '12' || $ac == '13' || $ac == '14' || $ac == '15' || $ac == '16' ||
                $ac == '31' || $ac == '32' || $ac == '33' || $ac == '34' || $ac == '35' || $ac == '36'){
                $item['unit_value'] = 0;
            }

            return array_merge($item,
                [
                    'percentage_igv' => $percentageIgv,
                    'discount_percentage' => $discountPercentage,
                    'discount_base' => $discountBase,
                    'total_taxed' => $item['total_taxed'],
                ]
            );
        }, $invoiceDetail);

        // recalculate total
        $invoiceData['total_discount_base'] = 0;
        $invoiceData['total_discount_percentage'] = 0;
        if($invoiceData['total_discount'] > 0){
//            $invoiceData['total_discount_base'] = $invoiceData['total_value'] + $invoiceData['total_discount'];
            $invoiceData['total_discount_base'] = $invoiceData['total_value'];
            $invoiceData['total_discount_percentage'] = ($invoiceData['total_discount'] * 100 ) / ($invoiceData['total_value'] + $invoiceData['total_discount']);
        }
        $invoiceData['guide'] = json_decode($invoiceData['guide'],true);
        $invoiceData['legend'] = json_decode($invoiceData['legend'],true);
        $invoiceData['related'] = json_decode($invoiceData['related'],true);

        // Prepare Invoice
        $invoice['serie'] = $invoiceData['serie'];
        $invoice['number'] = $invoiceData['number'];
        $invoice['issueDate'] = $invoiceData['date_of_issue'];
        $invoice['issueTime'] = $invoiceData['time_of_issue'];
        $invoice['invoiceTypeCode'] = $invoiceData['document_code'];
        $invoice['amounInWord'] = NumberToLetter::StringFormat((int)$invoiceData['total']) . ' ' .$invoiceData['currency_type_code_description'];
        $invoice['supplierRuc'] = $business['ruc'];
        $invoice['defaultUrl'] = 'WWW.SNFACT.COM';
        $invoice['supplierName'] = htmlspecialchars($business['social_reason']);
        $invoice['supplierDocumentType'] = '6';					// TIPO DE DOCUMENTO EMISOR
        $invoice['customerDocumentType'] = $invoiceData['customer_identity_document_code'];					// TIPO DE DOCUMENTO CLIENTE
        $invoice['customerDocument'] = $invoiceData['customer_document_number'];			// DOCUMENTO DEL CLIENTE
        $invoice['customerName'] = htmlspecialchars($invoiceData['customer_social_reason']);
        $invoice['totalTaxAmount'] = RoundCurrency($invoiceData['total_tax']);					// TOTAL DE IMPUESTOS
        $invoice['totalBaseAmount'] = RoundCurrency($invoiceData['total_value']);					// VALOR TOTAL DE LA VENTA
        $invoice['totalSaleAmount'] = RoundCurrency($invoiceData['total']);					// VALOR TOTAL DE LA VENTA + IMPUESTOS
        $invoice['totalDiscountAmount'] = RoundCurrency($invoiceData['total_discount']);				// VALOR TOTAL DE LOS DESCUENTOS
        $invoice['totalExtraChargeAmount'] = RoundCurrency($invoiceData['total_charge']);			// VALOR TOTAL DE LOS CARGOS EXTRA
        $invoice['totalPrepaidAmount'] = RoundCurrency($invoiceData['total_prepayment']);				// VALOR TOTAL DE LOS MONTOS PAGADOS COMO ADELANTO
        $invoice['totalPayableAmount'] = RoundCurrency($invoiceData['total']);				// MONTO TOTAL QUE SE COBRA

        $invoice['totalIgvAmount'] = RoundCurrency($invoiceData['total_igv']);					// VALOR TOTAL DEL IGV
        $invoice['totalIgvTaxableAmount'] = RoundCurrency($invoiceData['total_taxed']);			// VALOR TOTAL DE LA VENTA GRABADA
        $invoice['totalIscAmount'] = RoundCurrency($invoiceData['total_isc']);				// VALOR TOTAL DEL ISC
        $invoice['totalIscTaxableAmount'] = '0.00';				// VALOR TOTAL AL CUAL SE APLICA EL ISC.
        $invoice['totalFreeAmount'] = RoundCurrency($invoiceData['total_free']);				// VALOR TOTAL INAFECTO A INPUESTOS
        $invoice['totalExoneratedAmount'] = RoundCurrency($invoiceData['total_exonerated']);				// VALOR TOTAL INAFECTO A INPUESTOS
        $invoice['totalInafectedAmount'] = RoundCurrency($invoiceData['total_unaffected']);				// VALOR TOTAL INAFECTO A INPUESTOS
        $invoice['totalOtherTaxAmount'] = RoundCurrency($invoiceData['total_other_taxed']);				// VALOR TOTAL DE otros impuestos
        $invoice['totalOtherTaxableAmount'] = RoundCurrency($invoiceData['total_base_other_taxed']);				// VALOR TOTAL AL CUAL SE APLICA otros impuestos.

        $invoice['totalBagTaxAmount'] = RoundCurrency($invoiceData['total_plastic_bag_tax']);	// total			    // VALOR TOTAL del impuesto a las bolsas
        $invoice['bagTaxAmountPerUnit'] = RoundCurrency($invoiceData['percentage_plastic_bag_tax']); // percentage				// VALOR TOTAL del impuesto a las bolsas
        $invoice['operationTypeCode'] = $invoiceData['operation_code'];				// Codigo del tipo de operacion (Venta interna : 0101 - Exportacion : 0102  Catalogo 25)
//        $invoice['operationTypeCode'] = '0401';				// Codigo del tipo de operacion (Venta interna : 0101 - Exportacion : 0102  Catalogo 25)

        $invoice['globalDiscountPercent'] = RoundCurrency($invoiceData['total_discount_percentage'] / 100,5);				// DESCUENTO EN PORCENTAJE
        $invoice['bagTaxAmount'] = '1.00';							// CODIGO DE LA MONEDA
        $invoice['globalDiscountAmount'] = RoundCurrency($invoiceData['total_discount']);				// VALOR TOTAL DE LOS DESCUENTOS                            // ---------------- Cambiar la variable  -- total_discount_base
        $invoice['codigoMoneda'] = $invoiceData['currency_code'];							// CODIGO DE LA MONEDA
        $invoice['amazoniaGoods'] = 0;							// BIENES EN LA AMAZONIA
        $invoice['amazoniaService'] = 0;						// SERVICIOS EN LA AMAZONIA
        $invoice['orderReference'] = $invoiceData['purchase_order'];							// Referencia de la orden de compra o servicio

        // CREDIT AND DEBIT
        $invoice['invoiceReferenceList'] = array();
        if($invoiceData['document_code'] == '07' || $invoiceData['document_code'] == '08'){
          if ($invoiceData['document_code'] === '07'){
            $invoice['creditNoteTypeCode'] = $invoiceData['credit_debit_reason_code'];
            $invoice['creditNoteTypeDescription'] = $invoiceData['credit_debit_reason_description'];
          }
          if ($invoiceData['document_code'] === '08'){
            $invoice['debitNoteTypeCode'] = $invoiceData['credit_debit_reason_code'];
            $invoice['debitNoteTypeDescription'] = htmlspecialchars($invoiceData['credit_debit_reason_description']);
          }

          $referencedInvoice = array();
          $referencedInvoice['billingReferenceSerie'] = $invoiceData['update_serie'];
          $referencedInvoice['billingReferenceNumber'] = $invoiceData['update_number'];
          $referencedInvoice['billingReferenceTypeCode'] = $invoiceData['update_document_code'];
          array_push($invoice['invoiceReferenceList'], $referencedInvoice);
        }

        //REFERENCIA A GUIAS DE REMISION
        $invoice['referenceDocumentList'] = array();
//        foreach ($invoiceData['guide'] as $row){
//            $referencedDocument = array();
//            $referencedDocument['referencedDocument'] = $row['serie'];							// SERIE Y NUMERO DEL DOCUMENTO
//            $referencedDocument['referencedDocumentTypeCode'] = $row['document_code'];						// TIPO DOCUMENTO CAT 01
//            array_push($invoice['referenceDocumentList'], $referencedDocument);
//        }
        $invoice['itemList'] = array();

        //PERCEPCION
        $invoice['totalAmountWithPerception'] = $invoiceData['total_with_perception'];						// MONTO TOTAL DE LA VENTA MAS LA PERCEPCION
        $invoice['perceptionTypeCode'] = $invoiceData['perception_code'];								// CODIGO DEL TIPO DE PERCEPCION CAT 53
        $invoice['perceptionPercent'] = $invoiceData['perception_percentage'];								// MONTO TOTAL DE LA VENTA MAS LA PERCEPCION
        $invoice['perceptionAmount'] = $invoiceData['perception_amount'];									// MONTO DE LA PERCEPCION
        $invoice['perceptionTaxableAmount'] = $invoiceData['perception_base'];						// MONTO SOBRE EL CUAL SE CALCULA LA PERCEPCION

        //DETRACCION
//        $invoice['detractionAccount'] = $invoiceData['detraction_bill'];						        // CODIGO DEL TIPO DE PERCEPCION CAT 53
        $invoice['detractionAccount'] = $business['detraction_bank_account'];						        // CODIGO DEL TIPO DE PERCEPCION CAT 53
        $invoice['detractionTypeCode'] = '';								// CODIGO DEL TIPO DE PERCEPCION CAT 53
        $invoice['detractionPercent'] = '';								// MONTO TOTAL DE LA VENTA MAS LA PERCEPCION
        $invoice['detractionAmount'] = '';									// MONTO DE LA PERCEPCION

        //DETRACCION - HIDROBIOLOGICO
        $invoice['boatLicensePlate'] = '';						// placa de la embarcacion
        $invoice['boatName'] = '';						// placa de la embarcacion

        //DETRACCION - TRANSPORTE DE CARGA
        $invoice['despatchDetail'] = '';
        $invoice['deliveryAdressCode'] = '';				// UBIGEO DE DESTINO
        $invoice['deliveryAdress'] = '';					// DIRECCION DESTINO
        $invoice['originAdressCode'] = '';					// UBIGEO DE ORIGEN
        $invoice['originAdress'] = '';						// DIRECCION ORIGEN

        //REGULACION PAGOS POR ADELANTADO				REGLA:		totalPrepaidAmount = sumatoria items prepaidPaymentList
        $invoice['prepaidPaymentList'] = array();
        foreach ($invoiceDetail as $row){
//            if ($row['prepayment_regulation']){
//                $item = array();
//                $item['documentSerieNumber'] = $row['prepayment_serie'] . '-' . $row['prepayment_correlative'];				// serie y numero del documento del anticipo
//                $item['documentTypeCode'] = '02';						// codigo del tipo de documento CAT 12 (02 o 03)
//                $item['prepaidAmount'] = $row['total_value'];						// monto del anticipo
//                array_push($invoice['prepaidPaymentList'], $item);
//            }
        }

        //FACTURA - GUIA 								REGLA: referalGuideIncluded = 1
        $invoice['referalGuideIncluded'] = $invoiceData['transfer_code'] == '' ? 0 : 1;
        $invoice['transferReasonCode'] = $invoiceData['transfer_code'];						// CODIGO DEL Motivo de Traslado CAT 20
        $invoice['grossWeightMeasure'] = 'KGM';						// UNIDAD DE MEDIDA DEL PESO TOTAL DEL ENVIO CAT 03(KGM = Kilogramo)
        $invoice['grossWeight'] = $invoiceData['total_gross_weight'];							// PESO
        $invoice['transferMethodCode'] = $invoiceData['transport_code'];						// CODIGO DEL METODO DE TRANSPORTE CAT 18
        $invoice['carrierDocumentType'] = $invoiceData['carrier_document_code'];						// TIPO DE DOCUMENTO DEL TRANSPORTISTA
        $invoice['carrierRuc'] = $invoiceData['carrier_document_number'];						// NUMERO DE DOCUMENTO DEL TRANSPORTISTA
        $invoice['carrierName'] = htmlspecialchars($invoiceData['carrier_denomination']);					// NOMBRE DEL TRANSPORTISTA
        $invoice['licensePlate'] = $invoiceData['carrier_plate_number'];						// PLACA DEL VEHICULO
        $invoice['driverDocumentType'] = $invoiceData['driver_document_code'];						// TIPO DE DOCUMENTO DEL CONDUCTOR
        $invoice['driverDocument'] = $invoiceData['driver_document_number'];						// NUMERO DE DOCUMENTO DEL CONDUCTOR

        //FACTURA - EMISOR ITINERANTE
        $invoice['itinerantSuplier'] = $invoiceData['itinerant_enable'];							// 1/0 VENDEODR ITINERANTO = 1
        $invoice['itinerantAddressCode'] = $invoiceData['itinerant_location'];							// VENTA ITINERANTE - UBIGEO
        $invoice['itinerantAddress'] = $invoiceData['itinerant_address'];							// VENTA ITINERANTE - DIRECCION
        $invoice['itinerantUrbanization'] = $invoiceData['itinerant_urbanization'];							//VENTA ITINERANTE - URBANIZACION
        $invoice['itinerantProvince'] = $invoiceData['itinerant_province'];							// VENTA ITINERANTE - PROVINCIA
        $invoice['itinerantRegion'] = $invoiceData['itinerant_department'];							// VENTA ITINERANTE - REGION O DEPARTAMENTO
        $invoice['itinerantDistrict'] = $invoiceData['itinerant_district'];							// VENTA ITINERANTE - DISTRITO

//        if (!$invoiceData['whit_detraction']){
//            $invoice['deliveryAdressCode'] = $invoiceData['location_arrival_code'];					// UBIGEO DE DESTINO
//            $invoice['deliveryAdress'] = $invoiceData['address_arrival_point'];						// DIRECCION DESTINO
//            $invoice['originAdressCode'] = $invoiceData['location_starting_code'];						// UBIGEO DE ORIGEN
//            $invoice['originAdress'] = $invoiceData['address_starting_point'];							// DIRECCION ORIGEN
//        }

        foreach ($invoiceDetail as $row){
            if (true){
                $item = array();
                $item['itemUnitCode'] = $row['unit_measure'];			            // CODIGO UNIDAD
                $item['itemCuantity'] = $row['quantity'];							            // CANTIDAD
                $item['itemFinalBaseAmount'] = RoundCurrency($row['total_value']);					            // VALOR TOTAL DEL ITEM (CANTIDAD POR PRECIO UNITARIO menos descuentos)

                $item['itemTotalBaseAmount'] = RoundCurrency($row['discount_base']);		// base			    // VALOR TOTAL DEL ITEM (CANTIDAD POR PRECIO UNITARIO)
                $item['itemDiscountAmount'] = RoundCurrency($row['discount']);					                // Monto del descuento
                $item['itemDiscountPercent'] = RoundCurrency($row['discount_percentage']);                     // Porcentaje del descuento

                $item['singleItemPrice'] = RoundCurrency($row['unit_price']);					                // VALOR
                $item['onerous'] = $row['affectation_onerous'];								                    // 1 = OPERACION ONEROSA | 2 = OPERACION NO ONEROSA
                $item['itemTotalTaxAmount'] = RoundCurrency($row['total_taxed']);					            // VALOR TOTAL DE IMPUESTOS DEL ITEM
                $item['itemIgvTaxableAmount'] = RoundCurrency($row['total_base_igv']);					        // VALOR en base AL CUAL SE CALCULA EL IGV
                $item['itemTotalIgvAmount'] = RoundCurrency($row['igv']);					                    // VALOR TOTAL DE IGV CORRESPONDIENTE AL ITEM
                $item['itemTaxPercent'] = RoundCurrency($row['percentage_igv']);						        // PORCENTAJE EN BASE AL CUAL SE ESTA CALCULANDO EL IMPUESTO
                $item['itemIgvTaxCode'] = $row['affectation_code'];							                    // CODIGO DE TIPO DE IGV
                $item['itemTaxCode'] = $row['affectation_tribute_code'];					                    // CODIGO DE IMPUESTO
                $item['itemTaxName'] = $row['affectation_name'];							                    // NOMBRE DE IMPUESTO
                $item['itemTaxNamecode'] = $row['affectation_international_code'];			                    // CODIGO DEL NOMBRE DE IMPUESTO
                $item['itemDescription'] = htmlspecialchars($row['description']);                                   // DESCRIPCION DEL ITEM
                $item['ItemClassificationCode'] = $row['product_code'];			                                // CODGIO DE TIPO DE PRODUCTO
                $item['singleItemBasePrice'] = RoundCurrency($row['unit_value']);				                // VALOR BASE DEL ITEM (SIN IMPUESTOS)
                $item['itemBagCuantity'] = (int)$row['quantity_plastic_bag'];							        // CANTIDAD DE BOLSAS PARA EL ITEM
                //            $item['bagTaxAmount'] = $row['plastic_bag_tax'];							        // CANTIDAD DE BOLSAS PARA EL ITEM

                $item['itemIscAmount'] = RoundCurrency($row['isc']);							                // CANTIDAD DE BOLSAS PARA EL ITEM
                $item['itemIscTaxableAmount'] = RoundCurrency($row['total_base_isc']);						    // CANTIDAD DE BOLSAS PARA EL ITEM
                $item['itemIscTaxPercent'] = RoundCurrency($row['tax_isc']);							        // CANTIDAD DE BOLSAS PARA EL ITEM
                $item['itemIscSystemType'] = $row['system_isc_code'];							// CATALOGO 08 sistema de calculo del Isc

//                if ($invoiceData['whit_detraction']){
//                    //DETRACCION - HIDROBIOLOGICO
//                    $item['transportReferencialAmount'] = $invoiceData['detraction_referral_value'];						// Valor referencial del servicio de transporte
//                    $item['effectiveLoadReferencialAmount'] = $invoiceData['detraction_effective_load'];					// Valor referencial sobre la carga efectiva
//                    $item['payLoadReferencialAmount'] = $invoiceData['detraction_useful_load'];						// Valor referencial sobre la carga útil nominal
//
//                    //DETRACCION - TRANSPORTE DE CARGA
//                    $item['speciesKind'] = $invoiceData['detraction_species_kind'];								// tipo de ESPECIE
//                    $item['deliveryAddress'] = $invoiceData['detraction_delivery_address'];			// DIRECCION ENTREGA
//                    $item['deliveryDate'] = $invoiceData['detraction_delivery_date'];						// FECHA ENTREGA
//                    $item['quantity'] = $invoiceData['detraction_quantity'];
//                } else {
                    //DETRACCION - HIDROBIOLOGICO
                    $item['transportReferencialAmount'] = 0;						// Valor referencial del servicio de transporte
                    $item['effectiveLoadReferencialAmount'] = 0;					// Valor referencial sobre la carga efectiva
                    $item['payLoadReferencialAmount'] = 0;						// Valor referencial sobre la carga útil nominal

                    //DETRACCION - TRANSPORTE DE CARGA
                    $item['speciesKind'] = '';								// tipo de ESPECIE
                    $item['deliveryAddress'] = '';			// DIRECCION ENTREGA
                    $item['deliveryDate'] = '';						// FECHA ENTREGA
                    $item['quantity'] = 0;
//                }

                array_push($invoice['itemList'], $item);
            }
        }

        $billingManager = new BillingManager($this->connection,$business['environment'] == '1');
        switch ($invoiceData['document_code']) {
            case '01':
                $resInvoice = $billingManager->SendInvoice($invoiceData['invoice_id'], $invoice, $userReferId, true);
                break;
            case '03':
                $resInvoice = $billingManager->SendInvoice($invoiceData['invoice_id'], $invoice, $userReferId, false);
                break;
            case '07':
                $resInvoice = $billingManager->SendCreditNote($invoiceData['invoice_id'], $invoice, $userReferId, $invoiceData['update_document_code'] == '01');
                break;
            case '08':
                $resInvoice = $billingManager->SendDebitNote($invoiceData['invoice_id'], $invoice, $userReferId, $invoiceData['update_document_code'] == '01');
                break;
            default:
                throw new Exception('Wrong invoice Type!');
                break;
        }

        // Generate
        if ($resInvoice->success){
            $this->invoiceModel->updateInvoiceSunatByInvoiceId($invoiceData['invoice_id'],[
                'xml_url' => $resInvoice->xmlPath,
                'invoice_state_id' => 2,
            ]);
            $res->digestValue = $resInvoice->digestValue;
            $res->xmlPath = $resInvoice->xmlPath;
            $res->success = true;
        }else{
            $this->invoiceModel->updateInvoiceSunatByInvoiceId($invoiceData['invoice_id'],[
                'other_message' => $resInvoice->errorMessage,
            ]);
            $res->message .= $resInvoice->errorMessage;
            $res->success = false;
            return $res;
        }

        // Send
        if($resInvoice->send === true){
          if ($resInvoice->sunatComunicationSuccess){
              $this->invoiceModel->updateInvoiceSunatByInvoiceId($invoiceData['invoice_id'],[
                  'response_message' => $resInvoice->sunatDescription,
                  'response_code' => $resInvoice->sunatResponseCode,
                  'other_message' => '',
                  'send' => true,
              ]);
              $res->message .= $resInvoice->sunatDescription . ' ['.$resInvoice->sunatResponseCode.']';
              $res->success = true;
          } else {
              $this->invoiceModel->updateInvoiceSunatByInvoiceId($invoiceData['invoice_id'],[
                  'response_message' => $resInvoice->sunatCommuniationMessage,
                  'send' => true,
              ]);
              $res->message .= $resInvoice->sunatCommuniationMessage;
              $res->success = false;
              return $res;
          }

          // Reader
          if ($resInvoice->readerSuccess){
              $this->invoiceModel->updateInvoiceSunatByInvoiceId($invoiceData['invoice_id'],[
                  'cdr_url' => $resInvoice->cdrPath,
                  'invoice_state_id' => 3,
              ]);
              $res->cdrPath = $resInvoice->cdrPath;
              $res->success = true;
          } else {
              $res->message .= $resInvoice->readerMessage;
              $res->success = false;
          }
        }

        return $res;
    }

    public function SendEmail($to, array $invoice, array $business){
        return EmailManager::sendInvoice(
          $to,
          "{$invoice['document_type_code_description']} {$invoice['serie']}-{$invoice['number']} | {$invoice['customer_social_reason']}",
          APP_EMAIL,
          $business['commercial_reason'],
          [
            'documentDescription' => $invoice['document_type_code_description'],
            'serie' => $invoice['serie'],
            'number' => $invoice['number'],
            'socialReason' => $invoice['customer_social_reason'],
            'dateOfIssue' => $invoice['date_of_issue'],
            'dateOfDue' => $invoice['date_of_due'],
            'total' => "{$invoice['currency_type_code_symbol']} {$invoice['total']}",
            'documentUrl' => HOST . URL_PATH .  "/query?ruc=sss&serie={$invoice['serie']}&number={$invoice['number']}",
          ],
          [
            ROOT_DIR . $invoice['pdf_url'],
            ROOT_DIR . $invoice['xml_url'],
            ROOT_DIR . $invoice['cdr_url'],
          ]
        );
    }

    public function BuildDocument($invoiceId, $userReferId, $sendEmail = false){
        $res = new Result();
        try{
            $business = $this->businessModel->getByUserId($userReferId);
            $invoice = $this->invoiceModel->getAllDataById($invoiceId);
            $invoiceDetail = $this->invoiceItemModel->byInvoiceIdXML($invoiceId);

            $perceptionTypeCodeModel = new CatPerceptionTypeCode($this->connection);
            $perceptionTypeCode = $perceptionTypeCodeModel->getAll();

            if ($invoice['invoice_state_id'] == '3'){
                throw new Exception('Este documento ya fue informado ante la sunat');
            } elseif (($invoice['invoice_state_id'] == '4' && $invoice['document_code'] == '01')){
                throw new Exception('Este documento esta anulado');
            }

            // PERCEPTION
            $perceptionCode = $invoice['perception_code'];
            $perceptionPercentage = 0;
            $perceptionAmount = 0;
            $perceptionBase = 0;
            $totalWithPerception = 0;
            if ($invoice['perception_code'] != ''){
                $index = array_search($perceptionCode, array_column($perceptionTypeCode, 'code'));
                $perceptionPercentage = $perceptionTypeCode[$index]['percentage'] / 100;
                $perceptionAmount = RoundCurrency($perceptionPercentage  * $invoice['total']);
                $perceptionBase = $invoice['total'];
                $totalWithPerception = $invoice['total'] + $perceptionAmount;
            }
            $invoice['perception_code'] = '51';
            $invoice['perception_percentage'] = $perceptionPercentage;
            $invoice['perception_amount'] = $perceptionAmount;
            $invoice['perception_base'] = $perceptionBase;
            $invoice['total_with_perception'] = $totalWithPerception;

            // Itinerant
            if ($invoice['itinerant_enable']){
                $geographicalLocationCodeModel = new CatGeographicalLocationCode($this->connection);
                $itinerantLocation = $geographicalLocationCodeModel->getBy('code',$invoice['itinerant_location']);
                $itinerantProvince = $itinerantLocation['province'];
                $itinerantDepartment = $itinerantLocation['department'];
                $itinerantDistrict = $itinerantLocation['district'];
            }
            $invoice['itinerant_province'] = $itinerantProvince ?? '';
            $invoice['itinerant_department'] = $itinerantDepartment ?? '';
            $invoice['itinerant_district'] = $itinerantDistrict ?? '';

            // XML
            $documentData = [
                'invoice' => $invoice,
                'invoiceDetail' => $invoiceDetail,
                'business' => $business,
            ];
            $resXml = $this->GenerateXML($documentData, $userReferId);
            $res->message = $resXml->message;
            $res->success = $resXml->success;
            if (!$resXml->success){
                $this->invoiceModel->updateInvoiceSunatByInvoiceId($invoiceId,[
                    'other_message' =>  $resXml->message,
                ]);
            }
            $res->result = [
              'xml_url' => $resXml->xmlPath,
              'cdr_url' => $resXml->cdrPath,
            ];

            // PDF
            $documentData['invoice']['digestValue'] = '';
            if ($resXml->success){
                $documentData['invoice']['digestValue'] = $resXml->digestValue;
            }
            $resPdf = $this->GeneratePdf($documentData);
            if (!$resPdf->success){
                throw new Exception($resPdf->errorMessage);
            }
            $res->result['pdf_url'] = $resPdf->pdfPath;

            // Email
            if($sendEmail === true && $invoice['customer_email'] != ''){
              $invoice['pdf_url'] = $resPdf->pdfPath;
              $invoice['xml_url'] = $resXml->xmlPath;
              $invoice['cdr_url'] = $resXml->cdrPath;
              $resMail = $this->SendEmail($invoice['customer_email'],$invoice,$business);
              if($resMail->success){
                $this->invoiceModel->updateInvoiceCustomerByInvoiceId($invoiceId,[
                  'email_sent'=>$resMail,
                ]);
              }
            }
        } catch (Exception $e){
            $res->errorMessage = $e->getMessage();
            $res->success = false;
        }

        return $res;
    }
}
