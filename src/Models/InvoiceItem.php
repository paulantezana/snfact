<?php


class InvoiceItem
{
    private $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function byInvoiceId($invoiceId) {
        try{
            $sql = 'SELECT * FROM invoice_item WHERE invoice_id = :invoice_id';

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':invoice_id' => $invoiceId,
            ]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function byInvoiceIdSummary($invoiceId) {
        try{
            $sql = 'SELECT * FROM invoice_item WHERE invoice_item.invoice_id = :invoice_id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':invoice_id' => $invoiceId,
            ]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function byInvoiceIdXML($invoiceId) {
        try{
            $sql = 'SELECT invoice_item.*, 
                                (IFNULL(invoice_item.igv,0) + IFNULL(invoice_item.isc,0) + IFNULL(invoice_item.other_taxed,0))  as total_taxed,
                                affectation.description as affectation_description, affectation.tribute_code as affectation_tribute_code, affectation.onerous as affectation_onerous,
                                affectation.international_code as affectation_international_code, affectation.name as affectation_name
                            FROM invoice_item
                            LEFT JOIN (
                                SELECT cat_affectation_igv_type_code.*, cat_tribute_type_code.international_code, cat_tribute_type_code.name  FROM cat_affectation_igv_type_code
                                INNER JOIN cat_tribute_type_code ON cat_affectation_igv_type_code.tribute_code = cat_tribute_type_code.code
                            ) as affectation ON affectation.code = invoice_item.affectation_code
                            WHERE invoice_item.invoice_id = :invoice_id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':invoice_id' => $invoiceId,
            ]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }
}
