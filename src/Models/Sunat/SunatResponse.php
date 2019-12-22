<?php
class SunatResponse
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function Insert($communicationId, $enabled, $userId, $sunatCommunication) {
        try {
            $currentDate = date('Y-m-d H:i:s');
 
            $query = $this->connection->prepare("INSERT INTO sunat_response (sunat_communication_id, sunat_communication_success, reader_success, sunat_response_code, sunat_response_description, enabled, creation_date, creation_user_id, modification_user_id, modification_date)
                                            VALUES (:communicationId, :sunatCommunicationSuccess, :readerSuccess, :sunatResponseCode, :sunatResponseDescription, :enabled, :creationDate, :creationUserId, :modificationUserId, :modificationDate);");
            $query->bindParam(':communicationId', $communicationId);
            $query->bindParam(':sunatCommunicationSuccess', $sunatCommunication->sunatComunicationSuccess);
            $query->bindParam(':readerSuccess', $sunatCommunication->readerSuccess);
            $query->bindParam(':sunatResponseCode', $sunatCommunication->sunatResponseCode);
            $query->bindParam(':sunatResponseDescription', $sunatCommunication->sunatDescription);
            $query->bindParam(':enabled', $enabled);
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