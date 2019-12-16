<?php


class BusinessSerie extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("business_serie","business_serie_id",$db);
    }

    public function GetAllByBusinessLocalId($businessLocalId){
        try {
            $sql = 'SELECT * FROM business_serie WHERE business_local_id = :business_local_id AND hidden IS NOT true';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':business_local_id' => $businessLocalId,
            ]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    public function GetNextCorrelative(array $document) {
        try{
            $sql = 'SELECT (max_correlative + 1) as number, serie, document_code, cdtc.description FROM business_serie
                    INNER JOIN cat_document_type_code cdtc on business_serie.document_code = cdtc.code
                    WHERE business_local_id = :business_local_id AND document_code = :document_code';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':business_local_id',$document['localId']);
            $stmt->bindParam(':document_code',$document['documentCode']);
            $stmt->execute();
            $dataCorrelative = $stmt->fetch();
            if (!$dataCorrelative){
//                $sql = 'INSERT INTO business_serie (business_local_id, serie, document_code, max_correlative) VALUES (:business_local_id, :serie, :document_code, :max_correlative)';
//                $stmt = $this->db->prepare($sql);
//                $startCorrelative = 0;
//                if (!$stmt->execute([
//                    ':business_local_id' => $document['localId'],
//                    ':serie' => $document['serie'],
//                    ':document_code' => $document['documentCode'],
//                    ':max_correlative' => $startCorrelative,
//                    ':state' => true,
//                ])){
                throw new Exception('Documento no soportado');
//                }
//                $data = $startCorrelative;
            } else {
                return $dataCorrelative;
            }
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }
}