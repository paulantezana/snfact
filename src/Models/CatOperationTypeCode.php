<?php

require_once __DIR__ . '/BaseModel.php';

class CatOperationTypeCode extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("cat_operation_type_code","code",$db);
    }
}