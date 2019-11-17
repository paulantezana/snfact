<?php


class CatGeographicalLocationCode extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("cat_geographical_location_code","code",$db);
    }

    public function Search(string $search) {
        try{
            $sql = "SELECT * FROM cat_geographical_location_code WHERE district LIKE :district OR province LIKE :province LIMIT 8";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':district' => '%' . $search . '%',
                ':province' => '%' . $search . '%',
            ]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("Error in : " . __FUNCTION__ . ' | ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
}
