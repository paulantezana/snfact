<?php


class UserRole extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("user_role","user_role_id", $connection);
    }
    public function Insert($userRole, $userId){
        try{
            $currentDate = date('Y-m-d H:i:s');

            $sql = "INSERT INTO user_role (updated_at, created_at, created_user_id, updated_user_id, name, business_id)
                    VALUES (:updated_at, :created_at, :created_user_id, :updated_user_id, :name, :business_id)";
            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
                ":updated_at" => $currentDate,
                ":created_at" => $currentDate,
                ":created_user_id" => $userId,
                ":updated_user_id" => $userId,

                ":name" => $userRole['name'],
                ":business_id" => $userRole['businessId'],
            ])){
                throw new Exception('No se pudo insertar el registro');
            }
            return  (int)$this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }
}