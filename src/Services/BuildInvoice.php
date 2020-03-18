<?php

require_once MODEL_PATH . '/Invoice.php';
require_once MODEL_PATH . '/InvoiceItem.php';
require_once MODEL_PATH . '/Business.php';
require_once MODEL_PATH . '/Catalogue/CatSystemIscTypeCode.php';
require_once MODEL_PATH . '/Catalogue/CatPerceptionTypeCode.php';

require_once __DIR__ . '/DocumentManager/DocumentManager.php';
require_once __DIR__ . '/BillingManager/BillingManager.php';

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

    private function GeneratePdf(array $documentData ) {
        $business = $documentData['business'];
        $sale =  $documentData['sale'];
        $detailSale =  $documentData['detailSale'];

        $business = array_merge($business,[
            'address' => 'AV. HUASCAR NRO. 224 DPTO. 303',
            'region' => 'CUSCO',
            'province' => 'CUSCO',
            'district' => 'WANCHAQ',
        ]);

        $invoice['headerContact'] = 'Teléfono: 084601425 | Celular: 979706609 | www.skynetcusco.com | info@skynetcusco.com';
        $invoice['documentType'] = $sale['document_type_code_description'];
        $invoice['documentCode'] = $sale['document_code'];
        $invoice['serie'] = $sale['serie'];
        $invoice['correlative'] = $sale['number'];
        $invoice['vehiclePlate'] = $sale['vehicle_plate'];
        $invoice['term'] = $sale['term'];
        $invoice['purchaseOrder'] = $sale['purchase_order'];
        $invoice['observation'] = $sale['observation'];
        $invoice['logo'] = $business['logo'];

        $invoice['businessRuc'] = $business['ruc'];
        $invoice['businessSocialReason'] = $business['social_reason'];
        $invoice['businessCommercialReason'] = $business['social_reason'];
        $invoice['businessAddress'] = $business['address'];
        $invoice['businessLocation'] = $business['district'] . ' ' . $business['province'] . ' ' . $business['region'];

        $invoice['customerDocumentNumber'] = $sale['customer_document_number'];
        $invoice['customerDocumentCode'] = $sale['customer_document_number'];
        $invoice['customerSocialReason'] = $sale['customer_social_reason'];
        $invoice['customerFiscalAddress'] = $sale['customer_fiscal_address'];
        $invoice['digestValue'] = $sale['digestValue'];
        $invoice['dateOfIssue'] = $sale['date_of_issue'];
        $invoice['dateOfDue'] = $sale['date_of_due'];
        $invoice['currencySymbol'] = $sale['currency_type_code_symbol'];
        $invoice['currencyDescription'] = $sale['currency_type_code_description'];
        $invoice['totalDiscount'] = $sale['total_discount'];
        $invoice['totalPrepayment'] = $sale['total_prepayment'];
        $invoice['totalExonerated'] = $sale['total_exonerated'];
        $invoice['totalUnaffected'] = $sale['total_unaffected'];
        $invoice['totalTaxed'] = $sale['total_taxed'];
        $invoice['totalIsc'] = $sale['total_isc'];
        $invoice['totalIgv'] = $sale['total_igv'];
        $invoice['totalFree'] = $sale['total_free'];
        $invoice['totalCharge'] = $sale['total_charge'];
        $invoice['totalPlasticBagTax'] = $sale['total_plastic_bag_tax'];
        $invoice['total'] = $sale['total'];
        $invoice['totalInWord'] = NumberToLetter::StringFormat((int)$sale['total']) . ' ' .$sale['currency_type_code_description'];
        $invoice['percentageIgv'] = $sale['percentage_igv'];

        $invoice['perceptionCode'] = $sale['perception_code'];
        $invoice['perceptionPercentage'] = $sale['perception_percentage'];
        $invoice['perceptionAmount'] = $sale['perception_amount'];
        $invoice['perceptionBase'] = $sale['perception_base'];
        $invoice['totalWithPerception'] = $sale['total_with_perception'];

        $invoice['guide'] = json_decode($sale['guide'],true);
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
        $invoice['whitGuide'] = $sale['whit_guide'];
        $invoice['transferCode'] = $sale['transfer_code'];
        $invoice['transportCode'] = $sale['transport_code'];
        $invoice['totalGrossWeight'] = $sale['total_gross_weight'];
        $invoice['carrierDenomination'] = "{$sale['carrier_document_code']} - {$sale['carrier_document_number']} - {$sale['carrier_denomination']}";
        $invoice['carrierPlateNumber'] = $sale['carrier_plate_number'];
        $invoice['driverDenomination'] = "{$sale['driver_document_code']} - {$sale['driver_document_number']} - {$sale['driver_full_name']}";
        $invoice['locationEndPoint'] = "{$sale['location_arrival_code']} - {$sale['address_arrival_point']}";
        $invoice['locationStartPoint'] = "{$sale['location_starting_code']} - {$sale['address_starting_point']}";

        foreach ($detailSale as $row){
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
        $resPdf = $documentManager->Invoice($invoice,$sale['pdf_format'] !== '' ? $sale['pdf_format'] : 'A4',$business['environment']);

        if ($resPdf->success){
            $this->invoiceModel->updateInvoiceSunatByInvoiceId($sale['invoice_id'],[
                'pdf_url'=> $resPdf->pdfPath
            ]);
        }
        return $resPdf;
    }

    private function GenerateXML(array $documentData, $userReferId) {
        $business = $documentData['business'];
        $sale =  $documentData['sale'];
        $detailSale =  $documentData['detailSale'];

        $detailSale = array_map(function ($item) use ($sale, $business){
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
                $percentageIgv = $sale['percentage_igv'];
            }

            // Total base igv
            if ($ac == '11' || $ac == '12' || $ac == '13' || $ac == '14' || $ac == '15' || $ac == '16'){
                $percentageIgvDecimal = $sale['percentage_igv'] / 100;
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
        },$detailSale);

        // recalculate total
        $sale['total_discount_base'] = 0;
        $sale['total_discount_percentage'] = 0;
        if($sale['total_discount'] > 0){
//            $sale['total_discount_base'] = $sale['total_value'] + $sale['total_discount'];
            $sale['total_discount_base'] = $sale['total_value'];
            $sale['total_discount_percentage'] = ($sale['total_discount'] * 100 ) / ($sale['total_value'] + $sale['total_discount']);
        }
        $sale['guide'] = json_decode($sale['guide'],true);
        $sale['legend'] = json_decode($sale['legend'],true);
        $sale['related'] = json_decode($sale['related'],true);

        // Prepare Invoice
        $invoice['serie'] = $sale['serie'];
        $invoice['number'] = $sale['number'];
        $invoice['issueDate'] = $sale['date_of_issue'];
        $invoice['issueTime'] = $sale['time_of_issue'];
        $invoice['invoiceTypeCode'] = $sale['document_code'];
        $invoice['amounInWord'] = NumberToLetter::StringFormat((int)$sale['total']) . ' ' .$sale['currency_type_code_description'];
        $invoice['supplierRuc'] = $business['ruc'];
        $invoice['defaultUrl'] = 'WWW.SKYFACT.COM';
        $invoice['supplierName'] = htmlspecialchars($business['social_reason']);
        $invoice['supplierDocumentType'] = '6';					// TIPO DE DOCUMENTO EMISOR
        $invoice['customerDocumentType'] = $sale['customer_identity_document_code'];					// TIPO DE DOCUMENTO CLIENTE
        $invoice['customerDocument'] = $sale['customer_document_number'];			// DOCUMENTO DEL CLIENTE
        $invoice['customerName'] = htmlspecialchars($sale['customer_social_reason']);
        $invoice['totalTaxAmount'] = RoundCurrency($sale['total_tax']);					// TOTAL DE IMPUESTOS
        $invoice['totalBaseAmount'] = RoundCurrency($sale['total_value']);					// VALOR TOTAL DE LA VENTA
        $invoice['totalSaleAmount'] = RoundCurrency($sale['total']);					// VALOR TOTAL DE LA VENTA + IMPUESTOS
        $invoice['totalDiscountAmount'] = RoundCurrency($sale['total_discount']);				// VALOR TOTAL DE LOS DESCUENTOS
        $invoice['totalExtraChargeAmount'] = RoundCurrency($sale['total_charge']);			// VALOR TOTAL DE LOS CARGOS EXTRA
        $invoice['totalPrepaidAmount'] = RoundCurrency($sale['total_prepayment']);				// VALOR TOTAL DE LOS MONTOS PAGADOS COMO ADELANTO
        $invoice['totalPayableAmount'] = RoundCurrency($sale['total']);				// MONTO TOTAL QUE SE COBRA

        $invoice['totalIgvAmount'] = RoundCurrency($sale['total_igv']);					// VALOR TOTAL DEL IGV
        $invoice['totalIgvTaxableAmount'] = RoundCurrency($sale['total_taxed']);			// VALOR TOTAL DE LA VENTA GRABADA
        $invoice['totalIscAmount'] = RoundCurrency($sale['total_isc']);				// VALOR TOTAL DEL ISC
        $invoice['totalIscTaxableAmount'] = '0.00';				// VALOR TOTAL AL CUAL SE APLICA EL ISC.
        $invoice['totalFreeAmount'] = RoundCurrency($sale['total_free']);				// VALOR TOTAL INAFECTO A INPUESTOS
        $invoice['totalExoneratedAmount'] = RoundCurrency($sale['total_exonerated']);				// VALOR TOTAL INAFECTO A INPUESTOS
        $invoice['totalInafectedAmount'] = RoundCurrency($sale['total_unaffected']);				// VALOR TOTAL INAFECTO A INPUESTOS
        $invoice['totalOtherTaxAmount'] = RoundCurrency($sale['total_other_taxed']);				// VALOR TOTAL DE otros impuestos
        $invoice['totalOtherTaxableAmount'] = RoundCurrency($sale['total_base_other_taxed']);				// VALOR TOTAL AL CUAL SE APLICA otros impuestos.

        $invoice['totalBagTaxAmount'] = RoundCurrency($sale['total_plastic_bag_tax']);	// total			    // VALOR TOTAL del impuesto a las bolsas
        $invoice['bagTaxAmountPerUnit'] = RoundCurrency($sale['percentage_plastic_bag_tax']); // percentage				// VALOR TOTAL del impuesto a las bolsas
        $invoice['operationTypeCode'] = $sale['operation_code'];				// Codigo del tipo de operacion (Venta interna : 0101 - Exportacion : 0102  Catalogo 25)
//        $invoice['operationTypeCode'] = '0401';				// Codigo del tipo de operacion (Venta interna : 0101 - Exportacion : 0102  Catalogo 25)

        $invoice['globalDiscountPercent'] = RoundCurrency($sale['total_discount_percentage'] / 100,5);				// DESCUENTO EN PORCENTAJE
        $invoice['bagTaxAmount'] = '1.00';							// CODIGO DE LA MONEDA
        $invoice['globalDiscountAmount'] = RoundCurrency($sale['total_discount']);				// VALOR TOTAL DE LOS DESCUENTOS                            // ---------------- Cambiar la variable  -- total_discount_base
        $invoice['codigoMoneda'] = $sale['currency_code'];							// CODIGO DE LA MONEDA
        $invoice['amazoniaGoods'] = 0;							// BIENES EN LA AMAZONIA
        $invoice['amazoniaService'] = 0;						// SERVICIOS EN LA AMAZONIA
        $invoice['orderReference'] = $sale['purchase_order'];							// Referencia de la orden de compra o servicio

        //REFERENCIA A GUIAS DE REMISION
        $invoice['referenceDocumentList'] = array();
//        foreach ($sale['guide'] as $row){
//            $referencedDocument = array();
//            $referencedDocument['referencedDocument'] = $row['serie'];							// SERIE Y NUMERO DEL DOCUMENTO
//            $referencedDocument['referencedDocumentTypeCode'] = $row['document_code'];						// TIPO DOCUMENTO CAT 01
//            array_push($invoice['referenceDocumentList'], $referencedDocument);
//        }
        $invoice['itemList'] = array();

        //PERCEPCION
        $invoice['totalAmountWithPerception'] = $sale['total_with_perception'];						// MONTO TOTAL DE LA VENTA MAS LA PERCEPCION
        $invoice['perceptionTypeCode'] = $sale['perception_code'];								// CODIGO DEL TIPO DE PERCEPCION CAT 53
        $invoice['perceptionPercent'] = $sale['perception_percentage'];								// MONTO TOTAL DE LA VENTA MAS LA PERCEPCION
        $invoice['perceptionAmount'] = $sale['perception_amount'];									// MONTO DE LA PERCEPCION
        $invoice['perceptionTaxableAmount'] = $sale['perception_base'];						// MONTO SOBRE EL CUAL SE CALCULA LA PERCEPCION

        //DETRACCION
//        $invoice['detractionAccount'] = $sale['detraction_bill'];						        // CODIGO DEL TIPO DE PERCEPCION CAT 53
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
        foreach ($detailSale as $row){
//            if ($row['prepayment_regulation']){
//                $item = array();
//                $item['documentSerieNumber'] = $row['prepayment_serie'] . '-' . $row['prepayment_correlative'];				// serie y numero del documento del anticipo
//                $item['documentTypeCode'] = '02';						// codigo del tipo de documento CAT 12 (02 o 03)
//                $item['prepaidAmount'] = $row['total_value'];						// monto del anticipo
//                array_push($invoice['prepaidPaymentList'], $item);
//            }
        }

        //FACTURA - GUIA 								REGLA: referalGuideIncluded = 1
        $invoice['referalGuideIncluded'] = $sale['transfer_code'] == '' ? 0 : 1;
        $invoice['transferReasonCode'] = $sale['transfer_code'];						// CODIGO DEL Motivo de Traslado CAT 20
        $invoice['grossWeightMeasure'] = 'KGM';						// UNIDAD DE MEDIDA DEL PESO TOTAL DEL ENVIO CAT 03(KGM = Kilogramo)
        $invoice['grossWeight'] = $sale['total_gross_weight'];							// PESO
        $invoice['transferMethodCode'] = $sale['transport_code'];						// CODIGO DEL METODO DE TRANSPORTE CAT 18
        $invoice['carrierDocumentType'] = $sale['carrier_document_code'];						// TIPO DE DOCUMENTO DEL TRANSPORTISTA
        $invoice['carrierRuc'] = $sale['carrier_document_number'];						// NUMERO DE DOCUMENTO DEL TRANSPORTISTA
        $invoice['carrierName'] = htmlspecialchars($sale['carrier_denomination']);					// NOMBRE DEL TRANSPORTISTA
        $invoice['licensePlate'] = $sale['carrier_plate_number'];						// PLACA DEL VEHICULO
        $invoice['driverDocumentType'] = $sale['driver_document_code'];						// TIPO DE DOCUMENTO DEL CONDUCTOR
        $invoice['driverDocument'] = $sale['driver_document_number'];						// NUMERO DE DOCUMENTO DEL CONDUCTOR

        //FACTURA - EMISOR ITINERANTE
        $invoice['itinerantSuplier'] = $sale['itinerant_enable'];							// 1/0 VENDEODR ITINERANTO = 1
        $invoice['itinerantAddressCode'] = $sale['itinerant_location'];							// VENTA ITINERANTE - UBIGEO
        $invoice['itinerantAddress'] = $sale['itinerant_address'];							// VENTA ITINERANTE - DIRECCION
        $invoice['itinerantUrbanization'] = $sale['itinerant_urbanization'];							//VENTA ITINERANTE - URBANIZACION
        $invoice['itinerantProvince'] = $sale['itinerant_province'];							// VENTA ITINERANTE - PROVINCIA
        $invoice['itinerantRegion'] = $sale['itinerant_department'];							// VENTA ITINERANTE - REGION O DEPARTAMENTO
        $invoice['itinerantDistrict'] = $sale['itinerant_district'];							// VENTA ITINERANTE - DISTRITO

//        if (!$sale['whit_detraction']){
//            $invoice['deliveryAdressCode'] = $sale['location_arrival_code'];					// UBIGEO DE DESTINO
//            $invoice['deliveryAdress'] = $sale['address_arrival_point'];						// DIRECCION DESTINO
//            $invoice['originAdressCode'] = $sale['location_starting_code'];						// UBIGEO DE ORIGEN
//            $invoice['originAdress'] = $sale['address_starting_point'];							// DIRECCION ORIGEN
//        }

        foreach ($detailSale as $row){
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

//                if ($sale['whit_detraction']){
//                    //DETRACCION - HIDROBIOLOGICO
//                    $item['transportReferencialAmount'] = $sale['detraction_referral_value'];						// Valor referencial del servicio de transporte
//                    $item['effectiveLoadReferencialAmount'] = $sale['detraction_effective_load'];					// Valor referencial sobre la carga efectiva
//                    $item['payLoadReferencialAmount'] = $sale['detraction_useful_load'];						// Valor referencial sobre la carga útil nominal
//
//                    //DETRACCION - TRANSPORTE DE CARGA
//                    $item['speciesKind'] = $sale['detraction_species_kind'];								// tipo de ESPECIE
//                    $item['deliveryAddress'] = $sale['detraction_delivery_address'];			// DIRECCION ENTREGA
//                    $item['deliveryDate'] = $sale['detraction_delivery_date'];						// FECHA ENTREGA
//                    $item['quantity'] = $sale['detraction_quantity'];
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

        $billingManager = new BillingManager($this->connection);
        $directoryXmlPath = '/assets/files/xml/' . date('Ym') . '/' . $business['ruc'] . '/';
        $fileName = $business['ruc'] . '-' . $sale['document_code'] . '-' . $sale['serie'] . '-' . $sale['number'] . '.xml';

        $res = new Result();
        $res->digestValue = '';

//        if ($sale['document_code'] === '01'){
        $resInvoice = $billingManager->SendInvoice($sale['invoice_id'], $invoice,$userReferId);
        if ($resInvoice->success){
            $this->invoiceModel->updateInvoiceSunatByInvoiceId($sale['invoice_id'],[
                'xml_url' => $directoryXmlPath . $fileName,
                'invoice_state_id' => 2,
            ]);
            $res->digestValue = $resInvoice->digestValue;
            $res->success = true;
        }else{
            $this->invoiceModel->updateInvoiceSunatByInvoiceId($sale['invoice_id'],[
                'other_message' => $resInvoice->errorMessage,
            ]);
            $res->message .= $resInvoice->errorMessage;
            $res->success = false;
            return $res;
        }

        if ($resInvoice->sunatComunicationSuccess){
            $this->invoiceModel->updateInvoiceSunatByInvoiceId($sale['invoice_id'],[
                'response_message' => $resInvoice->sunatDescription,
                'response_code' => $resInvoice->sunatResponseCode,
                'other_message' => '',
            ]);
            $res->success = true;
        } else {
            $this->invoiceModel->updateInvoiceSunatByInvoiceId($sale['invoice_id'],[
                'response_message' => $resInvoice->sunatCommuniationError,
            ]);
            $res->message .= $resInvoice->sunatCommuniationError;
            $res->success = false;
            return $res;
        }

        if ($resInvoice->readerSuccess){
            $this->invoiceModel->updateInvoiceSunatByInvoiceId($sale['invoice_id'],[
                'cdr_url' => $directoryXmlPath . 'R-' . $fileName,
                'invoice_state_id' => 3,
            ]);
            $res->success = true;
        } else {
            $res->message .= $resInvoice->readerError;
            $res->success = false;
        }
//        }elseif ($sale['document_code'] === '03'){
//            $resInvoice = $billingManager->SaveTicketInvoice($sale['invoice_id'], $invoice, $userReferId);
//
//            if ($resInvoice->success){
//                $this->saleModel->UpdateInvoiceSunatByInvoiceId($sale['invoice_id'],[
//                    'xml_url' => $directoryXmlPath . $fileName,
//                    'invoice_state_id' => 2,
//                ]);
//                $res->digestValue = $resInvoice->digestValue;
//                $res->success = true;
//            }else{
//                $res->errorMessage .= $resInvoice->errorMessage;
//                $res->success = false;
//            }
//        }

        return $res;
    }

    public function BuildDocument($invoiceId, $userReferId){
        $res = new Result();
        $links =[];
        try{
            $business = $this->businessModel->getByUserId($userReferId);
            $invoice = $this->invoiceModel->getAllDataById($invoiceId);
            $detailSale = $this->invoiceItemModel->byInvoiceIdXML($invoiceId);

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
                'sale' => $invoice,
                'detailSale' => $detailSale,
                'business' => $business,
            ];
            $resXml = $this->GenerateXML($documentData,$userReferId);
            $res->message = $resXml->message;
            $res->success = $resXml->success;
            if (!$resXml->success){
                $this->invoiceModel->updateInvoiceSunatByInvoiceId($invoiceId,[
                    'other_message' =>  $resXml->message,
                ]);
            }
            $links['xmlPath'] = '';
            $links['cdrPath'] = '';

            // PDF
            $documentData['sale']['digestValue'] = '';
            if ($resXml->success){
                $documentData['sale']['digestValue'] = $resXml->digestValue;
            }
            $resPdf = $this->GeneratePdf($documentData);
            if (!$resPdf->success){
                throw new Exception($resPdf->errorMessage);
            }
            $links['pdfPath'] = $resPdf->pdfPath;

            // Email


            $res->result = $links;
        }catch (Exception $e){
            $res->errorMessage = $e->getMessage();
            $res->success = false;
        }

        return $res;
    }
}
