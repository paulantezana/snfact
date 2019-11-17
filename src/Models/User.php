<?php


class User extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("user","user_id",$db);
    }

    public function Paginate($page = 1, $limit = 10, $search = '')
    {
        try {
            $offset = ($page - 1) * $limit;
            $totalRows = $this->db->query("SELECT COUNT(*) FROM user WHERE user_name LIKE '%{$search}%'")->fetchColumn();
            $totalPages = ceil($totalRows / $limit);

            $sql = "SELECT user.*, ur.name as user_role FROM user
                    INNER JOIN user_role ur on user.user_role_id = ur.user_role_id
                    WHERE user_name LIKE '%{$search}%' LIMIT $offset, $limit";
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


    public function login($user, $password)
    {
        try{
            $currentDate = date('Y-m-d H:i:s');

            // Hash password
            $password = sha1(trim($password));

            $sql = 'SELECT * FROM user WHERE email = :email AND password = :password LIMIT 1 ';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':email' => $user,
                ':password' => $password,
            ]);

            $data = $stmt->fetch();

            if(!$data){
                $sql = 'SELECT * FROM user WHERE user_name = :user_name AND password = :password LIMIT 1';
                $stmt = $this->db->prepare($sql);

                $stmt->execute([
                    ':user_name' => $user,
                    ':password' => $password,
                ]);

                if($stmt->rowCount() == 0){
                    throw new Exception("El usuario o contraseñas es icorrecta");
                }
            }

            $data = $stmt->fetch();
            if ($data['state'] == '0'){
                throw new Exception("Usted no esta autorizado para ingresar al sistema.");
            }

            return $data;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : " . __FUNCTION__ . ' | ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }

    public function Insert($user, $userId){
        try{
            $currentDate = date('Y-m-d H:i:s');

            $sql = "INSERT INTO user (updated_at, created_at, created_user_id, updated_user_id, password, email,
                                        avatar, user_name, state, user_role_id)
                    VALUES (:updated_at, :created_at, :created_user_id, :updated_user_id, :password, :email,
                                        :avatar, :user_name, :state, :user_role_id)";

            $stmt = $this->db->prepare($sql);

            if(!$stmt->execute([
                ":updated_at" => $currentDate,
                ":created_at" => $currentDate,
                ":created_user_id" => $userId,
                ":updated_user_id" => $userId,

                ":password" => sha1($user['password'] ?? '') ,
                ":email" => $user['email'] ?? '',
                ":avatar" => '',
                ":user_name" => $user['userName'] ?? '',
                ":state" => $user['state'] ?? false,

                ":user_role_id" => $user['userRoleId'],
            ])){
                throw new Exception('No se pudo insertar el registro');
            }
            $lastInsertId = (int)$this->db->lastInsertId();

            return $lastInsertId;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : " . __FUNCTION__ . ' | ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }

    public function GetById($id) {
        try{
            $sql = "SELECT user_id, email, avatar, user_name, state, login_count, updated_at, user_role_id, created_at FROM user WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":user_id"=>$id]);

            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception("Error en metodo : " . __FUNCTION__ . ' | ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }

    public function GetByIdFa2($id) {
        try{
            $sql = "SELECT user_id, email, avatar, user_name, state, login_count, updated_at, user_role_id, created_at, fa2_secret FROM user WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":user_id"=>$id]);

            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception("Error en metodo : " . __FUNCTION__ . ' | ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }

    public function GetBy($columnName, $value) {
        try{
            $sql = "SELECT user_id, email, avatar, user_name, state, login_count, updated_at, user_role_id, created_at FROM user WHERE $columnName = :$columnName LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":$columnName" => $value]);

            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception("Error en metodo : " . __FUNCTION__ . ' | ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
}
