<?php


class InvoiceVoided extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("invoice_voided","invoice_voided_id",$db);
    }
}