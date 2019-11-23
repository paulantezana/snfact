<?php


class CatTransferReasonCode extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("cat_transfer_reason_code","code",$db);
    }
}