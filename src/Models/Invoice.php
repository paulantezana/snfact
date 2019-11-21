<?php


class Invoice extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("invoice","invoice_id",$db);
    }
}