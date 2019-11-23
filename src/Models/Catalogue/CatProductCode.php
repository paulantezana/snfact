<?php


class CatProductCode extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("cat_product_code","code",$db);
    }
    public function Search($search){
        try{
            $sql = "SELECT * FROM cat_product_code WHERE description LIKE :description OR code LIKE :code LIMIT 8";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ":description" => '%' . $search . '%',
                ":code" => '%' . $search . '%',
            ]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("Error in : " . __FUNCTION__ . ' | ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
}
