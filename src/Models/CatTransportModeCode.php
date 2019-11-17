<?php


class CatTransportModeCode extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("cat_transport_mode_code","code",$db);
    }
}