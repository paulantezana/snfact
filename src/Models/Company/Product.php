<?php

class Product extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("product", "product_id", $connection);
    }

    public function GetAllByBusinessId($businessId)
    {
        try {
            $sql = 'SELECT * FROM product WHERE business_id = :business_id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':business_id' => $businessId
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
            $totalRows = $this->db->query("SELECT COUNT(*) FROM product WHERE business_id = '$businessId' AND product_code LIKE '%{$search}%'  ")->fetchColumn();
            $totalPages = ceil($totalRows / $limit);

            $sql = "SELECT product.*, c.name as category_description, caitc.description as affectation_igv_description  FROM product
                    INNER JOIN category c on product.category_id = c.category_id
                    INNER JOIN cat_affectation_igv_type_code caitc on product.affectation_code = caitc.code
                    WHERE product.business_id = :business_id AND product_code LIKE '%{$search}%' LIMIT $offset, $limit";
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

    public function insert($product, $userReferId)
    {
        $currentDate = date('Y-m-d H:i:s');
        $sql = 'INSERT INTO product (updated_at, created_at, created_user_id, updated_user_id, business_id, description, unit_price, unit_value,
                                    product_key, product_code, unit_measure_code, affectation_code, system_isc_code, isc, category_id) 
                                VALUES (:updated_at, :created_at, :created_user_id, :updated_user_id, :business_id, :description, :unit_price, :unit_value,
                                    :product_key, :product_code, :unit_measure_code, :affectation_code, :system_isc_code, :isc, :category_id)';
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute([
            ':updated_at' => $currentDate,
            ':created_at' => $currentDate,
            ':created_user_id' => $_SESSION[SESS_KEY],
            ':updated_user_id' => $_SESSION[SESS_KEY],

            ':business_id' => $product['businessId'],
            ':description' => $product['description'],
            ':unit_price' => $product['unitPrice'],
            ':unit_value' => $product['unitValue'],
            ':product_key' => $product['productKey'],
            ':product_code' => $product['productCode'],
            ':unit_measure_code' => $product['unitMeasureCode'],
            ':affectation_code' => $product['affectationCode'],
            ':system_isc_code' => $product['systemIscCode'],
            ':isc' => $product['isc'],
            ':category_id' => $product['categoryId'],
        ])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function Search($search){
        try{
            $sql = 'SELECT * FROM product WHERE (description LIKE :description OR product_code LIKE :product_code) AND business_id = :business_id LIMIT 8';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':description' => '%' . $search['search'] . '%',
                ':product_code' => '%' . $search['search'] . '%',
                ':business_id' => $search['businessId'],
            ]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("Error in : " . __FUNCTION__ . ' | ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
}
