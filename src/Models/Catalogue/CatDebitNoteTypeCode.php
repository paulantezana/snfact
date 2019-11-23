<?php


class CatDebitNoteTypeCode extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("cat_debit_note_type_code","code",$db);
    }
}