<?php
class SunatXml
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function Insert($xmlTypeId, $referenceId, $userId) {
        try {
            $currentDate = date('Y-m-d H:i:s');
 
            $query = $this->connection->prepare("INSERT INTO sunat_xml (sunat_xml_type_id, reference_id, creation_date, creation_user_id, modification_user_id, modification_date)
                                            VALUES (:xmlTypeId, :referenceId, :creationDate, :creationUserId, :modificationUserId, :modificationDate);");
            $query->bindParam(':xmlTypeId', $xmlTypeId);
            $query->bindParam(':referenceId', $referenceId);
            $query->bindParam(':creationDate', $currentDate);
            $query->bindParam(':creationUserId', $userId);
            $query->bindParam(':modificationUserId', $userId);
            $query->bindParam(':modificationDate', $currentDate);

            $query->execute();
            
            return $this->connection->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Error in function : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }
}