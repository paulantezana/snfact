<?php


class CatIdentityDocumentTypeCode extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("cat_identity_document_type_code","code",$db);
    }
}