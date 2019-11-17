<?php


class CatCreditNoteTypeCode extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("cat_credit_note_type_code","code",$db);
    }
}