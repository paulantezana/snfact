<?php


class BusinessSerie extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("business_serie","business_serie_id",$db);
    }

    public function getAllByBusinessLocalId($businessLocalId){
        try {
            $sql = 'SELECT * FROM business_serie WHERE business_local_id = :business_local_id AND hidden IS NOT true';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':business_local_id' => $businessLocalId,
            ]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function getDocumentSerieNumber(array $document, $contingency = 0) {
        try{
            $sql = 'SELECT (max_correlative + 1) as number, serie, document_code, cdtc.description FROM business_serie
                    INNER JOIN cat_document_type_code cdtc on business_serie.document_code = cdtc.code
                    WHERE business_local_id = :business_local_id AND document_code = :document_code AND contingency = :contingency';
            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
                ':business_local_id' => $document['businessLocalId'],
                ':document_code' => $document['documentCode'],
                ':contingency' => $contingency,
            ])){
                throw new Exception('Documento no soportado');
            }
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function getDocumentSerieNumberStartWith(array $document, $contingency = 0) {
        try{
            $sql = 'SELECT (max_correlative + 1) as number, serie, document_code, cdtc.description FROM business_serie
                    INNER JOIN cat_document_type_code cdtc on business_serie.document_code = cdtc.code
                    WHERE business_local_id = :business_local_id AND document_code = :document_code AND contingency = :contingency AND serie LIKE :serie';
            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
                ':business_local_id' => $document['businessLocalId'],
                ':document_code' => $document['documentCode'],
                ':contingency' => $contingency,
                ':serie' =>   $document['startWith'] . '%',
            ])){
                throw new Exception('Documento no soportado');
            }
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function getNextNumber(array $document) {
        try{
            $sql = 'SELECT (max_correlative + 1) as number FROM business_serie
                    WHERE business_local_id = :business_local_id AND document_code = :document_code AND serie = :serie';
            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
                ':business_local_id'=>$document['businessLocalId'],
                ':document_code'=>$document['documentCode'],
                ':serie'=>$document['serie'],
            ])){
                throw new Exception('Error al buscar el documento');
            }
            $data = $stmt->fetch();
            if (!$data){
                throw new Exception('Documento no soportado');
            }
            return $data;
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }
}