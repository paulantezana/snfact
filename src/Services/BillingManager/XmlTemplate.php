<?php
class XmlTemplate
{
    public static function InvoiceBase()
    {
    	return
    	  '<?xml version="1.0" encoding="UTF-8"?>
			<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
			  <ext:UBLExtensions>
			    <ext:UBLExtension>
			      <ext:ExtensionContent>
			      </ext:ExtensionContent>
			    </ext:UBLExtension>
			  </ext:UBLExtensions>
			  <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
			  <cbc:CustomizationID>2.0</cbc:CustomizationID>
			  <cbc:ID>{{serie}}-{{number}}</cbc:ID>
			  <cbc:IssueDate>{{issueDate}}</cbc:IssueDate>
			  <cbc:IssueTime>{{issueTime}}</cbc:IssueTime>
			  <cbc:InvoiceTypeCode listID="{{operationTypeCode}}" listAgencyName="PE:SUNAT" listName="Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01" name="Tipo de Operacion" listSchemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo51">{{invoiceTypeCode}}</cbc:InvoiceTypeCode>
			  <cbc:Note languageLocaleID="1000">{{amounInWord}}</cbc:Note>
			  <cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listAgencyName="United Nations Economic Commission for Europe" listName="Currency">{{codigoMoneda}}</cbc:DocumentCurrencyCode>
			  <cac:Signature>
			    <cbc:ID>{{supplierRuc}}</cbc:ID>
			    <cbc:Note>{{defaultUrl}}</cbc:Note>
			    <cac:SignatoryParty>
			      <cac:PartyIdentification>
			        <cbc:ID>{{supplierRuc}}</cbc:ID>
			      </cac:PartyIdentification>
			      <cac:PartyName>
			        <cbc:Name>{{supplierName}}</cbc:Name>
			      </cac:PartyName>
			    </cac:SignatoryParty>
			    <cac:DigitalSignatureAttachment>
			      <cac:ExternalReference>
			        <cbc:URI>{{supplierRuc}}</cbc:URI>
			      </cac:ExternalReference>
			    </cac:DigitalSignatureAttachment>
			  </cac:Signature>
			  <cac:AccountingSupplierParty>
			    <cac:Party>
			      <cac:PartyIdentification>
			        <cbc:ID schemeID="{{supplierDocumentType}}" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">{{supplierRuc}}</cbc:ID>
			      </cac:PartyIdentification>
			      <cac:PartyName>
			        <cbc:Name>{{supplierName}}</cbc:Name>
			      </cac:PartyName>
			      <cac:PartyLegalEntity>
			        <cbc:RegistrationName>{{supplierName}}</cbc:RegistrationName>
			        <cac:RegistrationAddress>
			          <cbc:AddressTypeCode listAgencyName="PE:SUNAT" listName="Establecimientos anexos">0001</cbc:AddressTypeCode>
			        </cac:RegistrationAddress>
			      </cac:PartyLegalEntity>
			    </cac:Party>
			  </cac:AccountingSupplierParty>
			  <cac:AccountingCustomerParty>
			    <cac:Party>
			      <cac:PartyIdentification>
			        <cbc:ID schemeID="{{customerDocumentType}}" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">{{customerDocument}}</cbc:ID>
			      </cac:PartyIdentification>
			      <cac:PartyLegalEntity>
			        <cbc:RegistrationName>{{customerName}}</cbc:RegistrationName>
			      </cac:PartyLegalEntity>
			    </cac:Party>
			  </cac:AccountingCustomerParty>
			  <cac:TaxTotal>
			    <cbc:TaxAmount currencyID="{{codigoMoneda}}">{{totalTaxAmount}}</cbc:TaxAmount>
			  </cac:TaxTotal>
			  <cac:LegalMonetaryTotal>
			    <cbc:LineExtensionAmount currencyID="{{codigoMoneda}}">{{totalBaseAmount}}</cbc:LineExtensionAmount>
			    <cbc:TaxInclusiveAmount currencyID="{{codigoMoneda}}">{{totalSaleAmount}}</cbc:TaxInclusiveAmount>
			    <cbc:AllowanceTotalAmount currencyID="{{codigoMoneda}}">{{totalDiscountAmount}}</cbc:AllowanceTotalAmount>
			    <cbc:ChargeTotalAmount currencyID="{{codigoMoneda}}">{{totalExtraChargeAmount}}</cbc:ChargeTotalAmount>
			    <cbc:PrepaidAmount currencyID="{{codigoMoneda}}">{{totalPrepaidAmount}}</cbc:PrepaidAmount>
			    <cbc:PayableAmount currencyID="{{codigoMoneda}}">{{totalPayableAmount}}</cbc:PayableAmount>
			  </cac:LegalMonetaryTotal>
			</Invoice>';
    }

    public static function InvoiceDocumentReference()
    {
    	return
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
	    	<cac:DespatchDocumentReference>
			  <cbc:ID>{{referencedDocument}}</cbc:ID>
   			  <cbc:DocumentTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">{{referencedDocumentTypeCode}}</cbc:DocumentTypeCode>
			</cac:DespatchDocumentReference>
    	  </Invoice>';
    }

    public static function InvoiceAditionalDocumentReference()
    {
    	return
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
	    	<cac:AdditionalDocumentReference>
		        <cbc:ID>{{documentSerieNumber}}</cbc:ID>
		        <cbc:DocumentTypeCode listAgencyName="PE:SUNAT" listName="Documento Relacionado" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo12">{{documentTypeCode}}</cbc:DocumentTypeCode>
		        <cbc:DocumentStatusCode listAgencyName="PE:SUNAT" listName="Anticipo">{{correlative}}</cbc:DocumentStatusCode>
		        <cac:IssuerParty>
		            <cac:PartyIdentification>
		                <cbc:ID schemeAgencyName="PE:SUNAT" schemeID="{{supplierDocumentType}}" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">{{supplierRuc}}</cbc:ID>
		            </cac:PartyIdentification>
		        </cac:IssuerParty>
		    </cac:AdditionalDocumentReference>
    	  </Invoice>';
    }

    public static function InvoicePrepaidPayment()
    {
    	return
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
		    <cac:PrepaidPayment>
		        <cbc:ID schemeAgencyName="PE:SUNAT" schemeName="Anticipo">{{aditionalDocumentCorrelative}}</cbc:ID>
		        <cbc:PaidAmount currencyID="PEN">{{prepaidAmount}}</cbc:PaidAmount>
		    </cac:PrepaidPayment>
    	  </Invoice>';
    }

    public static function InvoicePerception()
    {
    	return
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
	    	<cac:PaymentMeans>
		        <cbc:PaymentMeansCode listAgencyName="PE:SUNAT" listName="Medio de pago" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo59">009</cbc:PaymentMeansCode>
		    </cac:PaymentMeans>
		    <cac:PaymentTerms>
		        <cbc:ID>Percepcion</cbc:ID>
		        <cbc:Amount currencyID="PEN">{{totalAmountWithPerception}}</cbc:Amount>
		    </cac:PaymentTerms>
    	  </Invoice>';
    }

    public static function InvoiceDetraction()
    {
    	return
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
	    	<cac:PaymentMeans>
		        <cbc:PaymentMeansCode listAgencyName="PE:SUNAT" listName="Medio de pago" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo59">009</cbc:PaymentMeansCode>
		        <cac:PayeeFinancialAccount>
		            <cbc:ID>{{detractionAccount}}</cbc:ID>
		        </cac:PayeeFinancialAccount>
		    </cac:PaymentMeans>
		    <cac:PaymentTerms>
		        <cbc:PaymentMeansID schemeAgencyName="PE:SUNAT" schemeName="Codigo de detraccion" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo54">{{detractionTypeCode}}</cbc:PaymentMeansID>
		        <cbc:PaymentPercent>{{detractionPercent}}</cbc:PaymentPercent>
		        <cbc:Amount currencyID="PEN">{{detractionAmount}}</cbc:Amount>
		    </cac:PaymentTerms>
    	  </Invoice>';
    }

    public static function TotalTax()
    {
    	return
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
	    	<cac:TaxSubtotal>
		      <cbc:TaxableAmount currencyID="{{codigoMoneda}}">{{totalTaxableAmount}}</cbc:TaxableAmount>
		      <cbc:TaxAmount currencyID="{{codigoMoneda}}">{{totalTaxAmount}}</cbc:TaxAmount>
		      <cac:TaxCategory>
		        <cac:TaxScheme>
		          <cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">{{taxId}}</cbc:ID>
		          <cbc:Name>{{taxName}}</cbc:Name>
		          <cbc:TaxTypeCode>{{taxTypeCode}}</cbc:TaxTypeCode>
		        </cac:TaxScheme>
		       </cac:TaxCategory>
	    	</cac:TaxSubtotal>
		  </Invoice>';
    }

    public static function TotalDiscount()
    {
    	return
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
    		<cac:AllowanceCharge>
			    <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
			    <cbc:AllowanceChargeReasonCode listAgencyName="PE:SUNAT" listName="Cargo/descuento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo53">02</cbc:AllowanceChargeReasonCode>
			    <cbc:MultiplierFactorNumeric>{{globalDiscountPercent}}</cbc:MultiplierFactorNumeric>
			    <cbc:Amount currencyID="{{codigoMoneda}}">{{globalDiscountAmount}}</cbc:Amount>
			    <cbc:BaseAmount currencyID="{{codigoMoneda}}">{{totalBaseAmount}}</cbc:BaseAmount>
			</cac:AllowanceCharge>
		  </Invoice>';
    }

    public static function TotalBagTax()
    {
    	return
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
	    	<cac:TaxSubtotal>
		      <cbc:TaxAmount currencyID="{{codigoMoneda}}">{{totalBagTaxAmount}}</cbc:TaxAmount>
		      <cac:TaxCategory>
		        <cac:TaxScheme>
		          <cbc:ID schemeAgencyName="PE:SUNAT" schemeName="Codigo de tributos" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">7152</cbc:ID>
		          <cbc:Name>ICBPER</cbc:Name>
		          <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
		        </cac:TaxScheme>
		      </cac:TaxCategory>
		    </cac:TaxSubtotal>
		  </Invoice>';
    }

    public static function InvoiceCaption()
    {
    	return
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
	    	<cbc:Note languageLocaleID="{{captionCode}}">{{captionDescription}}</cbc:Note>
		  </Invoice>';
    }

    public static function InvoiceOrderReference()
    {
    	return
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
	    	<cac:OrderReference>
		      <cbc:ID>{{orderReference}}</cbc:ID>
		    </cac:OrderReference>
		  </Invoice>';
    }

    public static function InvoiceDelivery()
    {
    	return
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
	    	<cac:Delivery>
		        <cac:Shipment>
		            <cbc:ID schemeAgencyName="PE:SUNAT" schemeName="Motivo de Traslado" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo20">{{transferReasonCode}}</cbc:ID>
		            <cbc:GrossWeightMeasure unitCode="{{grossWeightMeasure}}">{{grossWeight}}</cbc:GrossWeightMeasure>
		            <cac:ShipmentStage>
		                <cbc:TransportModeCode listAgencyName="PE:SUNAT" listName="Modalidad de Transporte" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo18">{{transferMethodCode}}</cbc:TransportModeCode>
		                <cac:CarrierParty>
		                    <cac:PartyIdentification>
		                        <cbc:ID schemeAgencyName="PE:SUNAT" schemeID="{{carrierDocumentType}}" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">{{carrierRuc}}</cbc:ID>
		                    </cac:PartyIdentification>
		                    <cac:PartyLegalEntity>
		                        <cbc:RegistrationName>{{carrierName}}</cbc:RegistrationName>
		                    </cac:PartyLegalEntity>
		                </cac:CarrierParty>
		                <cac:TransportMeans>
		                    <cac:RoadTransport>
		                        <cbc:LicensePlateID>{{licensePlate}}</cbc:LicensePlateID>
		                    </cac:RoadTransport>
		                </cac:TransportMeans>
		                <cac:DriverPerson>
		                    <cbc:ID schemeAgencyName="PE:SUNAT" schemeID="{{driverDocumentType}}" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">{{driverDocument}}</cbc:ID>
		                </cac:DriverPerson>
		            </cac:ShipmentStage>
		            <cac:Delivery>
		                <cac:DeliveryAddress>
		                    <cbc:ID schemeAgencyName="PE:INEI" schemeName="Ubigeos">{{deliveryAdressCode}}</cbc:ID>
		                    <cac:AddressLine>
		                        <cbc:Line>{{deliveryAdress}}</cbc:Line>
		                    </cac:AddressLine>
		                </cac:DeliveryAddress>
		            </cac:Delivery>
		            <cac:OriginAddress>
		                <cbc:ID schemeAgencyName="PE:INEI" schemeName="Ubigeos">{{originAdressCode}}</cbc:ID>
		                <cac:AddressLine>
		                    <cbc:Line>{{originAdress}}</cbc:Line>
		                </cac:AddressLine>
		            </cac:OriginAddress>
		        </cac:Shipment>
		    </cac:Delivery>
		  </Invoice>';
    }

    public static function InvoiceItinerantDeliveryAdress()
    {
    	return 
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
			    <cac:Delivery>
			    	<cac:DeliveryLocation>
			    		<cac:Address>
			    			<cbc:ID schemeAgencyName="PE:INEI" schemeName="Ubigeos">{{itinerantAddressCode}}</cbc:ID>
			    			<cbc:CitySubdivisionName>{{itinerantUrbanization}}</cbc:CitySubdivisionName>
			    			<cbc:CityName>{{itinerantProvince}}</cbc:CityName>
			    			<cbc:CountrySubentity>{{itinerantRegion}}</cbc:CountrySubentity>
			    			<cbc:District>{{itinerantDistrict}}</cbc:District>
			    			<cac:AddressLine>
			    				<cbc:Line>{{itinerantAddress}}</cbc:Line>
			    			</cac:AddressLine>
			    			<cac:Country>
			    				<cbc:IdentificationCode listID="ISO 3166-1" listAgencyName="United Nations Economic Commission for Europe" listName="Country">PE</cbc:IdentificationCode>
			    			</cac:Country>
			    		</cac:Address>
				    </cac:DeliveryLocation>
				</cac:Delivery>
			</Invoice>';
    }

    public static function InvoiceItem()
    {
    	return
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
	    	  <cac:InvoiceLine>
			    <cbc:ID>{{itemCorrelative}}</cbc:ID>
			    <cbc:InvoicedQuantity unitCode="{{itemUnitCode}}" unitCodeListID="UN/ECE rec 20" unitCodeListAgencyName="United Nations Economic Commission for Europe">{{itemCuantity}}</cbc:InvoicedQuantity>
			    <cbc:LineExtensionAmount currencyID="{{codigoMoneda}}">{{itemFinalBaseAmount}}</cbc:LineExtensionAmount>
			    <cac:PricingReference>
			      <cac:AlternativeConditionPrice>
			        <cbc:PriceAmount currencyID="{{codigoMoneda}}">{{singleItemPrice}}</cbc:PriceAmount>
			        <cbc:PriceTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Precio" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">{{itemTransactionType}}</cbc:PriceTypeCode>
			      </cac:AlternativeConditionPrice>
			    </cac:PricingReference>
			    <cac:TaxTotal>
			      <cbc:TaxAmount currencyID="{{codigoMoneda}}">{{itemTotalTaxAmount}}</cbc:TaxAmount>
			      <cac:TaxSubtotal>
			        <cbc:TaxableAmount currencyID="{{codigoMoneda}}">{{itemIgvTaxableAmount}}</cbc:TaxableAmount>
			        <cbc:TaxAmount currencyID="{{codigoMoneda}}">{{itemTotalIgvAmount}}</cbc:TaxAmount>
			        <cac:TaxCategory>
			          <cbc:Percent>{{itemTaxPercent}}</cbc:Percent>
			          <cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="Afectacion del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">{{itemIgvTaxCode}}</cbc:TaxExemptionReasonCode>
			          <cac:TaxScheme>
			            <cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">{{itemTaxCode}}</cbc:ID>
			            <cbc:Name>{{itemTaxName}}</cbc:Name>
			            <cbc:TaxTypeCode>{{itemTaxNamecode}}</cbc:TaxTypeCode>
			          </cac:TaxScheme>
			        </cac:TaxCategory>
			      </cac:TaxSubtotal>
			    </cac:TaxTotal>
			    <cac:Item>
			      <cbc:Description>{{itemDescription}}</cbc:Description>
			      <cac:CommodityClassification>
			        <cbc:ItemClassificationCode listID="UNSPSC" listAgencyName="GS1 US" listName="Item Classification">{{ItemClassificationCode}}</cbc:ItemClassificationCode>
			      </cac:CommodityClassification>
			    </cac:Item>
			    <cac:Price>
			      <cbc:PriceAmount currencyID="{{codigoMoneda}}">{{singleItemBasePrice}}</cbc:PriceAmount>
			    </cac:Price>
			  </cac:InvoiceLine>
			</Invoice>';
    }

    public static function InvoiceItemBagTax()
    {
    	return
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
	    	<cac:TaxSubtotal>
		      <cbc:TaxAmount currencyID="{{codigoMoneda}}">{{bagTaxAmount}}</cbc:TaxAmount>
		      <cbc:BaseUnitMeasure unitCode="NIU">{{itemBagTaxCuantity}}</cbc:BaseUnitMeasure>
		      <cac:TaxCategory>
		        <cbc:PerUnitAmount currencyID="{{codigoMoneda}}">{{bagTaxAmountPerUnit}}</cbc:PerUnitAmount>
		        <cac:TaxScheme>
		          <cbc:ID schemeAgencyName="PE:SUNAT" schemeName="Codigo de tributos" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">7152</cbc:ID>
		          <cbc:Name>ICBPER</cbc:Name>
		          <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
		        </cac:TaxScheme>
		      </cac:TaxCategory>
		    </cac:TaxSubtotal>
		  </Invoice>';
    }

    public static function InvoiceItemIsc()
    {
    	return
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
    		<cac:TaxSubtotal>
		        <cbc:TaxableAmount currencyID="{{codigoMoneda}}">{{itemIscTaxableAmount}}</cbc:TaxableAmount>
		        <cbc:TaxAmount currencyID="{{codigoMoneda}}">{{itemIscAmount}}</cbc:TaxAmount>
		        <cac:TaxCategory>
		          <cbc:Percent>{{itemIscTaxPercent}}</cbc:Percent>
		          <cbc:TierRange>{{itemIscSystemType}}</cbc:TierRange>
		          <cac:TaxScheme>
		            <cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">2000</cbc:ID>
		            <cbc:Name>ISC</cbc:Name>
		            <cbc:TaxTypeCode>EXC</cbc:TaxTypeCode>
		          </cac:TaxScheme>
		        </cac:TaxCategory>
		    </cac:TaxSubtotal>
		  </Invoice>';
    }

    public static function InvoiceItemAllowanceCharge()
    {
    	return
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
    		<cac:AllowanceCharge>
		      <cbc:ChargeIndicator>{{chargeIndicator}}</cbc:ChargeIndicator>
		      <cbc:AllowanceChargeReasonCode listAgencyName="PE:SUNAT" listName="Cargo/descuento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo53">{{allowanceChargeCode}}</cbc:AllowanceChargeReasonCode>
		      <cbc:MultiplierFactorNumeric>{{allowanceChargePercent}}</cbc:MultiplierFactorNumeric>
		      <cbc:Amount currencyID="{{codigoMoneda}}">{{allowanceChargeAmount}}</cbc:Amount>
		      <cbc:BaseAmount currencyID="{{codigoMoneda}}">{{allowanceChargeBaseAmount}}</cbc:BaseAmount>
		    </cac:AllowanceCharge>
		  </Invoice>';
    }

    public static function InvoiceItemDetraccionHidro()
    {
    	return
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
    		<cac:AdditionalItemProperty>
    		  <cbc:Name>Matrícula de la embarcación</cbc:Name>
    		  <cbc:NameCode listName="Propiedad del item" listAgencyName="listName" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo55">3001</cbc:NameCode>
    		  <cbc:Value>{{boatLicensePlate}}</cbc:Value>
    		</cac:AdditionalItemProperty>
    		<cac:AdditionalItemProperty>
    		  <cbc:Name>Nombre de la embarcación</cbc:Name>
    		  <cbc:NameCode listName="Propiedad del item" listAgencyName="listName" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo55">3002</cbc:NameCode>
    		  <cbc:Value>{{boatName}}</cbc:Value>
    		</cac:AdditionalItemProperty>
    		<cac:AdditionalItemProperty>
    		  <cbc:Name>Tipo de especie vendida</cbc:Name>
    		  <cbc:NameCode listName="Propiedad del item" listAgencyName="listName" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo55">3003</cbc:NameCode>
    		  <cbc:Value>{{speciesKind}}</cbc:Value>
    		</cac:AdditionalItemProperty>
    		<cac:AdditionalItemProperty>
    		  <cbc:Name>Lugar de descarga</cbc:Name>
    		  <cbc:NameCode listName="Propiedad del item" listAgencyName="listName" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo55">3004</cbc:NameCode>
    		  <cbc:Value>{{deliveryAddress}}</cbc:Value>
    		</cac:AdditionalItemProperty>
    		<cac:AdditionalItemProperty>
    		  <cbc:Name>Fecha de descarga</cbc:Name>
    		  <cbc:NameCode listName="Propiedad del item" listAgencyName="listName" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo55">3005</cbc:NameCode>
    		  <cac:UsabilityPeriod>
      		    <cbc:StartDate>{{deliveryDate}}</cbc:StartDate>
    		  </cac:UsabilityPeriod>
    		</cac:AdditionalItemProperty>
    		<cac:AdditionalItemProperty>
    		  <cbc:Name>Cantidad de especie vendida</cbc:Name>
    		  <cbc:NameCode listName="Propiedad del item" listAgencyName="listName" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo55">3006</cbc:NameCode>
    		  <cbc:ValueQuantity unitCode="TNE">{{quantity}}</cbc:ValueQuantity>
    		</cac:AdditionalItemProperty>
		  </Invoice>';
    }

    public static function InvoiceItemDetraccionTrans()
    {
    	return
    	  '<Invoice xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
    		<cac:Delivery>
		      <cac:Despatch>
		        <cbc:Instructions>{{despatchDetail}}</cbc:Instructions>
		        <cac:DespatchAddress>
		          <cbc:ID schemeAgencyName="PE:INEI" schemeName="Ubigeos">{{originAdressCode}}</cbc:ID>
		          <cac:AddressLine>
		            <cbc:Line>{{originAdress}}</cbc:Line>
		          </cac:AddressLine>
		        </cac:DespatchAddress>
		      </cac:Despatch>
		    </cac:Delivery>
		    <cac:Delivery>
		      <cac:DeliveryLocation>
		        <cac:Address>
		          <cbc:ID schemeAgencyName="PE:INEI" schemeName="Ubigeos">{{deliveryAdressCode}}</cbc:ID>
		          <cac:AddressLine>
		            <cbc:Line>{{deliveryAdress}}</cbc:Line>
		          </cac:AddressLine>
		        </cac:Address>
		      </cac:DeliveryLocation>
		    </cac:Delivery>
		    <cac:Delivery>
		      <cac:DeliveryTerms>
		        <cbc:ID>01</cbc:ID>
		        <cbc:Amount currencyID="{{codigoMoneda}}">{{transportReferencialAmount}}</cbc:Amount>
		      </cac:DeliveryTerms>
		    </cac:Delivery>
		    <cac:Delivery>
		      <cac:DeliveryTerms>
		        <cbc:ID>02</cbc:ID>
		        <cbc:Amount currencyID="{{codigoMoneda}}">{{effectiveLoadReferencialAmount}}</cbc:Amount>
		      </cac:DeliveryTerms>
		    </cac:Delivery>
		    <cac:Delivery>
		      <cac:DeliveryTerms>
		        <cbc:ID>03</cbc:ID>
		        <cbc:Amount currencyID="{{codigoMoneda}}">{{payLoadReferencialAmount}}</cbc:Amount>
		      </cac:DeliveryTerms>
		    </cac:Delivery>
		  </Invoice>';
    }

    public static function SummaryBase()
    {
    	return
    	  '<?xml version="1.0" encoding="UTF-8"?>
			<SummaryDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
			  <ext:UBLExtensions>
			    <ext:UBLExtension>
			      <ext:ExtensionContent>
			      </ext:ExtensionContent>
			    </ext:UBLExtension>
			  </ext:UBLExtensions>
			  <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
			  <cbc:CustomizationID>1.1</cbc:CustomizationID>
			  <cbc:ID>RC-{{idDate}}-{{number}}</cbc:ID>
			  <cbc:ReferenceDate>{{referenceDate}}</cbc:ReferenceDate>
			  <cbc:IssueDate>{{issueDate}}</cbc:IssueDate>
			  <cac:Signature>
			    <cbc:ID>{{supplierRuc}}</cbc:ID>
			    <cbc:Note>{{defaultUrl}}</cbc:Note>
			    <cac:SignatoryParty>
			      <cac:PartyIdentification>
			        <cbc:ID>{{supplierRuc}}</cbc:ID>
			      </cac:PartyIdentification>
			      <cac:PartyName>
			        <cbc:Name>{{supplierName}}</cbc:Name>
			      </cac:PartyName>
			    </cac:SignatoryParty>
			    <cac:DigitalSignatureAttachment>
			      <cac:ExternalReference>
			        <cbc:URI>{{supplierRuc}}</cbc:URI>
			      </cac:ExternalReference>
			    </cac:DigitalSignatureAttachment>
			  </cac:Signature>
			  <cac:AccountingSupplierParty>
			    <cbc:CustomerAssignedAccountID>{{supplierRuc}}</cbc:CustomerAssignedAccountID>
				<cbc:AdditionalAccountID>{{supplierDocumentType}}</cbc:AdditionalAccountID>
				<cac:Party>
			      <cac:PartyLegalEntity>
			        <cbc:RegistrationName>{{supplierName}}</cbc:RegistrationName>
			      </cac:PartyLegalEntity>
			    </cac:Party>
			  </cac:AccountingSupplierParty>
			</SummaryDocuments>';
    }

    public static function SummaryInvoice()
    {
    	return
    	  '<SummaryDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	    	  <sac:SummaryDocumentsLine>
			    <cbc:LineID>{{invoiceCorrelative}}</cbc:LineID>
			    <cbc:DocumentTypeCode>{{documentTypeCode}}</cbc:DocumentTypeCode>
			    <cbc:ID>{{serie}}-{{number}}</cbc:ID>
			    <cac:AccountingCustomerParty>
			      <cbc:CustomerAssignedAccountID>{{customerDocument}}</cbc:CustomerAssignedAccountID>
			      <cbc:AdditionalAccountID>{{customerDocumentType}}</cbc:AdditionalAccountID>
			    </cac:AccountingCustomerParty>
			    <cac:Status>
			      <cbc:ConditionCode>{{statusCode}}</cbc:ConditionCode>
			    </cac:Status>
			    <sac:TotalAmount currencyID="{{codigoMoneda}}">{{totalSaleAmount}}</sac:TotalAmount>
			    <sac:BillingPayment>
			      <cbc:PaidAmount currencyID="{{codigoMoneda}}">{{totalTaxableAmount}}</cbc:PaidAmount>
			      <cbc:InstructionID>01</cbc:InstructionID>
			    </sac:BillingPayment>
			    <cac:TaxTotal>
			      <cbc:TaxAmount currencyID="{{codigoMoneda}}">{{igvAmount}}</cbc:TaxAmount>
			      <cac:TaxSubtotal>
			        <cbc:TaxAmount currencyID="{{codigoMoneda}}">{{igvAmount}}</cbc:TaxAmount>
			        <cac:TaxCategory>
			          <cac:TaxScheme>
			            <cbc:ID>1000</cbc:ID>
			            <cbc:Name>IGV</cbc:Name>
			            <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
			          </cac:TaxScheme>
			        </cac:TaxCategory>
			      </cac:TaxSubtotal>
			    </cac:TaxTotal>
			  </sac:SummaryDocumentsLine>
			</SummaryDocuments>';
    }

    public static function SummaryInvoicePayment()
    {
    	return
    	  '<SummaryDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	    	  	<sac:BillingPayment>
			      <cbc:PaidAmount currencyID="{{codigoMoneda}}">{{totalAmount}}</cbc:PaidAmount>
			      <cbc:InstructionID>{{instructionId}}</cbc:InstructionID>
			    </sac:BillingPayment>
			</SummaryDocuments>';
    }

    public static function SummaryDocumentReference()
    {
    	return
    	  '<SummaryDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	    	  <cac:BillingReference>
				<cac:InvoiceDocumentReference>
				  <cbc:ID>{{serie}}-{{number}}</cbc:ID>
				  <cbc:DocumentTypeCode>03</cbc:DocumentTypeCode>
				</cac:InvoiceDocumentReference>
			  </cac:BillingReference>
			</SummaryDocuments>';
    }

    public static function SummaryInvoicePerception()
    {
    	return
	    	'<SummaryDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	    	  <sac:SUNATPerceptionSummaryDocumentReference>
				<sac:SUNATPerceptionSystemCode>{{perceptionSystemCode}}</sac:SUNATPerceptionSystemCode>
				<sac:SUNATPerceptionPercent>{{perceptionPercent}}</sac:SUNATPerceptionPercent>
				<cbc:TotalInvoiceAmount currencyID="{{codigoMoneda}}">{{perceptionAmount}}</cbc:TotalInvoiceAmount>
				<sac:SUNATTotalCashed currencyID="{{codigoMoneda}}">{{totalAmountWithPerception}}</sac:SUNATTotalCashed>
				<cbc:TaxableAmount currencyID="{{codigoMoneda}}">{{perceptionTaxableAmount}}</cbc:TaxableAmount>
			  </sac:SUNATPerceptionSummaryDocumentReference>
			</SummaryDocuments>';
    }

    public static function SummaryInvoiceTax()
    {
    	return
    	  '<SummaryDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	    	  	<cac:TaxTotal>
			      <cbc:TaxAmount currencyID="{{codigoMoneda}}">{{taxAmount}}</cbc:TaxAmount>
			      <cac:TaxSubtotal>
			        <cbc:TaxAmount currencyID="{{codigoMoneda}}">{{taxAmount}}</cbc:TaxAmount>
			        <cac:TaxCategory>
			          <cac:TaxScheme>
			            <cbc:ID>{{taxId}}</cbc:ID>
			            <cbc:Name>{{taxName}}</cbc:Name>
			            <cbc:TaxTypeCode>{{taxTypeCode}}</cbc:TaxTypeCode>
			          </cac:TaxScheme>
			        </cac:TaxCategory>
			      </cac:TaxSubtotal>
			    </cac:TaxTotal>
			</SummaryDocuments>';
    }

    public static function CreditNoteBase()
    {
    	return
    		'<?xml version="1.0" encoding="UTF-8"?>
			<CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
			  <ext:UBLExtensions>
			    <ext:UBLExtension>
			      <ext:ExtensionContent>
			      </ext:ExtensionContent>
				</ext:UBLExtension>
			  </ext:UBLExtensions>
			  <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
			  <cbc:CustomizationID>2.0</cbc:CustomizationID>
			  <cbc:ID>{{serie}}-{{number}}</cbc:ID>
			  <cbc:IssueDate>{{issueDate}}</cbc:IssueDate>
			  <cbc:IssueTime>{{issueTime}}</cbc:IssueTime>
			  <cbc:Note languageLocaleID="1000">{{amounInWord}}</cbc:Note>
			  <cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listAgencyName="United Nations Economic Commission for Europe" listName="Currency">{{codigoMoneda}}</cbc:DocumentCurrencyCode>
			  <cac:DiscrepancyResponse>
			    <cbc:ReferenceID>{{serie}}-{{number}}</cbc:ReferenceID>
			    <cbc:ResponseCode>{{creditNoteTypeCode}}</cbc:ResponseCode>
			    <cbc:Description>{{creditNoteTypeDescription}}</cbc:Description>
			  </cac:DiscrepancyResponse>
			  <cac:Signature>
			    <cbc:ID>{{supplierRuc}}</cbc:ID>
			    <cbc:Note>{{defaultUrl}}</cbc:Note>
			    <cac:SignatoryParty>
			      <cac:PartyIdentification>
			        <cbc:ID>{{supplierRuc}}</cbc:ID>
			      </cac:PartyIdentification>
			      <cac:PartyName>
			        <cbc:Name>{{supplierName}}</cbc:Name>
			      </cac:PartyName>
			    </cac:SignatoryParty>
			    <cac:DigitalSignatureAttachment>
			      <cac:ExternalReference>
			        <cbc:URI>{{supplierRuc}}</cbc:URI>
			      </cac:ExternalReference>
			    </cac:DigitalSignatureAttachment>
			  </cac:Signature>
			  <cac:AccountingSupplierParty>
			    <cac:Party>
			      <cac:PartyIdentification>
			        <cbc:ID schemeID="{{supplierDocumentType}}" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">{{supplierRuc}}</cbc:ID>
			      </cac:PartyIdentification>
			      <cac:PartyName>
			        <cbc:Name>{{supplierName}}</cbc:Name>
			      </cac:PartyName>
			      <cac:PartyLegalEntity>
			        <cbc:RegistrationName>{{supplierName}}</cbc:RegistrationName>
			        <cac:RegistrationAddress>
			          <cbc:AddressTypeCode listAgencyName="PE:SUNAT" listName="Establecimientos anexos">0001</cbc:AddressTypeCode>
			        </cac:RegistrationAddress>
			      </cac:PartyLegalEntity>
			    </cac:Party>
			  </cac:AccountingSupplierParty>
			  <cac:AccountingCustomerParty>
			    <cac:Party>
			      <cac:PartyIdentification>
			        <cbc:ID schemeID="{{customerDocumentType}}" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">{{customerDocument}}</cbc:ID>
			      </cac:PartyIdentification>
			      <cac:PartyLegalEntity>
			        <cbc:RegistrationName>{{customerName}}</cbc:RegistrationName>
			      </cac:PartyLegalEntity>
			    </cac:Party>
			  </cac:AccountingCustomerParty>
			  <cac:TaxTotal>
			    <cbc:TaxAmount currencyID="{{codigoMoneda}}">{{totalTaxAmount}}</cbc:TaxAmount>
			  </cac:TaxTotal>
			  <cac:LegalMonetaryTotal>
			    <cbc:LineExtensionAmount currencyID="{{codigoMoneda}}">{{totalBaseAmount}}</cbc:LineExtensionAmount>
			    <cbc:TaxInclusiveAmount currencyID="{{codigoMoneda}}">{{totalSaleAmount}}</cbc:TaxInclusiveAmount>
			    <cbc:AllowanceTotalAmount currencyID="{{codigoMoneda}}">{{totalDiscountAmount}}</cbc:AllowanceTotalAmount>
			    <cbc:ChargeTotalAmount currencyID="{{codigoMoneda}}">{{totalExtraChargeAmount}}</cbc:ChargeTotalAmount>
			    <cbc:PrepaidAmount currencyID="{{codigoMoneda}}">{{totalPrepaidAmount}}</cbc:PrepaidAmount>
			    <cbc:PayableAmount currencyID="{{codigoMoneda}}">{{totalPayableAmount}}</cbc:PayableAmount>
			  </cac:LegalMonetaryTotal>
			</CreditNote>';
    }

    public static function CreditNoteItem()
    {
    	return
    	  '<CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	    	  <cac:CreditNoteLine>
			    <cbc:ID>{{itemCorrelative}}</cbc:ID>
			    <cbc:CreditedQuantity unitCode="{{itemUnitCode}}" unitCodeListID="UN/ECE rec 20" unitCodeListAgencyName="United Nations Economic Commission for Europe">{{itemCuantity}}</cbc:CreditedQuantity>
			    <cbc:LineExtensionAmount currencyID="{{codigoMoneda}}">{{itemFinalBaseAmount}}</cbc:LineExtensionAmount>
			    <cac:PricingReference>
			      <cac:AlternativeConditionPrice>
			        <cbc:PriceAmount currencyID="{{codigoMoneda}}">{{singleItemPrice}}</cbc:PriceAmount>
			        <cbc:PriceTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Precio" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">{{itemTransactionType}}</cbc:PriceTypeCode>
			      </cac:AlternativeConditionPrice>
			    </cac:PricingReference>
			    <cac:TaxTotal>
			      <cbc:TaxAmount currencyID="{{codigoMoneda}}">{{itemTotalTaxAmount}}</cbc:TaxAmount>
			      <cac:TaxSubtotal>
			        <cbc:TaxableAmount currencyID="{{codigoMoneda}}">{{itemIgvTaxableAmount}}</cbc:TaxableAmount>
			        <cbc:TaxAmount currencyID="{{codigoMoneda}}">{{itemTotalIgvAmount}}</cbc:TaxAmount>
			        <cac:TaxCategory>
			          <cbc:Percent>{{itemTaxPercent}}</cbc:Percent>
			          <cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="Afectacion del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">{{itemIgvTaxCode}}</cbc:TaxExemptionReasonCode>
			          <cac:TaxScheme>
			            <cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">{{itemTaxCode}}</cbc:ID>
			            <cbc:Name>{{itemTaxName}}</cbc:Name>
			            <cbc:TaxTypeCode>{{itemTaxNamecode}}</cbc:TaxTypeCode>
			          </cac:TaxScheme>
			        </cac:TaxCategory>
			      </cac:TaxSubtotal>
			    </cac:TaxTotal>
			    <cac:Item>
			      <cbc:Description>{{itemDescription}}</cbc:Description>
			      <cac:CommodityClassification>
			        <cbc:ItemClassificationCode listID="UNSPSC" listAgencyName="GS1 US" listName="Item Classification">{{ItemClassificationCode}}</cbc:ItemClassificationCode>
			      </cac:CommodityClassification>
			    </cac:Item>
			    <cac:Price>
			      <cbc:PriceAmount currencyID="{{codigoMoneda}}">{{singleItemBasePrice}}</cbc:PriceAmount>
			    </cac:Price>
			  </cac:CreditNoteLine>
			</CreditNote>';
    }

    public static function NoteBillingReference()
    {
    	return
    	  '<CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    	  	<cac:BillingReference>
    	  		<cac:InvoiceDocumentReference>
			      <cbc:ID>{{billingReferenceSerie}}-{{billingReferenceNumber}}</cbc:ID>
			      <cbc:DocumentTypeCode>{{billingReferenceTypeCode}}</cbc:DocumentTypeCode>
			    </cac:InvoiceDocumentReference>
    	  	</cac:BillingReference>
    	  </CreditNote>';
    }

    public static function DebitNoteBase()
    {
    	return
    		'<?xml version="1.0" encoding="UTF-8"?>
			<DebitNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
			  <ext:UBLExtensions>
			    <ext:UBLExtension>
			      <ext:ExtensionContent>
			      </ext:ExtensionContent>
				</ext:UBLExtension>
			  </ext:UBLExtensions>
			  <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
			  <cbc:CustomizationID>2.0</cbc:CustomizationID>
			  <cbc:ID>{{serie}}-{{number}}</cbc:ID>
			  <cbc:IssueDate>{{issueDate}}</cbc:IssueDate>
			  <cbc:IssueTime>{{issueTime}}</cbc:IssueTime>
			  <cbc:Note languageLocaleID="1000">{{amounInWord}}</cbc:Note>
			  <cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listAgencyName="United Nations Economic Commission for Europe" listName="Currency">{{codigoMoneda}}</cbc:DocumentCurrencyCode>
			  <cac:DiscrepancyResponse>
			    <cbc:ReferenceID>{{serie}}-{{number}}</cbc:ReferenceID>
			    <cbc:ResponseCode>{{debitNoteTypeCode}}</cbc:ResponseCode>
			    <cbc:Description>{{debitNoteTypeDescription}}</cbc:Description>
			  </cac:DiscrepancyResponse>
			  <cac:Signature>
			    <cbc:ID>{{supplierRuc}}</cbc:ID>
			    <cbc:Note>{{defaultUrl}}</cbc:Note>
			    <cac:SignatoryParty>
			      <cac:PartyIdentification>
			        <cbc:ID>{{supplierRuc}}</cbc:ID>
			      </cac:PartyIdentification>
			      <cac:PartyName>
			        <cbc:Name>{{supplierName}}</cbc:Name>
			      </cac:PartyName>
			    </cac:SignatoryParty>
			    <cac:DigitalSignatureAttachment>
			      <cac:ExternalReference>
			        <cbc:URI>{{supplierRuc}}</cbc:URI>
			      </cac:ExternalReference>
			    </cac:DigitalSignatureAttachment>
			  </cac:Signature>
			  <cac:AccountingSupplierParty>
			    <cac:Party>
			      <cac:PartyIdentification>
			        <cbc:ID schemeID="{{supplierDocumentType}}" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">{{supplierRuc}}</cbc:ID>
			      </cac:PartyIdentification>
			      <cac:PartyName>
			        <cbc:Name>{{supplierName}}</cbc:Name>
			      </cac:PartyName>
			      <cac:PartyLegalEntity>
			        <cbc:RegistrationName>{{supplierName}}</cbc:RegistrationName>
			        <cac:RegistrationAddress>
			          <cbc:AddressTypeCode listAgencyName="PE:SUNAT" listName="Establecimientos anexos">0001</cbc:AddressTypeCode>
			        </cac:RegistrationAddress>
			      </cac:PartyLegalEntity>
			    </cac:Party>
			  </cac:AccountingSupplierParty>
			  <cac:AccountingCustomerParty>
			    <cac:Party>
			      <cac:PartyIdentification>
			        <cbc:ID schemeID="{{customerDocumentType}}" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">{{customerDocument}}</cbc:ID>
			      </cac:PartyIdentification>
			      <cac:PartyLegalEntity>
			        <cbc:RegistrationName>{{customerName}}</cbc:RegistrationName>
			      </cac:PartyLegalEntity>
			    </cac:Party>
			  </cac:AccountingCustomerParty>
			  <cac:TaxTotal>
			    <cbc:TaxAmount currencyID="{{codigoMoneda}}">{{totalTaxAmount}}</cbc:TaxAmount>
			  </cac:TaxTotal>
			  <cac:RequestedMonetaryTotal>
			    <cbc:LineExtensionAmount currencyID="{{codigoMoneda}}">{{totalBaseAmount}}</cbc:LineExtensionAmount>
			    <cbc:TaxInclusiveAmount currencyID="{{codigoMoneda}}">{{totalSaleAmount}}</cbc:TaxInclusiveAmount>
			    <cbc:AllowanceTotalAmount currencyID="{{codigoMoneda}}">{{totalDiscountAmount}}</cbc:AllowanceTotalAmount>
			    <cbc:ChargeTotalAmount currencyID="{{codigoMoneda}}">{{totalExtraChargeAmount}}</cbc:ChargeTotalAmount>
			    <cbc:PrepaidAmount currencyID="{{codigoMoneda}}">{{totalPrepaidAmount}}</cbc:PrepaidAmount>
			    <cbc:PayableAmount currencyID="{{codigoMoneda}}">{{totalPayableAmount}}</cbc:PayableAmount>
			  </cac:RequestedMonetaryTotal>
			</DebitNote>';
    }

    public static function DebitNoteItem()
    {
    	return
    	  '<DebitNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	    	  <cac:DebitNoteLine>
			    <cbc:ID>{{itemCorrelative}}</cbc:ID>
			    <cbc:DebitedQuantity unitCode="{{itemUnitCode}}" unitCodeListID="UN/ECE rec 20" unitCodeListAgencyName="United Nations Economic Commission for Europe">{{itemCuantity}}</cbc:DebitedQuantity>
			    <cbc:LineExtensionAmount currencyID="{{codigoMoneda}}">{{itemFinalBaseAmount}}</cbc:LineExtensionAmount>
			    <cac:PricingReference>
			      <cac:AlternativeConditionPrice>
			        <cbc:PriceAmount currencyID="{{codigoMoneda}}">{{singleItemPrice}}</cbc:PriceAmount>
			        <cbc:PriceTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Precio" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">{{itemTransactionType}}</cbc:PriceTypeCode>
			      </cac:AlternativeConditionPrice>
			    </cac:PricingReference>
			    <cac:TaxTotal>
			      <cbc:TaxAmount currencyID="{{codigoMoneda}}">{{itemTotalTaxAmount}}</cbc:TaxAmount>
			      <cac:TaxSubtotal>
			        <cbc:TaxableAmount currencyID="{{codigoMoneda}}">{{itemIgvTaxableAmount}}</cbc:TaxableAmount>
			        <cbc:TaxAmount currencyID="{{codigoMoneda}}">{{itemTotalIgvAmount}}</cbc:TaxAmount>
			        <cac:TaxCategory>
			          <cbc:Percent>{{itemTaxPercent}}</cbc:Percent>
			          <cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="Afectacion del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">{{itemIgvTaxCode}}</cbc:TaxExemptionReasonCode>
			          <cac:TaxScheme>
			            <cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">{{itemTaxCode}}</cbc:ID>
			            <cbc:Name>{{itemTaxName}}</cbc:Name>
			            <cbc:TaxTypeCode>{{itemTaxNamecode}}</cbc:TaxTypeCode>
			          </cac:TaxScheme>
			        </cac:TaxCategory>
			      </cac:TaxSubtotal>
			    </cac:TaxTotal>
			    <cac:Item>
			      <cbc:Description>{{itemDescription}}</cbc:Description>
			      <cac:CommodityClassification>
			        <cbc:ItemClassificationCode listID="UNSPSC" listAgencyName="GS1 US" listName="Item Classification">{{ItemClassificationCode}}</cbc:ItemClassificationCode>
			      </cac:CommodityClassification>
			    </cac:Item>
			    <cac:Price>
			      <cbc:PriceAmount currencyID="{{codigoMoneda}}">{{singleItemBasePrice}}</cbc:PriceAmount>
			    </cac:Price>
			  </cac:DebitNoteLine>
			</DebitNote>';
    }

    public static function VoidCommunicationBase()
    {
    	return
    		'<?xml version="1.0" encoding="UTF-8"?>
			<VoidedDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:VoidedDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
			  <ext:UBLExtensions>
			    <ext:UBLExtension>
			      <ext:ExtensionContent>
			      </ext:ExtensionContent>
			    </ext:UBLExtension>
			  </ext:UBLExtensions>
			  <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
			  <cbc:CustomizationID>1.0</cbc:CustomizationID>
			  <cbc:ID>RA-{{idDate}}-{{correlativeNumber}}</cbc:ID>
			  <cbc:ReferenceDate>{{referenceDate}}</cbc:ReferenceDate>
			  <cbc:IssueDate>{{issueDate}}</cbc:IssueDate>
			  <cac:Signature>
				<cbc:ID>{{supplierRuc}}</cbc:ID>
				<cbc:Note>{{defaultUrl}}</cbc:Note>
				<cac:SignatoryParty>
				  <cac:PartyIdentification>
					<cbc:ID>{{supplierRuc}}</cbc:ID>
				  </cac:PartyIdentification>
				  <cac:PartyName>
					<cbc:Name>{{supplierName}}</cbc:Name>
				  </cac:PartyName>
				</cac:SignatoryParty>
				<cac:DigitalSignatureAttachment>
				  <cac:ExternalReference>
					<cbc:URI>{{supplierRuc}}</cbc:URI>
				  </cac:ExternalReference>
				</cac:DigitalSignatureAttachment>
			  </cac:Signature>
			  <cac:AccountingSupplierParty>
			    <cbc:CustomerAssignedAccountID>{{supplierRuc}}</cbc:CustomerAssignedAccountID>
			    <cbc:AdditionalAccountID>{{supplierDocumentType}}</cbc:AdditionalAccountID>
			    <cac:Party>
			      <cac:PartyLegalEntity>
			        <cbc:RegistrationName>{{supplierName}}</cbc:RegistrationName>
			      </cac:PartyLegalEntity>
			    </cac:Party>
			  </cac:AccountingSupplierParty>
			  <sac:VoidedDocumentsLine>
			    <cbc:LineID>1</cbc:LineID>
			    <cbc:DocumentTypeCode>{{documentTypeCode}}</cbc:DocumentTypeCode>
			    <sac:DocumentSerialID>{{documentSerie}}</sac:DocumentSerialID>
			    <sac:DocumentNumberID>{{documentNumber}}</sac:DocumentNumberID>
			    <sac:VoidReasonDescription>{{reason}}</sac:VoidReasonDescription>
			  </sac:VoidedDocumentsLine>
			</VoidedDocuments>';
    }

    public static function ReferalGuideBase()
    {
    	return
    	  '<?xml version="1.0"?>
    	    <DespatchAdvice xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2">
			  <ext:UBLExtensions>
			    <ext:UBLExtension>
			      <ext:ExtensionContent>
				  </ext:ExtensionContent>
			    </ext:UBLExtension>
			  </ext:UBLExtensions>
			  <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
			  <cbc:CustomizationID>1.0</cbc:CustomizationID>
			  <cbc:ID>{{serie}}-{{number}}</cbc:ID>
			  <cbc:IssueDate>{{issueDate}}</cbc:IssueDate>
			  <cbc:IssueTime>{{issueTime}}</cbc:IssueTime>
			  <cbc:DespatchAdviceTypeCode>{{invoiceTypeCode}}</cbc:DespatchAdviceTypeCode>
			  <cac:Signature>
				<cbc:ID>{{supplierRuc}}</cbc:ID>
				<cbc:Note>{{defaultUrl}}</cbc:Note>
				<cac:SignatoryParty>
				  <cac:PartyIdentification>
					<cbc:ID>{{supplierRuc}}</cbc:ID>
				  </cac:PartyIdentification>
				  <cac:PartyName>
					<cbc:Name>{{supplierName}}</cbc:Name>
				  </cac:PartyName>
				</cac:SignatoryParty>
				<cac:DigitalSignatureAttachment>
				  <cac:ExternalReference>
					<cbc:URI>{{supplierRuc}}</cbc:URI>
				  </cac:ExternalReference>
				</cac:DigitalSignatureAttachment>
			  </cac:Signature>
			  <cac:DespatchSupplierParty>
			    <cbc:CustomerAssignedAccountID schemeID="{{supplierDocumentType}}">{{supplierRuc}}</cbc:CustomerAssignedAccountID>
			    <cac:Party>
			      <cac:PartyLegalEntity>
			        <cbc:RegistrationName>{{supplierName}}</cbc:RegistrationName>
			      </cac:PartyLegalEntity>
			    </cac:Party>
			  </cac:DespatchSupplierParty>
			  <cac:DeliveryCustomerParty>
			    <cbc:CustomerAssignedAccountID schemeID="{{customerDocumentType}}">{{customerDocument}}</cbc:CustomerAssignedAccountID>
			    <cac:Party>
			      <cac:PartyLegalEntity>
			        <cbc:RegistrationName>{{customerName}}</cbc:RegistrationName>
			      </cac:PartyLegalEntity>
			    </cac:Party>
			  </cac:DeliveryCustomerParty>
			  <cac:Shipment>
			    <cbc:ID>1</cbc:ID>
			    <cbc:HandlingCode>{{transferReasonCode}}</cbc:HandlingCode>
			    <cbc:Information>{{transferReason}}</cbc:Information>
			    <cbc:GrossWeightMeasure unitCode="{{grossWeightMeasure}}">{{grossWeight}}</cbc:GrossWeightMeasure>
			    <cbc:TotalTransportHandlingUnitQuantity>{{packageQuantity}}</cbc:TotalTransportHandlingUnitQuantity>
			    <cac:ShipmentStage>
			      <cbc:ID>1</cbc:ID>
			      <cbc:TransportModeCode>{{transferMethodCode}}</cbc:TransportModeCode>
			      <cac:TransitPeriod>
			        <cbc:StartDate>{{referralDate}}</cbc:StartDate>
			      </cac:TransitPeriod>
			      <cac:CarrierParty>
			        <cac:PartyIdentification>
			          <cbc:ID schemeID="{{carrierDocumentType}}">{{carrierRuc}}</cbc:ID>
			        </cac:PartyIdentification>
			        <cac:PartyName>
			          <cbc:Name>{{carrierName}}</cbc:Name>
			        </cac:PartyName>
			      </cac:CarrierParty>
				  <cac:TransportMeans>
			        <cac:RoadTransport>
			          <cbc:LicensePlateID>{{licensePlate}}</cbc:LicensePlateID>
			        </cac:RoadTransport>
			      </cac:TransportMeans>
			      <cac:DriverPerson>
			        <cbc:ID schemeID="{{driverDocumentType}}">{{driverDocument}}</cbc:ID>
			      </cac:DriverPerson>
			    </cac:ShipmentStage>
			    <cac:Delivery>
			      <cac:DeliveryAddress>
			        <cbc:ID>{{deliveryAdressCode}}</cbc:ID>
			        <cbc:StreetName>{{deliveryAdress}}</cbc:StreetName>
			      </cac:DeliveryAddress>
			    </cac:Delivery>
			    <cac:TransportHandlingUnit>
			      <cbc:ID>{{licensePlate}}</cbc:ID>
			    </cac:TransportHandlingUnit>
			    <cac:OriginAddress>
			      <cbc:ID>{{originAdressCode}}</cbc:ID>
			      <cbc:StreetName>{{originAdress}}</cbc:StreetName>
			    </cac:OriginAddress>
			  </cac:Shipment>
			</DespatchAdvice>';
    }

    public static function ReferalGuideItem()
    {
    	return
    	  '<DespatchAdvice xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2">
			  <cac:DespatchLine>
			    <cbc:ID>{{itemCorrelative}}</cbc:ID>
			    <cbc:DeliveredQuantity unitCode="{{itemUnitCode}}">{{itemCuantity}}</cbc:DeliveredQuantity>
			    <cac:OrderLineReference>
			      <cbc:LineID>{{itemCorrelative}}</cbc:LineID>
			    </cac:OrderLineReference>
			    <cac:Item>
			      <cbc:Name>{{itemDescription}}</cbc:Name>
			      <cac:SellersItemIdentification>
			        <cbc:ID>{{itemCode}}</cbc:ID>
			      </cac:SellersItemIdentification>
			    </cac:Item>
			  </cac:DespatchLine>
			</DespatchAdvice>';
    }
}
?>
