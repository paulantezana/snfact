<?php


class InvoiceSummary extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("invoice_summary","invoice_summary_id",$db);
    }
}