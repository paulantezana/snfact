<?php
class SunatSummaryResponse
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function Get($sunatSummaryResponseId) {
        try {
            $currentDate = date('Y-m-d H:i:s');
 
            $query = $this->connection->prepare("SELECT ticket, response_code
                                                FROM sunat_summary_response
                                                WHERE sunat_summary_response_id = :sunatSummaryResponseId;");
            $query->bindParam(':sunatSummaryResponseId', $sunatSummaryResponseId);
            $query->execute();
            $data = $query->fetchAll();

            if (count($data) > 0) {
                return $data[0];
            }
            else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error in function : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Insert($communicationId, $enabled, $userId, $sunatCommunication) {
        try {
            $currentDate = date('Y-m-d H:i:s');
 
            $query = $this->connection->prepare("INSERT INTO sunat_summary_response (sunat_communication_id, sunat_communication_success, ticket, enabled, creation_date, creation_user_id, modification_user_id, modification_date)
                                            VALUES (:communicationId, :sunatCommunicationSuccess, :ticket, :enabled, :creationDate, :creationUserId, :modificationUserId, :modificationDate);");
            $query->bindParam(':communicationId', $communicationId);
            $query->bindParam(':sunatCommunicationSuccess', $sunatCommunication->sunatComunicationSuccess);
            $query->bindParam(':ticket', $sunatCommunication->ticket);
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

    public function UpdateSunatResponse($sunatSummaryResponseId, $statusCode, $userId) {
        try {
            $currentDate = date('Y-m-d H:i:s');
 
            $query = $this->connection->prepare("UPDATE sunat_summary_response SET response_code =  :responseCode , modification_date = :modificationDate, modification_user_id = :modificationUserId WHERE sunat_summary_response_id = :sunatSummaryResponseId;");
            $query->bindParam(':sunatSummaryResponseId', $sunatSummaryResponseId);
            $query->bindParam(':responseCode', $statusCode);
            $query->bindParam(':modificationDate', $currentDate);
            $query->bindParam(':modificationUserId', $userId);

            $query->execute();
            
            return $this->connection->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Error in function : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }
}