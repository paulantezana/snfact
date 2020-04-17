<?php


class Invoice extends Model
{
    public function __construct(PDO $db)
    {
        parent::__construct("invoice","invoice_id",$db);
    }

    public function getAllDataById(int $invoiceID) {
        try{
            $sql = "SELECT invoice.*,
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
                            srg.address_arrival_point, srg.location_starting_code, srg.address_starting_point,

                            IFNULL(incnd.serie, '') AS update_serie, IFNULL(incnd.number,0) AS update_number, IFNULL(incnd.document_code,'') AS update_document_code,
                            IFNULL(incnd.credit_debit_reason_code,'') AS credit_debit_reason_code, IFNULL(incnd.credit_debit_reason_description,'') AS credit_debit_reason_description
                    FROM invoice
                    INNER JOIN invoice_customer ic on invoice.invoice_id = ic.invoice_id
                    INNER JOIN invoice_sunat isn on invoice.invoice_id = isn.invoice_id
                    INNER JOIN cat_document_type_code ON invoice.document_code = cat_document_type_code.code
                    INNER JOIN cat_currency_type_code ON invoice.currency_code = cat_currency_type_code.code
                    INNER JOIN cat_operation_type_code ON invoice.operation_code = cat_operation_type_code.code
                    LEFT JOIN (
                        SELECT i.document_code, i.serie, i.number, invoice_credit_debit.invoice_id, ccdtc.code AS credit_debit_reason_code, ccdtc.description AS credit_debit_reason_description
                        FROM invoice_credit_debit
                        INNER JOIN invoice AS i ON invoice_credit_debit.invoice_parent_id = i.invoice_id
                        INNER JOIN cat_credit_debit_type_code AS ccdtc ON invoice_credit_debit.credit_debit_id = ccdtc.cat_credit_debit_type_code_id
                    ) as incnd ON invoice.invoice_id = incnd.invoice_id
                    LEFT JOIN invoice_referral_guide srg ON invoice.invoice_id = srg.invoice_id
                    WHERE invoice.invoice_id = :invoice_id LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':invoice_id'=>$invoiceID]);
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception("Error in : " . __FUNCTION__ . ' | ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }

    public function searchBySerieNumber(array $search) {
      try{
        $sql = 'SELECT  invoice.invoice_id, invoice.serie, invoice.number, invoice.total, invoice.date_of_issue, cdtc.description as document_type_code_description
                        FROM invoice
                      INNER JOIN cat_document_type_code cdtc on invoice.document_code = cdtc.code
                      WHERE  invoice.serie LIKE :serie OR invoice.number LIKE :number AND invoice.local_id = :local_id LIMIT 8';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
          ':serie' => '%' . $search['search'] . '%',
          ':number' => '%' . $search['search'] . '%',
          ':local_id' => $search['localId'],
        ]);
        return $stmt->fetchAll();
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
                $filterNumber++;
            }
            $sqlFilter .= $filterNumber >= 1 ? ' AND ' : ' WHERE ';
            $sqlFilter .= "invoice.local_id = {$businessLocalId}";

            $offset = ($page - 1) * $limit;
            $total_rows = $this->db->query("SELECT COUNT(invoice.invoice_id) FROM invoice INNER JOIN invoice_customer ic on invoice.invoice_id = ic.invoice_id {$sqlFilter}")->fetchColumn();
            $total_pages = ceil($total_rows / $limit);

            $sql = "SELECT invoice.*, cat_document_type_code.description as document_type_code_description, cat_operation_type_code.description as operation_type_code_description,
                           ic.social_reason as customer_social_reason, ic.document_number as customer_document_number,
                           ic.email_sent as customer_email_sent, ic.email as customer_email,
                           cat_currency_type_code.symbol as currency_symbol,
                           isn.invoice_state_id,  isn.send, isn.response_code, isn.response_message, isn.other_message, isn.pdf_url, isn.xml_url, isn.cdr_url,
                           IFNULL(incnd.serie, '') AS update_serie, IFNULL(incnd.number,0) AS update_number, IFNULL(incnd.document_code,'') AS update_document_code
                    FROM invoice
                        INNER JOIN invoice_customer ic on invoice.invoice_id = ic.invoice_id
                        INNER JOIN invoice_sunat isn on invoice.invoice_id = isn.invoice_id
                        INNER JOIN cat_document_type_code ON invoice.document_code = cat_document_type_code.code
                        INNER JOIN cat_currency_type_code ON invoice.currency_code = cat_currency_type_code.code
                        INNER JOIN cat_operation_type_code ON invoice.operation_code = cat_operation_type_code.code
                        LEFT JOIN (
                            SELECT i.document_code, i.serie, i.number, invoice_credit_debit.invoice_id FROM invoice_credit_debit
                            INNER JOIN invoice i on invoice_credit_debit.invoice_parent_id = i.invoice_id
                        ) as incnd  ON invoice.invoice_id = incnd.invoice_id";

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
            $sql = "INSERT INTO invoice_customer (invoice_id, document_number, identity_document_code, social_reason, fiscal_address, email, telephone, email_sent)
                    VALUES (:invoice_id, :document_number, :identity_document_code, :social_reason, :fiscal_address, :email, :telephone, :email_sent)";
            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
                ':invoice_id' => $invoiceId,
                ':document_number' => $invoice['customer']['documentNumber'],
                ':identity_document_code' => $invoice['customer']['documentCode'],
                ':social_reason' => $invoice['customer']['socialReason'],
                ':fiscal_address' => $invoice['customer']['address'],
                ':email' => $invoice['customer']['email'],
                ':telephone' => $invoice['customer']['telephone'] ?? '',
                ':email_sent' => 0,
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
                    ':quantity' => $row['quantity'],
                    ':unit_value' => $row['unitValue'],
                    ':unit_price' => $row['unitPrice'],
                    ':discount' => $row['discount'],

                    ':affectation_code' => $row['affectationCode'],
                    ':total_base_igv' => $row['totalBaseIgv'] ?? 0,
                    ':igv' => $row['igv'],

                    ':system_isc_code' => $row['iscSystem'] ?? '',
                    ':total_base_isc' => $row['totalBaseIsc'] ?? 0,
                    ':tax_isc' => $row['iscTax'] ?? 0,
                    ':isc' => $row['isc'] ?? 0,

                    ':total_base_other_taxed' => 0,
                    ':percentage_other_taxed' => 0,
                    ':other_taxed' => 0,

                    ':quantity_plastic_bag' => $row['quantityPlasticBag'],
                    ':plastic_bag_tax' => $row['plasticBagTax'],

                    ':total_value' => $row['totalValue'],
                    ':total' => $row['total'],
                    ':charge' => 0,
                ])){
                    throw new Exception('Error al insertar los items');
                }
            }

            // Insert Detraction
            if (isset($invoice['detractionEnabled']) && $invoice['detractionEnabled'] === true){
                $detraction = $invoice['detraction'];
                $sql = "INSERT INTO invoice_detraction(invoice_id, referral_value, effective_load, useful_load, travel_detail, whit_detraction, detraction_code, percentage, amount,
                                                        location_starting_code, address_starting_point, location_arrival_code, address_arrival_point,
                                                        boat_registration, boat_name, species_kind, delivery_address, delivery_date, quantity
                                                    )
                                                    VALUES (:invoice_id, :referral_value, :effective_load, :useful_load, :travel_detail, :whit_detraction, :detraction_code, :percentage, :amount,
                                                        :location_starting_code, :address_starting_point, :location_arrival_code, :address_arrival_point,
                                                        :boat_registration, :boat_name, :species_kind, :delivery_address, :delivery_date, :quantity
                                                    )";
                $stmt = $this->db->prepare($sql);

                if (!$stmt->execute([
                    ':invoice_id' => $invoiceId,
                    ':referral_value' => $detraction['referralValue'],
                    ':effective_load' => $detraction['effectiveLoad'],
                    ':useful_load' => $detraction['usefulLoad'],
                    ':travel_detail' => $detraction['travelDetail'],

                    ':whit_detraction' => $invoice['detractionEnabled'],
                    ':detraction_code' => $detraction['subjectCode'],
                    ':percentage' => $detraction['percentage'],
                    ':amount' => $detraction['total'] * ($detraction['percentage'] / 100),
                    ':location_starting_code' => $detraction['locationStartingCode'],
                    ':address_starting_point' => $detraction['addressStartingPoint'],
                    ':location_arrival_code' => $detraction['locationArrivalCode'],
                    ':address_arrival_point' => $detraction['addressArrivalPoint'],

                    ':boat_registration' => $detraction['boatRegistration'],
                    ':boat_name' => $detraction['boatName'],
                    ':species_kind' => $detraction['speciesSold'],
                    ':delivery_address' => $detraction['deliveryAddress'],
                    ':delivery_date' => $detraction['deliveryDate'],
                    ':quantity' => $detraction['quantity'],
                ])){
                    throw new Exception('No se pudo insertar el registro');
                }
            }

            // Insert invoice guide
            if (isset($invoice['guideEnabled']) && $invoice['guideEnabled'] === true){
                $referralGuide = $invoice['guide'];
                $sql = "INSERT INTO invoice_referral_guide(invoice_id, whit_guide, document_code, transfer_code, transport_code, transfer_start_date, total_gross_weight,
                                                        carrier_document_code, carrier_document_number, carrier_denomination, driver_document_code,
                                                        driver_document_number, driver_full_name, location_starting_code, address_starting_point,
                                                        location_arrival_code, address_arrival_point)
                                                    VALUES (:invoice_id, :whit_guide, :document_code, :transfer_code, :transport_code, :transfer_start_date, :total_gross_weight,
                                                        :carrier_document_code, :carrier_document_number, :carrier_denomination, :driver_document_code,
                                                        :driver_document_number, :driver_full_name, :location_starting_code, :address_starting_point,
                                                        :location_arrival_code, :address_arrival_point)";
                $stmt = $this->db->prepare($sql);
                if (!$stmt->execute([
                    ':invoice_id' => $invoiceId,
                    ':whit_guide' => $invoice['guideEnabled'],
                    ':document_code' => '09',
                    ':transfer_code' => $referralGuide['transferCode'],
                    ':transport_code' => $referralGuide['transportCode'],
                    ':transfer_start_date' => $referralGuide['transferStartDate'],
                    ':total_gross_weight' => $referralGuide['totalGrossWeight'],
                    ':carrier_document_code' => $referralGuide['carrierDocumentCode'],
                    ':carrier_document_number' => $referralGuide['carrierDocumentNumber'],
                    ':carrier_denomination' => $referralGuide['carrierDenomination'],
                    ':driver_document_code' => $referralGuide['driverDocumentCode'],
                    ':driver_document_number' => $referralGuide['driverDocumentNumber'],
                    ':driver_full_name' => $referralGuide['driverFullName'],
                    ':location_starting_code' => $referralGuide['locationStartingCode'],
                    ':address_starting_point' => $referralGuide['addressStartingPoint'],
                    ':location_arrival_code' => $referralGuide['locationArrivalCode'],
                    ':address_arrival_point' => $referralGuide['addressArrivalPoint'],
                ])){
                    throw new Exception('No se pudo insertar el registro');
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

  public function updateInvoiceCustomerByInvoiceId($invoiceId, $data)
  {
    try {
      $sql = "UPDATE invoice_customer SET ";
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
