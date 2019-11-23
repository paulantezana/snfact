<?php


class CatCurrencyTypeCode extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("cat_currency_type_code","code",$db);
    }
}