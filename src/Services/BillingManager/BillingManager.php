<?php

require_once __DIR__ . "/XmlTemplate.php";
require_once __DIR__ . "/DigestGenerator.php";
require_once __DIR__ . "/SignatureGenerator.php";
require_once __DIR__ . "/SignatureGenerator.php";

require_once MODEL_PATH . "/Sunat/SunatXml.php";
require_once MODEL_PATH . "/Sunat/SunatCommunication.php";
require_once MODEL_PATH . "/Sunat/SunatResponse.php";
require_once MODEL_PATH . "/Sunat/SunatSummaryResponse.php";

class BillingManager
{
    protected $connection;

    private $sunatXmlModel;
    private $sunatCommunicationModel;
    private $sunatResponseModel;
    private $sunatSummaryResponseModel;
    private $environment;

    public function __construct($connection,$environment)
    {
        $this->connection = $connection;
        $this->environment = $environment;
        $this->sunatXmlModel = new SunatXml($this->connection);
        $this->sunatCommunicationModel = new SunatCommunication($this->connection);
        $this->sunatResponseModel = new SunatResponse($this->connection);
        $this->sunatSummaryResponseModel = new SunatSummaryResponse($this->connection);
    }

    public function SendInvoice($referenceId, $invoice, $userId, $send = true)
    {
        $res = new Result();
        $res->send = false;
        try {
            if (!($invoice['invoiceTypeCode'] == '01' || $invoice['invoiceTypeCode'] == '03')) {
                throw new Exception('Wrong invoice Type!');
            }

            if (!(count($invoice['itemList']) > 0)) {
                throw new Exception('There is no items.');
            }

            $folderPath = $this->FolderPathValidation($invoice['supplierRuc']);
            $fileName = $invoice['supplierRuc'] . '-' . $invoice['invoiceTypeCode'] . '-' . $invoice['serie'] . '-' . $invoice['number'] .'.xml';

            $xmlGeneratorResult = $this->SaveInvoice($folderPath, $fileName, $referenceId, $invoice, 1, $userId);
            $res->message = $xmlGeneratorResult->message;
            $res->success = $xmlGeneratorResult->success;
            if($xmlGeneratorResult->success === true){
              $res->xmlPath = str_replace(ROOT_DIR,'',$folderPath . $fileName);
              $res->digestValue = $xmlGeneratorResult->digestValue;
            }

            if ($xmlGeneratorResult->success === true && $send === true) {
                $sunatCommunicationId = $this->sunatCommunicationModel->Insert(1, $referenceId, $userId);
                $res->send = true;

                $sunatResult = $this->SendDocument(1, $folderPath, $fileName);
                $res->sunatComunicationSuccess = $sunatResult->success;
                $res->sunatCommuniationMessage = $sunatResult->message;
                if ($sunatResult->success == true) {
                    $readerResult = $this->ReadSunatAnswer($folderPath, $fileName);
                    $res->readerSuccess = $readerResult->success;
                    $res->readerMessage = $readerResult->message;
                    $res->sunatResponseCode = $readerResult->sunatResponseCode;
                    $res->sunatDescription = $readerResult->sunatDescription;
                    $res->cdrPath = $readerResult->cdrPath;

                    $this->sunatResponseModel->Insert($sunatCommunicationId, $referenceId, $userId, $res);
                } else {
                    $res->readerSuccess = false;
                    $res->readerMessage = '';
                }
            }
        } catch (Exception $e) {
            $res->message = $e->getMessage()."\n\n".$e->getTraceAsString();
        }

        return $res;
    }

    public function SendCreditNote($referenceId, $creditNote, $userId, $send = true)
    {
        $res = new Result();
      $res->send = false;
        try {
            if (!(count($creditNote['itemList']) > 0)) {
                throw new Exception('There is no items.');
            }

            $folderPath = $this->FolderPathValidation($creditNote['supplierRuc']);
            $fileName = $creditNote['supplierRuc'] . '-07-' . $creditNote['serie'] . '-' . $creditNote['number'] .'.xml';

            $xmlGeneratorResult = $this->SaveCreditNote($folderPath, $fileName, $referenceId, $creditNote, 4, $userId);
            $res->message = $xmlGeneratorResult->message;
            $res->success = $xmlGeneratorResult->success;
            if($xmlGeneratorResult->success === true){
              $res->xmlPath = str_replace(ROOT_DIR,'',$folderPath . $fileName);
              $res->digestValue = $xmlGeneratorResult->digestValue;
            }


          if ($xmlGeneratorResult->success === true && $send === true) {
                $sunatCommunicationId = $this->sunatCommunicationModel->Insert(1, $referenceId, $userId);
                $res->send = true;

                $sunatResult = $this->SendDocument(1, $folderPath, $fileName);
                $res->sunatComunicationSuccess = $sunatResult->success;
                $res->sunatCommuniationMessage = $sunatResult->message;
                if ($sunatResult->success == true) {
                    $readerResult = $this->ReadSunatAnswer($folderPath, $fileName);
                    $res->readerSuccess = $readerResult->success;
                    $res->readerMessage = $readerResult->message;
                    $res->sunatResponseCode = $readerResult->sunatResponseCode;
                    $res->sunatDescription = $readerResult->sunatDescription;
                    $res->cdrPath = $readerResult->cdrPath;

                    $this->sunatResponseModel->Insert($sunatCommunicationId, $referenceId, $userId, $res);
                }
                else{
                    $res->readerSuccess = false;
                    $res->readerMessage = '';
                }

                $res->success = true;
                $res->digestValue = $xmlGeneratorResult->digestValue;
            }
        } catch (Exception $e) {
            $res->message = $e->getMessage()."\n\n".$e->getTraceAsString();
        }

        return $res;
    }

    public function SendDebitNote($referenceId, $debitNote, $userId, $send = true)
    {
        $res = new Result();
      $res->send = false;
        try {
            if (!(count($debitNote['itemList']) > 0)) {
                throw new Exception('There is no items.');
            }

            $folderPath = $this->FolderPathValidation($debitNote['supplierRuc']);
            $fileName = $debitNote['supplierRuc'] . '-08-' . $debitNote['serie'] . '-' . $debitNote['number'] .'.xml';

            $xmlGeneratorResult = $this->SaveDebitNote($folderPath, $fileName, $referenceId, $debitNote, 5, $userId);
            $res->message = $xmlGeneratorResult->message;
            $res->success = $xmlGeneratorResult->success;
            if($xmlGeneratorResult->success === true){
              $res->xmlPath = str_replace(ROOT_DIR,'',$folderPath . $fileName);
              $res->digestValue = $xmlGeneratorResult->digestValue;
            }

            if ($xmlGeneratorResult->success === true && $send === true) {
                $sunatCommunicationId = $this->sunatCommunicationModel->Insert(1, $referenceId, $userId);
                $res->send = true;

                $sunatResult = $this->SendDocument(1, $folderPath, $fileName);
                $res->sunatComunicationSuccess = $sunatResult->success;
                $res->sunatCommuniationMessage = $sunatResult->message;
                if ($sunatResult->success == true) {
                    $readerResult = $this->ReadSunatAnswer($folderPath, $fileName);
                    $res->readerSuccess = $readerResult->success;
                    $res->readerMessage = $readerResult->message;
                    $res->sunatResponseCode = $readerResult->sunatResponseCode;
                    $res->sunatDescription = $readerResult->sunatDescription;
                    $res->cdrPath = $readerResult->cdrPath;

                    $this->sunatResponseModel->Insert($sunatCommunicationId, $referenceId, $userId, $res);
                }
                else{
                    $res->readerSuccess = false;
                    $res->readerMessage = '';
                }

                $res->success = true;
                $res->digestValue = $xmlGeneratorResult->digestValue;
            }
        } catch (Exception $e) {
            $res->message = $e->getMessage()."\n\n".$e->getTraceAsString();
        }

        return $res;
    }

    public function SendAvoidance($referenceId, $avoidance, $userId)
    {
        $res = new Result();
        try {
            $folderPath = $this->FolderPathValidation($avoidance['supplierRuc']);
            $fileName = $avoidance['supplierRuc'] . '-RA-' . str_replace('-', '', $avoidance['issueDate']) . '-' . $avoidance['correlativeNumber'] .'.xml';

            $xmlGeneratorResult = $this->SaveAvoidance($folderPath, $fileName, $referenceId, $avoidance, 6, $userId);

            if ($xmlGeneratorResult->success == true) {
                $sunatCommunicationId = $this->sunatCommunicationModel->Insert(2, $referenceId, $userId);

                $sunatResult = $this->SendDocument(2, $folderPath, $fileName);
                $res->sunatComunicationSuccess = $sunatResult->success;
                $res->sunatCommuniationError = $sunatResult->message;
                $res->ticket = $sunatResult->ticket->ticket;
                if ($sunatResult->success == true) {
                    $this->sunatSummaryResponseModel->Insert($sunatCommunicationId, $referenceId, $userId, $res);
                }

                $res->success = true;
            }
            else{
                return $xmlGeneratorResult;
            }
        } catch (Exception $e) {
            $res->message = $e->getMessage()."\n\n".$e->getTraceAsString();
        }

        return $res;
    }

    public function SendDailySummary($referenceId, $summary, $userId)
    {
        $res = new Result();
        try {
            $folderPath = $this->FolderPathValidation($summary['supplierRuc']);

            $fileName = $summary['supplierRuc'] . '-RC-' . str_replace('-', '', $summary['issueDate']) . '-' . $summary['correlative'] .'.xml';

            $xmlGeneratorResult = $this->SaveSummary($folderPath, $fileName, $referenceId, $summary, 3, $userId);

            if ($xmlGeneratorResult->success == true) {
                $sunatCommunicationId = $this->sunatCommunicationModel->Insert(2, $referenceId, $userId);

                $sunatResult = $this->SendDocument(2, $folderPath, $fileName);
                $res->sunatComunicationSuccess = $sunatResult->success;
                $res->sunatCommuniationError = $sunatResult->message;
                if ($sunatResult->success == true) {
                    $res->ticket = $sunatResult->ticket->ticket;
                    $this->sunatSummaryResponseModel->Insert($sunatCommunicationId, $referenceId, $userId, $res);
                }

                $res->success = true;
            }
            else{
                return $xmlGeneratorResult;
            }
        } catch (Exception $e) {
            $res->message = $e->getMessage()."\n\n".$e->getTraceAsString();
        }

        return $res;
    }

    public function SendReferralGuide($referenceId, $referralGuide, $userId)
    {
        $res = new Result();
        try {
            if (!(count($referralGuide['itemList']) > 0)) {
                throw new Exception('There is no items.');
            }

            if (!(count($referralGuide['invoiceTypeCode']) != '09')) {
                throw new Exception('Wrong invoiceTypeCode!');
            }

            $folderPath = $this->FolderPathValidation($referralGuide['supplierRuc']);
            $fileName = $referralGuide['supplierRuc'] . '-' . $referralGuide['invoiceTypeCode'] . '-' . $referralGuide['serie'] . '-' . $referralGuide['number'] .'.xml';

            $xmlGeneratorResult = $this->SaveReferralGuide($folderPath, $fileName, $referenceId, $referralGuide, 7, $userId);

            if ($xmlGeneratorResult->success == true) {
                $sunatCommunicationId = $this->sunatCommunicationModel->Insert(3, $referenceId, $userId);

                $sunatResult = $this->SendDocument(3, $folderPath, $fileName);
                $res->sunatComunicationSuccess = $sunatResult->success;
                $res->sunatCommuniationError = $sunatResult->message;
                if ($sunatResult->success == true) {
                    $readerResult = $this->ReadSunatAnswer($folderPath, $fileName);
                    $res->readerSuccess = $readerResult->success;
                    $res->readerError = $readerResult->message;
                    $res->sunatResponseCode = $readerResult->sunatResponseCode;
                    $res->sunatDescription = $readerResult->sunatDescription;

                    $this->sunatResponseModel->Insert($sunatCommunicationId, $referenceId, $userId, $res);
                }
                else{
                    $res->readerSuccess = false;
                    $res->readerError = '';
                }

                $res->success = true;
                $res->digestValue = $xmlGeneratorResult->digestValue;
            }
            else{
                return $xmlGeneratorResult;
            }
        } catch (Exception $e) {
            $res->message = $e->getMessage()."\n\n".$e->getTraceAsString();
        }

        return $res;
        $fileName = $request->ruc . '-' . $request->type . '-' . $request->serie . '-' . $request->number;
    }

    private function SaveSummary($folderPath, $fileName, $referenceId, $summary, $xmlTypeId, $userId)
    {
        $res = new Result();
        try {
            $summaryTemplate = XmlTemplate::SummaryBase();

            $variableList = array();

            $variableList['idDate'] = str_replace('-', '', $summary['issueDate']);
            $variableList['issueDate'] = $summary['issueDate'];
            $variableList['number'] = $summary['correlative'];
            $variableList['referenceDate'] = $summary['referenceDate'];

            $variableList['supplierRuc'] = $summary['supplierRuc'];
            $variableList['defaultUrl'] = $summary['defaultUrl'];
            $variableList['supplierName'] = $summary['supplierName'];
            $variableList['supplierDocumentType'] = $summary['supplierDocumentType'];

            foreach($variableList as $key => $value)
            {
                $summaryTemplate = str_replace('{{'.$key.'}}', $value, $summaryTemplate);
            }

            $xml = new DOMDocument();
            $xml->preserveWhiteSpace = false;
            $xml->loadXML($summaryTemplate);
            $summaryNode = $xml->getElementsByTagName('SummaryDocuments')->item(0);

            $invoiceCorrelative = 1;
            foreach ($summary['invoiceList'] as $invoice) {
                $invoiceTemplate = XmlTemplate::SummaryInvoice();

                $invoiceVariableList = array();
                $invoiceVariableList['invoiceCorrelative'] = $invoiceCorrelative++;
                $invoiceVariableList['documentTypeCode'] = $invoice['documentTypeCode'];
                $invoiceVariableList['serie'] = $invoice['serie'];
                $invoiceVariableList['number'] = $invoice['number'];
                $invoiceVariableList['customerDocument'] = $invoice['customerDocument'];
                $invoiceVariableList['customerDocumentType'] = $invoice['customerDocumentType'];
                $invoiceVariableList['statusCode'] = $invoice['statusCode'];
                $invoiceVariableList['totalSaleAmount'] = $invoice['totalSaleAmount'];
                $invoiceVariableList['totalTaxableAmount'] = $invoice['totalTaxableAmount'];
                $invoiceVariableList['igvAmount'] = $invoice['igvAmount'];
                $invoiceVariableList['codigoMoneda'] = $invoice['codigoMoneda'];
                foreach($invoiceVariableList as $key => $value)
                {
                    $invoiceTemplate = str_replace('{{'.$key.'}}', $value, $invoiceTemplate);
                }

                $invoiceXml = new DOMDocument();
                $invoiceXml->preserveWhiteSpace = false;
                $invoiceXml->loadXML($invoiceTemplate);
                $invoiceNode = $invoiceXml->getElementsByTagName('SummaryDocumentsLine')->item(0);
                $taxTotalNode = $invoiceXml->getElementsByTagName('TaxTotal')->item(0);
                $statusNode = $invoiceXml->getElementsByTagName('Status')->item(0);

                if ($invoice['documentTypeCode'] == '07' || $invoice['documentTypeCode'] == '08') {
                    $documentReferenceTemplate = XmlTemplate::SummaryDocumentReference();

                    $documentReferenceVariableList = array();
                    $documentReferenceVariableList['serie'] = $invoice['referencedInvoiceSerie'];
                    $documentReferenceVariableList['number'] = $invoice['referencedInvoiceNumber'];

                    foreach($documentReferenceVariableList as $key => $value)
                    {
                        $documentReferenceTemplate = str_replace('{{'.$key.'}}', $value, $documentReferenceTemplate);
                    }

                    $documentReferenceXml = new DOMDocument();
                    $documentReferenceXml->preserveWhiteSpace = false;
                    $documentReferenceXml->loadXML($documentReferenceTemplate);
                    $node = $documentReferenceXml->getElementsByTagName('BillingReference')->item(0);
                    $node = $invoiceXml->importNode($node, true);
                    $invoiceNode->insertBefore($node, $statusNode);
                }

                if ($invoice['documentTypeCode'] == '03' && $invoice['perceptionAmount'] > 0) {
                    $invoicePerceptionTemplate = XmlTemplate::SummaryInvoicePerception();

                    $invoicePerceptionVariableList = array();
                    $invoicePerceptionVariableList['perceptionSystemCode'] = $invoice['perceptionSystemCode'];
                    $invoicePerceptionVariableList['perceptionPercent'] = $invoice['perceptionPercent'];
                    $invoicePerceptionVariableList['perceptionAmount'] = $invoice['perceptionAmount'];
                    $invoicePerceptionVariableList['totalAmountWithPerception'] = $invoice['totalAmountWithPerception'];
                    $invoicePerceptionVariableList['perceptionTaxableAmount'] = $invoice['perceptionTaxableAmount'];
                    $invoicePerceptionVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                    foreach($invoicePerceptionVariableList as $key => $value)
                    {
                        $invoicePerceptionTemplate = str_replace('{{'.$key.'}}', $value, $invoicePerceptionTemplate);
                    }

                    $invoicePerceptionXml = new DOMDocument();
                    $invoicePerceptionXml->preserveWhiteSpace = false;
                    $invoicePerceptionXml->loadXML($invoicePerceptionTemplate);
                    $node = $invoicePerceptionXml->getElementsByTagName('SUNATPerceptionSummaryDocumentReference')->item(0);
                    $node = $invoiceXml->importNode($node, true);
                    $invoiceNode->insertBefore($node, $statusNode);
                }

                if ($invoice['exoneratedAmount'] > 0) {
                    $invoicePaymentTemplate = XmlTemplate::SummaryInvoicePayment();

                    $invoicePaymentVariableList = array();
                    $invoicePaymentVariableList['totalAmount'] = $invoice['exoneratedAmount'];
                    $invoicePaymentVariableList['instructionId'] = '02';
                    $invoicePaymentVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                    foreach($invoicePaymentVariableList as $key => $value)
                    {
                        $invoicePaymentTemplate = str_replace('{{'.$key.'}}', $value, $invoicePaymentTemplate);
                    }

                    $invoicePaymentXml = new DOMDocument();
                    $invoicePaymentXml->preserveWhiteSpace = false;
                    $invoicePaymentXml->loadXML($invoicePaymentTemplate);
                    $node = $invoicePaymentXml->getElementsByTagName('BillingPayment')->item(0);
                    $node = $invoiceXml->importNode($node, true);
                    $invoiceNode->insertBefore($node, $taxTotalNode);
                }

                if ($invoice['unaffectedAmount'] > 0) {
                    $invoicePaymentTemplate = XmlTemplate::SummaryInvoicePayment();

                    $invoicePaymentVariableList = array();
                    $invoicePaymentVariableList['totalAmount'] = $invoice['unaffectedAmount'];
                    $invoicePaymentVariableList['instructionId'] = '03';
                    $invoicePaymentVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                    foreach($invoicePaymentVariableList as $key => $value)
                    {
                        $invoicePaymentTemplate = str_replace('{{'.$key.'}}', $value, $invoicePaymentTemplate);
                    }

                    $invoicePaymentXml = new DOMDocument();
                    $invoicePaymentXml->preserveWhiteSpace = false;
                    $invoicePaymentXml->loadXML($invoicePaymentTemplate);
                    $node = $invoicePaymentXml->getElementsByTagName('BillingPayment')->item(0);
                    $node = $invoiceXml->importNode($node, true);
                    $invoiceNode->insertBefore($node, $taxTotalNode);
                }

                if ($invoice['freeAmount'] > 0) {
                    $invoicePaymentTemplate = XmlTemplate::SummaryInvoicePayment();

                    $invoicePaymentVariableList = array();
                    $invoicePaymentVariableList['totalAmount'] = $invoice['freeAmount'];
                    $invoicePaymentVariableList['instructionId'] = '05';
                    $invoicePaymentVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                    foreach($invoicePaymentVariableList as $key => $value)
                    {
                        $invoicePaymentTemplate = str_replace('{{'.$key.'}}', $value, $invoicePaymentTemplate);
                    }

                    $invoicePaymentXml = new DOMDocument();
                    $invoicePaymentXml->preserveWhiteSpace = false;
                    $invoicePaymentXml->loadXML($invoicePaymentTemplate);
                    $node = $invoicePaymentXml->getElementsByTagName('BillingPayment')->item(0);
                    $node = $invoiceXml->importNode($node, true);
                    $invoiceNode->insertBefore($node, $taxTotalNode);
                }

                if ($invoice['iscAmount'] > 0) {
                    $invoiceTaxTemplate = XmlTemplate::SummaryInvoiceTax();

                    $invoiceTaxVariableList = array();
                    $invoiceTaxVariableList['taxAmount'] = $invoice['iscAmount'];
                    $invoiceTaxVariableList['taxId'] = '2000';
                    $invoiceTaxVariableList['taxName'] = 'ISC';
                    $invoiceTaxVariableList['taxTypeCode'] = 'EXC';
                    $invoiceTaxVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                    foreach($invoiceTaxVariableList as $key => $value)
                    {
                        $invoiceTaxTemplate = str_replace('{{'.$key.'}}', $value, $invoiceTaxTemplate);
                    }

                    $invoiceTaxXml = new DOMDocument();
                    $invoiceTaxXml->preserveWhiteSpace = false;
                    $invoiceTaxXml->loadXML($invoiceTaxTemplate);
                    $node = $invoiceTaxXml->getElementsByTagName('TaxTotal')->item(0);
                    $node = $invoiceXml->importNode($node, true);
                    $invoiceNode->appendChild($node);
                }

                if ($invoice['otherTaxAmount'] > 0) {
                    $invoiceTaxTemplate = XmlTemplate::SummaryInvoiceTax();

                    $invoiceTaxVariableList = array();
                    $invoiceTaxVariableList['taxAmount'] = $invoice['otherTaxAmount'];
                    $invoiceTaxVariableList['taxId'] = '9999';
                    $invoiceTaxVariableList['taxName'] = 'OTROS';
                    $invoiceTaxVariableList['taxTypeCode'] = 'OTH';
                    $invoiceTaxVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                    foreach($invoiceTaxVariableList as $key => $value)
                    {
                        $invoiceTaxTemplate = str_replace('{{'.$key.'}}', $value, $invoiceTaxTemplate);
                    }

                    $invoiceTaxXml = new DOMDocument();
                    $invoiceTaxXml->preserveWhiteSpace = false;
                    $invoiceTaxXml->loadXML($invoiceTaxTemplate);
                    $node = $invoiceTaxXml->getElementsByTagName('TaxTotal')->item(0);
                    $node = $invoiceXml->importNode($node, true);
                    $invoiceNode->appendChild($node);
                }

                $node = $xml->importNode($invoiceNode, true);
                $summaryNode->appendChild($node);
            }

            $xml->formatOutput = true;

            file_put_contents($folderPath.$fileName, $xml->saveXML());

            $this->SignDocument($folderPath.$fileName);

            $this->sunatXmlModel->Insert($xmlTypeId, $referenceId, $userId);

            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage()."\n\n".$e->getTraceAsString();
        }

        return $res;
    }

    private function SaveInvoice($folderPath, $fileName, $referenceId, $invoice, $xmlTypeId, $userId)
    {
        $res = new Result();
        try {
            $invoiceTemplate = XmlTemplate::InvoiceBase();
            $aditionalDocumentCorrelative = 1;

            $variableList = array();

            $variableList['serie'] = $invoice['serie'];
            $variableList['number'] = $invoice['number'];
            $variableList['issueDate'] = $invoice['issueDate'];
            $variableList['issueTime'] = $invoice['issueTime'];
            $variableList['invoiceTypeCode'] = $invoice['invoiceTypeCode'];
            $variableList['amounInWord'] = $invoice['amounInWord'];
            $variableList['supplierRuc'] = $invoice['supplierRuc'];
            $variableList['defaultUrl'] = $invoice['defaultUrl'];
            $variableList['supplierName'] = $invoice['supplierName'];
            $variableList['supplierDocumentType'] = $invoice['supplierDocumentType'];
            $variableList['customerDocumentType'] = $invoice['customerDocumentType'];
            $variableList['customerDocument'] = $invoice['customerDocument'];
            $variableList['customerName'] = $invoice['customerName'];
            $variableList['totalTaxAmount'] = $invoice['totalTaxAmount'];
            $variableList['totalBaseAmount'] = $invoice['totalBaseAmount'];
            $variableList['totalSaleAmount'] = $invoice['totalSaleAmount'];
            $variableList['totalDiscountAmount'] = $invoice['totalDiscountAmount'];
            $variableList['totalExtraChargeAmount'] = $invoice['totalExtraChargeAmount'];
            $variableList['totalPrepaidAmount'] = $invoice['totalPrepaidAmount'];
            $variableList['totalPayableAmount'] = $invoice['totalPayableAmount'];
            $variableList['operationTypeCode'] = $invoice['operationTypeCode'];
            $variableList['codigoMoneda'] = $invoice['codigoMoneda'];

            foreach($variableList as $key => $value)
            {
                $invoiceTemplate = str_replace('{{'.$key.'}}', $value, $invoiceTemplate);
            }

            $xml = new DOMDocument();
            $xml->preserveWhiteSpace = false;
            $xml->loadXML($invoiceTemplate);
            $TaxTotal = $xml->getElementsByTagName('TaxTotal')->item(0);
            $invoiceNode = $xml->getElementsByTagName('Invoice')->item(0);
            $currencyNode = $xml->getElementsByTagName('DocumentCurrencyCode')->item(0);
            $signatureNode = $xml->getElementsByTagName('Signature')->item(0);

            if ($invoice['amazoniaGoods'] == 1) {
                $captionTemplate = XmlTemplate::InvoiceCaption();

                $captionVariableList = array();
                $captionVariableList['captionCode'] = '2001';
                $captionVariableList['captionDescription'] = 'BIENES TRANSFERIDOS EN LA AMAZONÍA REGIÓN SELVAPARA SER CONSUMIDOS EN LA MISMA';

                foreach($captionVariableList as $key => $value)
                {
                    $captionTemplate = str_replace('{{'.$key.'}}', $value, $captionTemplate);
                }

                $captionXml = new DOMDocument();
                $captionXml->preserveWhiteSpace = false;
                $captionXml->loadXML($captionTemplate);
                $node = $captionXml->getElementsByTagName('Note')->item(0);
                $node = $xml->importNode($node, true);
                $invoiceNode->insertBefore($node, $currencyNode);
            }

            if ($invoice['amazoniaService'] == 1) {
                $captionTemplate = XmlTemplate::InvoiceCaption();

                $captionVariableList = array();
                $captionVariableList['captionCode'] = '2002';
                $captionVariableList['captionDescription'] = 'SERVICIOS PRESTADOS EN LA AMAZONÍA REGIÓN SELVA PARA SER CONSUMIDOS EN LA MISMA';

                foreach($captionVariableList as $key => $value)
                {
                    $captionTemplate = str_replace('{{'.$key.'}}', $value, $captionTemplate);
                }

                $captionXml = new DOMDocument();
                $captionXml->preserveWhiteSpace = false;
                $captionXml->loadXML($captionTemplate);
                $node = $captionXml->getElementsByTagName('Note')->item(0);
                $node = $xml->importNode($node, true);
                $invoiceNode->insertBefore($node, $currencyNode);
            }

            if ($invoice['itinerantSuplier'] == 1) {
                $captionTemplate = XmlTemplate::InvoiceCaption();

                $captionVariableList = array();
                $captionVariableList['captionCode'] = '2005';
                $captionVariableList['captionDescription'] = 'Venta realizada por emisor itinerante';

                foreach($captionVariableList as $key => $value)
                {
                    $captionTemplate = str_replace('{{'.$key.'}}', $value, $captionTemplate);
                }

                $captionXml = new DOMDocument();
                $captionXml->preserveWhiteSpace = false;
                $captionXml->loadXML($captionTemplate);
                $node = $captionXml->getElementsByTagName('Note')->item(0);
                $node = $xml->importNode($node, true);
                $invoiceNode->insertBefore($node, $currencyNode);

                $deliveryTemplate = XmlTemplate::InvoiceItinerantDeliveryAdress();
                $deliveryVariableList = array();
                $deliveryVariableList['itinerantAddressCode'] = $invoice['itinerantAddressCode'];
                $deliveryVariableList['itinerantAddress'] = $invoice['itinerantAddress'];
                $deliveryVariableList['itinerantUrbanization'] = $invoice['itinerantUrbanization'];
                $deliveryVariableList['itinerantProvince'] = $invoice['itinerantProvince'];
                $deliveryVariableList['itinerantRegion'] = $invoice['itinerantRegion'];
                $deliveryVariableList['itinerantDistrict'] = $invoice['itinerantDistrict'];

                foreach($deliveryVariableList as $key => $value)
                {
                    $deliveryTemplate = str_replace('{{'.$key.'}}', $value, $deliveryTemplate);
                }

                $deliveryXml = new DOMDocument();
                $deliveryXml->preserveWhiteSpace = false;
                $deliveryXml->loadXML($deliveryTemplate);
                $node = $deliveryXml->getElementsByTagName('Delivery')->item(0);
                $node = $xml->importNode($node, true);
                $invoiceNode->insertBefore($node, $TaxTotal);
            }

            if (strlen($invoice['orderReference']) > 0) {
                $captionTemplate = XmlTemplate::InvoiceOrderReference();

                $captionVariableList = array();
                $captionVariableList['orderReference'] = $invoice['orderReference'];

                foreach($captionVariableList as $key => $value)
                {
                    $captionTemplate = str_replace('{{'.$key.'}}', $value, $captionTemplate);
                }

                $captionXml = new DOMDocument();
                $captionXml->preserveWhiteSpace = false;
                $captionXml->loadXML($captionTemplate);
                $node = $captionXml->getElementsByTagName('OrderReference')->item(0);
                $node = $xml->importNode($node, true);
                $invoiceNode->insertBefore($node, $signatureNode);
            }

            if (isset($invoice['referenceDocumentList'])) {
                foreach ($invoice['referenceDocumentList'] as $referencedDocument) {
                    $referenceDocumenTemplate = XmlTemplate::InvoiceDocumentReference();

                    $itemVariableList = array();
                    $itemVariableList['referencedDocument'] = $referencedDocument['referencedDocument'];
                    $itemVariableList['referencedDocumentTypeCode'] = $referencedDocument['referencedDocumentTypeCode'];

                    foreach($itemVariableList as $key => $value)
                    {
                        $referenceDocumenTemplate = str_replace('{{'.$key.'}}', $value, $referenceDocumenTemplate);
                    }

                    $referencedDocumentXml = new DOMDocument();
                    $referencedDocumentXml->preserveWhiteSpace = false;
                    $referencedDocumentXml->loadXML($referenceDocumenTemplate);
                    $node = $referencedDocumentXml->getElementsByTagName('DespatchDocumentReference')->item(0);
                    $node = $xml->importNode($node, true);
                    $invoiceNode->insertBefore($node, $signatureNode);
                }
            }

            if ($invoice['totalIgvAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $invoice['totalIgvTaxableAmount'];
                $igvVariableList['totalTaxAmount'] = $invoice['totalIgvAmount'];
                $igvVariableList['taxId'] = 1000;
                $igvVariableList['taxName'] = "IGV";
                $igvVariableList['taxTypeCode'] = "VAT";
                $igvVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($invoice['totalIscAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $invoice['totalIscTaxableAmount'];
                $igvVariableList['totalTaxAmount'] = $invoice['totalIscAmount'];
                $igvVariableList['taxId'] = 2000;
                $igvVariableList['taxName'] = "ISC";
                $igvVariableList['taxTypeCode'] = "EXC";
                $igvVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($invoice['totalExoneratedAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $invoice['totalExoneratedAmount'];
                $igvVariableList['totalTaxAmount'] = "0.00";
                $igvVariableList['taxId'] = 9997;
                $igvVariableList['taxName'] = "EXO";
                $igvVariableList['taxTypeCode'] = "VAT";
                $igvVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($invoice['totalInafectedAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $invoice['totalInafectedAmount'];
                $igvVariableList['totalTaxAmount'] = "0.00";
                $igvVariableList['taxId'] = 9998;
                $igvVariableList['taxName'] = "INA";
                $igvVariableList['taxTypeCode'] = "FRE";
                $igvVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($invoice['totalOtherTaxAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $invoice['totalOtherTaxTaxableAmount'];
                $igvVariableList['totalTaxAmount'] = $invoice['totalOtherTaxAmount'];
                $igvVariableList['taxId'] = 9999;
                $igvVariableList['taxName'] = "OTROS CONCEPTOS DE PAGO";
                $igvVariableList['taxTypeCode'] = "OTH";
                $igvVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($invoice['totalBagTaxAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalBagTax();
                $igvVariableList = array();
                $igvVariableList['totalBagTaxAmount'] = $invoice['totalBagTaxAmount'];
                $igvVariableList['bagTaxAmount'] = $invoice['totalBagTaxAmount'];
                $igvVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($invoice['referalGuideIncluded'] == 1) {
                $deliveryTemplate = XmlTemplate::InvoiceDelivery();
                $deliveryVariableList = array();
                $deliveryVariableList['transferReasonCode'] = $invoice['transferReasonCode'];
                $deliveryVariableList['grossWeightMeasure'] = $invoice['grossWeightMeasure'];
                $deliveryVariableList['grossWeight'] = $invoice['grossWeight'];
                $deliveryVariableList['transferMethodCode'] = $invoice['transferMethodCode'];
                $deliveryVariableList['carrierDocumentType'] = $invoice['carrierDocumentType'];
                $deliveryVariableList['carrierRuc'] = $invoice['carrierRuc'];
                $deliveryVariableList['carrierName'] = $invoice['carrierName'];
                $deliveryVariableList['licensePlate'] = $invoice['licensePlate'];
                $deliveryVariableList['deliveryAdressCode'] = $invoice['deliveryAdressCode'];
                $deliveryVariableList['deliveryAdress'] = $invoice['deliveryAdress'];
                $deliveryVariableList['originAdressCode'] = $invoice['originAdressCode'];
                $deliveryVariableList['originAdress'] = $invoice['originAdress'];
                $deliveryVariableList['driverDocumentType'] = $invoice['driverDocumentType'];
                $deliveryVariableList['driverDocument'] = $invoice['driverDocument'];

                foreach($deliveryVariableList as $key => $value)
                {
                    $deliveryTemplate = str_replace('{{'.$key.'}}', $value, $deliveryTemplate);
                }

                $deliveryXml = new DOMDocument();
                $deliveryXml->preserveWhiteSpace = false;
                $deliveryXml->loadXML($deliveryTemplate);
                $node = $deliveryXml->getElementsByTagName('Delivery')->item(0);
                $node = $xml->importNode($node, true);
                $invoiceNode->insertBefore($node, $TaxTotal);
            }

            if ($invoice['perceptionAmount'] > 0) {
                $captionTemplate = XmlTemplate::InvoiceCaption();
                $captionVariableList = array();
                $captionVariableList['captionCode'] = '2000';
                $captionVariableList['captionDescription'] = 'COMPROBANTE DE PERCEPCIÓN';

                foreach($captionVariableList as $key => $value)
                {
                    $captionTemplate = str_replace('{{'.$key.'}}', $value, $captionTemplate);
                }

                $captionXml = new DOMDocument();
                $captionXml->preserveWhiteSpace = false;
                $captionXml->loadXML($captionTemplate);
                $node = $captionXml->getElementsByTagName('Note')->item(0);
                $node = $xml->importNode($node, true);
                $invoiceNode->insertBefore($node, $currencyNode);

                $perceptionTemplate = XmlTemplate::InvoicePerception();
                $perceptionVariableList = array();
                $perceptionVariableList['totalAmountWithPerception'] = $invoice['totalAmountWithPerception'];

                foreach($perceptionVariableList as $key => $value)
                {
                    $perceptionTemplate = str_replace('{{'.$key.'}}', $value, $perceptionTemplate);
                }

                $perceptionXml = new DOMDocument();
                $perceptionXml->preserveWhiteSpace = false;
                $perceptionXml->loadXML($perceptionTemplate);
                $node = $perceptionXml->getElementsByTagName('PaymentMeans')->item(0);
                $node = $xml->importNode($node, true);
                $invoiceNode->insertBefore($node, $TaxTotal);

                $node = $perceptionXml->getElementsByTagName('PaymentTerms')->item(0);
                $node = $xml->importNode($node, true);
                $invoiceNode->insertBefore($node, $TaxTotal);

                $allowanceTemplate = XmlTemplate::InvoiceItemAllowanceCharge();
                $allowanceVariableList = array();
                $allowanceVariableList['chargeIndicator'] = 'true';
                $allowanceVariableList['allowanceChargeCode'] = $invoice['perceptionTypeCode'];
                $allowanceVariableList['allowanceChargePercent'] = $invoice['perceptionPercent'];
                $allowanceVariableList['allowanceChargeAmount'] = $invoice['perceptionAmount'];
                $allowanceVariableList['allowanceChargeBaseAmount'] = $invoice['perceptionTaxableAmount'];
                $allowanceVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                foreach($allowanceVariableList as $key => $value)
                {
                    $allowanceTemplate = str_replace('{{'.$key.'}}', $value, $allowanceTemplate);
                }

                $allowanceXml = new DOMDocument();
                $allowanceXml->preserveWhiteSpace = false;
                $allowanceXml->loadXML($allowanceTemplate);
                $node = $allowanceXml->getElementsByTagName('AllowanceCharge')->item(0);
                $node = $xml->importNode($node, true);
                $invoiceNode->insertBefore($node, $TaxTotal);
            }

            if ($invoice['detractionAmount'] > 0) {
                $captionTemplate = XmlTemplate::InvoiceCaption();
                $captionVariableList = array();
                $captionVariableList['captionCode'] = '2006';
                $captionVariableList['captionDescription'] = 'Operación sujeta a detracción';

                foreach($captionVariableList as $key => $value)
                {
                    $captionTemplate = str_replace('{{'.$key.'}}', $value, $captionTemplate);
                }

                $captionXml = new DOMDocument();
                $captionXml->preserveWhiteSpace = false;
                $captionXml->loadXML($captionTemplate);
                $node = $captionXml->getElementsByTagName('Note')->item(0);
                $node = $xml->importNode($node, true);
                $invoiceNode->insertBefore($node, $currencyNode);

                $detractionTemplate = XmlTemplate::InvoiceDetraction();
                $detractionVariableList = array();
                $detractionVariableList['detractionAccount'] = $invoice['detractionAccount'];
                $detractionVariableList['detractionTypeCode'] = $invoice['detractionTypeCode'];
                $detractionVariableList['detractionPercent'] = $invoice['detractionPercent'];
                $detractionVariableList['detractionAmount'] = $invoice['detractionAmount'];

                foreach($detractionVariableList as $key => $value)
                {
                    $detractionTemplate = str_replace('{{'.$key.'}}', $value, $detractionTemplate);
                }

                $detractionXml = new DOMDocument();
                $detractionXml->preserveWhiteSpace = false;
                $detractionXml->loadXML($detractionTemplate);
                $node = $detractionXml->getElementsByTagName('PaymentMeans')->item(0);
                $node = $xml->importNode($node, true);
                $invoiceNode->insertBefore($node, $TaxTotal);

                $node = $detractionXml->getElementsByTagName('PaymentTerms')->item(0);
                $node = $xml->importNode($node, true);
                $invoiceNode->insertBefore($node, $TaxTotal);
            }

            if ($invoice['totalPrepaidAmount'] > 0) {
                foreach ($invoice['prepaidPaymentList'] as $prepaidPayment) {
                    $documentTemplate = XmlTemplate::InvoiceAditionalDocumentReference();
                    $documentVariableList = array();
                    $documentVariableList['documentSerieNumber'] = $prepaidPayment['documentSerieNumber'];
                    $documentVariableList['documentTypeCode'] = $prepaidPayment['documentTypeCode'];
                    $documentVariableList['correlative'] = $aditionalDocumentCorrelative;
                    $documentVariableList['supplierDocumentType'] = $invoice['supplierDocumentType'];
                    $documentVariableList['supplierRuc'] = $invoice['supplierRuc'];

                    foreach($documentVariableList as $key => $value)
                    {
                        $documentTemplate = str_replace('{{'.$key.'}}', $value, $documentTemplate);
                    }

                    $documentXml = new DOMDocument();
                    $documentXml->preserveWhiteSpace = false;
                    $documentXml->loadXML($documentTemplate);
                    $node = $documentXml->getElementsByTagName('AdditionalDocumentReference')->item(0);
                    $node = $xml->importNode($node, true);
                    $invoiceNode->insertBefore($node, $signatureNode);

                    $prepaidTemplate = XmlTemplate::InvoicePrepaidPayment();
                    $prepaidVariableList = array();
                    $prepaidVariableList['aditionalDocumentCorrelative'] = $aditionalDocumentCorrelative++;
                    $prepaidVariableList['prepaidAmount'] = $prepaidPayment['prepaidAmount'];

                    foreach($prepaidVariableList as $key => $value)
                    {
                        $prepaidTemplate = str_replace('{{'.$key.'}}', $value, $prepaidTemplate);
                    }

                    $prepaidXml = new DOMDocument();
                    $prepaidXml->preserveWhiteSpace = false;
                    $prepaidXml->loadXML($prepaidTemplate);
                    $node = $prepaidXml->getElementsByTagName('PrepaidPayment')->item(0);
                    $node = $xml->importNode($node, true);
                    $invoiceNode->insertBefore($node, $TaxTotal);
                }
            }

            if ($invoice['totalFreeAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $invoice['totalFreeAmount'];
                $igvVariableList['totalTaxAmount'] = "0.00";
                $igvVariableList['taxId'] = 9996;
                $igvVariableList['taxName'] = "GRA";
                $igvVariableList['taxTypeCode'] = "FRE";
                $igvVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($invoice['globalDiscountAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalDiscount();
                $igvVariableList = array();
                $igvVariableList['globalDiscountPercent'] = $invoice['globalDiscountPercent'];
                $igvVariableList['globalDiscountAmount'] = $invoice['globalDiscountAmount'];
                $igvVariableList['totalBaseAmount'] = $invoice['totalBaseAmount'];
                $igvVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('AllowanceCharge')->item(0);
                $node = $xml->importNode($node, true);
                $invoiceNode->insertBefore($node, $TaxTotal);
            }

            $itemCorrelative = 1;
            foreach ($invoice['itemList'] as $item) {
                $invoiceItemTemplate = XmlTemplate::InvoiceItem();

                $itemVariableList = array();
                $itemVariableList['itemCorrelative'] = $itemCorrelative++;
                $itemVariableList['itemUnitCode'] = $item['itemUnitCode'];
                $itemVariableList['itemCuantity'] = $item['itemCuantity'];
                $itemVariableList['itemFinalBaseAmount'] = $item['itemFinalBaseAmount'];
                $itemVariableList['itemIgvTaxableAmount'] = $item['itemIgvTaxableAmount'];
                $itemVariableList['singleItemPrice'] = $item['singleItemPrice'];
                $itemVariableList['codigoMoneda'] = $invoice['codigoMoneda'];
                if ($item['onerous'] == 1) {
                    $itemVariableList['itemTransactionType'] = '01';
                }
                else{
                    $itemVariableList['itemTransactionType'] = '02';
                }

                $itemVariableList['itemTotalTaxAmount'] = $item['itemTotalTaxAmount'];
                $itemVariableList['itemTotalIgvAmount'] = $item['itemTotalIgvAmount'];
                $itemVariableList['itemTaxPercent'] = $item['itemTaxPercent'];
                $itemVariableList['itemIgvTaxCode'] = $item['itemIgvTaxCode'];
                $itemVariableList['itemTaxCode'] = $item['itemTaxCode'];
                $itemVariableList['itemTaxName'] = $item['itemTaxName'];
                $itemVariableList['itemTaxNamecode'] = $item['itemTaxNamecode'];
                $itemVariableList['itemDescription'] = $item['itemDescription'];
                $itemVariableList['ItemClassificationCode'] = $item['ItemClassificationCode'];
                $itemVariableList['singleItemBasePrice'] = $item['singleItemBasePrice'];

                foreach($itemVariableList as $key => $value)
                {
                    $invoiceItemTemplate = str_replace('{{'.$key.'}}', $value, $invoiceItemTemplate);
                }

                $xmlItem = new DOMDocument();
                $xmlItem->preserveWhiteSpace = false;
                $xmlItem->loadXML($invoiceItemTemplate);
                $invoiceLineNode = $xmlItem->getElementsByTagName('InvoiceLine')->item(0);
                $itemTaxTotalNode = $xmlItem->getElementsByTagName('TaxTotal')->item(0);
                $itemNode = $xmlItem->getElementsByTagName('Item')->item(0);

                if ($item['itemBagCuantity'] > 0) {
                    $itemBagTaxTemplate = XmlTemplate::InvoiceItemBagTax();
                    $itemBagTaxVariableList = array();
                    $itemBagTaxVariableList['itemBagTaxCuantity'] = $item['itemBagCuantity'];
                    $itemBagTaxVariableList['bagTaxAmountPerUnit'] = $invoice['bagTaxAmountPerUnit'];
                    $itemBagTaxVariableList['bagTaxAmount'] = $item['bagTaxAmount'];
                    $itemBagTaxVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                    foreach($itemBagTaxVariableList as $key => $value)
                    {
                        $itemBagTaxTemplate = str_replace('{{'.$key.'}}', $value, $itemBagTaxTemplate);
                    }

                    $xmlItemBagTax = new DOMDocument();
                    $xmlItemBagTax->preserveWhiteSpace = false;
                    $xmlItemBagTax->loadXML($itemBagTaxTemplate);
                    $node = $xmlItemBagTax->getElementsByTagName('TaxSubtotal')->item(0);
                    $node = $xmlItem->importNode($node, true);
                    $itemTaxTotalNode->appendChild($node);
                }

                if ($item['itemIscAmount'] > 0) {
                    $itemIscTemplate = XmlTemplate::InvoiceItemIsc();
                    $itemIscVariableList = array();
                    $itemIscVariableList['itemIscTaxableAmount'] = $item['itemIscTaxableAmount'];
                    $itemIscVariableList['itemIscAmount'] = $item['itemIscAmount'];
                    $itemIscVariableList['itemIscTaxPercent'] = $item['itemIscTaxPercent'];
                    $itemIscVariableList['itemIgvTaxCode'] = $item['itemIgvTaxCode'];
                    $itemIscVariableList['itemIscSystemType'] = $item['itemIscSystemType'];
                    $itemIscVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                    foreach($itemIscVariableList as $key => $value)
                    {
                        $itemIscTemplate = str_replace('{{'.$key.'}}', $value, $itemIscTemplate);
                    }

                    $xmlItemIsc = new DOMDocument();
                    $xmlItemIsc->preserveWhiteSpace = false;
                    $xmlItemIsc->loadXML($itemIscTemplate);
                    $node = $xmlItemIsc->getElementsByTagName('TaxSubtotal')->item(0);
                    $node = $xmlItem->importNode($node, true);
                    $itemTaxTotalNode->appendChild($node);
                }

                if ($invoice['detractionTypeCode'] == '004') {
                    $itemDetractionTemplate = XmlTemplate::InvoiceItemDetraccionHidro();

                    $itemDetractionVariableList = array();
                    $itemDetractionVariableList['boatLicensePlate'] = $invoice['boatLicensePlate'];
                    $itemDetractionVariableList['boatName'] = $invoice['boatName'];
                    $itemDetractionVariableList['speciesKind'] = $item['speciesKind'];
                    $itemDetractionVariableList['deliveryAddress'] = $item['deliveryAddress'];
                    $itemDetractionVariableList['deliveryDate'] = $item['deliveryDate'];
                    $itemDetractionVariableList['quantity'] = $item['quantity'];

                    foreach($itemDetractionVariableList as $key => $value)
                    {
                        $itemDetractionTemplate = str_replace('{{'.$key.'}}', $value, $itemDetractionTemplate);
                    }

                    $xmlItemDiscount = new DOMDocument();
                    $xmlItemDiscount->preserveWhiteSpace = false;
                    $xmlItemDiscount->loadXML($itemDetractionTemplate);
                    $node = $xmlItemDiscount->getElementsByTagName('AdditionalItemProperty');

                    $importedNode = $xmlItem->importNode($node->item(0), true);
                    $itemNode->appendChild($importedNode);
                    $importedNode = $xmlItem->importNode($node->item(1), true);
                    $itemNode->appendChild($importedNode);
                    $importedNode = $xmlItem->importNode($node->item(2), true);
                    $itemNode->appendChild($importedNode);
                    $importedNode = $xmlItem->importNode($node->item(3), true);
                    $itemNode->appendChild($importedNode);
                    $importedNode = $xmlItem->importNode($node->item(4), true);
                    $itemNode->appendChild($importedNode);
                    $importedNode = $xmlItem->importNode($node->item(5), true);
                    $itemNode->appendChild($importedNode);
                }
                else if ($invoice['detractionTypeCode'] == '027') {
                    $itemDetractionTemplate = XmlTemplate::InvoiceItemDetraccionTrans();

                    $itemDetractionVariableList = array();
                    $itemDetractionVariableList['despatchDetail'] = $invoice['despatchDetail'];
                    $itemDetractionVariableList['originAdressCode'] = $invoice['originAdressCode'];
                    $itemDetractionVariableList['originAdress'] = $invoice['originAdress'];
                    $itemDetractionVariableList['deliveryAdressCode'] = $invoice['deliveryAdressCode'];
                    $itemDetractionVariableList['deliveryAdress'] = $invoice['deliveryAdress'];
                    $itemDetractionVariableList['codigoMoneda'] = $invoice['codigoMoneda'];
                    $itemDetractionVariableList['transportReferencialAmount'] = $item['transportReferencialAmount'];
                    $itemDetractionVariableList['effectiveLoadReferencialAmount'] = $item['effectiveLoadReferencialAmount'];
                    $itemDetractionVariableList['payLoadReferencialAmount'] = $item['payLoadReferencialAmount'];

                    foreach($itemDetractionVariableList as $key => $value)
                    {
                        $itemDetractionTemplate = str_replace('{{'.$key.'}}', $value, $itemDetractionTemplate);
                    }

                    $xmlItemDiscount = new DOMDocument();
                    $xmlItemDiscount->preserveWhiteSpace = false;
                    $xmlItemDiscount->loadXML($itemDetractionTemplate);
                    $node = $xmlItemDiscount->getElementsByTagName('Delivery');

                    $importedNode = $xmlItem->importNode($node->item(0), true);
                    $invoiceLineNode->insertBefore($importedNode, $itemTaxTotalNode);
                    $importedNode = $xmlItem->importNode($node->item(1), true);
                    $invoiceLineNode->insertBefore($importedNode, $itemTaxTotalNode);
                    $importedNode = $xmlItem->importNode($node->item(2), true);
                    $invoiceLineNode->insertBefore($importedNode, $itemTaxTotalNode);
                    $importedNode = $xmlItem->importNode($node->item(3), true);
                    $invoiceLineNode->insertBefore($importedNode, $itemTaxTotalNode);
                    $importedNode = $xmlItem->importNode($node->item(4), true);
                    $invoiceLineNode->insertBefore($importedNode, $itemTaxTotalNode);
                }

                if ($item['itemDiscountAmount'] > 0) {
                    $itemDiscountTemplate = XmlTemplate::InvoiceItemAllowanceCharge();

                    $itemDiscountVariableList = array();
                    $itemDiscountVariableList['chargeIndicator'] = 'false';
                    $itemDiscountVariableList['allowanceChargeCode'] = '00';
                    $itemDiscountVariableList['allowanceChargePercent'] = $item['itemDiscountPercent'];
                    $itemDiscountVariableList['allowanceChargeAmount'] = $item['itemDiscountAmount'];
                    $itemDiscountVariableList['allowanceChargeBaseAmount'] = $item['itemTotalBaseAmount'];
                    $itemDiscountVariableList['codigoMoneda'] = $invoice['codigoMoneda'];

                    foreach($itemDiscountVariableList as $key => $value)
                    {
                        $itemDiscountTemplate = str_replace('{{'.$key.'}}', $value, $itemDiscountTemplate);
                    }

                    $xmlItemDiscount = new DOMDocument();
                    $xmlItemDiscount->preserveWhiteSpace = false;
                    $xmlItemDiscount->loadXML($itemDiscountTemplate);
                    $node = $xmlItemDiscount->getElementsByTagName('AllowanceCharge')->item(0);
                    $node = $xmlItem->importNode($node, true);
                    $invoiceLineNode->insertBefore($node, $itemTaxTotalNode);
                }

                $node = $xmlItem->getElementsByTagName('InvoiceLine')->item(0);
                $node = $xml->importNode($node, true);
                $invoiceNode->appendChild($node);
            }

            $xml->formatOutput = true;

            file_put_contents($folderPath . $fileName, $xml->saveXML());

            $res->digestValue = $this->SignDocument($folderPath . $fileName);

            $this->sunatXmlModel->Insert($xmlTypeId, $referenceId, $userId);

            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage() . "\n\n".$e->getTraceAsString();
        }

        return $res;
    }

    private function SaveCreditNote($folderPath, $fileName, $referenceId, $creditNote, $xmlTypeId, $userId)
    {
        $res = new Result();
        try {
            $creditNoteTemplate = XmlTemplate::CreditNoteBase();

            $variableList = array();

            $variableList['serie'] = $creditNote['serie'];
            $variableList['number'] = $creditNote['number'];
            $variableList['issueDate'] = $creditNote['issueDate'];
            $variableList['issueTime'] = $creditNote['issueTime'];
            $variableList['amounInWord'] = $creditNote['amounInWord'];
            $variableList['creditNoteTypeCode'] = $creditNote['creditNoteTypeCode'];
            $variableList['creditNoteTypeDescription'] = $creditNote['creditNoteTypeDescription'];
            $variableList['supplierRuc'] = $creditNote['supplierRuc'];
            $variableList['defaultUrl'] = $creditNote['defaultUrl'];
            $variableList['supplierName'] = $creditNote['supplierName'];
            $variableList['supplierDocumentType'] = $creditNote['supplierDocumentType'];
            $variableList['customerDocumentType'] = $creditNote['customerDocumentType'];
            $variableList['customerDocument'] = $creditNote['customerDocument'];
            $variableList['customerName'] = $creditNote['customerName'];
            $variableList['totalTaxAmount'] = $creditNote['totalTaxAmount'];
            $variableList['totalBaseAmount'] = $creditNote['totalBaseAmount'];
            $variableList['totalSaleAmount'] = $creditNote['totalSaleAmount'];
            $variableList['totalDiscountAmount'] = $creditNote['totalDiscountAmount'];
            $variableList['totalExtraChargeAmount'] = $creditNote['totalExtraChargeAmount'];
            $variableList['totalPrepaidAmount'] = $creditNote['totalPrepaidAmount'];
            $variableList['totalPayableAmount'] = $creditNote['totalPayableAmount'];
            $variableList['codigoMoneda'] = $creditNote['codigoMoneda'];

            foreach($variableList as $key => $value)
            {
                $creditNoteTemplate = str_replace('{{'.$key.'}}', $value, $creditNoteTemplate);
            }

            $xml = new DOMDocument();
            $xml->preserveWhiteSpace = false;
            $xml->loadXML($creditNoteTemplate);
            $TaxTotal = $xml->getElementsByTagName('TaxTotal')->item(0);
            $creditNoteNode = $xml->getElementsByTagName('CreditNote')->item(0);
            $signatureNode = $xml->getElementsByTagName('Signature')->item(0);
            $currencyNode = $xml->getElementsByTagName('DocumentCurrencyCode')->item(0);

            if ($creditNote['amazoniaGoods'] == 1) {
                $captionTemplate = XmlTemplate::InvoiceCaption();

                $captionVariableList = array();
                $captionVariableList['captionCode'] = '2001';
                $captionVariableList['captionDescription'] = 'BIENES TRANSFERIDOS EN LA AMAZONÍA REGIÓN SELVAPARA SER CONSUMIDOS EN LA MISMA';

                foreach($captionVariableList as $key => $value)
                {
                    $captionTemplate = str_replace('{{'.$key.'}}', $value, $captionTemplate);
                }

                $captionXml = new DOMDocument();
                $captionXml->preserveWhiteSpace = false;
                $captionXml->loadXML($captionTemplate);
                $node = $captionXml->getElementsByTagName('Note')->item(0);
                $node = $xml->importNode($node, true);
                $creditNoteNode->insertBefore($node, $currencyNode);
            }

            if ($creditNote['amazoniaService'] == 1) {
                $captionTemplate = XmlTemplate::InvoiceCaption();

                $captionVariableList = array();
                $captionVariableList['captionCode'] = '2002';
                $captionVariableList['captionDescription'] = 'SERVICIOS PRESTADOS EN LA AMAZONÍA REGIÓN SELVA PARA SER CONSUMIDOS EN LA MISMA';

                foreach($captionVariableList as $key => $value)
                {
                    $captionTemplate = str_replace('{{'.$key.'}}', $value, $captionTemplate);
                }

                $captionXml = new DOMDocument();
                $captionXml->preserveWhiteSpace = false;
                $captionXml->loadXML($captionTemplate);
                $node = $captionXml->getElementsByTagName('Note')->item(0);
                $node = $xml->importNode($node, true);
                $creditNoteNode->insertBefore($node, $currencyNode);
            }

            if (strlen($creditNote['orderReference']) > 0) {
                $captionTemplate = XmlTemplate::InvoiceOrderReference();

                $captionVariableList = array();
                $captionVariableList['orderReference'] = $creditNote['orderReference'];

                foreach($captionVariableList as $key => $value)
                {
                    $captionTemplate = str_replace('{{'.$key.'}}', $value, $captionTemplate);
                }

                $captionXml = new DOMDocument();
                $captionXml->preserveWhiteSpace = false;
                $captionXml->loadXML($captionTemplate);
                $node = $captionXml->getElementsByTagName('OrderReference')->item(0);
                $node = $xml->importNode($node, true);
                $creditNoteNode->insertBefore($node, $signatureNode);
            }

            foreach ($creditNote['invoiceReferenceList'] as $referencedInvoice) {
                $billingReferenceTemplate = XmlTemplate::NoteBillingReference();
                $billingReferenceVariableList = array();
                $billingReferenceVariableList['billingReferenceSerie'] = $referencedInvoice['billingReferenceSerie'];
                $billingReferenceVariableList['billingReferenceNumber'] = $referencedInvoice['billingReferenceNumber'];
                $billingReferenceVariableList['billingReferenceTypeCode'] = $referencedInvoice['billingReferenceTypeCode'];

                foreach($billingReferenceVariableList as $key => $value)
                {
                    $billingReferenceTemplate = str_replace('{{'.$key.'}}', $value, $billingReferenceTemplate);
                }

                $billingReferenceXml = new DOMDocument();
                $billingReferenceXml->preserveWhiteSpace = false;
                $billingReferenceXml->loadXML($billingReferenceTemplate);
                $node = $billingReferenceXml->getElementsByTagName('BillingReference')->item(0);
                $node = $xml->importNode($node, true);
                $creditNoteNode->insertBefore($node, $signatureNode);
            }

            if (isset($creditNote['referenceDocumentList'])) {
                foreach ($creditNote['referenceDocumentList'] as $referencedDocument) {
                    $referenceDocumenTemplate = XmlTemplate::InvoiceDocumentReference();

                    $itemVariableList = array();
                    $itemVariableList['referencedDocument'] = $referencedDocument['referencedDocument'];
                    $itemVariableList['referencedDocumentTypeCode'] = $referencedDocument['referencedDocumentTypeCode'];

                    foreach($itemVariableList as $key => $value)
                    {
                        $referenceDocumenTemplate = str_replace('{{'.$key.'}}', $value, $referenceDocumenTemplate);
                    }

                    $referencedDocumentXml = new DOMDocument();
                    $referencedDocumentXml->preserveWhiteSpace = false;
                    $referencedDocumentXml->loadXML($referenceDocumenTemplate);
                    $node = $referencedDocumentXml->getElementsByTagName('DespatchDocumentReference')->item(0);
                    $node = $xml->importNode($node, true);
                    $creditNoteNode->insertBefore($node, $signatureNode);
                }
            }

            if ($creditNote['totalIgvAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $creditNote['totalIgvTaxableAmount'];
                $igvVariableList['totalTaxAmount'] = $creditNote['totalIgvAmount'];
                $igvVariableList['taxId'] = 1000;
                $igvVariableList['taxName'] = "IGV";
                $igvVariableList['taxTypeCode'] = "VAT";
                $igvVariableList['codigoMoneda'] = $creditNote['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($creditNote['totalIscAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $creditNote['totalIscTaxableAmount'];
                $igvVariableList['totalTaxAmount'] = $creditNote['totalIscAmount'];
                $igvVariableList['taxId'] = 2000;
                $igvVariableList['taxName'] = "ISC";
                $igvVariableList['taxTypeCode'] = "EXC";
                $igvVariableList['codigoMoneda'] = $creditNote['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($creditNote['totalFreeAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $creditNote['totalFreeAmount'];
                $igvVariableList['totalTaxAmount'] = "0.00";
                $igvVariableList['taxId'] = 9996;
                $igvVariableList['taxName'] = "GRA";
                $igvVariableList['taxTypeCode'] = "FRE";
                $igvVariableList['codigoMoneda'] = $creditNote['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($creditNote['totalExoneratedAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $creditNote['totalExoneratedAmount'];
                $igvVariableList['totalTaxAmount'] = "0.00";
                $igvVariableList['taxId'] = 9997;
                $igvVariableList['taxName'] = "EXO";
                $igvVariableList['taxTypeCode'] = "VAT";
                $igvVariableList['codigoMoneda'] = $creditNote['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($creditNote['totalInafectedAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $creditNote['totalInafectedAmount'];
                $igvVariableList['totalTaxAmount'] = "0.00";
                $igvVariableList['taxId'] = 9998;
                $igvVariableList['taxName'] = "INA";
                $igvVariableList['taxTypeCode'] = "FRE";
                $igvVariableList['codigoMoneda'] = $creditNote['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($creditNote['totalOtherTaxAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $creditNote['totalOtherTaxTaxableAmount'];
                $igvVariableList['totalTaxAmount'] = $creditNote['totalOtherTaxAmount'];
                $igvVariableList['taxId'] = 9999;
                $igvVariableList['taxName'] = "OTROS CONCEPTOS DE PAGO";
                $igvVariableList['taxTypeCode'] = "OTH";
                $igvVariableList['codigoMoneda'] = $creditNote['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($creditNote['totalBagTaxAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalBagTax();
                $igvVariableList = array();
                $igvVariableList['totalBagTaxAmount'] = $creditNote['totalBagTaxAmount'];
                $igvVariableList['bagTaxAmount'] = $creditNote['totalBagTaxAmount'];
                $igvVariableList['codigoMoneda'] = $creditNote['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($creditNote['globalDiscountAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalDiscount();
                $igvVariableList = array();
                $igvVariableList['globalDiscountPercent'] = $creditNote['globalDiscountPercent'];
                $igvVariableList['globalDiscountAmount'] = $creditNote['globalDiscountAmount'];
                $igvVariableList['totalBaseAmount'] = $creditNote['totalBaseAmount'];
                $igvVariableList['codigoMoneda'] = $creditNote['codigoMoneda'];
                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('AllowanceCharge')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
                $creditNoteNode->insertBefore($node, $TaxTotal);
            }

            $itemCorrelative = 1;
            foreach ($creditNote['itemList'] as $item) {
                $creditNoteItemTemplate = XmlTemplate::CreditNoteItem();

                $itemVariableList = array();
                $itemVariableList['itemCorrelative'] = $itemCorrelative++;
                $itemVariableList['itemUnitCode'] = $item['itemUnitCode'];
                $itemVariableList['itemCuantity'] = $item['itemCuantity'];
                $itemVariableList['itemFinalBaseAmount'] = $item['itemFinalBaseAmount'];
                $itemVariableList['itemIgvTaxableAmount'] = $item['itemIgvTaxableAmount'];
                $itemVariableList['singleItemPrice'] = $item['singleItemPrice'];
                $itemVariableList['codigoMoneda'] = $creditNote['codigoMoneda'];
                if ($item['onerous'] == 1) {
                    $itemVariableList['itemTransactionType'] = '01';
                }
                else{
                    $itemVariableList['itemTransactionType'] = '02';
                }

                $itemVariableList['itemTotalTaxAmount'] = $item['itemTotalTaxAmount'];
                $itemVariableList['itemTotalIgvAmount'] = $item['itemTotalIgvAmount'];
                $itemVariableList['itemTaxPercent'] = $item['itemTaxPercent'];
                $itemVariableList['itemIgvTaxCode'] = $item['itemIgvTaxCode'];
                $itemVariableList['itemTaxCode'] = $item['itemTaxCode'];
                $itemVariableList['itemTaxName'] = $item['itemTaxName'];
                $itemVariableList['itemTaxNamecode'] = $item['itemTaxNamecode'];
                $itemVariableList['itemDescription'] = $item['itemDescription'];
                $itemVariableList['ItemClassificationCode'] = $item['ItemClassificationCode'];
                $itemVariableList['singleItemBasePrice'] = $item['singleItemBasePrice'];

                foreach($itemVariableList as $key => $value)
                {
                    $creditNoteItemTemplate = str_replace('{{'.$key.'}}', $value, $creditNoteItemTemplate);
                }

                $xmlItem = new DOMDocument();
                $xmlItem->preserveWhiteSpace = false;
                $xmlItem->loadXML($creditNoteItemTemplate);
                $creditNoteLineNode = $xmlItem->getElementsByTagName('CreditNoteLine')->item(0);
                $itemTaxTotalNode = $xmlItem->getElementsByTagName('TaxTotal')->item(0);

                if ($item['itemBagCuantity'] > 0) {
                    $itemBagTaxTemplate = XmlTemplate::InvoiceItemBagTax();
                    $itemBagTaxVariableList = array();
                    $itemBagTaxVariableList['itemBagTaxCuantity'] = $item['itemBagCuantity'];
                    $itemBagTaxVariableList['bagTaxAmountPerUnit'] = $invoice['bagTaxAmountPerUnit'];
                    $itemBagTaxVariableList['codigoMoneda'] = $creditNote['codigoMoneda'];

                    foreach($itemBagTaxVariableList as $key => $value)
                    {
                        $itemBagTaxTemplate = str_replace('{{'.$key.'}}', $value, $itemBagTaxTemplate);
                    }

                    $xmlItemBagTax = new DOMDocument();
                    $xmlItemBagTax->preserveWhiteSpace = false;
                    $xmlItemBagTax->loadXML($itemBagTaxTemplate);
                    $node = $xmlItemBagTax->getElementsByTagName('TaxSubtotal')->item(0);
                    $node = $xmlItem->importNode($node, true);
                    $itemTaxTotalNode->appendChild($node);
                }

                if ($item['itemIscAmount'] > 0) {
                    $itemIscTemplate = XmlTemplate::InvoiceItemIsc();
                    $itemIscVariableList = array();
                    $itemIscVariableList['itemIscTaxableAmount'] = $item['itemIscTaxableAmount'];
                    $itemIscVariableList['itemIscAmount'] = $item['itemIscAmount'];
                    $itemIscVariableList['itemIscTaxPercent'] = $item['itemIscTaxPercent'];
                    $itemIscVariableList['itemIgvTaxCode'] = $item['itemIgvTaxCode'];
                    $itemIscVariableList['itemIscSystemType'] = $item['itemIscSystemType'];
                    $itemIscVariableList['codigoMoneda'] = $creditNote['codigoMoneda'];

                    foreach($itemIscVariableList as $key => $value)
                    {
                        $itemIscTemplate = str_replace('{{'.$key.'}}', $value, $itemIscTemplate);
                    }

                    $xmlItemIsc = new DOMDocument();
                    $xmlItemIsc->preserveWhiteSpace = false;
                    $xmlItemIsc->loadXML($itemIscTemplate);
                    $node = $xmlItemIsc->getElementsByTagName('TaxSubtotal')->item(0);
                    $node = $xmlItem->importNode($node, true);
                    $itemTaxTotalNode->appendChild($node);
                }

                if ($item['itemDiscountAmount'] > 0) {
                    $itemDiscountTemplate = XmlTemplate::InvoiceItemAllowanceCharge();

                    $itemDiscountVariableList = array();
                    $itemDiscountVariableList['chargeIndicator'] = 'false';
                    $itemDiscountVariableList['allowanceChargeCode'] = '00';
                    $itemDiscountVariableList['allowanceChargePercent'] = $item['itemDiscountPercent'];
                    $itemDiscountVariableList['allowanceChargeAmount'] = $item['itemDiscountAmount'];
                    $itemDiscountVariableList['allowanceChargeBaseAmount'] = $item['itemTotalBaseAmount'];
                    $itemDiscountVariableList['codigoMoneda'] = $creditNote['codigoMoneda'];

                    foreach($itemDiscountVariableList as $key => $value)
                    {
                        $itemDiscountTemplate = str_replace('{{'.$key.'}}', $value, $itemDiscountTemplate);
                    }

                    $xmlItemDiscount = new DOMDocument();
                    $xmlItemDiscount->preserveWhiteSpace = false;
                    $xmlItemDiscount->loadXML($itemDiscountTemplate);
                    $node = $xmlItemDiscount->getElementsByTagName('AllowanceCharge')->item(0);
                    $node = $xmlItem->importNode($node, true);
                    $creditNoteLineNode->insertBefore($node, $itemTaxTotalNode);
                }

                $node = $xmlItem->getElementsByTagName('CreditNoteLine')->item(0);
                $node = $xml->importNode($node, true);
                $creditNoteNode->appendChild($node);
            }

            $xml->formatOutput = true;

            file_put_contents($folderPath.$fileName, $xml->saveXML());

            $res->digestValue = $this->SignDocument($folderPath.$fileName);

            $this->sunatXmlModel->Insert($xmlTypeId, $referenceId, $userId);

            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage()."\n\n".$e->getTraceAsString();
        }

        return $res;
    }

    private function SaveDebitNote($folderPath, $fileName, $referenceId, $debitNote, $xmlTypeId, $userId)
    {
        $res = new Result();
        try {
            $debitNoteTemplate = XmlTemplate::DebitNoteBase();

            $variableList = array();

            $variableList['serie'] = $debitNote['serie'];
            $variableList['number'] = $debitNote['number'];
            $variableList['issueDate'] = $debitNote['issueDate'];
            $variableList['issueTime'] = $debitNote['issueTime'];
            $variableList['amounInWord'] = $debitNote['amounInWord'];
            $variableList['debitNoteTypeCode'] = $debitNote['debitNoteTypeCode'];
            $variableList['debitNoteTypeDescription'] = $debitNote['debitNoteTypeDescription'];
            $variableList['supplierRuc'] = $debitNote['supplierRuc'];
            $variableList['defaultUrl'] = $debitNote['defaultUrl'];
            $variableList['supplierName'] = $debitNote['supplierName'];
            $variableList['supplierDocumentType'] = $debitNote['supplierDocumentType'];
            $variableList['customerDocumentType'] = $debitNote['customerDocumentType'];
            $variableList['customerDocument'] = $debitNote['customerDocument'];
            $variableList['customerName'] = $debitNote['customerName'];
            $variableList['totalTaxAmount'] = $debitNote['totalTaxAmount'];
            $variableList['totalBaseAmount'] = $debitNote['totalBaseAmount'];
            $variableList['totalSaleAmount'] = $debitNote['totalSaleAmount'];
            $variableList['totalDiscountAmount'] = $debitNote['totalDiscountAmount'];
            $variableList['totalExtraChargeAmount'] = $debitNote['totalExtraChargeAmount'];
            $variableList['totalPrepaidAmount'] = $debitNote['totalPrepaidAmount'];
            $variableList['totalPayableAmount'] = $debitNote['totalPayableAmount'];
            $variableList['codigoMoneda'] = $debitNote['codigoMoneda'];

            foreach($variableList as $key => $value)
            {
                $debitNoteTemplate = str_replace('{{'.$key.'}}', $value, $debitNoteTemplate);
            }

            $xml = new DOMDocument();
            $xml->preserveWhiteSpace = false;
            $xml->loadXML($debitNoteTemplate);
            $TaxTotal = $xml->getElementsByTagName('TaxTotal')->item(0);
            $debitNoteNode = $xml->getElementsByTagName('DebitNote')->item(0);
            $signatureNode = $xml->getElementsByTagName('Signature')->item(0);
            $currencyNode = $xml->getElementsByTagName('DocumentCurrencyCode')->item(0);

            if ($debitNote['amazoniaGoods'] == 1) {
                $captionTemplate = XmlTemplate::InvoiceCaption();

                $captionVariableList = array();
                $captionVariableList['captionCode'] = '2001';
                $captionVariableList['captionDescription'] = 'BIENES TRANSFERIDOS EN LA AMAZONÍA REGIÓN SELVAPARA SER CONSUMIDOS EN LA MISMA';

                foreach($captionVariableList as $key => $value)
                {
                    $captionTemplate = str_replace('{{'.$key.'}}', $value, $captionTemplate);
                }

                $captionXml = new DOMDocument();
                $captionXml->preserveWhiteSpace = false;
                $captionXml->loadXML($captionTemplate);
                $node = $captionXml->getElementsByTagName('Note')->item(0);
                $node = $xml->importNode($node, true);
                $debitNoteNode->insertBefore($node, $currencyNode);
            }

            if ($debitNote['amazoniaService'] == 1) {
                $captionTemplate = XmlTemplate::InvoiceCaption();

                $captionVariableList = array();
                $captionVariableList['captionCode'] = '2002';
                $captionVariableList['captionDescription'] = 'SERVICIOS PRESTADOS EN LA AMAZONÍA REGIÓN SELVA PARA SER CONSUMIDOS EN LA MISMA';

                foreach($captionVariableList as $key => $value)
                {
                    $captionTemplate = str_replace('{{'.$key.'}}', $value, $captionTemplate);
                }

                $captionXml = new DOMDocument();
                $captionXml->preserveWhiteSpace = false;
                $captionXml->loadXML($captionTemplate);
                $node = $captionXml->getElementsByTagName('Note')->item(0);
                $node = $xml->importNode($node, true);
                $debitNoteNode->insertBefore($node, $currencyNode);
            }

            if (strlen($debitNote['orderReference']) > 0) {
                $captionTemplate = XmlTemplate::InvoiceOrderReference();

                $captionVariableList = array();
                $captionVariableList['orderReference'] = $debitNote['orderReference'];

                foreach($captionVariableList as $key => $value)
                {
                    $captionTemplate = str_replace('{{'.$key.'}}', $value, $captionTemplate);
                }

                $captionXml = new DOMDocument();
                $captionXml->preserveWhiteSpace = false;
                $captionXml->loadXML($captionTemplate);
                $node = $captionXml->getElementsByTagName('OrderReference')->item(0);
                $node = $xml->importNode($node, true);
                $debitNoteNode->insertBefore($node, $signatureNode);
            }

            foreach ($debitNote['invoiceReferenceList'] as $referencedInvoice) {
                $billingReferenceTemplate = XmlTemplate::NoteBillingReference();
                $billingReferenceVariableList = array();
                $billingReferenceVariableList['billingReferenceSerie'] = $referencedInvoice['billingReferenceSerie'];
                $billingReferenceVariableList['billingReferenceNumber'] = $referencedInvoice['billingReferenceNumber'];
                $billingReferenceVariableList['billingReferenceTypeCode'] = $referencedInvoice['billingReferenceTypeCode'];

                foreach($billingReferenceVariableList as $key => $value)
                {
                    $billingReferenceTemplate = str_replace('{{'.$key.'}}', $value, $billingReferenceTemplate);
                }

                $billingReferenceXml = new DOMDocument();
                $billingReferenceXml->preserveWhiteSpace = false;
                $billingReferenceXml->loadXML($billingReferenceTemplate);
                $node = $billingReferenceXml->getElementsByTagName('BillingReference')->item(0);
                $node = $xml->importNode($node, true);
                $debitNoteNode->insertBefore($node, $signatureNode);
            }

            if (isset($debitNote['referenceDocumentList'])) {
                foreach ($debitNote['referenceDocumentList'] as $referencedDocument) {
                    $referenceDocumenTemplate = XmlTemplate::InvoiceDocumentReference();

                    $itemVariableList = array();
                    $itemVariableList['referencedDocument'] = $referencedDocument['referencedDocument'];
                    $itemVariableList['referencedDocumentTypeCode'] = $referencedDocument['referencedDocumentTypeCode'];

                    foreach($itemVariableList as $key => $value)
                    {
                        $referenceDocumenTemplate = str_replace('{{'.$key.'}}', $value, $referenceDocumenTemplate);
                    }

                    $referencedDocumentXml = new DOMDocument();
                    $referencedDocumentXml->preserveWhiteSpace = false;
                    $referencedDocumentXml->loadXML($referenceDocumenTemplate);
                    $node = $referencedDocumentXml->getElementsByTagName('DespatchDocumentReference')->item(0);
                    $node = $xml->importNode($node, true);
                    $debitNoteNode->insertBefore($node, $signatureNode);
                }
            }

            if ($debitNote['totalIgvAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $debitNote['totalIgvTaxableAmount'];
                $igvVariableList['totalTaxAmount'] = $debitNote['totalIgvAmount'];
                $igvVariableList['taxId'] = 1000;
                $igvVariableList['taxName'] = "IGV";
                $igvVariableList['taxTypeCode'] = "VAT";
                $igvVariableList['codigoMoneda'] = $debitNote['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($debitNote['totalIscAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $debitNote['totalIscTaxableAmount'];
                $igvVariableList['totalTaxAmount'] = $debitNote['totalIscAmount'];
                $igvVariableList['taxId'] = 2000;
                $igvVariableList['taxName'] = "ISC";
                $igvVariableList['taxTypeCode'] = "EXC";
                $igvVariableList['codigoMoneda'] = $debitNote['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($debitNote['totalFreeAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $debitNote['totalFreeAmount'];
                $igvVariableList['totalTaxAmount'] = "0.00";
                $igvVariableList['taxId'] = 9996;
                $igvVariableList['taxName'] = "GRA";
                $igvVariableList['taxTypeCode'] = "FRE";
                $igvVariableList['codigoMoneda'] = $debitNote['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($debitNote['totalExoneratedAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $debitNote['totalExoneratedAmount'];
                $igvVariableList['totalTaxAmount'] = "0.00";
                $igvVariableList['taxId'] = 9997;
                $igvVariableList['taxName'] = "EXO";
                $igvVariableList['taxTypeCode'] = "VAT";
                $igvVariableList['codigoMoneda'] = $debitNote['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($debitNote['totalInafectedAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $debitNote['totalInafectedAmount'];
                $igvVariableList['totalTaxAmount'] = "0.00";
                $igvVariableList['taxId'] = 9998;
                $igvVariableList['taxName'] = "INA";
                $igvVariableList['taxTypeCode'] = "FRE";
                $igvVariableList['codigoMoneda'] = $debitNote['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($debitNote['totalOtherTaxAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalTax();
                $igvVariableList = array();
                $igvVariableList['totalTaxableAmount'] = $debitNote['totalOtherTaxTaxableAmount'];
                $igvVariableList['totalTaxAmount'] = $debitNote['totalOtherTaxAmount'];
                $igvVariableList['taxId'] = 9999;
                $igvVariableList['taxName'] = "OTROS CONCEPTOS DE PAGO";
                $igvVariableList['taxTypeCode'] = "OTH";
                $igvVariableList['codigoMoneda'] = $debitNote['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($debitNote['totalBagTaxAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalBagTax();
                $igvVariableList = array();
                $igvVariableList['totalBagTaxAmount'] = $debitNote['totalBagTaxAmount'];
                $igvVariableList['bagTaxAmount'] = $debitNote['totalBagTaxAmount'];
                $igvVariableList['codigoMoneda'] = $debitNote['codigoMoneda'];

                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('TaxSubtotal')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
            }

            if ($debitNote['globalDiscountAmount'] > 0) {
                $igvTaxSubTotalTemplate = XmlTemplate::TotalDiscount();
                $igvVariableList = array();
                $igvVariableList['globalDiscountPercent'] = $debitNote['globalDiscountPercent'];
                $igvVariableList['globalDiscountAmount'] = $debitNote['globalDiscountAmount'];
                $igvVariableList['totalBaseAmount'] = $debitNote['totalBaseAmount'];
                $igvVariableList['codigoMoneda'] = $debitNote['codigoMoneda'];
                foreach($igvVariableList as $key => $value)
                {
                    $igvTaxSubTotalTemplate = str_replace('{{'.$key.'}}', $value, $igvTaxSubTotalTemplate);
                }

                $xmligvTax = new DOMDocument();
                $xmligvTax->preserveWhiteSpace = false;
                $xmligvTax->loadXML($igvTaxSubTotalTemplate);
                $node = $xmligvTax->getElementsByTagName('AllowanceCharge')->item(0);
                $node = $xml->importNode($node, true);
                $TaxTotal->appendChild($node);
                $debitNoteNode->insertBefore($node, $TaxTotal);
            }

            $itemCorrelative = 1;
            foreach ($debitNote['itemList'] as $item) {
                $debitNoteItemTemplate = XmlTemplate::DebitNoteItem();

                $itemVariableList = array();
                $itemVariableList['itemCorrelative'] = $itemCorrelative++;
                $itemVariableList['itemUnitCode'] = $item['itemUnitCode'];
                $itemVariableList['itemCuantity'] = $item['itemCuantity'];
                $itemVariableList['itemFinalBaseAmount'] = $item['itemFinalBaseAmount'];
                $itemVariableList['itemIgvTaxableAmount'] = $item['itemIgvTaxableAmount'];
                $itemVariableList['singleItemPrice'] = $item['singleItemPrice'];
                $itemVariableList['codigoMoneda'] = $debitNote['codigoMoneda'];
                if ($item['onerous'] == 1) {
                    $itemVariableList['itemTransactionType'] = '01';
                }
                else{
                    $itemVariableList['itemTransactionType'] = '02';
                }

                $itemVariableList['itemTotalTaxAmount'] = $item['itemTotalTaxAmount'];
                $itemVariableList['itemTotalIgvAmount'] = $item['itemTotalIgvAmount'];
                $itemVariableList['itemTaxPercent'] = $item['itemTaxPercent'];
                $itemVariableList['itemIgvTaxCode'] = $item['itemIgvTaxCode'];
                $itemVariableList['itemTaxCode'] = $item['itemTaxCode'];
                $itemVariableList['itemTaxName'] = $item['itemTaxName'];
                $itemVariableList['itemTaxNamecode'] = $item['itemTaxNamecode'];
                $itemVariableList['itemDescription'] = $item['itemDescription'];
                $itemVariableList['ItemClassificationCode'] = $item['ItemClassificationCode'];
                $itemVariableList['singleItemBasePrice'] = $item['singleItemBasePrice'];

                foreach($itemVariableList as $key => $value)
                {
                    $debitNoteItemTemplate = str_replace('{{'.$key.'}}', $value, $debitNoteItemTemplate);
                }

                $xmlItem = new DOMDocument();
                $xmlItem->preserveWhiteSpace = false;
                $xmlItem->loadXML($debitNoteItemTemplate);
                $debitNoteLineNode = $xmlItem->getElementsByTagName('debitNoteLine')->item(0);
                $itemTaxTotalNode = $xmlItem->getElementsByTagName('TaxTotal')->item(0);

                if ($item['itemBagCuantity'] > 0) {
                    $itemBagTaxTemplate = XmlTemplate::InvoiceItemBagTax();
                    $itemBagTaxVariableList = array();
                    $itemBagTaxVariableList['itemBagTaxCuantity'] = $item['itemBagCuantity'];
                    $itemBagTaxVariableList['bagTaxAmountPerUnit'] = $invoice['bagTaxAmountPerUnit'];
                    $itemBagTaxVariableList['codigoMoneda'] = $debitNote['codigoMoneda'];

                    foreach($itemBagTaxVariableList as $key => $value)
                    {
                        $itemBagTaxTemplate = str_replace('{{'.$key.'}}', $value, $itemBagTaxTemplate);
                    }

                    $xmlItemBagTax = new DOMDocument();
                    $xmlItemBagTax->preserveWhiteSpace = false;
                    $xmlItemBagTax->loadXML($itemBagTaxTemplate);
                    $node = $xmlItemBagTax->getElementsByTagName('TaxSubtotal')->item(0);
                    $node = $xmlItem->importNode($node, true);
                    $itemTaxTotalNode->appendChild($node);
                }

                if ($item['itemIscAmount'] > 0) {
                    $itemIscTemplate = XmlTemplate::InvoiceItemIsc();
                    $itemIscVariableList = array();
                    $itemIscVariableList['itemIscTaxableAmount'] = $item['itemIscTaxableAmount'];
                    $itemIscVariableList['itemIscAmount'] = $item['itemIscAmount'];
                    $itemIscVariableList['itemIscTaxPercent'] = $item['itemIscTaxPercent'];
                    $itemIscVariableList['itemIgvTaxCode'] = $item['itemIgvTaxCode'];
                    $itemIscVariableList['itemIscSystemType'] = $item['itemIscSystemType'];
                    $itemIscVariableList['codigoMoneda'] = $debitNote['codigoMoneda'];

                    foreach($itemIscVariableList as $key => $value)
                    {
                        $itemIscTemplate = str_replace('{{'.$key.'}}', $value, $itemIscTemplate);
                    }

                    $xmlItemIsc = new DOMDocument();
                    $xmlItemIsc->preserveWhiteSpace = false;
                    $xmlItemIsc->loadXML($itemIscTemplate);
                    $node = $xmlItemIsc->getElementsByTagName('TaxSubtotal')->item(0);
                    $node = $xmlItem->importNode($node, true);
                    $itemTaxTotalNode->appendChild($node);
                }

                if ($item['itemDiscountAmount'] > 0) {
                    $itemDiscountTemplate = XmlTemplate::InvoiceItemAllowanceCharge();

                    $itemDiscountVariableList = array();
                    $itemDiscountVariableList['chargeIndicator'] = 'false';
                    $itemDiscountVariableList['allowanceChargeCode'] = '00';
                    $itemDiscountVariableList['allowanceChargePercent'] = $item['itemDiscountPercent'];
                    $itemDiscountVariableList['allowanceChargeAmount'] = $item['itemDiscountAmount'];
                    $itemDiscountVariableList['allowanceChargeBaseAmount'] = $item['itemTotalBaseAmount'];
                    $itemDiscountVariableList['codigoMoneda'] = $debitNote['codigoMoneda'];

                    foreach($itemDiscountVariableList as $key => $value)
                    {
                        $itemDiscountTemplate = str_replace('{{'.$key.'}}', $value, $itemDiscountTemplate);
                    }

                    $xmlItemDiscount = new DOMDocument();
                    $xmlItemDiscount->preserveWhiteSpace = false;
                    $xmlItemDiscount->loadXML($itemDiscountTemplate);
                    $node = $xmlItemDiscount->getElementsByTagName('AllowanceCharge')->item(0);
                    $node = $xmlItem->importNode($node, true);
                    $debitNoteLineNode->insertBefore($node, $itemTaxTotalNode);
                }

                $node = $xmlItem->getElementsByTagName('DebitNoteLine')->item(0);
                $node = $xml->importNode($node, true);
                $debitNoteNode->appendChild($node);
            }

            $xml->formatOutput = true;

            file_put_contents($folderPath.$fileName, $xml->saveXML());

            $res->digestValue = $this->SignDocument($folderPath.$fileName);

            $this->sunatXmlModel->Insert($xmlTypeId, $referenceId, $userId);

            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage()."\n\n".$e->getTraceAsString();
        }

        return $res;
    }

    private function SaveAvoidance($folderPath, $fileName, $referenceId, $avoidance, $xmlTypeId, $userId)
    {
        $res = new Result();
        try {
            $debitNoteTemplate = XmlTemplate::VoidCommunicationBase();

            $variableList = array();

            $variableList['idDate'] = str_replace('-', '', $avoidance['issueDate']);
            $variableList['correlativeNumber'] = $avoidance['correlativeNumber'];
            $variableList['referenceDate'] = $avoidance['referenceDate'];
            $variableList['issueDate'] = $avoidance['issueDate'];
            $variableList['supplierRuc'] = $avoidance['supplierRuc'];
            $variableList['defaultUrl'] = $avoidance['defaultUrl'];
            $variableList['supplierName'] = $avoidance['supplierName'];
            $variableList['supplierDocumentType'] = $avoidance['supplierDocumentType'];
            $variableList['documentTypeCode'] = $avoidance['documentTypeCode'];
            $variableList['documentSerie'] = $avoidance['documentSerie'];
            $variableList['documentNumber'] = $avoidance['documentNumber'];
            $variableList['reason'] = $avoidance['reason'];

            foreach($variableList as $key => $value)
            {
                $debitNoteTemplate = str_replace('{{'.$key.'}}', $value, $debitNoteTemplate);
            }

            $xml = new DOMDocument();
            $xml->preserveWhiteSpace = false;
            $xml->loadXML($debitNoteTemplate);
            $xml->formatOutput = true;

            file_put_contents($folderPath.$fileName, $xml->saveXML());
            $res->digestValue = $this->SignDocument($folderPath.$fileName);
            $this->sunatXmlModel->Insert($xmlTypeId, $referenceId, $userId);
            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage()."\n\n".$e->getTraceAsString();
        }

        return $res;
    }

    private function SaveReferralGuide($folderPath, $fileName, $referenceId, $referralGuide, $xmlTypeId, $userId)
    {
        $res = new Result();
        try {
            $referralGuideTemplate = XmlTemplate::ReferalGuideBase();

            $variableList = array();

            $variableList['serie'] = $referralGuide['serie'];
            $variableList['number'] = $referralGuide['number'];
            $variableList['issueDate'] = $referralGuide['issueDate'];
            $variableList['issueTime'] = $referralGuide['issueTime'];
            $variableList['invoiceTypeCode'] = $referralGuide['invoiceTypeCode'];
            $variableList['supplierRuc'] = $referralGuide['supplierRuc'];
            $variableList['defaultUrl'] = $referralGuide['defaultUrl'];
            $variableList['supplierName'] = $referralGuide['supplierName'];
            $variableList['supplierDocumentType'] = $referralGuide['supplierDocumentType'];
            $variableList['customerDocumentType'] = $referralGuide['customerDocumentType'];
            $variableList['customerDocument'] = $referralGuide['customerDocument'];
            $variableList['customerName'] = $referralGuide['customerName'];
            $variableList['transferReasonCode'] = $referralGuide['transferReasonCode'];
            $variableList['transferReason'] = $referralGuide['transferReason'];
            $variableList['grossWeightMeasure'] = $referralGuide['grossWeightMeasure'];
            $variableList['grossWeight'] = $referralGuide['grossWeight'];
            $variableList['packageQuantity'] = $referralGuide['packageQuantity'];
            $variableList['transferMethodCode'] = $referralGuide['transferMethodCode'];
            $variableList['referralDate'] = $referralGuide['referralDate'];
            $variableList['carrierDocumentType'] = $referralGuide['carrierDocumentType'];
            $variableList['carrierRuc'] = $referralGuide['carrierRuc'];
            $variableList['carrierName'] = $referralGuide['carrierName'];
            $variableList['driverDocumentType'] = $referralGuide['driverDocumentType'];
            $variableList['driverDocument'] = $referralGuide['driverDocument'];
            $variableList['licensePlate'] = $referralGuide['licensePlate'];
            $variableList['deliveryAdressCode'] = $referralGuide['deliveryAdressCode'];
            $variableList['deliveryAdress'] = $referralGuide['deliveryAdress'];
            $variableList['originAdressCode'] = $referralGuide['originAdressCode'];
            $variableList['originAdress'] = $referralGuide['originAdress'];

            foreach($variableList as $key => $value)
            {
                $referralGuideTemplate = str_replace('{{'.$key.'}}', $value, $referralGuideTemplate);
            }

            $xml = new DOMDocument();
            $xml->preserveWhiteSpace = false;
            $xml->loadXML($referralGuideTemplate);
            $referralGuideNode = $xml->getElementsByTagName('DespatchAdvice')->item(0);

            $itemCorrelative = 1;
            foreach ($referralGuide['itemList'] as $item) {
                $referralGuideItemTemplate = XmlTemplate::ReferalGuideItem();

                $itemVariableList = array();
                $itemVariableList['itemCorrelative'] = $itemCorrelative++;
                $itemVariableList['itemUnitCode'] = $item['itemUnitCode'];
                $itemVariableList['itemCuantity'] = $item['itemCuantity'];
                $itemVariableList['itemDescription'] = $item['itemDescription'];
                $itemVariableList['itemCode'] = $item['itemCode'];

                foreach($itemVariableList as $key => $value)
                {
                    $referralGuideItemTemplate = str_replace('{{'.$key.'}}', $value, $referralGuideItemTemplate);
                }

                $xmlItem = new DOMDocument();
                $xmlItem->preserveWhiteSpace = false;
                $xmlItem->loadXML($referralGuideItemTemplate);
                $node = $xmlItem->getElementsByTagName('DespatchLine')->item(0);
                $node = $xml->importNode($node, true);
                $referralGuideNode->appendChild($node);
            }

            $xml->formatOutput = true;
            file_put_contents($folderPath.$fileName, $xml->saveXML());

            $res->digestValue = $this->SignDocument($folderPath.$fileName);

            $this->sunatXmlModel->Insert($xmlTypeId, $referenceId, $userId);

            $res->success = true;
        } catch (Exception $e) {
            $res->message = $e->getMessage()."\n\n".$e->getTraceAsString();
        }

        return $res;
    }

    public function GetStatus($supplierRuc, $sunatSummaryResponseId, $userId)
  {
    $res = new Result();
    try {
      $sunatSummaryResponse = $this->sunatSummaryResponseModel->Get($sunatSummaryResponseId);

      if ($sunatSummaryResponse == false) {
        throw new Exception('Wrong summary submmission id.');
      }

      if ($sunatSummaryResponse['response_code'] == '0' || $sunatSummaryResponse['response_code'] == '99') {
        throw new Exception('Selected summary submission already got an answer.');
      }

      try {
        $url = SUNAT_SERVICE_URL;

        $options = array(
          'Username' => '20100066603MODDATOS',
          'Password' => 'moddatos',
        );

        $client = new SoapClient($url, $options);

        $data = array(
          'ticket' => $sunatSummaryResponse['ticket'],
        );

        $result = $client->getStatus($data);

        $this->sunatSummaryResponseModel->UpdateSunatResponse($sunatSummaryResponseId, $result->statusCode, $userId);

        if ($result->statusCode == 0 || $result->statusCode == 99) {
          $folderPath = $this->FolderPathValidation($invoice['supplierRuc']);

          $this->UnzipResponse($folderPath, $sunatSummaryResponse['ticket'].'.zip', $result->content);
        }
      } catch ( SoapFault $e ) {
        throw new Exception('Error executing SUNAT connection : '. $e->getMessage());
      }

      $res->success = true;
    } catch (Exception $e) {
      $res->message = $e->getMessage()."\n\n".$e->getTraceAsString();
    }

    return $res;
  }

    private function FolderPathValidation($supplierRuc)
  {
    try {
      $todayFolderName = date('Ym');
      $rootPath = FILE_PATH . '/xml/';
      if (!file_exists(ROOT_DIR. $rootPath. $todayFolderName)) {
        mkdir(ROOT_DIR.$rootPath.$todayFolderName);
      }

      if (!file_exists(ROOT_DIR.$rootPath.$todayFolderName.'/'.$supplierRuc)) {
        mkdir(ROOT_DIR.$rootPath.$todayFolderName.'/'.$supplierRuc);
      }

      return ROOT_DIR.$rootPath.$todayFolderName.'/'.$supplierRuc.'/';

    } catch (Exception $e) {
      throw new Exception('Error in : ' .__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
    }
  }

    private function SignDocument($filePath)
    {
        try {
            $doc = new DOMDocument();
            $doc->loadXML(file_get_contents($filePath));
            $objDSig = new DigestGenerator();
            $objDSig->setCanonicalMethod(DigestGenerator::EXC_C14N);
            $digestValue = $objDSig->addReference($doc, DigestGenerator::SHA256, array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'), $options = array('force_uri' => true));
            $objKey = new SignatureGenerator(SignatureGenerator::RSA_SHA256, array('type'=>'private'));
            $objKey->loadKey(dirname(__FILE__) . '/privkey.pem', TRUE);
            $objDSig->sign($objKey, $doc->getElementsByTagName('ExtensionContent')->item(0));
            $objDSig->add509Cert(file_get_contents(dirname(__FILE__) . '/mycert.pem'));

            file_put_contents($filePath, $doc->saveXML());
            return $digestValue;
        } catch (Exception $e) {
            throw new Exception('Error in : ' .__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    private function SendDocument($communicationMethod, $folderPath, $fileName)
    {
        $res = new Result();
        try {
            $zip = new ZipArchive;

            $zipPath = $folderPath . str_replace('.xml', '.zip', $fileName);
            $zipName = str_replace('.xml', '.zip', $fileName);
            $answerZipName = str_replace('.xml', '-Respuesta.zip', $fileName);

            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE)
            {
                // Add files to the zip file
                // $zip->addEmptyDir('dummy');
                $zip->addFile($folderPath.$fileName, $fileName);
                $zip->close();
            }

            $content = file_get_contents($zipPath);


            try {
                $options = array(
                    // 'Username' => '20100066603MODDATOS',
                    'Username' => '10451329575MODDATOS',
                    'Password' => 'moddatos',
                );

                $data = array(
                    'fileName' => $zipName,
                    'contentFile' => $content,
                );

                if ($communicationMethod == 1 || $communicationMethod == 2) {
                    $url = SUNAT_SERVICE_URL;
                    $client = new SoapClient($url, $options);

                    if ($communicationMethod == 1) {
                        $result = $client->sendBill($data);
                        $this->UnzipResponse($folderPath, $answerZipName, $result->applicationResponse);
                    }
                    else {
                        $res->ticket = $client->sendSummary($data);
                    }
                }
                else{
                    $url = SUNAT_GUIDE_SERVICE_URL;
                    $client = new SoapClient($url, $options);

                    $result = $client->sendBill($data);
                    $this->UnzipResponse($folderPath, $answerZipName, $result->applicationResponse);
                }

                unlink($zipPath);

            } catch ( SoapFault $e ) {
                throw new Exception('Error executing SUNAT connection : '. $e->getMessage());
            }

            $res->success = true;
        } catch (Exception $e) {
            $res->message =  'Error in : ' .__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString();
        }

        return $res;
    }

    private function ReadSunatAnswer($folderPath, $fileName)
    {
        $res = new Result();
        try {
            $sunatAnswer = simplexml_load_file($folderPath.'R-'.$fileName, null, null, 'ar', true);
            $x = $sunatAnswer->children('cac',true);
            $y = $x->DocumentResponse->children('cac',true);
            $response = $y->Response->children('cbc',true);
            $res->sunatDescription = (string)$response->Description;
            $res->sunatResponseCode = (string)$response->ResponseCode;
            $res->cdrPath = str_replace(ROOT_DIR , '',$folderPath.'R-'.$fileName);
            $res->success = true;
        } catch (Exception $e) {
            $res->success = false;
            $res->message =  'Error in : ' .__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString();
        }

        return $res;
    }

    private function UnzipResponse($folderPath, $zipName, $zipByteArray)
    {
        try {
            $temporaryZip = tempnam(sys_get_temp_dir(), $zipName);
            file_put_contents($temporaryZip, $zipByteArray);

            $zip = new ZipArchive;
            if (true === $zip->open($temporaryZip)) {
                $zip->extractTo($folderPath);
                $zip->close();
            }

            unlink($temporaryZip);
        } catch (Exception $e) {
            throw new Exception('Error in : ' .__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }
}
?>
