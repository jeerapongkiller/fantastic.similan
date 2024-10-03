<?php
require_once __DIR__ . '/DB.php';

class Receipt extends DB
{
    public $response = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function showlistreceipt($period, $date_form, $date_to, $agent, $type, $rec_id)
    {
        $bind_types = "";
        $params = array();

        $query = "SELECT REC.*,
                    COVER.id as cover_id, COVER.inv_full as inv_full,
                    INV.id as inv_id, INV.no as inv_no, INV.withholding as withholding,
                    VAT.id as vat_id, VAT.name as vat_name,
                    PAYT.id as payt_id, PAYT.name as payt_name,
                    BANACC.id as banacc_id, BANACC.account_name as account_name, BANACC.account_no as account_no,
                    BANK.id as bank_id, BANK.name as bank_name, 
                    BRCH.id as brch_id, BRCH.name as brch_name,
                    BO.id as bo_id, BO.voucher_no_agent as voucher_no_agent, BO.discount as discount,
                    BONO.bo_full as book_full,
                    BSTA.id as booksta_id, BSTA.name as booksta_name, BSTA.name_class as booksta_class, BSTA.button_class as booksta_button,
                    BTYE.id as booktye_id, BTYE.name as booktye_name,
                    COMP.id as comp_id, COMP.name as comp_name, COMP.address as comp_address, COMP.telephone as comp_telephone, COMP.tat_license as comp_tat,
                    BOPA.id as bopa_id,
                    BOPAY.id as bopay_id, BOPAY.name as bopay_name, BOPAY.name_class as bopay_name_class, BOPAY.created_at as bopay_created,
                    BOPAE.id as bopae_id, BOPAE.date_paid as date_paid, BOPAE.total_paid as total_paid, BOPAE.card_no as card_no, BOPAE.photo as bopae_photo, BOPAE.note as bopae_note, BOPAE.payment_type_id as payment_type_id, 
                    BP.id as bp_id, BP.travel_date as travel_date, BP.adult as bp_adult, BP.child as bp_child, BP.infant as bp_infant, BP.note as bp_note,
                    BP.private_type as bp_private_type,
                    BPR.id as bpr_id, BPR.rate_adult as rate_adult, BPR.rate_child as rate_child, BPR.rate_infant as rate_infant, BPR.rate_total as rate_total, 
                    PROD.id as product_id, PROD.name as product_name,
                    CUS.id as cus_id, CUS.name as cus_name, CUS.head as cus_head,
                    BT.id as bt_id, BT.adult as bt_adult, BT.child as bt_child, BT.infant as bt_infant, BT.start_pickup as start_pickup, BT.end_pickup as end_pickup,
                    BT.hotel_pickup as hotel_pickup, BT.hotel_dropoff as hotel_dropoff, BT.room_no as room_no, BT.note as bt_note, BT.transfer_type as transfer_type,
                    BT.pickup_type as pickup_type,
                    BTR.id as btr_id, BTR.rate_adult as btr_rate_adult, BTR.rate_child as btr_rate_child, BTR.rate_infant as btr_rate_infant, BTR.rate_private as rate_private,
                    CARC.id as cars_category_id, CARC.name as cars_category,
                    BEC.id as bec_id, BEC.name as bec_name, BEC.adult as bec_adult, BEC.child as bec_child, BEC.infant as bec_infant, BEC.privates as bec_privates, BEC.type as bec_type,
                    BEC.rate_adult as bec_rate_adult, BEC.rate_child as bec_rate_child, BEC.rate_infant as bec_rate_infant, BEC.rate_private as bec_rate_private, 
                    EXTRA.id as extra_id, EXTRA.name as extra_name,
                    PICK.id as pickup_id, PICK.name as pickup_name
                FROM receipts REC
                LEFT JOIN invoice_cover COVER
                    ON REC.cover_id = COVER.id
                LEFT JOIN invoices INV
                    ON COVER.id = INV.cover_id
                LEFT JOIN vat_type VAT
                    ON INV.vat_id = VAT.id
                LEFT JOIN payments_type PAYT
                    ON REC.payment_id = PAYT.id
                LEFT JOIN bank_account BANACC
                    ON REC.bank_account_id = BANACC.id
                LEFT JOIN banks BANK
                    ON REC.bank_cheque_id = BANK.id
                LEFT JOIN branches BRCH
                    ON INV.branche_id = BRCH.id
                LEFT JOIN bookings BO
                    ON INV.booking_id = BO.id
                LEFT JOIN bookings_no BONO
                    ON BO.id = BONO.booking_id
                LEFT JOIN booking_status BSTA
                    ON BO.booking_status_id = BSTA.id
                LEFT JOIN booking_type BTYE
                    ON BO.booking_type_id = BTYE.id
                LEFT JOIN companies COMP
                    ON BO.company_id = COMP.id
                LEFT JOIN booking_paid BOPA
                    ON BO.id = BOPA.booking_id
                LEFT JOIN booking_payment BOPAY
                    ON BOPA.booking_payment_id = BOPAY.id
                LEFT JOIN booking_paid_detail BOPAE
                    ON BOPA.id = BOPAE.booking_paid_id
                LEFT JOIN booking_products BP
                    ON BO.id = BP.booking_id
                LEFT JOIN booking_product_rates BPR
                    ON BP.id = BPR.booking_products_id
                LEFT JOIN booking_transfer BT
                    ON BP.id = BT.booking_products_id
                LEFT JOIN booking_transfer_rates BTR
                    ON BT.id = BTR.booking_transfer_id
                LEFT JOIN cars_category CARC
                    ON BTR.cars_category_id = CARC.id 
                LEFT JOIN booking_extra_charge BEC
                    ON BO.id = BEC.booking_id
                LEFT JOIN extra_charges EXTRA
                    ON BEC.extra_charge_id = EXTRA.id
                LEFT JOIN products PROD
                    ON BP.product_id = PROD.id
                LEFT JOIN customers CUS
                    ON BO.id = CUS.booking_id
                LEFT JOIN place PICK
                    ON BT.pickup_id = PICK.id
                WHERE REC.is_deleted = 0
        ";

        if ($type == 'list') {
            if (isset($period) && $period != "all") {
                $query .= " AND REC.rec_date BETWEEN ? AND ?";
                $bind_types .= "ss";
                array_push($params, $date_form, $date_to);
            }

            if (isset($agent) && $agent != "all") {
                $query .= " AND COMP.id = ?";
                $bind_types .= "i";
                array_push($params, $agent);
            }
        }

        if ($type == 'preview') {
            if (isset($rec_id) && $rec_id > 0) {
                $query .= " AND REC.id = ?";
                $bind_types .= "i";
                array_push($params, $rec_id);
            }
        }

        $query .= " ORDER BY rec_full ASC";
        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function showlistinvoice($period, $date_form, $date_to, $agent)
    {
        $bind_types = "";
        $params = array();

        $query = "SELECT INV.*,
                    COVER.id as cover_id, COVER.inv_full as inv_full, COVER.inv_date as inv_date,
                    VAT.id as vat_id, VAT.name as vat_name,
                    PAYT.id as payt_id, PAYT.name as payt_name,
                    BANACC.id as banacc_id, BANACC.account_name as account_name, BANACC.account_no as account_no,
                    BANK.id as bank_id, BANK.name as bank_name, 
                    BRCH.id as brch_id, BRCH.name as brch_name,
                    BO.id as bo_id, BO.voucher_no_agent as voucher_no_agent, BO.discount as discount,
                    BONO.bo_full as book_full,
                    BSTA.id as booksta_id, BSTA.name as booksta_name, BSTA.name_class as booksta_class, BSTA.button_class as booksta_button,
                    BTYE.id as booktye_id, BTYE.name as booktye_name,
                    COMP.id as comp_id, COMP.name as comp_name, COMP.address as comp_address, COMP.telephone as comp_telephone, COMP.tat_license as comp_tat,
                    BOPA.id as bopa_id,
                    BOPAY.id as bopay_id, BOPAY.name as bopay_name, BOPAY.name_class as bopay_name_class, BOPAY.created_at as bopay_created,
                    BOPAE.id as bopae_id, BOPAE.date_paid as date_paid, BOPAE.total_paid as total_paid, BOPAE.card_no as card_no, BOPAE.photo as bopae_photo, BOPAE.note as bopae_note, BOPAE.payment_type_id as payment_type_id, 
                    BP.id as bp_id, BP.travel_date as travel_date, BP.adult as bp_adult, BP.child as bp_child, BP.infant as bp_infant, BP.note as bp_note,
                    BP.private_type as bp_private_type,
                    BPR.id as bpr_id, BPR.rate_adult as rate_adult, BPR.rate_child as rate_child, BPR.rate_infant as rate_infant, BPR.rate_total as rate_total, 
                    PROD.id as product_id, PROD.name as product_name,
                    CUS.id as cus_id, CUS.name as cus_name, CUS.head as cus_head,
                    BT.id as bt_id, BT.adult as bt_adult, BT.child as bt_child, BT.infant as bt_infant, BT.start_pickup as start_pickup, BT.end_pickup as end_pickup,
                    BT.hotel_pickup as hotel_pickup, BT.hotel_dropoff as hotel_dropoff, BT.room_no as room_no, BT.note as bt_note, BT.transfer_type as transfer_type,
                    BT.pickup_type as pickup_type,
                    BTR.id as btr_id, BTR.rate_adult as btr_rate_adult, BTR.rate_child as btr_rate_child, BTR.rate_infant as btr_rate_infant, BTR.rate_private as rate_private,
                    CARC.id as cars_category_id, CARC.name as cars_category,
                    BEC.id as bec_id, BEC.name as bec_name, BEC.adult as bec_adult, BEC.child as bec_child, BEC.infant as bec_infant, BEC.privates as bec_privates, BEC.type as bec_type,
                    BEC.rate_adult as bec_rate_adult, BEC.rate_child as bec_rate_child, BEC.rate_infant as bec_rate_infant, BEC.rate_private as bec_rate_private, 
                    EXTRA.id as extra_id, EXTRA.name as extra_name,
                    PICK.id as pickup_id, PICK.name as pickup_name,
                    REC.id as rec_id
                FROM invoices INV
                LEFT JOIN invoice_cover COVER
                    ON INV.cover_id = COVER.id
                LEFT JOIN vat_type VAT
                    ON INV.vat_id = VAT.id
                LEFT JOIN payments_type PAYT
                    ON INV.payment_id = PAYT.id
                LEFT JOIN bank_account BANACC
                    ON INV.bank_account_id = BANACC.id
                LEFT JOIN banks BANK
                    ON BANACC.bank_id = BANK.id
                LEFT JOIN branches BRCH
                    ON INV.branche_id = BRCH.id
                LEFT JOIN bookings BO
                    ON INV.booking_id = BO.id
                LEFT JOIN bookings_no BONO
                    ON BO.id = BONO.booking_id
                LEFT JOIN booking_status BSTA
                    ON BO.booking_status_id = BSTA.id
                LEFT JOIN booking_type BTYE
                    ON BO.booking_type_id = BTYE.id
                LEFT JOIN companies COMP
                    ON BO.company_id = COMP.id
                LEFT JOIN booking_paid BOPA
                    ON BO.id = BOPA.booking_id
                LEFT JOIN booking_payment BOPAY
                    ON BOPA.booking_payment_id = BOPAY.id
                LEFT JOIN booking_paid_detail BOPAE
                    ON BOPA.id = BOPAE.booking_paid_id
                LEFT JOIN booking_products BP
                    ON BO.id = BP.booking_id
                LEFT JOIN booking_product_rates BPR
                    ON BP.id = BPR.booking_products_id
                LEFT JOIN booking_transfer BT
                    ON BP.id = BT.booking_products_id
                LEFT JOIN booking_transfer_rates BTR
                    ON BT.id = BTR.booking_transfer_id
                LEFT JOIN cars_category CARC
                    ON BTR.cars_category_id = CARC.id 
                LEFT JOIN booking_extra_charge BEC
                    ON BO.id = BEC.booking_id
                LEFT JOIN extra_charges EXTRA
                    ON BEC.extra_charge_id = EXTRA.id
                LEFT JOIN products PROD
                    ON BP.product_id = PROD.id
                LEFT JOIN customers CUS
                    ON BO.id = CUS.booking_id
                LEFT JOIN place PICK
                    ON BT.pickup_id = PICK.id
                LEFT JOIN receipts REC
                    ON COVER.id = REC.cover_id
                WHERE INV.is_deleted = 0
                AND (REC.id IS NULL OR REC.is_deleted = 1)
        ";

        if (isset($period) && $period != "all") {
            $query .= " AND INV.rec_date BETWEEN ? AND ?";
            $bind_types .= "ss";
            array_push($params, $date_form, $date_to);
        }

        if (isset($agent) && $agent != "all") {
            $query .= " AND COMP.id = ?";
            $bind_types .= "i";
            array_push($params, $agent);
        }

        $query .= " ORDER BY COVER.inv_full ASC";
        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function showlistagent()
    {
        $query = "SELECT companies.*, 
            companies_type.id as comptypeId, companies_type.name as comptypeName
            FROM companies 
            LEFT JOIN companies_type
                ON companies.company_type_id = companies_type.id
            WHERE companies.is_deleted = 0 AND companies.company_type_id = 2
        ";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function showpaymentstype(int $num)
    {
        $query = "SELECT id, name, type
            FROM payments_type 
            WHERE id > 0
        ";
        $query .= $num > 0 ? " AND type = " . $num : "";
        $query .= " ORDER BY id ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function showbank()
    {
        $query = "SELECT id, name
            FROM banks 
            WHERE id > 0
        ";
        $query .= " ORDER BY name ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function showvat()
    {
        $query = "SELECT id, name
            FROM vat_type 
            WHERE id > 0
        ";
        $query .= " ORDER BY name ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function showbranch()
    {
        $query = "SELECT id, name
            FROM branches 
            WHERE id > 0
        ";
        $query .= " ORDER BY name ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function showpayments(int $num)
    {
        $query = "SELECT id, name, type
            FROM payments_type 
            WHERE id > 0
        ";
        $query .= $num > 0 ? " AND type = " . $num : "";
        $query .= " ORDER BY id ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function showbankaccount()
    {
        $query = "SELECT BAA.*,
            BAN.id as banID, BAN.name as banName 
            FROM bank_account BAA
            LEFT JOIN banks BAN
                ON BAA.bank_id = BAN.id
            WHERE BAA.id > 0
        ";
        $query .= " ORDER BY BAA.account_name ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function checkrecno()
    {
        $query = "SELECT *,
            MAX(rec_no) as max_rec_no
            FROM receipts 
        ";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();

        return $data;
    }

    public function sumbtrprivate(int $bt_id)
    {
        $query = "SELECT SUM(rate_private) as sum_rate_private
            FROM booking_transfer_rates 
            WHERE booking_transfer_id = " . $bt_id;
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();

        return $data;
    }

    public function insert_data(int $rec_no, string $rec_full, string $rec_date, int $cheque_no, string $cheque_date, int $bank_account_id, int $bank_cheque_id, int $cover_id, int $payment_id, int $is_approved)
    {
        $bind_types = "";
        $params = array();

        $query = "INSERT INTO  receipts (rec_no, rec_full, rec_date, price, cheque_no, cheque_date, file, note, bank_account_id, bank_cheque_id, cover_id, payment_id, receipts_by, is_approved, is_deleted, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW(), NOW())";

        $bind_types .= "s";
        array_push($params, $rec_no);

        $bind_types .= "s";
        array_push($params, $rec_full);

        $bind_types .= "s";
        array_push($params, $rec_date);

        $bind_types .= "i";
        array_push($params, 0);

        $bind_types .= "i";
        array_push($params, $cheque_no);

        $bind_types .= "s";
        array_push($params, $cheque_date);

        $bind_types .= "s";
        array_push($params, '');

        $bind_types .= "s";
        array_push($params, '');

        $bind_types .= "i";
        array_push($params, $bank_account_id);

        $bind_types .= "i";
        array_push($params, $bank_cheque_id);

        $bind_types .= "i";
        array_push($params, $cover_id);

        $bind_types .= "i";
        array_push($params, $payment_id);

        $bind_types .= "i";
        array_push($params, $_SESSION["supplier"]["id"]);

        $bind_types .= "i";
        array_push($params, $is_approved);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = $this->connection->insert_id;
        }

        return $this->response;
    }

    public function update_data(string $rec_date, int $cheque_no, string $cheque_date, int $bank_account_id, int $bank_cheque_id, int $payment_id, int $is_approved, int $id)
    {
        $bind_types = "";
        $params = array();

        $query = "UPDATE receipts SET";

        $query .= " rec_date = ?,";
        $bind_types .= "s";
        array_push($params, $rec_date);

        $query .= " cheque_no = ?,";
        $bind_types .= "i";
        array_push($params, $cheque_no);

        $query .= " cheque_date = ?,";
        $bind_types .= "s";
        array_push($params, $cheque_date);

        $query .= " bank_account_id = ?,";
        $bind_types .= "s";
        array_push($params, $bank_account_id);

        $query .= " bank_cheque_id = ?,";
        $bind_types .= "s";
        array_push($params, $bank_cheque_id);

        $query .= " payment_id = ?,";
        $bind_types .= "s";
        array_push($params, $payment_id);

        $query .= " receipts_by = ?,";
        $bind_types .= "i";
        array_push($params, $_SESSION["supplier"]["id"]);

        $query .= " is_approved = ?,";
        $bind_types .= "i";
        array_push($params, $is_approved);

        $query .= " updated_at = now()";

        $query .= " WHERE id = ?";
        $bind_types .= "i";
        array_push($params, $id);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = true;
        }

        return $this->response;
    }

    public function delete_data(int $id)
    {
        $query = "DELETE FROM receipts WHERE id = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("i", $id);
        $statement->execute();
        if ($statement->execute()) {
            $this->response = true;
        }

        return $this->response;
    }

    public function insert_booking_paid(int $bo_id, int $book_payment)
    {
        $bind_types = "";
        $params = array();

        $query = "INSERT INTO booking_paid (booking_payment_id, booking_id, user_id, updated_at, created_at)
        VALUES (?, ?, ?, now(), now())";

        $bind_types .= "i";
        array_push($params, $book_payment);

        $bind_types .= "i";
        array_push($params, $bo_id);

        $bind_types .= "i";
        array_push($params, $_SESSION["supplier"]["id"]);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = $this->connection->insert_id;
        }

        return $this->response;
    }

    public function update_booking_paid(int $bo_id, int $book_payment)
    {
        $bind_types = "";
        $params = array();

        $query = "UPDATE booking_paid SET";

        $query .= " booking_payment_id = ?,";
        $bind_types .= "i";
        array_push($params, $book_payment);

        $query .= " updated_at = now()";

        $query .= " WHERE booking_id = ?";
        $bind_types .= "i";
        array_push($params, $bo_id);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = true;
        }

        return $this->response;
    }

    public function delete_booking_paid(int $id)
    {
        $query = "DELETE FROM booking_paid WHERE booking_id = ? AND booking_payment_id = 3";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("i", $id);
        $statement->execute();
        if ($statement->execute()) {
            $this->response = true;
        }

        return $this->response;
    }
}
