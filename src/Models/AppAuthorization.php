<?php

class AppAuthorization extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("app_authorization","app_authorization_id", $connection);
    }

    public function getAllId()
    {
        try {
            $sql = 'SELECT app_authorization_id FROM app_authorization';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function getMenu($userRoleId){
        try{
            $sql = 'SELECT app.module FROM user_role_authorization as ur
                        INNER JOIN app_authorization app ON ur.app_authorization_id = app.app_authorization_id
                        WHERE ur.user_role_id = :user_role_id
                        GROUP BY app.module';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_role_id' => $userRoleId,
            ]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function getAllByUserRoleId($userRoleId){
        try{
            $sql = 'SELECT * FROM user_role_authorization WHERE user_role_id = :user_role_id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_role_id' => $userRoleId
            ]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function save($authIds, $userRoleId, $userId){
        try{
            $this->db->beginTransaction();

            $sql = 'DELETE FROM user_role_authorization WHERE user_role_id = :user_role_id';
            $stmt = $this->db->prepare($sql);
            if (!$stmt->execute([
                ':user_role_id' => $userRoleId,
            ])){
                throw new Exception("No se pudo elimiar el registro");
            }

            foreach ($authIds as $row){
                $sql = 'INSERT INTO user_role_authorization (user_role_id, app_authorization_id) 
                        VALUES (:user_role_id, :app_authorization_id)';
                $stmt = $this->db->prepare($sql);
                if (!$stmt->execute([
                    ':user_role_id' => $userRoleId,
                    ':app_authorization_id' => $row,
                ])){
                    throw new Exception("No se pudo insertar el registro");
                }
            }

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function register($authIds, $userRoleId){
        try{
            foreach ($authIds as $row){
                $sql = 'INSERT INTO user_role_authorization (user_role_id, app_authorization_id) 
                        VALUES (:user_role_id, :app_authorization_id)';
                $stmt = $this->db->prepare($sql);
                if (!$stmt->execute([
                    ':user_role_id' => $userRoleId,
                    ':app_authorization_id' => $row['app_authorization_id'],
                ])){
                    throw new Exception("No se pudo insertar el registro");
                }
            }
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function isAuthorized($module, $action, $userRoleId){
        try{
            $sql = 'SELECT count(*) as count FROM user_role_authorization as ur
                        INNER JOIN app_authorization app ON ur.app_authorization_id = app.app_authorization_id
                        WHERE ur.user_role_id = :user_role_id AND app.module = :module AND app.action = :action
                        GROUP BY app.module';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_role_id' => $userRoleId,
                ':module' => $module,
                ':action' => $action,
            ]);

            if ($stmt->fetch()['count'] > 0){
                return $stmt->fetch();
            }
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }
}