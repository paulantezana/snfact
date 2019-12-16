<?php

class AppAuthorization extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("app_authorization","app_authorization_id", $connection);
    }
    public function GetMenu($userRoleId){
        try{
            $sql = 'SELECT app.module, GROUP_CONCAT(app.description) as description FROM user_role_authorization as ur
                        INNER JOIN app_authorization app ON ur.app_authorization_id = app.app_authorization_id
                        WHERE ur.user_role_id = :user_role_id
                        GROUP BY app.module';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_role_id' => $userRoleId,
            ]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    public function GetAllByUserRoleId($userRoleId){
        try{
            $sql = 'SELECT * FROM user_role_authorization WHERE user_role_id = :user_role_id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_role_id' => $userRoleId
            ]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    public function Save($authIds, $userRoleId, $userId){
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
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    public function IsAuthorized($module,$action,$userRoleId){
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
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }
}