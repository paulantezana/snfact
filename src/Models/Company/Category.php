<?php

class Category extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("category", "category_id", $connection);
    }

    public function GetAllByBusinessId($businessId)
    {
        try {
            $sql = "SELECT * FROM category WHERE business_id = :business_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':business_id' => $businessId,
            ]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }
    public function Paginate($page, $limit = 10, $search = '', $businessId = 0)
    {
        try {
            $offset = ($page - 1) * $limit;
            $totalRows = $this->db->query("SELECT COUNT(*) FROM category WHERE business_id = '$businessId' AND name LIKE '%{$search}%'")->fetchColumn();

            $totalPages = ceil($totalRows / $limit);

            $sql = "SELECT * FROM category 
                    WHERE business_id = :business_id AND name LIKE '%{$search}%' ORDER BY category_id DESC LIMIT $offset, $limit";
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

    public function insert($category)
    {
        try{
            $currentDate = date('Y-m-d H:i:s');
            $sql = 'INSERT INTO category (updated_at, created_at, created_user_id, updated_user_id, business_id,
                                        parent_id, name, description, state) 
                                VALUES (:updated_at, :created_at, :created_user_id, :updated_user_id, :business_id,
                                        :parent_id, :name, :description, :state)';
            $stmt = $this->db->prepare($sql);

            if (!$stmt->execute([
                ':updated_at' => $currentDate,
                ':created_at' => $currentDate,
                ':created_user_id' => $_SESSION[SESS_KEY],
                ':updated_user_id' => $_SESSION[SESS_KEY],

                ':business_id' => $category['businessId'],
                ':parent_id' => $category['parentId'],
                ':name' => $category['name'],
                ':state' => $category['state'],
                ':description' => $category['description'],
            ])) {
                throw new Exception('No se pudo insertar el nuevo registro');
            }

            $lastId = $this->db->lastInsertId();
            return $this->GetById($lastId);
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function DeleteById($id)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE {$this->tableID} = :{$this->tableID}";
            $stmt = $this->db->prepare($sql);
            if (!$stmt->execute([
                ":{$this->tableID}" => $id,
            ])) {
                throw new Exception("No se pudo elimiar el registro");
            }
            return $id;
        } catch (PDOException $e) {
            if($e->getCode() == '23000'){
                throw new Exception('No fue eliminado porque existen PRODUCTOS relacionados');
            } else {
                throw new Exception('PDO: ' . $e->getMessage());
            }
        } catch(Exception $e){
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }
}
