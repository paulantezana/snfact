<?php


class CatPerceptionTypeCode extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("cat_perception_type_code","code",$db);
    }
}