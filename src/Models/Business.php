<?php


class Business extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("business","business_id",$db);
    }

    public function paginate($page, $limit = 10, $search = '', $businessId = 0)
    {
        try {
            $offset = ($page - 1) * $limit;
            $totalRows = $this->db->query("SELECT COUNT(*) FROM business WHERE social_reason LIKE '%{$search}%'")->fetchColumn();

            $totalPages = ceil($totalRows / $limit);

            $sql = "SELECT * FROM business WHERE social_reason LIKE '%{$search}%' ORDER BY business_id DESC LIMIT $offset, $limit";
            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                ':business_id' => $businessId
            ]);
            $data = $stmt->fetchAll();

            $paginate = [
                'current' => $page,
                'pages' => $totalPages,
                'limit' => $limit,
                'data' => $data,
            ];
            return $paginate;
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function getByUserId($userReferID){
        try{
            $sql = "SELECT business.* FROM business 
                    INNER JOIN business_user ON business.business_id = business_user.business_id
                    WHERE business_user.user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":user_id" => $userReferID]);
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function save($business){
        try{
            if (((int)$business['business_id'] ?? 0) >= 1){
                $this->updateById((int)$business['business_id'],[
                    'continue_payment' => false,
                    'ruc' => $business['ruc'],
                    'social_reason' => $business['social_reason'],
                    'commercial_reason' => $business['commercial_reason'],
                    'email' => $business['email'],
                    'phone' => $business['phone'],
                    'web_site' => $business['web_site'],
                ]);
                return $business['business_id'];
            } else {
                $sql = "INSERT INTO business (continue_payment, ruc, social_reason, commercial_reason, email, phone, web_site)
                        VALUES (:continue_payment, :ruc, :social_reason, :commercial_reason, :email, :phone, :web_site)";
                $stmt = $this->db->prepare($sql);
                if(!$stmt->execute([
                    ':continue_payment' => (bool)$business['continue_payment'] ?? false,
                    ':ruc' => $business['ruc'],
                    ':social_reason' => $business['social_reason'],
                    ':commercial_reason' => $business['commercial_reason'],
                    ':email' => $business['email'],
                    ':phone' => $business['phone'],
                    ':web_site' => $business['web_site'],
                ])){
                    throw new Exception("Error al insertar el registro");
                }
                $businessId = (int)$this->db->lastInsertId();


                $sql = "INSERT INTO business_user (business_id, user_id) VALUES (:business_id, :user_id)";
                $stmt = $this->db->prepare($sql);
                if(!$stmt->execute([
                    ':business_id' => $businessId,
                    ':user_id' => $_SESSION[SESS_KEY],
                ])){
                    throw new Exception("Error al insertar el registro");
                }

                return $businessId;
            }
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function insert($business, $userReferId){
        try{
            $sql = "INSERT INTO business (continue_payment, ruc, social_reason, commercial_reason, email, phone, web_site, environment, state)
                    VALUES (:continue_payment, :ruc, :social_reason, :commercial_reason, :email, :phone, :web_site, :environment, :state)";
            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
                ':continue_payment' => (bool)$business['continue_payment'] ?? false,
                ':ruc' => $business['ruc'],
                ':social_reason' => $business['social_reason'],
                ':commercial_reason' => $business['commercial_reason'],
                ':email' => $business['email'],
                ':phone' => $business['phone'],
                ':web_site' => $business['web_site'],
                ':environment' => $business['environment'],
                ':state' => $business['state'],
            ])){
                throw new Exception("Error al insertar el registro");
            }
            $businessId = (int)$this->db->lastInsertId();

            $sql = "INSERT INTO business_user (business_id, user_id) VALUES (:business_id, :user_id)";
            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
                ':business_id' => $businessId,
                ':user_id' => $userReferId,
            ])){
                throw new Exception("Error al insertar el registro");
            }

            return $businessId;
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }
}