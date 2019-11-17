<?php

class Product extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("product", "product_id", $connection);
    }

    public function Paginate($page = 1, $limit = 10, $search = '')
    {
        try {
            $offset = ($page - 1) * $limit;
            $totalRows = $this->db->query("SELECT COUNT(*) FROM product WHERE product_code LIKE '%{$search}%'")->fetchColumn();
            $totalPages = ceil($totalRows / $limit);

            $sql = "SELECT product.*, c.name as category_description, caitc.description as affectation_igv_description  FROM product
                    INNER JOIN category c on product.category_id = c.category_id
                    INNER JOIN cat_affectation_igv_type_code caitc on product.affectation_code = caitc.code
                    WHERE product_code LIKE '%{$search}%' LIMIT $offset, $limit";
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

    public function insert($product, $userReferId)
    {
        $currentDate = date('Y-m-d H:i:s');
        $sql = 'INSERT INTO product (updated_at, created_at, created_user_id, updated_user_id, business_id, description, unit_price,
                                    product_key, product_code, unit_measure_code, affectation_code, system_isc_code, isc, category_id) 
                                VALUES (:updated_at, :created_at, :created_user_id, :updated_user_id, :business_id, :description, :unit_price,
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
}
