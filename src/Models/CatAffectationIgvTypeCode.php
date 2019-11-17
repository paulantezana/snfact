<?php


class CatAffectationIgvTypeCode extends  Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("cat_affectation_igv_type_code","code",$db);
    }
}