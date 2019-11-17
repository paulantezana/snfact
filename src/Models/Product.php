<?php

class Customer extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("customer", "customer_id", $connection);
    }

    public function Paginate($page = 1, $limit = 10, $search = '')
    {
        try {
            $offset = ($page - 1) * $limit;
            $totalRows = $this->db->query("SELECT COUNT(*) FROM customer WHERE social_reason LIKE '%{$search}%'")->fetchColumn();
            $totalPages = ceil($totalRows / $limit);

            $sql = "SELECT * FROM customer WHERE social_reason LIKE '%{$search}%' LIMIT $offset, $limit";
            $stmt = $this->db->prepare($sql);

            $stmt->execute();
            $data = $stmt->fetchAll();

            $paginate = [
                'current' => $page,
                'pages' => $totalPages,
                'limit' => $limit,
                'data' => $data,
            ];
            return $paginate;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : " . __FUNCTION__ . ' | ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }

    public function insert($customer, $userReferId)
    {
        $currentDate = date('Y-m-d H:i:s');
        $sql = 'INSERT INTO customer (updated_at, created_at, created_user_id, updated_user_id, business_id, document_number,
                                    identity_document_code, social_reason, commercial_reason, fiscal_address, main_email, telephone) 
                                VALUES (:updated_at, :created_at, :created_user_id, :updated_user_id, :business_id, :document_number,
                                    :identity_document_code, :social_reason, :commercial_reason, :fiscal_address,: main_email, :telephone)';
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute([
            ':updated_at' => $currentDate,
            ':created_at' => $currentDate,
            ':created_user_id' => $_SESSION[SESS_KEY],
            ':updated_user_id' => $_SESSION[SESS_KEY],
            ':business_id' => $customer['businessId'],
            ':document_number' => $customer['documentNumber'],
            ':identity_document_code' => $customer['identityDocumentCode'],
            ':social_reason' => $customer['socialReason'],
            ':commercial_reason' => $customer['commercialReason'],
            ':fiscal_address' => $customer['fiscalAddress'],
            ':main_email' => $customer['mainEmail'],
            ':telephone' => $customer['telephone'],
        ])) {
            return $this->db->lastInsertId();
        }
        return false;
    }
}
