<?php

class MngAppAuthorization extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("mng_app_authorization","mng_app_authorization_id", $connection);
    }
    public function GetMenu($userRoleId){
        try{
            $sql = 'SELECT app.module, GROUP_CONCAT(app.description) as description FROM mng_user_role_authorization as ur
                        INNER JOIN mng_app_authorization app ON ur.mng_app_authorization_id = app.mng_app_authorization_id
                        WHERE ur.mng_user_role_id = :mng_user_role_id
                        GROUP BY app.module';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':mng_user_role_id' => $userRoleId,
            ]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    public function GetAllByUserRoleId($userRoleId){
        try{
            $sql = 'SELECT * FROM mng_user_role_authorization WHERE mng_user_role_id = :mng_user_role_id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':mng_user_role_id' => $userRoleId
            ]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    public function Save($authIds, $userRoleId, $userId){
        try{
            $this->db->beginTransaction();

            $sql = 'DELETE FROM mng_user_role_authorization WHERE mng_user_role_id = :mng_user_role_id';
            $stmt = $this->db->prepare($sql);
            if (!$stmt->execute([
                ':mng_user_role_id' => $userRoleId,
            ])){
                throw new Exception("No se pudo elimiar el registro");
            }

            foreach ($authIds as $row){
                $sql = 'INSERT INTO mng_user_role_authorization (mng_user_role_id, mng_app_authorization_id) 
                        VALUES (:mng_user_role_id, :mng_app_authorization_id)';
                $stmt = $this->db->prepare($sql);
                if (!$stmt->execute([
                    ':mng_user_role_id' => $userRoleId,
                    ':mng_app_authorization_id' => $row,
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
            $sql = 'SELECT count(*) as count FROM mng_user_role_authorization as ur
                        INNER JOIN mng_app_authorization app ON ur.mng_app_authorization_id = app.mng_app_authorization_id
                        WHERE ur.mng_user_role_id = :mng_user_role_id AND app.module = :module AND app.action = :action
                        GROUP BY app.module';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':mng_user_role_id' => $userRoleId,
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