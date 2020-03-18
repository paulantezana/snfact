<?php

require_once __DIR__ . '/DocumentSizePDF.php';
require_once ROOT_DIR . '/src/Helpers/QRCode/qrcode.class.php';

class DocumentManager
{
    private $pdf;

    public function __construct()
    {
        $this->pdf = new PDF();
    }

    public function Invoice($invoice, $size, $valid)
    {
        $res = new Result();
        try {
            $this->SetDocumentBase($size, $valid);

            if (strtoupper($size) === 'A4' || strtoupper($size) === 'A5') {
                $this->GenerateInvoiceA4A5($invoice);
            } elseif (strtoupper($size) == 'TICKET') {
                $this->GenerateInvoiceTicket($invoice);
            } else {
                throw new Exception('unsupported document size!');
            }

            $folderPath = $this->ValidateFolderPath($invoice['businessRuc']);
            $fileName = sha1($invoice['businessRuc'] . '-' . $invoice['documentCode'] . '-' . $invoice['serie'] . '-' . $invoice['correlative'] . '-' . $size);

            $this->pdf->Output('F', $folderPath['absolutePath'] . $fileName . '.pdf');
            $res->success = true;
            $res->pdfPath = $folderPath['relativePath'] . $fileName . '.pdf';
        } catch (Exception $e) {
            $res->success = false;
            $res->errorMessage = $res->errorMessage = $e->getMessage() . "\n\n" . $e->getTraceAsString();
        }
        return $res;
    }

    public function InvoiceNCND($invoice, $size, $valid)
    {
        $res = new Result();
        try {
            $this->SetDocumentBase($size, $valid);

            if (strtoupper($size) === 'A4' || strtoupper($size) === 'A5') {
                $this->GenerateInvoiceNCNDA4A5($invoice);
            } elseif (strtoupper($size) == 'TICKET') {
                $this->GenerateInvoiceNCNDTicket($invoice);
            } else {
                throw new Exception('unsupported document size!');
            }

            $folderPath = $this->ValidateFolderPath($invoice['businessRuc']);
            $fileName = sha1($invoice['businessRuc'] . '-' . $invoice['documentCode'] . '-' . $invoice['serie'] . '-' . $invoice['correlative'] . '-' . $size);

            $this->pdf->Output('F', $folderPath['absolutePath'] . $fileName . '.pdf');
            $res->success = true;
            $res->pdfPath = $folderPath['relativePath'] . $fileName . '.pdf';
        } catch (Exception $e) {
            $res->success = false;
            $res->errorMessage = $res->errorMessage = $e->getMessage() . "\n\n" . $e->getTraceAsString();
        }
        return $res;
    }

    public function Summary($summary, $size, $valid)
    {
        $res = new Result();
        try {
            $this->SetDocumentBase($size, $valid);

            if (strtoupper($size) === 'A4' || strtoupper($size) === 'A5') {
                $this->GenerateSummaryA4A5($summary);
            } elseif (strtoupper($size) == 'TICKET') {
                $this->GenerateSummaryTicket($summary);
            } else {
                throw new Exception('unsupported document size!');
            }

            $folderPath = $this->ValidateFolderPath($summary['businessRuc']);
            $fileName = sha1($summary['businessRuc'] . '-' . $summary['documentCode'] . '-' . $summary['serie'] . '-' . $summary['correlative'] . '-' . $size);

            $this->pdf->Output('F', $folderPath['absolutePath'] . $fileName . '.pdf');
            $res->success = true;
            $res->pdfPath = $folderPath['relativePath'] . $fileName . '.pdf';
        } catch (Exception $e) {
            $res->success = false;
            $res->errorMessage = $res->errorMessage = $e->getMessage() . "\n\n" . $e->getTraceAsString();
        }
        return $res;
    }

    public function Guide($guide, $size, $valid)
    {
        $res = new Result();
        try {
            $this->SetDocumentBase($size, $valid);

            if (strtoupper($size) === 'A4' || strtoupper($size) === 'A5') {
                $this->GenerateGuideA4A5($guide);
            } elseif (strtoupper($size) == 'TICKET') {
                $this->GenerateGuideTicket($guide);
            } else {
                throw new Exception('unsupported document size!');
            }

            $folderPath = $this->ValidateFolderPath($guide['businessRuc']);
            $fileName = sha1($guide['businessRuc'] . '-' . $guide['documentCode'] . '-' . $guide['serie'] . '-' . $guide['correlative'] . '-' . $size);

            $this->pdf->Output('F', $folderPath['absolutePath'] . $fileName . '.pdf');
            $res->success = true;
            $res->pdfPath = $folderPath['relativePath'] . $fileName . '.pdf';
        } catch (Exception $e) {
            $res->success = false;
            $res->errorMessage = $res->errorMessage = $e->getMessage() . "\n\n" . $e->getTraceAsString();
        }
        return $res;
    }

    public function Voided($voided, $size, $valid)
    {
        $res = new Result();
        try {
            $this->SetDocumentBase($size, $valid);

            if (strtoupper($size) === 'A4' || strtoupper($size) === 'A5') {
                $this->GenerateVoidedA4A5($voided);
            } elseif (strtoupper($size) == 'TICKET') {
                $this->GenerateVoidedTicket($voided);
            } else {
                throw new Exception('unsupported document size!');
            }

            $folderPath = $this->ValidateFolderPath($voided['businessRuc']);
            $fileName = sha1($voided['businessRuc'] . '-' . $voided['documentCode'] . '-' . $voided['serie'] . '-' . $voided['correlative'] . '-' . $size);

            $this->pdf->Output('F', $folderPath['absolutePath'] . $fileName . '.pdf');
            $res->success = true;
            $res->pdfPath = $folderPath['relativePath'] . $fileName . '.pdf';
        } catch (Exception $e) {
            $res->success = false;
            $res->errorMessage = $res->errorMessage = $e->getMessage() . "\n\n" . $e->getTraceAsString();
        }
        return $res;
    }

    private function GenerateInvoiceA4A5($invoice)
    {
        // Margin
        $marginLeft = 12;
        $marginTop = 12;
        $pageWidth = $this->pdf->GetPageWidth() - ($marginLeft + $marginLeft);
        $this->pdf->SetMargins($marginLeft, $marginTop, $marginLeft);

        // Config
        $gutter = 2;
        $clearCollapse = 1.5;
        $rightRectWidth = 60;
        $rightRectX = ($pageWidth - $rightRectWidth) + $marginLeft;
        $leftRectWidth = ($pageWidth - ($rightRectWidth + $gutter));
        $leftRectX = $marginLeft;
        $fontFamilyName = "Calibri";
        $fontSize = 9;

        // Header
        $beforeY = $this->DocumentHeaderA4($invoice);

        // Customer
        $labelWidth = 35;
        $labelX = $marginLeft + $clearCollapse;
        $descriptionWidth = $pageWidth - ($rightRectWidth + $gutter + $clearCollapse + $labelWidth);

        $this->pdf->SetXY($labelX, $beforeY);
        $this->pdf->SetFont($fontFamilyName, 'B', $fontSize);
        $this->pdf->Cell(35, 4, 'CLIENTE: ', 0, 0);
        $this->pdf->Ln();

        $this->pdf->SetXY($labelX, $this->pdf->GetY());
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell($labelWidth, 3.5, 'RUC');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->Cell($descriptionWidth, 3.5, ': ' . ($invoice['customerDocumentNumber']));
        $this->pdf->Ln();

        $this->pdf->SetXY($labelX, $this->pdf->GetY());
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell($labelWidth, 3.5, 'DENOMINACIÓN');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell($descriptionWidth, 3.5, ': ' . strtoupper($invoice['customerSocialReason']), 0, 'L');

        $this->pdf->SetXY($labelX, $this->pdf->GetY());
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell($labelWidth, 3.5, 'DIRECCIÓN');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell($descriptionWidth, 3.5, ': ' . strtoupper($invoice['customerFiscalAddress']), 0, 'L');

        $this->pdf->RoundedRect($leftRectX, $beforeY - 2, $leftRectWidth, $this->pdf->GetY() - $beforeY + 3.5, 2);

        // Description
        $this->pdf->RoundedRect($rightRectX, $beforeY - 2, $rightRectWidth, $this->pdf->GetY() - $beforeY + 3.5, 2);

        $beforeYAux = $beforeY;
        $beforeY = $this->pdf->GetY();

        // Date
        $this->pdf->SetXY($rightRectX + 2, $beforeYAux);
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell(28, 3.5, 'FECHA EMISIÓN');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell(0, 3.5, sprintf(": %s", $invoice['dateOfIssue']), 0, 'L');

        $this->pdf->SetXY($rightRectX + 2, $this->pdf->GetY());
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell(28, 3.5, 'FECHA DE VENC');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell(0, 3.5, sprintf(": %s", $invoice['dateOfDue']), 0, 'L');

        // Currency
        $this->pdf->SetXY($rightRectX + 2, $this->pdf->GetY());
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell(28, 3.5, 'MONEDA');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell(0, 3.5, sprintf(': %s', strtoupper($invoice['currencyDescription'])), 0, 'L');

        // Table
        $this->pdf->SetXY($marginLeft, $beforeY + 3.5);

        $this->pdf->SetTableWidths([12, 12, 18, 84, 20, 20, 20]);
        $this->pdf->SetTableHAligns(['C', 'C', 'C', 'L', 'R', 'R', 'R']);

        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->TableRow(['CANT', 'UM', 'CÓD', 'DESCRIPCIÓN', 'V/U', 'P/U', 'IMPORTE'], 7, true, 'H');
        $this->pdf->SetFont($fontFamilyName, '');

        foreach ($invoice['itemList'] as $key => $row) {
            //            $fill = ($key % 2) == 0;
            $discount = $row['discount'] > 0 ? '-' . $row['discount'] : '';
            $this->pdf->TableRow([
                $row['quantity'],
                $row['unitMeasureCode'],
                $row['productCode'],
                $row['productDescription'],
                $row['unitValue'],
                $row['unitPrice'] . $discount,
                $row['total'],
            ], 4, false, 'H');
        }
        $this->pdf->Ln(1.5);

        //186.00155555556
        $this->pdf->SetTableWidths([133, 20, 33]);
        $this->pdf->SetTableHAligns(['R', 'R', 'R']);

        $currencySymbol = $invoice['currencySymbol'];
        $this->pdf->SetFont($fontFamilyName, 'B');

        if ($invoice['totalDiscount'] > 0) {
            $this->pdf->TableRow(['DESCUENTO(-)', $currencySymbol, $invoice['totalDiscount']], 3.5, false, '');
        }

        if ($invoice['totalPrepayment'] > 0) {
            $this->pdf->TableRow(['ANTICIPO', $currencySymbol, $invoice['totalPrepayment']], 3.5, false, '');
        }

        if ($invoice['totalExonerated'] > 0) {
            $this->pdf->TableRow(['EXONERADA', $currencySymbol, $invoice['totalExonerated']], 3.5, false, '');
        }

        if ($invoice['totalUnaffected'] > 0) {
            $this->pdf->TableRow(['INAFECTA', $currencySymbol, $invoice['totalUnaffected']], 3.5, false, '');
        }

        $this->pdf->TableRow(['GRAVADA', $currencySymbol, $invoice['totalTaxed']], 3.5, false, '');

        if ($invoice['totalIsc'] > 0) {
            $this->pdf->TableRow(['ISC', $currencySymbol, $invoice['totalIsc']], 3.5, false, '');
        }

        $this->pdf->TableRow(['IGV '. $invoice['percentageIgv'] . ' %', $currencySymbol, $invoice['totalIgv']], 3.5, false, '');

        if ($invoice['totalFree'] > 0) {
            $this->pdf->TableRow(['GRATUITA', $currencySymbol, $invoice['totalFree']], 3.5, false, '');
        }

        if ($invoice['totalCharge'] > 0) {
            $this->pdf->TableRow(['OTROS CARGOS', $currencySymbol, $invoice['totalCharge']], 3.5, false, '');
        }

        if ($invoice['totalPlasticBagTax'] > 0) {
            $this->pdf->TableRow(['ICBPER', $currencySymbol, $invoice['totalPlasticBagTax']], 3.5, false, '');
        }

        $this->pdf->TableRow(['TOTAL', $currencySymbol, $invoice['total']], 3.5, false, '');

        $this->pdf->Ln();
        $this->pdf->Ln();

        // Perception
        if ($invoice['perceptionAmount'] > 0){
            $this->pdf->SetTableWidths([20, 40, 40, 40, 46]);
            $this->pdf->SetTableHAligns(['C', 'C', 'C', 'C', 'C']);
            $this->pdf->TableRow(['CODIGO','POCENTAGE','PERCEPCION','BASE IMPONIBLE','TOTAL CON PERCEPCION'],7,true,'H');
            $this->pdf->SetFont($fontFamilyName, '');
            $this->pdf->TableRow([$invoice['perceptionCode'],"{$invoice['perceptionPercentage']} %","$currencySymbol {$invoice['perceptionAmount']}","$currencySymbol {$invoice['perceptionBase']}","$currencySymbol {$invoice['totalWithPerception']}"],5, false, 'H');
        }
        $this->pdf->Ln();

        // Detraction
        if ($invoice['detractionCode']){
            $this->pdf->SetTableWidths([40, 40, 40]);
            $this->pdf->SetTableHAligns(['C', 'C', 'C']);
            $this->pdf->TableRow(['CODIGO DETRACCION','POCENTAGE','DETRACCION'],7,true,'H');
            $this->pdf->SetFont($fontFamilyName, '');
            $this->pdf->TableRow([$invoice['detractionCode'],"{$invoice['detractionPercentage']} %","$currencySymbol {$invoice['detractionAmount']}"],5, false, 'H');
            $this->pdf->Ln();
        }

        if ($invoice['detractionCode']){
            $this->pdf->SetTableWidths([40, 40, 25, 25, 20, 35]);
            $this->pdf->SetTableHAligns(['C', 'C', 'C','C','C','C']);
            $this->pdf->TableRow(['Punto de partida','Punto de llegada','Valor Referencial' ,'Carga Efectiva','Carga Útil','Detalle del Viaje'],5,true,'H');
            $this->pdf->SetFont($fontFamilyName, '');
            $this->pdf->TableRow([
                $invoice['detractionLocationStartPoint'],
                $invoice['detractionLocationEndPoint'],
                $invoice['detractionReferralValue'],
                $invoice['detractionEffectiveLoad'],
                $invoice['detractionUsefulLoad'],
                $invoice['detractionTravelDetail'],
            ],5, false, 'H');
            $this->pdf->Ln();
        }

        if ($invoice['detractionCode']){
            $this->pdf->SetTableWidths([40, 40, 25, 25, 20, 35]);
            $this->pdf->SetTableHAligns(['C', 'C', 'C','C','C','C']);
            $this->pdf->TableRow(['Matrícula Embarcación','Nombre Embarcación','Tipo Especie vendida' ,'Lugar de descarga','Cantidad Especie ','Fecha de descarga'],5,true,'H');
            $this->pdf->SetFont($fontFamilyName, '');
            $this->pdf->TableRow([
                $invoice['detractionBoatRegistration'],
                $invoice['detractionBoatName'],
                $invoice['detractionSpeciesKind'],
                $invoice['detractionDeliveryAddress'],
                $invoice['detractionQuantity'],
                $invoice['detractionDeliveryDate'],
            ],5, false, 'H');
            $this->pdf->Ln();
        }

        // Referral guide
        if ($invoice['whitGuide']){
            $this->pdf->SetTableWidths([50,100]);
            $this->pdf->SetTableHAligns(['R','L']);
            $this->pdf->SetTableFonts([
                ['style' => 'B'],
                ['style' => ''],
            ]);

            $this->pdf->SetFont($fontFamilyName,'B',$fontSize);
            $this->pdf->Cell($pageWidth,5 + 2,'DATOS DEL TRASLADO',1,0,'L',true);
            $beforeY = $this->pdf->GetY();
            $this->pdf->Ln();
            $this->pdf->TableRow(['MOTIVO DE TRASLADO:', $invoice['transferCode'] ],3.5,false,'');
            $this->pdf->TableRow(['MODALIDAD DE TRANSPORTE:', $invoice['transportCode'] ],3.5,false,'');
            $this->pdf->TableRow(['PESO BRUTO TOTAL (KGM):', $invoice['totalGrossWeight'] ],3.5,false,'');

            $this->pdf->TableRow(['TRANSPORTISTA DENOMINACIÓN:', $invoice['carrierDenomination'] ],3.5,false,'');
            $this->pdf->TableRow(['TRANSPORTISTA PLACA:', $invoice['carrierPlateNumber'] ],3.5,false,'');
            $this->pdf->TableRow(['CONDUCTOR:', $invoice['driverDenomination'] ],3.5,false,'');

            $this->pdf->TableRow(['PUNTO DE PARTIDA:', $invoice['locationStartPoint'] ],3.5,false,'');
            $this->pdf->TableRow(['PUNTO DE LLEGADA:', $invoice['locationEndPoint'] ],3.5,false,'');
            $this->pdf->RoundedRect($marginLeft,$beforeY,$pageWidth,$this->pdf->GetY() - $beforeY,0);
            $this->pdf->Ln();
            $this->pdf->Ln();
        }

        // Description
        $beforeY = $this->pdf->GetY();
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell(60, 4, 'IMPORTE EN LETRAS: ', 0, 0, 'R');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell(0, 4, $invoice['totalInWord'], 0, 'L');
        $this->pdf->RoundedRect($marginLeft, $beforeY - 2, $pageWidth, $this->pdf->GetY() - $beforeY + 3.5, 2);
        $this->pdf->Ln();

        if ($invoice['vehiclePlate']) {
            $this->pdf->SetFont($fontFamilyName, 'B');
            $this->pdf->Cell(60, 3.5, 'PLACA VEHICULO: ', 0, 0, 'R');
            $this->pdf->SetFont($fontFamilyName, '');
            $this->pdf->MultiCell(0, 3.5, strtoupper($invoice['vehiclePlate']), 0, 'L');
            $this->pdf->Ln(1);
        }
        if ($invoice['term']) {
            $this->pdf->SetFont($fontFamilyName, 'B');
            $this->pdf->Cell(60, 3.5, 'CONDICIONES DE PAGO: ', 0, 0, 'R');
            $this->pdf->SetFont($fontFamilyName, '');
            $this->pdf->MultiCell(0, 3.5, strtoupper($invoice['term']), 0, 'L');
            $this->pdf->Ln(1);
        }
        if ($invoice['purchaseOrder']) {
            $this->pdf->SetFont($fontFamilyName, 'B');
            $this->pdf->Cell(60, 3.5, 'ORDEN DE COMPRA/SERVICIO: ', 0, 0, 'R');
            $this->pdf->SetFont($fontFamilyName, '');
            $this->pdf->MultiCell(0, 3.5, strtoupper($invoice['purchaseOrder']), 0, 'L');
            $this->pdf->Ln(1);
        }
        if ($invoice['observation']) {
            $this->pdf->SetFont($fontFamilyName, 'B');
            $this->pdf->Cell(60, 3.5, 'OBSERVACIONES: ', 0, 0, 'R');
            $this->pdf->SetFont($fontFamilyName, '');
            $this->pdf->MultiCell(0, 3.5, $invoice['observation'], 0, 'L');
            $this->pdf->Ln(1);
        }
        if($invoice['guide']){
            $docRelated = array_reduce(
                $invoice['guide'],
                function ($old ,$next){
                    return $old . $next['document_code'] . ' ' . $next['serie'] . '  ';
                },
                ''
            );
            $this->pdf->SetFont($fontFamilyName, 'B');
            $this->pdf->Cell(60, 3.5, 'GUIAS: ', 0, 0, 'R');
            $this->pdf->SetFont($fontFamilyName, '');
            $this->pdf->MultiCell(0, 3.5, trim($docRelated), 0, 'L');
            $this->pdf->Ln(1);
        }
        $this->pdf->Ln();

        $beforeY = $this->pdf->GetY();
        $this->pdf->MultiCell(0, 4, sprintf('Representación impresa de %s, para ver el documento visita', strtoupper($invoice['documentType'])), 0, 'L');
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->MultiCell(0, 4, sprintf('https://skynetgroup.pse.pe/%s', $invoice['customerDocumentNumber']), 0, 'L');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->WriteHTML('Emitido mediante un <b>PROVEEDOR Autorizado por la SUNAT</b><br>mediante Resolución de Intendencia <b>No..............</b>', 4);
        $this->pdf->Ln();
        $this->pdf->WriteHTML('<b>Resumen: </b>' . ($invoice['digestValue']), 4);
        $this->pdf->Ln();

        $rightRectWidth = 35;
        $rightRectX = ($pageWidth - $rightRectWidth) + $marginLeft;
        $leftRectWidth = ($pageWidth - ($rightRectWidth + $gutter));
        $leftRectX = $marginLeft;

        $this->pdf->RoundedRect($leftRectX, $beforeY - 2, $leftRectWidth, 35, 2);

        // QR Code
        $QrCode = $this->GenerateQRCode([
            'businessRuc' => $invoice['businessRuc'],
            'documentCode' => $invoice['documentCode'],
            'serie' => $invoice['serie'],
            'correlative' => $invoice['correlative'],
            'totalIgv' => $invoice['totalIgv'],
            'total' => $invoice['total'],
            'dateOfIssue' => $invoice['dateOfIssue'],
            'customerDocumentCode' => $invoice['customerDocumentCode'],
            'customerDocumentNumber' => $invoice['customerDocumentNumber'],
            'digestValue' => $invoice['digestValue'],
        ]);
        $QrCode->disableBorder();
        $QrCode->displayFPDF($this->pdf, $rightRectX + 2, $beforeY, 31);

        $this->pdf->RoundedRect($rightRectX, $beforeY - 2, $rightRectWidth, 35, 2);
    }

    private function GenerateInvoiceTicket($invoice)
    {
        // Margin
        $marginLeft = 3;
        $marginTop = 3;
        $pageWidth = $this->pdf->GetPageWidth() - ($marginLeft + $marginLeft);
        $this->pdf->SetMargins($marginLeft, $marginTop, $marginLeft);

        // Config
        $fontFamilyName = "Calibri";
        $fontSize = 9;
        $lineHeight = 3.5;

        $this->DocumentHeaderTicket($invoice);

        $this->pdf->SetFont($fontFamilyName, 'B', $fontSize);
        $this->pdf->Cell(35, 4, 'CLIENTE: ', 0, 0);
        $this->pdf->Ln();

        $this->pdf->WriteHTML(sprintf(
            "<b>%s: </b> %s </br> %s </br> %s",
            strtoupper($invoice['customerDocumentCode']),
            strtoupper($invoice['customerDocumentNumber']),
            strtoupper($invoice['customerSocialReason']),
            strtoupper($invoice['customerFiscalAddress'])
        ), $lineHeight);
        $this->pdf->Ln($lineHeight);

        // Date
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell(28, $lineHeight, 'FECHA EMISIÓN');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell(0, $lineHeight, sprintf(": %s", $invoice['dateOfIssue']), 0, 'L');

        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell(28, $lineHeight, 'FECHA DE VENC');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell(0, $lineHeight, sprintf(": %s", $invoice['dateOfDue']), 0, 'L');

        // Currency
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell(28, $lineHeight, 'MONEDA');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell(0, $lineHeight, sprintf(': %s', strtoupper($invoice['currencyDescription'])), 0, 'L');

        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell(28, $lineHeight, 'IGV');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell(0, $lineHeight, ': '. $invoice['percentageIgv'] . ' %', 0, 'L');
        $this->pdf->Ln();

        // Invoice Detail
        $this->pdf->SetTableWidths([40, 12, 17]);
        $this->pdf->SetTableHAligns(['L', 'R', 'R']);

        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->TableRow(['[CANT.] DESCRIPCIÓN', 'P/U', 'IMPORTE'], 7, false, 'H');
        $this->pdf->SetFont($fontFamilyName, '');

        foreach ($invoice['itemList'] as $key => $row) {
            $this->pdf->TableRow([
                sprintf("[%s] %s", ($row['quantity']), $row['productDescription']),
                $row['unitPrice'],
                $row['total'],
            ], 4, false, 'H');
        }
        $this->pdf->Ln(2);

        // Invoice Detail resume
        $this->pdf->SetTableWidths([40, 12, 17]);
        $this->pdf->SetTableHAligns(['R', 'R', 'R']);

        $currencySymbol = $invoice['currencySymbol'];
        $this->pdf->SetFont($fontFamilyName, 'B');
        if ($invoice['totalDiscount'] > 0) {
            $this->pdf->TableRow(['DESCUENTO', $currencySymbol, $invoice['totalDiscount']], $lineHeight, false, '');
        }

        if ($invoice['totalPrepayment'] > 0) {
            $this->pdf->TableRow(['ANTICIPO', $currencySymbol, $invoice['totalPrepayment']], $lineHeight, false, '');
        }

        if ($invoice['totalExonerated'] > 0) {
            $this->pdf->TableRow(['EXONERADA', $currencySymbol, $invoice['totalExonerated']], $lineHeight, false, '');
        }

        if ($invoice['totalUnaffected'] > 0) {
            $this->pdf->TableRow(['INAFECTA', $currencySymbol, $invoice['totalUnaffected']], $lineHeight, false, '');
        }

        $this->pdf->TableRow(['GRAVADA', $currencySymbol, $invoice['totalTaxed']], $lineHeight, false, '');

        if ($invoice['totalIsc'] > 0) {
            $this->pdf->TableRow(['ISC', $currencySymbol, $invoice['totalIsc']], $lineHeight, false, '');
        }

        $this->pdf->TableRow(['IGV ' . $invoice['percentageIgv'] . ' %', $currencySymbol, $invoice['totalIgv']], $lineHeight, false, '');

        if ($invoice['totalFree'] > 0) {
            $this->pdf->TableRow(['GRATUITA', $currencySymbol, $invoice['totalFree']], $lineHeight, false, '');
        }

        if ($invoice['totalCharge'] > 0) {
            $this->pdf->TableRow(['OTROS CARGOS', $currencySymbol, $invoice['totalCharge']], $lineHeight, false, '');
        }

        if ($invoice['totalPlasticBagTax'] > 0) {
            $this->pdf->TableRow(['ICBPER', $currencySymbol, $invoice['totalPlasticBagTax']], $lineHeight, false, '');
        }
        $this->pdf->TableRow(['TOTAL', $currencySymbol, $invoice['total']], $lineHeight, false, '');
        $this->pdf->Ln();

        if ($invoice['perceptionAmount'] > 0){
            $this->pdf->SetTableWidths([10, 10, 25, 23]);
            $this->pdf->SetTableHAligns(['C', 'C', 'C', 'C']);
            $this->pdf->TableRow(['COD','%','PERCEPCION','TOTAL PER'],7,false,'H');
            $this->pdf->SetFont($fontFamilyName, '');
            $this->pdf->TableRow([$invoice['perceptionCode'],"{$invoice['perceptionPercentage']} %","$currencySymbol {$invoice['perceptionAmount']}","$currencySymbol {$invoice['totalWithPerception']}"],7, false, 'H');
            $this->pdf->Ln(4);
        }

        // Description
        $this->pdf->WriteHTML('<b>IMPORTE EN LETRAS: </b> '.$invoice['totalInWord'], $lineHeight);
        $this->pdf->Ln();

        $this->pdf->SetFont($fontFamilyName, '');
        if ($invoice['vehiclePlate']) {
            $this->pdf->WriteHTML("<b>PLACA VEHICULO: </b>" . strtoupper($invoice['vehiclePlate']), $lineHeight);
            $this->pdf->Ln();
        }
        if ($invoice['term']) {
            $this->pdf->WriteHTML("<b>CONDICIONES DE PAGO: </b>" . strtoupper($invoice['term']), $lineHeight);
            $this->pdf->Ln();
        }
        if ($invoice['purchaseOrder']) {
            $this->pdf->WriteHTML("<b>ORDEN DE COMPRA/SERVICIO: </b>" . strtoupper($invoice['purchaseOrder']), $lineHeight);
            $this->pdf->Ln();
        }
        if ($invoice['observation']) {
            $this->pdf->WriteHTML("<b>OBSERVACIONES: </b>" . $invoice['observation'], $lineHeight);
            $this->pdf->Ln();
        }
        if($invoice['guide']){
            $docRelated = array_reduce(
                $invoice['guide'],
                function ($old ,$next){
                    return $old . $next['document_code'] . ' ' . $next['serie'] . '  ';
                },
                ''
            );
            $this->pdf->WriteHTML("<b>GUIAS: </b>" . $docRelated, $lineHeight);
            $this->pdf->Ln();
        }

        $this->pdf->MultiCell(0, 4, sprintf('Representación impresa de %s, para ver el documento visita', strtoupper($invoice['documentType'])), 0, 'L');
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->MultiCell(0, 4, sprintf('https://skynetgroup.pse.pe/%s', $invoice['customerDocumentNumber']), 0, 'L');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->WriteHTML("Emitido mediante un <b>PROVEEDOR Autorizado por la SUNAT</b> mediante Resolución de Intendencia <b>No..............</b>", 4);
        $this->pdf->Ln();
        $this->pdf->WriteHTML("<p align='center'><b>Resumen: </b>{$invoice['digestValue']}</p>", 4);
        $this->pdf->Ln();

        // QR Code
        $QrCode = $this->GenerateQRCode([
            'businessRuc' => $invoice['businessRuc'],
            'documentCode' => $invoice['documentCode'],
            'serie' => $invoice['serie'],
            'correlative' => $invoice['correlative'],
            'totalIgv' => $invoice['totalIgv'],
            'total' => $invoice['total'],
            'dateOfIssue' => $invoice['dateOfIssue'],
            'customerDocumentCode' => $invoice['customerDocumentCode'],
            'customerDocumentNumber' => $invoice['customerDocumentNumber'],
            'digestValue' => $invoice['digestValue'],
        ]);
        $QrCode->disableBorder();
        $QrCode->displayFPDF($this->pdf, $marginLeft + ($pageWidth / 2) - 16, $this->pdf->GetY(), 32);
        $this->pdf->Ln(32 + 5);
        $this->pdf->WriteHTML('<p align="center">Emitido desde <b>' . '' . '</b></p>');
    }

    private function GenerateInvoiceNCNDA4A5($invoice)
    {
        // Margin
        $marginLeft = 12;
        $marginTop = 12;
        $pageWidth = $this->pdf->GetPageWidth() - ($marginLeft + $marginLeft);
        $this->pdf->SetMargins($marginLeft, $marginTop, $marginLeft);

        // Config
        $gutter = 2;
        $clearCollapse = 1.5;
        $rightRectWidth = 60;
        $rightRectX = ($pageWidth - $rightRectWidth) + $marginLeft;
        $leftRectWidth = ($pageWidth - ($rightRectWidth + $gutter));
        $leftRectX = $marginLeft;
        $fontFamilyName = "Calibri";
        $fontSize = 9;

        // Header
        $beforeY = $this->DocumentHeaderA4($invoice);

        // Customer
        $labelWidth = 35;
        $labelX = $marginLeft + $clearCollapse;
        $descriptionWidth = $pageWidth - ($rightRectWidth + $gutter + $clearCollapse + $labelWidth);

        $this->pdf->SetXY($labelX, $beforeY);
        $this->pdf->SetFont($fontFamilyName, 'B', $fontSize);
        $this->pdf->Cell(35, 4, 'CLIENTE: ', 0, 0);
        $this->pdf->Ln();

        $this->pdf->SetXY($labelX, $this->pdf->GetY());
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell($labelWidth, 3.5, 'RUC');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->Cell($descriptionWidth, 3.5, ': ' . ($invoice['customerDocumentNumber']));
        $this->pdf->Ln();

        $this->pdf->SetXY($labelX, $this->pdf->GetY());
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell($labelWidth, 3.5, 'DENOMINACIÓN');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell($descriptionWidth, 3.5, ': ' . strtoupper($invoice['customerSocialReason']), 0, 'L');

        $this->pdf->SetXY($labelX, $this->pdf->GetY());
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell($labelWidth, 3.5, 'DIRECCIÓN');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell($descriptionWidth, 3.5, ': ' . strtoupper($invoice['customerFiscalAddress']), 0, 'L');

        $this->pdf->RoundedRect($leftRectX, $beforeY - 2, $leftRectWidth, $this->pdf->GetY() - $beforeY + 3.5, 2);

        // Description
        $this->pdf->RoundedRect($rightRectX, $beforeY - 2, $rightRectWidth, $this->pdf->GetY() - $beforeY + 3.5, 2);

        $beforeYAux = $beforeY;
        $beforeY = $this->pdf->GetY();

        // Date
        $this->pdf->SetXY($rightRectX + 2, $beforeYAux);
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell(28, 3.5, 'FECHA EMISIÓN');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell(0, 3.5, sprintf(": %s", $invoice['dateOfIssue']), 0, 'L');

        $this->pdf->SetXY($rightRectX + 2, $this->pdf->GetY());
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell(28, 3.5, 'FECHA DE VENC');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell(0, 3.5, sprintf(": %s", $invoice['dateOfDue']), 0, 'L');

        // Currency
        $this->pdf->SetXY($rightRectX + 2, $this->pdf->GetY());
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell(28, 3.5, 'MONEDA');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell(0, 3.5, sprintf(': %s', strtoupper($invoice['currencyDescription'])), 0, 'L');

        // Table
        $this->pdf->SetXY($marginLeft, $beforeY + 3.5);

        $this->pdf->SetTableWidths([18, 12, 12, 84, 20, 20, 20]);
        $this->pdf->SetTableHAligns(['C', 'C', 'C', 'L', 'R', 'R', 'R']);

        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->TableRow(['CANT', 'UM', 'CÓD', 'DESCRIPCIÓN', 'V/U', 'P/U', 'IMPORTE'], 7, true, 'H');
        $this->pdf->SetFont($fontFamilyName, '');

        foreach ($invoice['itemList'] as $key => $row) {
            //            $fill = ($key % 2) == 0;
            $discount = $row['discount'] > 0 ? '-' . $row['discount'] : '';
            $this->pdf->TableRow([
                $row['quantity'],
                $row['unitMeasureCode'],
                $row['productCode'],
                $row['productDescription'],
                $row['unitValue'],
                $row['unitPrice'] . $discount,
                $row['total'],
            ], 4, false, 'H');
        }
        $this->pdf->Ln(1.5);

        //186.00155555556
        $this->pdf->SetTableWidths([133, 20, 33]);
        $this->pdf->SetTableHAligns(['R', 'R', 'R']);

        $currencySymbol = $invoice['currencySymbol'];
        $this->pdf->SetFont($fontFamilyName, 'B');

        if ($invoice['totalDiscount'] > 0) {
            $this->pdf->TableRow(['DESCUENTO(-)', $currencySymbol, $invoice['totalDiscount']], 3.5, false, '');
        }

        if ($invoice['totalPrepayment'] > 0) {
            $this->pdf->TableRow(['ANTICIPO', $currencySymbol, $invoice['totalPrepayment']], 3.5, false, '');
        }

        if ($invoice['totalExonerated'] > 0) {
            $this->pdf->TableRow(['EXONERADA', $currencySymbol, $invoice['totalExonerated']], 3.5, false, '');
        }

        if ($invoice['totalUnaffected'] > 0) {
            $this->pdf->TableRow(['INAFECTA', $currencySymbol, $invoice['totalUnaffected']], 3.5, false, '');
        }

        $this->pdf->TableRow(['GRAVADA', $currencySymbol, $invoice['totalTaxed']], 3.5, false, '');

        if ($invoice['totalIsc'] > 0) {
            $this->pdf->TableRow(['ISC', $currencySymbol, $invoice['totalIsc']], 3.5, false, '');
        }

        $this->pdf->TableRow(['IGV '. $invoice['percentageIgv'] . ' %', $currencySymbol, $invoice['totalIgv']], 3.5, false, '');

        if ($invoice['totalFree'] > 0) {
            $this->pdf->TableRow(['GRATUITA', $currencySymbol, $invoice['totalFree']], 3.5, false, '');
        }

        if ($invoice['totalCharge'] > 0) {
            $this->pdf->TableRow(['OTROS CARGOS', $currencySymbol, $invoice['totalCharge']], 3.5, false, '');
        }

        if ($invoice['totalPlasticBagTax'] > 0) {
            $this->pdf->TableRow(['ICBPER', $currencySymbol, $invoice['totalPlasticBagTax']], 3.5, false, '');
        }

        $this->pdf->TableRow(['TOTAL', $currencySymbol, $invoice['total']], 3.5, false, '');

        $this->pdf->Ln();
        $this->pdf->Ln();
        $this->pdf->Ln();


        // Description
        $beforeY = $this->pdf->GetY();
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell(60, 4, 'IMPORTE EN LETRAS: ', 0, 0, 'R');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell(0, 4, $invoice['totalInWord'], 0, 'L');
        $this->pdf->RoundedRect($marginLeft, $beforeY - 2, $pageWidth, $this->pdf->GetY() - $beforeY + 3.5, 2);
        $this->pdf->Ln();


        if ($invoice['vehiclePlate']) {
            $this->pdf->SetFont($fontFamilyName, 'B');
            $this->pdf->Cell(60, 3.5, 'PLACA VEHICULO: ', 0, 0, 'R');
            $this->pdf->SetFont($fontFamilyName, '');
            $this->pdf->MultiCell(0, 3.5, strtoupper($invoice['vehiclePlate']), 0, 'L');
            $this->pdf->Ln(1);
        }
        if ($invoice['term']) {
            $this->pdf->SetFont($fontFamilyName, 'B');
            $this->pdf->Cell(60, 3.5, 'CONDICIONES DE PAGO: ', 0, 0, 'R');
            $this->pdf->SetFont($fontFamilyName, '');
            $this->pdf->MultiCell(0, 3.5, strtoupper($invoice['term']), 0, 'L');
            $this->pdf->Ln(1);
        }
        if ($invoice['purchaseOrder']) {
            $this->pdf->SetFont($fontFamilyName, 'B');
            $this->pdf->Cell(60, 3.5, 'ORDEN DE COMPRA/SERVICIO: ', 0, 0, 'R');
            $this->pdf->SetFont($fontFamilyName, '');
            $this->pdf->MultiCell(0, 3.5, strtoupper($invoice['purchaseOrder']), 0, 'L');
            $this->pdf->Ln(1);
        }
        if ($invoice['observation']) {
            $this->pdf->SetFont($fontFamilyName, 'B');
            $this->pdf->Cell(60, 3.5, 'OBSERVACIONES: ', 0, 0, 'R');
            $this->pdf->SetFont($fontFamilyName, '');
            $this->pdf->MultiCell(0, 3.5, strtoupper($invoice['observation']), 0, 'L');
            $this->pdf->Ln(1);
        }
        $this->pdf->Ln();

        $this->pdf->WriteHTML("<b>MOTIVO DE EMISIÓN: </b>" . strtoupper($invoice['reasonUpdate'] ),4);
        $this->pdf->Ln();
        $this->pdf->WriteHTML("<b>DOCUMENTO RELACIONADO: </b>" . strtoupper($invoice['reasonUpdateDocument'] ),4);
        $this->pdf->Ln();
        $this->pdf->Ln();

        $beforeY = $this->pdf->GetY();
        $this->pdf->MultiCell(0, 4, sprintf('Representación impresa de %s, para ver el documento visita', strtoupper($invoice['documentType'])), 0, 'L');
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->MultiCell(0, 4, sprintf('https://skynetgroup.pse.pe/%s', $invoice['customerDocumentNumber']), 0, 'L');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->WriteHTML('Emitido mediante un <b>PROVEEDOR Autorizado por la SUNAT</b><br>mediante Resolución de Intendencia <b>No..............</b>', 4);
        $this->pdf->Ln();
        $this->pdf->WriteHTML('<b>Resumen: </b>' . ($invoice['digestValue']), 4);
        $this->pdf->Ln();

        $rightRectWidth = 35;
        $rightRectX = ($pageWidth - $rightRectWidth) + $marginLeft;
        $leftRectWidth = ($pageWidth - ($rightRectWidth + $gutter));
        $leftRectX = $marginLeft;

        $this->pdf->RoundedRect($leftRectX, $beforeY - 2, $leftRectWidth, 35, 2);

        // QR Code
        $QrCode = $this->GenerateQRCode([
            'businessRuc' => $invoice['businessRuc'],
            'documentCode' => $invoice['documentCode'],
            'serie' => $invoice['serie'],
            'correlative' => $invoice['correlative'],
            'totalIgv' => $invoice['totalIgv'],
            'total' => $invoice['total'],
            'dateOfIssue' => $invoice['dateOfIssue'],
            'customerDocumentCode' => $invoice['customerDocumentCode'],
            'customerDocumentNumber' => $invoice['customerDocumentNumber'],
            'digestValue' => $invoice['digestValue'],
        ]);
        $QrCode->disableBorder();
        $QrCode->displayFPDF($this->pdf, $rightRectX + 2, $beforeY, 31);

        $this->pdf->RoundedRect($rightRectX, $beforeY - 2, $rightRectWidth, 35, 2);
    }

    private function GenerateInvoiceNCNDTicket($invoice)
    {
        // Margin
        $marginLeft = 3;
        $marginTop = 3;
        $pageWidth = $this->pdf->GetPageWidth() - ($marginLeft + $marginLeft);
        $this->pdf->SetMargins($marginLeft, $marginTop, $marginLeft);

        // Config
        $fontFamilyName = "Calibri";
        $fontSize = 9;
        $lineHeight = 3.5;

        $this->DocumentHeaderTicket($invoice);

        $this->pdf->SetFont($fontFamilyName, 'B', $fontSize);
        $this->pdf->Cell(35, 4, 'CLIENTE: ', 0, 0);
        $this->pdf->Ln();

        $this->pdf->WriteHTML(sprintf(
            "<b>%s: </b> %s </br> %s </br> %s",
            strtoupper($invoice['customerDocumentCode']),
            strtoupper($invoice['customerDocumentNumber']),
            strtoupper($invoice['customerSocialReason']),
            strtoupper($invoice['customerFiscalAddress'])
        ), $lineHeight);
        $this->pdf->Ln($lineHeight);

        // Date
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell(28, $lineHeight, 'FECHA EMISIÓN');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell(0, $lineHeight, sprintf(": %s", $invoice['dateOfIssue']), 0, 'L');

        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell(28, $lineHeight, 'FECHA DE VENC');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell(0, $lineHeight, sprintf(": %s", $invoice['dateOfDue']), 0, 'L');

        // Currency
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell(28, $lineHeight, 'MONEDA');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell(0, $lineHeight, sprintf(': %s', strtoupper($invoice['currencyDescription'])), 0, 'L');

        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->Cell(28, $lineHeight, 'IGV');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->MultiCell(0, $lineHeight, ': '. $invoice['percentageIgv'] . ' %', 0, 'L');
        $this->pdf->Ln();

        // Invoice Detail
        $this->pdf->SetTableWidths([40, 12, 17]);
        $this->pdf->SetTableHAligns(['L', 'R', 'R']);

        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->TableRow(['[CANT.] DESCRIPCIÓN', 'P/U', 'IMPORTE'], 7, false, 'H');
        $this->pdf->SetFont($fontFamilyName, '');

        foreach ($invoice['itemList'] as $key => $row) {
            $this->pdf->TableRow([
                sprintf("[%s] %s", ($row['quantity']), $row['productDescription']),
                $row['unitPrice'],
                $row['total'],
            ], 4, false, 'H');
        }
        $this->pdf->Ln(2);

        // Invoice Detail resume
        $this->pdf->SetTableWidths([40, 12, 17]);
        $this->pdf->SetTableHAligns(['R', 'R', 'R']);

        $currencySymbol = $invoice['currencySymbol'];
        $this->pdf->SetFont($fontFamilyName, 'B');
        if ($invoice['totalDiscount'] > 0) {
            $this->pdf->TableRow(['DESCUENTO', $currencySymbol, $invoice['totalDiscount']], $lineHeight, false, '');
        }

        if ($invoice['totalPrepayment'] > 0) {
            $this->pdf->TableRow(['ANTICIPO', $currencySymbol, $invoice['totalPrepayment']], $lineHeight, false, '');
        }

        if ($invoice['totalExonerated'] > 0) {
            $this->pdf->TableRow(['EXONERADA', $currencySymbol, $invoice['totalExonerated']], $lineHeight, false, '');
        }

        if ($invoice['totalUnaffected'] > 0) {
            $this->pdf->TableRow(['INAFECTA', $currencySymbol, $invoice['totalUnaffected']], $lineHeight, false, '');
        }

        $this->pdf->TableRow(['GRAVADA', $currencySymbol, $invoice['totalTaxed']], $lineHeight, false, '');

        if ($invoice['totalIsc'] > 0) {
            $this->pdf->TableRow(['ISC', $currencySymbol, $invoice['totalIsc']], $lineHeight, false, '');
        }

        $this->pdf->TableRow(['IGV ' . $invoice['percentageIgv'] . ' %', $currencySymbol, $invoice['totalIgv']], $lineHeight, false, '');

        if ($invoice['totalFree'] > 0) {
            $this->pdf->TableRow(['GRATUITA', $currencySymbol, $invoice['totalFree']], $lineHeight, false, '');
        }

        if ($invoice['totalCharge'] > 0) {
            $this->pdf->TableRow(['OTROS CARGOS', $currencySymbol, $invoice['totalCharge']], $lineHeight, false, '');
        }

        if ($invoice['totalPlasticBagTax'] > 0) {
            $this->pdf->TableRow(['ICBPER', $currencySymbol, $invoice['totalPlasticBagTax']], $lineHeight, false, '');
        }
        $this->pdf->TableRow(['TOTAL', $currencySymbol, $invoice['total']], $lineHeight, false, '');
        $this->pdf->Ln();

        // Description
        $this->pdf->WriteHTML('<b>IMPORTE EN LETRAS: </b> '.$invoice['totalInWord'], $lineHeight);
        $this->pdf->Ln();

        $this->pdf->SetFont($fontFamilyName, '');
        if ($invoice['vehiclePlate']) {
            $this->pdf->WriteHTML("<b>PLACA VEHICULO: </b>" . strtoupper($invoice['vehiclePlate']), $lineHeight);
            $this->pdf->Ln();
        }
        if ($invoice['term']) {
            $this->pdf->WriteHTML("<b>CONDICIONES DE PAGO: </b>" . strtoupper($invoice['term']), $lineHeight);
            $this->pdf->Ln();
        }
        if ($invoice['purchaseOrder']) {
            $this->pdf->WriteHTML("<b>ORDEN DE COMPRA/SERVICIO: </b>" . strtoupper($invoice['purchaseOrder']), $lineHeight);
            $this->pdf->Ln();
        }
        if ($invoice['observation']) {
            $this->pdf->WriteHTML("<b>OBSERVACIONES: </b>" . strtoupper($invoice['observation']), $lineHeight);
            $this->pdf->Ln();
        }
        $this->pdf->Ln();

        $this->pdf->MultiCell(0, 4, sprintf('Representación impresa de %s, para ver el documento visita', strtoupper($invoice['documentType'])), 0, 'L');
        $this->pdf->SetFont($fontFamilyName, 'B');
        $this->pdf->MultiCell(0, 4, sprintf('https://skynetgroup.pse.pe/%s', $invoice['customerDocumentNumber']), 0, 'L');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->WriteHTML("Emitido mediante un <b>PROVEEDOR Autorizado por la SUNAT</b> mediante Resolución de Intendencia <b>No..............</b>", 4);
        $this->pdf->Ln();
        $this->pdf->WriteHTML("<p align='center'><b>Resumen: </b>{$invoice['digestValue']}</p>", 4);

        $this->pdf->Ln($lineHeight * 2);

        // QR Code
        $QrCode = $this->GenerateQRCode([
            'businessRuc' => $invoice['businessRuc'],
            'documentCode' => $invoice['documentCode'],
            'serie' => $invoice['serie'],
            'correlative' => $invoice['correlative'],
            'totalIgv' => $invoice['totalIgv'],
            'total' => $invoice['total'],
            'dateOfIssue' => $invoice['dateOfIssue'],
            'customerDocumentCode' => $invoice['customerDocumentCode'],
            'customerDocumentNumber' => $invoice['customerDocumentNumber'],
            'digestValue' => $invoice['digestValue'],
        ]);
        $QrCode->disableBorder();
        $QrCode->displayFPDF($this->pdf, $marginLeft + ($pageWidth / 2) - 16, $this->pdf->GetY(), 32);
        $this->pdf->Ln(32 + 5);
        $this->pdf->WriteHTML('<p align="center">Emitido desde <b>' . '' . '</b></p>');
    }

    private function GenerateSummaryA4A5($summary)
    {
        $this->DocumentHeaderA4($summary);
    }

    private function GenerateSummaryTicket($summary)
    {
        $this->DocumentHeaderTicket($summary);
    }

    private function GenerateGuideA4A5($guide)
    {
        // Margin
        $marginLeft = 12;
        $marginTop = 12;
        $pageWidth = $this->pdf->GetPageWidth() - ($marginLeft + $marginLeft);
        $this->pdf->SetMargins($marginLeft, $marginTop, $marginLeft);

        // Config
        $fontFamilyName = "Calibri";
        $fontSize = 9;
        $lineHeight = 3.5;

        // Header
        $beforeY = $this->DocumentHeaderA4($guide);
        $this->pdf->SetXY($this->pdf->GetX(),$beforeY);

        // Init
        $this->pdf->SetTableWidths([50,100]);
        $this->pdf->SetTableHAligns(['R','L']);
        $this->pdf->SetTableFonts([
            ['style' => 'B'],
            ['style' => ''],
        ]);

        // Addressee
        $this->pdf->SetFont($fontFamilyName,'B',$fontSize);
        $this->pdf->Cell($pageWidth,$lineHeight + 2,'DESTINATARIO',1,0,'L',true);
        $beforeY = $this->pdf->GetY();
        $this->pdf->Ln();
        $this->pdf->TableRow([ $guide['customerSocialReason'] . ':', $guide['customerDocumentNumber']],3.5,false,'');
        $this->pdf->TableRow(['DENOMINACIÓN:', $guide['customerSocialReason'] ],3.5,false,'');
        $this->pdf->RoundedRect($marginLeft,$beforeY,$pageWidth,$this->pdf->GetY() - $beforeY,0);
        $this->pdf->Ln(3);

        // transfer data
        $this->pdf->SetFont($fontFamilyName,'B',$fontSize);
        $this->pdf->Cell($pageWidth,$lineHeight + 2,'DATOS DEL TRASLADO',1,0,'L',true);
        $beforeY = $this->pdf->GetY();
        $this->pdf->Ln();
        $this->pdf->TableRow(['FECHA EMISIÓN:', $guide['dateOfIssue'] ],3.5,false,'');
        $this->pdf->TableRow(['FECHA INICIO DE TRASLADO:', $guide['transferStartDate'] ],3.5,false,'');
        $this->pdf->TableRow(['MOTIVO DE TRASLADO:', $guide['transferCode'] ],3.5,false,'');
        $this->pdf->TableRow(['MODALIDAD DE TRANSPORTE:', $guide['transportCode'] ],3.5,false,'');
        $this->pdf->TableRow(['PESO BRUTO TOTAL (KGM):', $guide['totalGrossWeight'] ],3.5,false,'');
        $this->pdf->TableRow(['NÚMERO DE BULTOS:', $guide['numberPackages'] ],3.5,false,'');
        $this->pdf->RoundedRect($marginLeft,$beforeY,$pageWidth,$this->pdf->GetY() - $beforeY,0);
        $this->pdf->Ln();

        // starting point and arrival
        $this->pdf->SetFont($fontFamilyName,'B',$fontSize);
        $this->pdf->Cell($pageWidth,$lineHeight + 2,'DATOS DEL PUNTO DE PARTIDA Y PUNTO DE LLEGADA',1,0,'L',true);
        $beforeY = $this->pdf->GetY();
        $this->pdf->Ln();
        $this->pdf->TableRow(['PUNTO DE PARTIDA:', $guide['locationStartingCode'] . ' ' . $guide['addressStartingPoint']],3.5,false,'');
        $this->pdf->TableRow(['PUNTO DE LLEGADA:', $guide['locationArrivalCode'] . ' ' . $guide['addressArrivalPoint']],3.5,false,'');
        $this->pdf->RoundedRect($marginLeft,$beforeY,$pageWidth,$this->pdf->GetY() - $beforeY,0);
        $this->pdf->Ln();

//        // transport data
        $carrier = sprintf("%s %s - %s", $guide['carrierDocumentDescription'],$guide['carrierDocumentNumber'], $guide['carrierDenomination']);
        $driver = sprintf("%s %s - %s", $guide['driverDocumentDescription'], $guide['driverDocumentNumber'], $guide['driverFullName']);

        $this->pdf->SetFont($fontFamilyName,'B',$fontSize);
        $this->pdf->Cell($pageWidth,$lineHeight + 2,'DATOS DEL TRANSPORTE',1,0,'L',true);
        $beforeY = $this->pdf->GetY();
        $this->pdf->Ln();
        $this->pdf->TableRow(['TRANSPORTISTA:', $carrier],3.5,false,'');
        $this->pdf->TableRow(['VEHÍCULO:', $guide['carrierPlateNumber'] ],3.5,false,'');
        $this->pdf->TableRow(['CONDUCTOR:', $driver],3.5,false,'');
        $this->pdf->RoundedRect($marginLeft,$beforeY,$pageWidth,$this->pdf->GetY() - $beforeY,0);
        $this->pdf->Ln();

        // Products
        $this->pdf->SetTableWidths([20,20,105,20,20]);
        $this->pdf->SetTableHAligns(['C','C','L','C','C']);
        $this->pdf->SetTableFonts([]);

        $this->pdf->SetFont($fontFamilyName,'B');
        $this->pdf->TableRow(['Nro','CÓD','DESCRIPCIÓN','U/M','CANTIDAD'],7,true,'R');
        $this->pdf->SetFont($fontFamilyName,'');

        foreach ($guide['itemList'] as $key => $row){
            $fill = (($key + 1) % 2) == 0;
            $this->pdf->TableRow([
                $key + 1,
                $row['productCode'] ,
                $row['description'],
                $row['unitMeasure'],
                $row['quantity'],
            ],4, $fill,'R');
        }
        $this->pdf->Ln(6);

        // Observations
        $beforeY = $this->pdf->GetY();
        $this->pdf->SetFont($fontFamilyName,'B');
        $this->pdf->Cell(60,4,'OBSERVACIONES: ',0,0,'R');
        $this->pdf->SetFont($fontFamilyName,'');
        $this->pdf->MultiCell(0,4, $guide['observations'],0,'L');
        $this->pdf->RoundedRect($marginLeft,$beforeY - 1,$pageWidth,$this->pdf->GetY() - $beforeY + 2,2);
        $this->pdf->Ln();
        $this->pdf->Ln();

        // Additional description
        $beforeY = $this->pdf->GetY();
        $this->pdf->MultiCell(0,4,sprintf('Representación impresa de %s, para ver el documento visita', strtoupper($guide['documentTypeCodeDescription'] )),0,'L');
        $this->pdf->SetFont($fontFamilyName,'B');
        $this->pdf->MultiCell(0,4,sprintf('https://skynetgroup.pse.pe/%s',$guide['customerDocumentNumber']),0,'L');
        $this->pdf->SetFont($fontFamilyName,'');
        $this->pdf->WriteHTML('Emitido mediante un <b>PROVEEDOR Autorizado por la SUNAT</b><br>mediante Resolución de Intendencia <b>No..............</b>',4);
        $this->pdf->RoundedRect($marginLeft,$beforeY - 2,$pageWidth,$this->pdf->GetY() - $beforeY + 7.5,2);
        $this->pdf->Ln();
    }

    private function GenerateGuideTicket($guide)
    {
        $this->DocumentHeaderTicket($guide);
    }

    private function GenerateVoidedA4A5($voided)
    {
        // Margin
        $marginLeft = 12;
        $marginTop = 12;
//        $pageWidth = $this->pdf->GetPageWidth() - ($marginLeft + $marginLeft);
        $this->pdf->SetMargins($marginLeft, $marginTop, $marginLeft);

        // Config
//        $gutter = 2;
        $clearCollapse = 1.5;
//        $rightRectWidth = 60;
//        $rightRectX = ($pageWidth - $rightRectWidth) + $marginLeft;
//        $leftRectWidth = ($pageWidth - ($rightRectWidth + $gutter));
//        $leftRectX = $marginLeft;
        $fontFamilyName = "Calibri";
        $fontSize = 9;

        // Header
        $beforeY = $this->DocumentHeaderA4($voided);

        // Customer
        $labelWidth = 35;
//        $labelX = $marginLeft + $clearCollapse;
//        $descriptionWidth = $pageWidth - ($rightRectWidth + $gutter + $clearCollapse + $labelWidth);

        $this->pdf->SetXY($marginLeft, $beforeY);
        $this->pdf->SetFont($fontFamilyName, 'B', $fontSize);

        // Date
        $this->pdf->SetTableWidths([40, 40]);
        $this->pdf->SetTableHAligns(['L', 'L']);
        $this->pdf->SetTableFonts([
            ['style' => 'B'],
            ['style' => ''],
        ]);
        $this->pdf->TableRow(['FECHA EMISIÓN', ' : ' . $voided['documentDateOfIssue']],5,false,'');
        $this->pdf->TableRow(['FECHA DE GENERACIÓN', ' : ' . $voided['dateOfIssue']],5,false,'');
        $this->pdf->TableRow(['NÚMERO DE TICKET', ' : ' . $voided['ticket']],5,false,'');
        $this->pdf->Ln();

        // --------------------------------------
        $this->pdf->SetTableWidths([40, 40, 20, 20, 66]);
        $this->pdf->SetTableHAligns(['C', 'C', 'C', 'C', 'C']);
        $this->pdf->TableRow(['FECHA', 'TIPO DE DOCUMENTO', 'SERIE', 'NUMERO', 'MOTIVO'],7,true,'H');
        $this->pdf->SetFont($fontFamilyName, '');
        $this->pdf->TableRow([
            $voided['documentDateOfIssue'],
            $voided['documentDocumentCode'],
            $voided['documentSerie'],
            $voided['documentCorrelative'],
            $voided['reason'],
        ],5, false, 'H');
    }

    private function GenerateVoidedTicket($voided)
    {
        $this->DocumentHeaderTicket($voided);
    }

    private function ValidateFolderPath($businessRuc)
    {
        try {
            $folderDateName = date('Ym');
            $pdfFolderPath = FILE_PATH . '/pdf/';

            if (!file_exists(ROOT_DIR . $pdfFolderPath . $folderDateName)) {
                mkdir(ROOT_DIR . $pdfFolderPath . $folderDateName);
            }

            if (!file_exists(ROOT_DIR . $pdfFolderPath . $folderDateName . '/' . $businessRuc)) {
                mkdir(ROOT_DIR . $pdfFolderPath . $folderDateName . '/' . $businessRuc);
            }

            return [
                'relativePath' => $pdfFolderPath . $folderDateName . '/' . $businessRuc . '/',
                'absolutePath' => ROOT_DIR . $pdfFolderPath . $folderDateName . '/' . $businessRuc . '/',
            ];
        } catch (Exception $e) {
            throw new Exception('Error in : ' . __FUNCTION__ . ' | ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }

    public function SetDocumentBase($size, $valid)
    {
        $size = strtoupper($size);
        switch ($size) {
            case "A4":
                $this->pdf = new DOCUMENT_A4_PDF('P', 'mm', 'A4');
                $this->pdf->SetValid($valid);
                break;
            case "A5":
                $this->pdf = new DOCUMENT_A5_PDF('L', 'mm', 'A5');
                $this->pdf->SetValid($valid);
                break;
            case "TICKET":
                $this->pdf = new DOCUMENT_TICKET_PDF('P', 'mm', [75, 240]);
                $this->pdf->SetValid($valid);
                break;
            default:
                break;
        }

        $this->pdf->AddFont('Calibri', '', 'Calibri_Regular.php');
        $this->pdf->AddFont('Calibri', 'B', 'Calibri_Bold.php');
        $this->pdf->AddFont('Calibri', 'I', 'Calibri_Italic.php');
    }

    private function GenerateQRCode($qrData)
    {
        $qrDataStr = sprintf(
            "%s | %s | %s | %s | %s | %s | %s | %s | %s | %s",
            $qrData['businessRuc'],
            $qrData['documentCode'],
            $qrData['serie'],
            $qrData['correlative'],
            $qrData['totalIgv'],
            $qrData['total'],
            $qrData['dateOfIssue'],
            $qrData['customerDocumentCode'],
            $qrData['customerDocumentNumber'],
            $qrData['digestValue']
        );

        return new QRcode(utf8_encode($qrDataStr), 'Q');
    }

    private function DocumentHeaderA4($invoice){
        // Margin
        $marginLeft = 12;
        $marginTop = 12;
        $pageWidth = $this->pdf->GetPageWidth() - ($marginLeft + $marginLeft);
        $this->pdf->SetMargins($marginLeft, $marginTop, $marginLeft);

        // Config
        $gutter = 2;
        $clearCollapse = 1.5;
        $rightRectWidth = 60;
        $rightRectX = ($pageWidth - $rightRectWidth) + $marginLeft;
        $leftRectWidth = ($pageWidth - ($rightRectWidth + $gutter));
        $leftRectX = $marginLeft;
        $fontFamilyName = "Calibri";
        $fontSize = 9;

        // Init
        $this->pdf->AliasNbPages();
        $this->pdf->AddPage();
        $this->pdf->SetFont($fontFamilyName, 'B', $fontSize + 2);
        $this->pdf->SetLineWidth(0.1);

        // Company Identity
        if (isset($invoice['logo']) && $invoice['logo'] != '') {
            $fileDirectory = FOLDER_PATH . '/' . $invoice['logo'];
            if (file_exists($fileDirectory)) {
                $this->pdf->Image($fileDirectory, $marginLeft, $marginTop, 0, 10);
                $this->pdf->SetXY($marginLeft, $marginTop + 15);
                $this->pdf->Cell(0, 5, ($invoice['businessSocialReason']));
                $this->pdf->Ln();
            }
        } else {
            $this->pdf->SetXY($marginLeft, $marginTop + 20);
            $this->pdf->Ln();
        }

        $companyContent = $invoice['businessAddress']  . PHP_EOL;
        $companyContent .= $invoice['businessLocation'] . PHP_EOL;
        $companyContent = strtoupper($companyContent);
        $this->pdf->SetFont($fontFamilyName, '', $fontSize - 1);
        $this->pdf->MultiCell(0, 3.5, $companyContent);

        $this->pdf->MultiCell(0, 3.5, $invoice['headerContact']);
        $this->pdf->Ln();

        $beforeY = $this->pdf->GetY();

        // Content invoice
        $this->pdf->SetFillColor(240);
        $this->pdf->RoundedRect($rightRectX, $marginTop, $rightRectWidth, 30, 2, 'DF');

        $this->pdf->SetXY($rightRectX, $marginTop + 3);
        $this->pdf->SetFont($fontFamilyName, 'B', $fontSize + 6);

        $saleContent = sprintf('RUC: %s', $invoice['businessRuc']) . PHP_EOL;
        $saleContent .= sprintf('%s', $invoice['documentType']) . PHP_EOL;
        $saleContent .= sprintf('%s-%08d', $invoice['serie'], $invoice['correlative']);

        $this->pdf->MultiCell(0, 6, $saleContent, 0, 'C');

        return $beforeY;
    }

    private function DocumentHeaderTicket($invoice){
        // Margin
        $marginLeft = 3;
        $marginTop = 3;
        $pageWidth = $this->pdf->GetPageWidth() - ($marginLeft + $marginLeft);
        $this->pdf->SetMargins($marginLeft, $marginTop, $marginLeft);

        // Config
        $fontFamilyName = "Calibri";
        $fontSize = 9;
        $lineHeight = 3.5;

        // Init
        $this->pdf->AliasNbPages();
        $this->pdf->AddPage();
        $this->pdf->SetLineWidth(0.1);

        // Company
        $this->pdf->SetFont($fontFamilyName, "B", $fontSize + 7);
        $this->pdf->MultiCell(0, $lineHeight + 1, $invoice['businessCommercialReason'], 0, 'C');
        $this->pdf->SetFont($fontFamilyName, "B", $fontSize);
        $this->pdf->MultiCell(0, $lineHeight, $invoice['businessSocialReason'], 0, 'C');
        $this->pdf->SetFont($fontFamilyName, "", $fontSize);

        $addressContent = $invoice['businessAddress']  . PHP_EOL;
        $addressContent .= $invoice['businessLocation'] . PHP_EOL;
        $addressContent = strtoupper($addressContent);
        $this->pdf->MultiCell(0, $lineHeight, $addressContent, 0, 'C');
        $this->pdf->SetFont($fontFamilyName, "B", $fontSize);
        $this->pdf->MultiCell(0, $lineHeight, "RUC " . $invoice['businessRuc'], 0, 'C');
        $this->pdf->SetFont($fontFamilyName, "", $fontSize - 1);

        $this->pdf->MultiCell(0, $lineHeight, $invoice['headerContact'], 0, 'C');
        $this->pdf->SetFont($fontFamilyName, "B", $fontSize);

        // Invoice
        $saleContent = sprintf('%s', $invoice['documentType']) . PHP_EOL;
        $saleContent .= sprintf('%s-%08d', $invoice['serie'], $invoice['correlative']);
        $this->pdf->MultiCell(0, $lineHeight, $saleContent, 0, 'C');
        $this->pdf->Ln(3);
    }
}
