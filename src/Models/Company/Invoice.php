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
                ':invoice_key' => $invoice['documentCode'] . $invoice['serie'] . $invoice['number'] . $invoice['localId'],
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
                ':total_exportation' => $invoice['totalExport'],
                ':total_other_charged' => $invoice['totalOtherCharger'],
                ':total_discount' => $invoice['totalDiscount'],
                ':total_exonerated' => $invoice['totalExonerated'],
                ':total_unaffected' => $invoice['totalUnaffected'],
                ':total_taxed' => $invoice['totalTaxed'],
                ':total_igv' => $invoice['totalIgv'],
                ':total_base_isc' => $invoice['totalBaseIsc'],
                ':total_isc' => $invoice['totalIsc'],
                ':total_charge' => 0,
                ':total_base_other_taxed' => 0,
                ':total_other_taxed' => 0,
                ':total_value' => $invoice['totalValue'],
                ':total_plastic_bag_tax' => $invoice['totalPlasticBagTax'],
                ':total' => $invoice['total'],
                ':global_discount_percentage' => $invoice['globalDiscountPercentage'],
                ':purchase_order' => $invoice['purchaseOrder'],
                ':vehicle_plate' => $invoice['vehiclePlate'],
                ':term' => $invoice['term'],
                ':percentage_plastic_bag_tax' => 0,
                ':percentage_igv' => $invoice['percentageIgv'],
                ':perception_code' => '',
                ':detraction' => '',
                ':related' => '',
                ':guide' => '',
                ':legend' => '',
                ':pdf_format' => $invoice['pdfFormat'],
            ])){
                throw new Exception('No se pudo insertar el registro');
            }
            $invoiceId = (int)$this->db->lastInsertId();

            // Insert customer
            $sql = "INSERT INTO invoice_customer (invoice_id, document_number, identity_document_code, social_reason, fiscal_address, email, telephone, sent_to_client)
                    VALUES (:invoice_id, :document_number, :identity_document_code, :social_reason, :fiscal_address, :email, :telephone, :sent_to_client)";
            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
                ':invoice_id' => $invoiceId,
                ':document_number' => $invoice['customer']['documentNumber'],
                ':identity_document_code' => $invoice['customer']['documentCode'],
                ':social_reason' => $invoice['customer']['socialReason'],
                ':fiscal_address' => $invoice['customer']['address'],
                ':email' => $invoice['customer']['email'],
                ':telephone' => $invoice['customer']['telephone'] ?? '',
                ':sent_to_client' => 0,
            ])){
                throw new Exception('No se pudo insertar el registro');
            }

            // Insert sunat states
            $sql = "INSERT INTO invoice_sunat (invoice_id, invoice_state_id)
                    VALUES (:invoice_id, :invoice_state_id)";
            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
                ':invoice_id' => $invoiceId,
                ':invoice_state_id' => 1,
            ])){
                throw new Exception('No se pudo insertar el registro');
            }

//            // Insert items
//            foreach ($invoice['item'] as $row){
//                $sql = "INSERT INTO invoice_item (invoice_id, product_code, unit_measure, description, quantity, unit_value, unit_price, discount, affectation_code,
//                                                    total_base_igv, igv, system_isc_code, total_base_isc, tax_isc,
//                                                    isc, total_base_other_taxed, percentage_other_taxed, other_taxed, plastic_bag_tax, quantity_plastic_bag,
//                                                    total_value, total, charge)
//                                        VALUES (:invoice_id, :product_code, :unit_measure, :description, :quantity, :unit_value, :unit_price, :discount, :affectation_code,
//                                                :total_base_igv, :igv, :system_isc_code, :total_base_isc, :tax_isc,
//                                                :isc, :total_base_other_taxed, :percentage_other_taxed, :other_taxed, :plastic_bag_tax, :quantity_plastic_bag,
//                                                 :total_value, :total, :charge)";
//                $stmt = $this->db->prepare($sql);
//                if (!$stmt->execute([
//                    ':invoice_id' => $invoiceId,
//                    ':product_code' => $row['productCode'],
//                    ':unit_measure' => $row['unitMeasure'],
//                    ':description' => $row['description'],
//                    ':quantity' => (float)($row['quantity']),
//                    ':unit_value' => (float)($row['unitValue']),
//                    ':unit_price' => (float)($row['unitPrice']),
//                    ':discount' => (float)($row['discount']),
//
//                    ':affectation_code' => $row['affectationCode'] ?? '',
//                    ':total_base_igv' => (float)($row['totalBaseIgv'] ?? 0),
//                    ':igv' => (float)($row['igv'] ?? 0),
//
//                    ':system_isc_code' => $row['iscSystem'] ?? '',
//                    ':total_base_isc' => (float)($row['totalBaseIsc'] ?? 0),
//                    ':tax_isc' => (float)($row['iscTax'] ?? 0),
//                    ':isc' => (float)($row['isc'] ?? 0),
//
//                    ':total_base_other_taxed' => 0,
//                    ':percentage_other_taxed' => 0,
//                    ':other_taxed' => 0,
//
//                    ':quantity_plastic_bag' => 0,
//                    ':plastic_bag_tax' => 0,
//
//                    ':total_value' => (float)($row['totalValue']),
//                    ':total' => (float)($row['total']),
//                    ':charge' => 0,
//                ])){
//                    throw new Exception('Error al insertar los items');
//                }
//            }

            $this->db->commit();
        }catch (Exception $e){
            $this->db->rollBack();
            throw new Exception('Line: ' . $e->getLine() . ' ' . $e->getMessage());
        }
    }
}