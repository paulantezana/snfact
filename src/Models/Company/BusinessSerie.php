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

    public function GetDocumentSerieNumber(array $document) {
        try{
            $sql = 'SELECT (max_correlative + 1) as number, serie, document_code, cdtc.description FROM business_serie
                    INNER JOIN cat_document_type_code cdtc on business_serie.document_code = cdtc.code
                    WHERE business_local_id = :business_local_id AND document_code = :document_code';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':business_local_id',$document['businessLocalId']);
            $stmt->bindParam(':document_code',$document['documentCode']);
            if(!$stmt->execute()){
                throw new Exception('Documento no soportado');
            }
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    public function GetNextNumber(array $document) {
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
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }
}