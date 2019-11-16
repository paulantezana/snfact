<?php


class UserRole extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("user_role","user_role_id", $connection);
    }
    public function Insert($userRole, $userId){
        try{
            $sql = "INSERT INTO user_role (name) VALUES (:name)";
            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
                ":name" => $userRole['name'],
            ])){
                throw new Exception('No se pudo insertar el registro');
            }
            return  (int)$this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Error en metodo : " . __FUNCTION__ . ' | ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
}