<?php


class BusinessLocal extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("business_local","business_local_id",$db);
    }

    public function paginateByBusinessId($page, $limit = 10, $search = "", $businessId = 0)
    {
        try {
            $offset = ($page - 1) * $limit;
            $totalRows = $this->db->query("SELECT COUNT(*) FROM  business_local WHERE business_id = {$businessId} AND short_name LIKE '%{$search}%' ")->fetchColumn();
            $totalPages = ceil($totalRows / $limit);

            $sql = "SELECT * FROM business_local WHERE business_id = :business_id AND short_name LIKE '%{$search}%' LIMIT $offset, $limit";
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

    public function getByIdDetail($id)
    {
        try {
            $sql = "SELECT * FROM business_local WHERE business_local_id = :business_local_id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":business_local_id" => $id]);
            $data = $stmt->fetch();

            $sql = "SELECT * FROM business_serie WHERE business_local_id = :business_local_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":business_local_id" => $id]);
            $data['item'] = $stmt->fetchAll();

            return $data;
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function getAllByBusinessId($businessId)
    {
        try {
            $sql = 'SELECT * FROM business_local WHERE business_id = :business_id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':business_id' => $businessId
            ]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function insert($businessLocal, $userReferId){
        try{
            $currentDate = date('Y-m-d H:i:s');

            $sql = "INSERT INTO business_local (updated_at, created_at, created_user_id, updated_user_id, short_name, 
                                                sunat_code, location_code, address, 
                                                pdf_invoice_size, pdf_header, description, state, business_id)
                    VALUES (:updated_at, :created_at, :created_user_id, :updated_user_id, :short_name,
                            :sunat_code, :location_code, :address, :pdf_invoice_size,
                            :pdf_header, :description, :state, :business_id)";
            $stmt = $this->db->prepare($sql);

            if(!$stmt->execute([
                ':updated_at' => $currentDate,
                ':created_at' => $currentDate,
                ':created_user_id' => $userReferId,
                ':updated_user_id' => $userReferId,

                ':short_name' => $businessLocal['shortName'],
                ':sunat_code' => $businessLocal['sunatCode'],
                ':location_code' => $businessLocal['locationCode'],
                ':address' => $businessLocal['address'],
                ':pdf_invoice_size' => $businessLocal['pdfInvoiceSize'],
                ':pdf_header' => $businessLocal['pdfHeader'],
                ':description' => $businessLocal['description'],
                ':state' => $businessLocal['state'],
                ':business_id' => $businessLocal['businessId'],
            ])){
                throw new Exception("Error al insertar el registro");
            }
            $businessLocalId = (int)$this->db->lastInsertId();

            foreach ($businessLocal['item'] as $row){
                $sql = "INSERT INTO business_serie (updated_at, business_local_id, serie, document_code, max_correlative, contingency)
                    VALUES (:updated_at, :business_local_id, :serie, :document_code, :max_correlative, :contingency)";
                $stmt = $this->db->prepare($sql);

                if(!$stmt->execute([
                    ':updated_at' => $currentDate,
                    ':business_local_id' => $businessLocalId,
                    ':serie' => $row['serie'],
                    ':document_code' => $row['documentCode'],
                    ':max_correlative' => 0,
                    ':contingency' => $row['contingency'],
                ])){
                    throw new Exception("Error al insertar el registro");
                }
            }

            return $businessLocalId;
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function update($businessLocal, $userReferId){
        try{
            $currentDate = date('Y-m-d H:i:s');

            $this->updateById($businessLocal['id'], [
                'updated_at' => $currentDate,
                'updated_user_id' => $userReferId,

                'short_name' => $businessLocal['shortName'],
                'sunat_code' => $businessLocal['sunatCode'],
                'location_code' => $businessLocal['locationCode'],
                'address' => $businessLocal['address'],
                'pdf_invoice_size' => $businessLocal['pdfInvoiceSize'],
                'pdf_header' => $businessLocal['pdfHeader'],
                'description' => $businessLocal['description'],
                'state' => $businessLocal['state'],
            ]);

            foreach ($businessLocal['item'] as $row){
                if ($row['businessSerieId']>=1){
                    $sql = "UPDATE business_serie SET updated_at = :updated_at, serie=:serie, document_code = :document_code, contingency = :contingency
                                WHERE business_serie_id = :business_serie_id";
                    $stmt = $this->db->prepare($sql);

                    if(!$stmt->execute([
                        ':updated_at' => $currentDate,
                        ':serie' => $row['serie'],
                        ':document_code' => $row['documentCode'],
                        ':contingency' => $row['contingency'],
                        ':business_serie_id' => $row['businessSerieId'],
                    ])){
                        throw new Exception("Error al actualizar el registro");
                    }
                } else {
                    $sql = "INSERT INTO business_serie (updated_at, business_local_id, serie, document_code, max_correlative, contingency)
                            VALUES (:updated_at, :business_local_id, :serie, :document_code, :max_correlative, :contingency)";
                    $stmt = $this->db->prepare($sql);

                    if(!$stmt->execute([
                        ':updated_at' => $currentDate,
                        ':business_local_id' => $businessLocal['id'],
                        ':serie' => $row['serie'],
                        ':document_code' => $row['documentCode'],
                        ':max_correlative' => 0,
                        ':contingency' => $row['contingency'],
                    ])){
                        throw new Exception("Error al insertar el registro");
                    }
                }
            }

            return $businessLocal['id'];
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }
}