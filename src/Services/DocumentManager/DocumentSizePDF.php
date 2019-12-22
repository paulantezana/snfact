<?php

require_once ROOT_DIR . '/src/Helpers/Fpdf/Pdf.php';

class DOCUMENT_TICKET_PDF extends Pdf {
    private $valid = false;

    public function SetValid($valid): void
    {
        $this->valid = $valid;
    }

    function Footer()
    {
        //Put the watermark
        if (!$this->valid){
            $this->SetFont('Arial','B',24);
            $this->SetTextColor(255, 0, 85);
            $this->RotatedText(11,$this->GetPageHeight() / 2.5,'SIN VALOR LEGAL',45);
        }
    }
}

class DOCUMENT_A4_PDF extends PDF {
    private $valid = false;

    public function SetValid($valid): void
    {
        $this->valid = $valid;
    }

    function Footer()
    {
        //Put the watermark
        if (!$this->valid){
            $this->SetFont('Arial','B',50);
            $this->SetTextColor(255, 0, 85);
            $this->RotatedText(45,$this->GetPageHeight() / 2 + 10,'SIN VALOR LEGAL',45);
        }

        // Page Number
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(100,100,100);
        $this->WriteHTML('<p align="center">Emitido desde</p>',10);
    }
}

class DOCUMENT_A5_PDF extends PDF {
    private $valid = false;

    public function SetValid($valid): void
    {
        $this->valid = $valid;
    }

    function Footer()
    {
        //Put the watermark
        if (!$this->valid){
            $this->SetFont('Arial','B',50);
            $this->SetTextColor(255, 0, 85);
            $this->RotatedText(45,$this->GetPageHeight() - 12,'SIN VALOR LEGAL',45);
        }

        // Page Number
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(100,100,100);
        $this->WriteHTML('<p align="center">Emitido desde</p>',10);
    }
}
