<?php


class Invoice extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("invoice","invoice_id",$db);
    }

    public function Insert($invoice, $userReferId){
        try{
            $currentDate = date('Y-m-d H:i:s');
            $currentTime = date('H:i:s');
            $this->db->beginTransaction();
            $sql = "INSERT INTO invoice (invoice_key, updated_at, created_at, created_user_id, updated_user_id, local_id, date_of_issue, time_of_issue, date_of_due,
                                        serie, number, observation, change_type, document_code, currency_code, operation_code, total_prepayment, total_free,
                                        total_exportation, total_other_charged, total_discount, total_exonerated, total_unaffected, total_taxed, total_igv, total_base_isc,
                                        total_isc, total_charge, total_base_other_taxed, total_other_taxed, total_value, total_plastic_bag_tax, total, global_discount_percentage,
                                        purchase_order, vehicle_plate, term, percentage_plastic_bag_tax, percentage_igv, perception_code, detraction, related, guide, legend, pdf_format)
                                VALUES (:invoice_key, :updated_at, :created_at, :created_user_id, :updated_user_id, :local_id, :date_of_issue, :time_of_issue, :date_of_due,
                                        :serie, :number, :observation, :change_type, :document_code, :currency_code, :operation_code, :total_prepayment, :total_free,
                                        :total_exportation, :total_other_charged, :total_discount, :total_exonerated, :total_unaffected, :total_taxed, :total_igv, :total_base_isc,
                                        :total_isc, :total_charge, :total_base_other_taxed, :total_other_taxed, :total_value, :total_plastic_bag_tax, :total, :global_discount_percentage,
                                        :purchase_order, :vehicle_plate, :term, :percentage_plastic_bag_tax, :percentage_igv, :perception_code, :detraction, :related, :guide, :legend, :pdf_format)";

            $stmt = $this->db->prepare($sql);

            if (!$stmt->execute([
                ':invoice_key' => $invoice['invoiceKey'],
                ':updated_at' => $currentDate,
                ':created_at' => $currentDate,
                ':created_user_id' => $userReferId,
                ':updated_user_id' => $userReferId,
                ':local_id' => $invoice['localId'],
                ':date_of_issue' => $invoice['dateOfIssue'],
                ':time_of_issue' => $invoice['timeOfIssue'],
                ':date_of_due' => $invoice['dateOfDue'],
                ':serie' => $invoice['serie'],
                ':number' => $invoice['number'],
                ':observation' => $invoice['observation'],
                ':change_type' => $invoice['changeType'],
                ':document_code' => $invoice['documentCode'],
                ':currency_code' => $invoice['currencyCode'],
                ':operation_code' => $invoice['operationCode'],
                ':total_prepayment' => $invoice['totalPrepayment'],
                ':total_free' => $invoice['totalFree'],
                ':total_exportation' => $invoice['totalExportation'],
                ':total_other_charged' => $invoice['totalOtherCharged'],
                ':total_discount' => $invoice['totalDiscount'],
                ':total_exonerated' => $invoice['totalExonerated'],
                ':total_unaffected' => $invoice['totalUnaffected'],
                ':total_taxed' => $invoice['totalTaxed'],
                ':total_igv' => $invoice['totalIgv'],
                ':total_base_isc' => $invoice['totalBaseIsc'],
                ':total_isc' => $invoice['totalIsc'],
                ':total_charge' => $invoice['totalCharge'],
                ':total_base_other_taxed' => $invoice['totalBaseOtherTaxed'],
                ':total_other_taxed' => $invoice['totalOtherTaxed'],
                ':total_value' => $invoice['totalValue'],
                ':total_plastic_bag_tax' => $invoice['totalPlasticBagTax'],
                ':total' => $invoice['total'],
                ':global_discount_percentage' => $invoice['globalDiscountPercentage'],
                ':purchase_order' => $invoice['purchaseOrder'],
                ':vehicle_plate' => $invoice['vehiclePlate'],
                ':term' => $invoice['term'],
                ':percentage_plastic_bag_tax' => $invoice['percentagePlasticBagTax'],
                ':percentage_igv' => $invoice['percentageIgv'],
                ':perception_code' => $invoice['perceptionCode'],
                ':detraction' => $invoice['detraction'],
                ':related' => $invoice['related'],
                ':guide' => $invoice['guide'],
                ':legend' => $invoice['legend'],
                ':pdf_format' => $invoice['pdfFormat'],
            ])){
                throw new Exception('No se pudo insertar el registro');
            }

            $this->db->commit();
        }catch (Exception $e){
            $this->db->rollBack();
            throw new Exception("Error in : " . __FUNCTION__ . ' | ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
}