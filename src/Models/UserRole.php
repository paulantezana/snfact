<?php


class UserRole extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("user_role","user_role_id", $connection);
    }

    public function getAllByBusinessId($businessId)
    {
        try {
            $sql = 'SELECT user_role_id, name FROM user_role WHERE business_id = :business_id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':business_id' => $businessId
            ]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function getAllByBusinessIdOn($businessId)
    {
        try {
            $sql = 'SELECT user_role_id, name FROM user_role WHERE business_id = :business_id AND state = 1';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':business_id' => $businessId
            ]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function insert($userRole, $userId){
        try{
            $currentDate = date('Y-m-d H:i:s');

            $sql = "INSERT INTO user_role (updated_at, created_at, created_user_id, updated_user_id, name, business_id, state)
                    VALUES (:updated_at, :created_at, :created_user_id, :updated_user_id, :name, :business_id, :state)";
            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
                ":updated_at" => $currentDate,
                ":created_at" => $currentDate,
                ":created_user_id" => $userId,
                ":updated_user_id" => $userId,

                ":name" => $userRole['name'],
                ":business_id" => $userRole['businessId'],
                ":state" => $userRole['state'],
            ])){
                throw new Exception('No se pudo insertar el registro');
            }
            return  (int)$this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }
}