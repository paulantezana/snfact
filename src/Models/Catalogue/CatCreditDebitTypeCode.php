<?php


class CatCreditDebitTypeCode extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("cat_credit_debit_type_code","catCreditDebitTypeCodeId",$db);
    }

    public function getByDocumentCode($documentCode)
    {
        try {
            $sql = "SELECT * FROM cat_credit_debit_type_code WHERE document_code = :document_code";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":document_code" => $documentCode]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

}