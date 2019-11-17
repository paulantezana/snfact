<?php


class CatUnitMeasureTypeCode extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("cat_unit_measure_type_code","code",$db);
    }
}