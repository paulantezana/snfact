<?php


class Invoice extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("invoice","invoice_id",$db);
    }

    public function getAllDataById(int $invoiceID) {
        try{
            $sql = 'SELECT invoice.*, 
                            (invoice.total_igv + invoice.total_isc + invoice.total_other_taxed) as total_tax,
                            cat_document_type_code.description as document_type_code_description, 
                            cat_operation_type_code.description as operation_type_code_description, 
                            ic.social_reason as customer_social_reason, ic.document_number as customer_document_number, 
                            ic.identity_document_code as customer_identity_document_code,
                            ic.fiscal_address as customer_fiscal_address, ic.email as customer_email,
                            cat_currency_type_code.symbol as currency_type_code_symbol,
                            cat_currency_type_code.description as currency_type_code_description,
       
                            isn.invoice_state_id, isn.pdf_url, isn.xml_url, isn.cdr_url,
       
                            srg.whit_guide, srg.transfer_code, srg.total_gross_weight, srg.transport_code, srg.carrier_document_code, srg.carrier_document_number,
                            srg.carrier_denomination, srg.carrier_plate_number, srg.driver_document_code, srg.driver_document_number, srg.driver_full_name, srg.location_arrival_code,
                            srg.address_arrival_point, srg.location_starting_code, srg.address_starting_point
                    FROM invoice
                    INNER JOIN invoice_customer ic on invoice.invoice_id = ic.invoice_id
                    INNER JOIN invoice_sunat isn on invoice.invoice_id = isn.invoice_id  
                    INNER JOIN cat_document_type_code ON invoice.document_code = cat_document_type_code.code
                    INNER JOIN cat_currency_type_code ON invoice.currency_code = cat_currency_type_code.code
                    INNER JOIN cat_operation_type_code ON invoice.operation_code = cat_operation_type_code.code
                    LEFT JOIN invoice_referral_guide srg ON invoice.invoice_id = srg.invoice_id
                    WHERE invoice.invoice_id = :invoice_id LIMIT 1';

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':invoice_id'=>$invoiceID]);
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception("Error in : " . __FUNCTION__ . ' | ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }

    public function paginate($page = 1, $limit = 10, $businessLocalId = 0, $filter = []) {
        try{
            $filterNumber = 0;
            $sqlFilter = '';
            if (isset($filter['documentCode']) && $filter['documentCode']){
                $sqlFilter .= " WHERE invoice.document_code = {$filter['documentCode']}";
                $filterNumber++;
            }
            if (isset($filter['customerDocumentNumber']) && $filter['customerDocumentNumber']){
                $sqlFilter .= $filterNumber >= 1 ? ' AND ' : ' WHERE ';
                $sqlFilter .= "ic.document_number = {$filter['customerDocumentNumber']}";
                $filterNumber++;
            }
            if (isset($filter['startDate']) && $filter['startDate']){
                $sqlFilter .= $filterNumber >= 1 ? ' AND ' : ' WHERE ';
                $sqlFilter .= "invoice.date_of_issue >= '{$filter['startDate']}'";
                $filterNumber++;
            }
            if (isset($filter['invoiceId']) && $filter['invoiceId']){
                $sqlFilter .= $filterNumber >= 1 ? ' AND ' : ' WHERE ';
                $sqlFilter .= "invoice.invoice_id = '{$filter['invoiceId']}'";
                $filterNumber++;
            }
            if (isset($filter['endDate']) && $filter['endDate']){
                $sqlFilter .= $filterNumber >= 1 ? ' AND ' : ' WHERE ';
                $sqlFilter .= "invoice.date_of_issue <= '{$filter['endDate']}'";
            }
            $sqlFilter .= $filterNumber >= 1 ? ' AND ' : ' WHERE ';
            $sqlFilter .= "invoice.local_id = {$businessLocalId}";

            $limit = 10;
            $offset = ($page - 1) * $limit;
            $total_rows = $this->db->query("SELECT COUNT(invoice.invoice_id) FROM invoice INNER JOIN invoice_customer ic on invoice.invoice_id = ic.invoice_id {$sqlFilter}")->fetchColumn();
            $total_pages = ceil($total_rows / $limit);

            $sql = "SELECT invoice.*, cat_document_type_code.description as document_type_code_description, cat_operation_type_code.description as operation_type_code_description,
                           ic.social_reason as customer_social_reason, ic.document_number as customer_document_number, 
                           ic.sent_to_client as customer_sent_to_client, ic.email as customer_email,
                           cat_currency_type_code.symbol as currency_symbol,
                           isn.invoice_state_id,  isn.send, isn.response_code, isn.response_message, isn.other_message, isn.pdf_url, isn.xml_url, isn.cdr_url
                    FROM invoice
                        INNER JOIN invoice_customer ic on invoice.invoice_id = ic.invoice_id
                        INNER JOIN invoice_sunat isn on invoice.invoice_id = isn.invoice_id
                        INNER JOIN cat_document_type_code ON invoice.document_code = cat_document_type_code.code
                        INNER JOIN cat_currency_type_code ON invoice.currency_code = cat_currency_type_code.code
                        INNER JOIN cat_operation_type_code ON invoice.operation_code = cat_operation_type_code.code ";

            $sql .= $sqlFilter;
            $sql .= " ORDER BY invoice.invoice_id DESC LIMIT $offset, $limit";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();

            return [
                'current' => $page,
                'pages' => $total_pages,
                'limit' => $limit,
                'data' => $data,
            ];
        } catch (Exception $e) {
            throw new Exception("Error in : " . __FUNCTION__ . ' | ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }

    public function insert($invoice, $userReferId){
        try{
            $currentDate = date('Y-m-d H:i:s');
            $this->db->beginTransaction();

            $sql = "INSERT INTO invoice (updated_at, created_at, created_user_id, updated_user_id, local_id, date_of_issue, time_of_issue, date_of_due,
                                        serie, number, observation, change_type, document_code, currency_code, operation_code, total_prepayment, total_free,
                                        total_exportation, total_other_charged, total_discount, total_exonerated, total_unaffected, total_taxed, total_igv, total_base_isc,
                                        total_isc, total_charge, total_base_other_taxed, total_other_taxed, total_value, total_plastic_bag_tax, total, global_discount_percentage,
                                        purchase_order, vehicle_plate, term, percentage_plastic_bag_tax, percentage_igv, perception_code, detraction, related, guide, legend, pdf_format)
                                VALUES (:updated_at, :created_at, :created_user_id, :updated_user_id, :local_id, :date_of_issue, :time_of_issue, :date_of_due,
                                        :serie, :number, :observation, :change_type, :document_code, :currency_code, :operation_code, :total_prepayment, :total_free,
                                        :total_exportation, :total_other_charged, :total_discount, :total_exonerated, :total_unaffected, :total_taxed, :total_igv, :total_base_isc,
                                        :total_isc, :total_charge, :total_base_other_taxed, :total_other_taxed, :total_value, :total_plastic_bag_tax, :total, :global_discount_percentage,
                                        :purchase_order, :vehicle_plate, :term, :percentage_plastic_bag_tax, :percentage_igv, :perception_code, :detraction, :related, :guide, :legend, :pdf_format)";
            $stmt = $this->db->prepare($sql);
            if (!$stmt->execute([
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

            // Insert Credit And Debit Note
            if(isset($invoice['invoiceUpdate']['invoiceId']) && $invoice['invoiceUpdate']['invoiceId'] > 0){
                $sql = "INSERT INTO invoice_credit_debit(invoice_id, serie, number, invoice_parent_id, credit_debit_id)
                                VALUES (:invoice_id, :serie, :number, :invoice_parent_id, :credit_debit_id)";
                $stmt = $this->db->prepare($sql);
                if(!$stmt->execute([
                    ':invoice_id' => $invoiceId,
                    ':serie' => $invoice['invoiceUpdate']['serie'],
                    ':number' => $invoice['invoiceUpdate']['number'],
                    ':invoice_parent_id' => $invoice['invoiceUpdate']['invoiceId'],
                    ':credit_debit_id' => $invoice['invoiceUpdate']['creditDebitId'],
                ])){
                    throw new Exception('No se pudo insertar el registro nota credito debito');
                }
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

            // Insert items
            foreach ($invoice['item'] as $row){
                $sql = "INSERT INTO invoice_item (invoice_id, product_code, unit_measure, description, quantity, unit_value, unit_price, discount, affectation_code,
                                                    total_base_igv, igv, system_isc_code, total_base_isc, tax_isc,
                                                    isc, total_base_other_taxed, percentage_other_taxed, other_taxed, plastic_bag_tax, quantity_plastic_bag,
                                                    total_value, total, charge)
                                        VALUES (:invoice_id, :product_code, :unit_measure, :description, :quantity, :unit_value, :unit_price, :discount, :affectation_code,
                                                :total_base_igv, :igv, :system_isc_code, :total_base_isc, :tax_isc,
                                                :isc, :total_base_other_taxed, :percentage_other_taxed, :other_taxed, :plastic_bag_tax, :quantity_plastic_bag,
                                                 :total_value, :total, :charge)";
                $stmt = $this->db->prepare($sql);
                if (!$stmt->execute([
                    ':invoice_id' => $invoiceId,
                    ':product_code' => $row['productCode'],
                    ':unit_measure' => $row['unitMeasure'],
                    ':description' => $row['description'],
                    ':quantity' => (float)($row['quantity']),
                    ':unit_value' => (float)($row['unitValue']),
                    ':unit_price' => (float)($row['unitPrice']),
                    ':discount' => (float)($row['discount']),

                    ':affectation_code' => $row['affectationCode'] ?? '',
                    ':total_base_igv' => (float)($row['totalBaseIgv'] ?? 0),
                    ':igv' => (float)($row['igv'] ?? 0),

                    ':system_isc_code' => $row['iscSystem'] ?? '',
                    ':total_base_isc' => (float)($row['totalBaseIsc'] ?? 0),
                    ':tax_isc' => (float)($row['iscTax'] ?? 0),
                    ':isc' => (float)($row['isc'] ?? 0),

                    ':total_base_other_taxed' => 0,
                    ':percentage_other_taxed' => 0,
                    ':other_taxed' => 0,

                    ':quantity_plastic_bag' => 0,
                    ':plastic_bag_tax' => 0,

                    ':total_value' => (float)($row['totalValue']),
                    ':total' => (float)($row['total']),
                    ':charge' => 0,
                ])){
                    throw new Exception('Error al insertar los items');
                }
            }

            $this->db->commit();
            return $invoiceId;
        }catch (Exception $e){
            $this->db->rollBack();
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }

    public function updateInvoiceSunatByInvoiceId($invoiceId, $data)
    {
        try {
            $sql = "UPDATE invoice_sunat SET ";
            foreach ($data as $key => $value) {
                $sql .= "$key = :$key, ";
            }
            $sql = trim(trim($sql), ',');
            $sql .= " WHERE invoice_id = :invoice_id";

            $execute = [];
            foreach ($data as $key => $value) {
                $execute[":$key"] = $value;
            }
            $execute[":invoice_id"] = $invoiceId;

            $stmt = $this->db->prepare($sql);
            if (!$stmt->execute($execute)) {
                throw new Exception("Error al actualizar el registro");
            }
            return $invoiceId;
        } catch (Exception $e) {
            throw new Exception('PDO: ' . $e->getMessage());
        }
    }
}