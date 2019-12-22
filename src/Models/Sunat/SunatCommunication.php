<?php
class SunatCommunication
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function Insert($communicationTypeId, $referenceId, $userId) {
        try {
            $currentDate = date('Y-m-d H:i:s');
 
            $query = $this->connection->prepare("INSERT INTO sunat_communication (sunat_communication_type_id, reference_id, creation_date, creation_user_id, modification_user_id, modification_date)
                                            VALUES (:communicationTypeId, :referenceId, :creationDate, :creationUserId, :modificationUserId, :modificationDate);");
            $query->bindParam(':communicationTypeId', $communicationTypeId);
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

    public function GetSummaryCommunication($sunatCommunicationId) {
        try {
            $query = $this->connection->prepare("SELECT res.ticket
                                                FROM sunat_communication com
                                                JOIN sunat_summary_response res on com.sunat_communication_id = res.sunat_communication_id
                                                JOIN 
                                                WHERE sunat_summary_response_id = :sunatSummaryResponseId;");
            $query->bindParam(':communicationId', $communicationId);
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
}