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
            throw new Exception("Error en metodo : " . __FUNCTION__ . ' | ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
    public function Paginate($page, $limit = 10, $search = '', $businessId = 0)
    {
        try {
            $offset = ($page - 1) * $limit;
            $totalRows = $this->db->query("SELECT COUNT(*) FROM category WHERE business_id = '$businessId' AND name LIKE '%{$search}%'")->fetchColumn();

            $totalPages = ceil($totalRows / $limit);

            $sql = "SELECT * FROM category 
                    WHERE business_id = :business_id AND name LIKE '%{$search}%' LIMIT $offset, $limit";
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
            throw new Exception("Error en metodo : " . __FUNCTION__ . ' | ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }

    public function insert($category)
    {
        $currentDate = date('Y-m-d H:i:s');
        $sql = 'INSERT INTO category (updated_at, created_at, created_user_id, updated_user_id, business_id,
                                        parent_id, name, description) 
                                VALUES (:updated_at, :created_at, :created_user_id, :updated_user_id, :business_id,
                                        :parent_id, :name, :description)';
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute([
            ':updated_at' => $currentDate,
            ':created_at' => $currentDate,
            ':created_user_id' => $_SESSION[SESS_KEY],
            ':updated_user_id' => $_SESSION[SESS_KEY],

            ':business_id' => $category['businessId'],
            ':parent_id' => $category['parentId'],
            ':name' => $category['name'],
            ':description' => $category['description'],
        ])) {
            return $this->db->lastInsertId();
        }
        return false;
    }
}
