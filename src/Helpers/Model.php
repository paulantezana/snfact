<?php


class Model
{
    protected $table;
    protected $tableID;
    protected $db;

    public function __construct($table, $tableID, PDO $db)
    {
        $this->table = $table;
        $this->tableID = $tableID;
        $this->db = $db;
    }

    public function GetAll()
    {
        try {
            $sql = 'SELECT * FROM ' . $this->table;
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    public function Paginate($page, $limit = 10)
    {
        try {
            $offset = ($page - 1) * $limit;
            $totalRows = $this->db->query('SELECT COUNT(*) FROM ' . $this->table)->fetchColumn();
            $totalPages = ceil($totalRows / $limit);

            $sql = "SELECT * FROM {$this->table} LIMIT $offset, $limit";
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
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    public function GetById($id)
    {
        try {
            $sql = "SELECT * FROM $this->table WHERE $this->tableID = :$this->tableID LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":$this->tableID" => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    public function GetBy($columnName, $value)
    {
        try {
            $sql = "SELECT * FROM $this->table WHERE $columnName = :$columnName LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":$columnName" => $value]);
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
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
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    public function DeleteBy($columnName, $value)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE $columnName = :$columnName";
            $stmt = $this->db->prepare($sql);

            if (!$stmt->execute([
                ":$columnName" => $value,
            ])) {
                throw new Exception("No se pudo elimiar el registro");
            }
            return $value;
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    public function UpdateById($id, $data)
    {
        try {
            $sql = "UPDATE {$this->table} SET ";
            foreach ($data as $key => $value) {
                $sql .= "$key = :$key, ";
            }
            $sql = trim(trim($sql), ',');
            $sql .= " WHERE {$this->tableID} = :{$this->tableID}";

            $execute = [];
            foreach ($data as $key => $value) {
                $execute[":$key"] = $value;
            }
            $execute[":{$this->tableID}"] = $id;

            $stmt = $this->db->prepare($sql);
            if (!$stmt->execute($execute)) {
                throw new Exception("Error al actualizar el registro");
            }
            return $id;
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    public function UpdateBy($columnName, $value, $data)
    {
        try {
            $sql = "UPDATE {$this->table} SET ";
            foreach ($data as $key => $value) {
                $sql .= "$key = :$key, ";
            }
            $sql = trim(trim($sql), ',');
            $sql .= " WHERE $columnName = :$columnName";

            $execute = [];
            foreach ($data as $key => $value) {
                $execute[":$key"] = $value;
            }
            $execute[":$columnName"] = $value;

            $stmt = $this->db->prepare($sql);
            if (!$stmt->execute($execute)) {
                throw new Exception("Error al actualizar el registro");
            }
            return $value;
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }

    public function SearchBy($columnName, $search)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE $columnName LIKE :$columnName  LIMIT 8";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ":$columnName" => '%' . $search . '%',
            ]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }
}
