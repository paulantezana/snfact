<?php


class CatSystemIscTypeCode extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("cat_system_isc_type_code","code",$db);
    }
}