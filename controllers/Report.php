<?php
require_once __DIR__ . '/DB.php';

class Report extends DB
{
    public $response = false;

    public function __construct()
    {
        parent::__construct();
    }

    // public function fetch_all_booking($status, $payment, $travel_date, $agent, $product)
    // {
    //     $query = "SELECT BO.*,
    //                 BONO.bo_full as bo_full,
    //                 BSTA.name_class as status_class, BSTA.name as status_name,
    //                 BOPA.total_paid as cot,
    //                 BOPAY.id as pay_id, BOPAY.name as pay_name, BOPAY.name_class as pay_name_class,
    //                 COMP.id as agent_id, COMP.name as agent_name,
    //                 PROD.id as product_id, PROD.name as product_name,
    //                 CUS.name as cus_name,
    //                 BP.id as bp_id, BP.travel_date as travel_date, BP.note as bp_note,
    //                 BPRS.max_tourist,
    //                 BT.id as bt_id, BT.start_pickup as start_pickup, BT.end_pickup as end_pickup,
    //                 BT.room_no as room_no, BT.note as bt_note, BT.hotel_pickup as outside_pickup, 
    //                 BT.hotel_dropoff as outside_dropoff, BT.pickup_type as pickup_type,
    //                 HOTELP.name_th as hotelp_name,
    //                 HOTELD.name_th as hoteld_name,
    //                 ZONE_P.name_th as zonep_name,
    //                 ZONE_D.name_th as zoned_name,
    //                 INV.id as inv_id
    //             FROM bookings BO
    //             LEFT JOIN bookings_no BONO
    //                 ON BO.id = BONO.booking_id
    //             LEFT JOIN booking_status BSTA
    //                 ON BO.booking_status_id = BSTA.id
    //             LEFT JOIN booking_paid BOPA
    //                 ON BO.id = BOPA.booking_id
    //             LEFT JOIN booking_payment BOPAY
    //                 ON BOPA.booking_payment_id = BOPAY.id
    //             LEFT JOIN companies COMP
    //                 ON BO.company_id = COMP.id
    //             LEFT JOIN customers CUS
    //                 ON BO.id = CUS.booking_id
    //             LEFT JOIN booking_products BP
    //                 ON BO.id = BP.booking_id
    //             LEFT JOIN products PROD
    //                 ON BP.product_id = PROD.id
    //             LEFT JOIN (
    //                 SELECT BP.booking_id,
    //                     SUM(BPR.adult) + SUM(BPR.child) + SUM(BPR.infant) + SUM(BPR.foc) AS max_tourist
    //                 FROM booking_products BP
    //                 JOIN booking_product_rates BPR 
    //                     ON BP.id = BPR.booking_products_id
    //                 GROUP BY BP.booking_id, BPR.category_id
    //             ) BPRS 
    //                 ON BPRS.booking_id = BO.id
    //             LEFT JOIN booking_transfer BT
    //                 ON BP.id = BT.booking_products_id
    //             LEFT JOIN hotel HOTELP
    //                 ON BT.hotel_pickup_id = HOTELP.id
    //             LEFT JOIN hotel HOTELD
    //                 ON BT.hotel_dropoff_id = HOTELD.id
    //             LEFT JOIN zones ZONE_P
    //                 ON BT.pickup_id = ZONE_P.id
    //             LEFT JOIN zones ZONE_D
    //                 ON BT.dropoff_id = ZONE_D.id
    //             LEFT JOIN invoices INV
    //                 ON BO.id = INV.booking_id
    //             WHERE BO.id > 0
    //     ";

    //     $query .= (!empty($travel_date) && $travel_date != '') ? !empty(substr($travel_date, 14, 24)) ? " AND BP.travel_date BETWEEN '" . substr($travel_date, 0, 10) . "' AND '" . substr($travel_date, 14, 24) . "'" : " AND BP.travel_date = '" . $travel_date . "'" : "";
    //     $query .= (!empty($agent) && $agent != 'all') ? " AND COMP.id = " . $agent : "";
    //     $query .= (!empty($agent) && $agent != 'all') ? " AND PROD.id = " . $product : "";

    //     if (!empty($status)) {
    //         if ($status != 'all') {
    //             $query .= " AND (";
    //             for ($i = 0; $i < count($status); $i++) {
    //                 $query .= $i == 0 ? " BSTA.id = " . $status[$i] : " OR BSTA.id = " . $status[$i];
    //             }
    //             $query .= " )";
    //         } 
    //     }

    //     if (!empty($payment)) {
    //         if ($payment != 'all') {
    //             $query .= " AND (";
    //             for ($i = 0; $i < count($payment); $i++) {
    //                 $query .= $i == 0 ? " BOPAY.id = " . $payment[$i] : " OR BOPAY.id = " . $payment[$i];
    //             }
    //             $query .= " )";
    //         } 
    //     }

    //     $query .= " ORDER BY BO.id DESC, BP.travel_date DESC, BOPA.id DESC";
 
    //     $statement = $this->connection->prepare($query);
    //     $statement->execute();
    //     $result = $statement->get_result();
    //     $data = $result->fetch_all(MYSQLI_ASSOC);

    //     return $data;
    // }


    public function showlist($status, $date_form, $date_to, $agent, $product, $payment, $type)
    {
        $bind_types = "";
        $params = array();

        // Select only the columns used by the report pages (list, search, print, excel)
        // to keep memory low on large date ranges. Joins are unchanged so row counts stay the same.
        $query = "SELECT BO.id, BO.voucher_no_agent, BO.discount, BO.sender, BO.booking_type_id,
                    BONO.bo_full as book_full,
                    BSTA.id as booksta_id, BSTA.name as booksta_name, BSTA.name_class as booksta_class,
                    BOPA.id as bopa_id, BOPA.total_paid as total_paid,
                    BOPAY.id as bopay_id, BOPAY.name as bopay_name, BOPAY.name_class as bopay_name_class,
                    COMP.id as comp_id, COMP.name as comp_name, COMP.logo as comp_logo,
                    BP.travel_date as travel_date,
                    BPR.id as bpr_id, BPR.adult as adult, BPR.child as child, BPR.infant as infant, BPR.foc as foc,
                    BPR.rates_adult as rates_adult, BPR.rates_child as rates_child, BPR.rates_infant as rates_infant, BPR.rates_private as rates_private,
                    PROD.id as product_id, PROD.name as product_name,
                    PRODC.name as category_name,
                    CUS.name as cus_name, CUS.head as cus_head,
                    BT.hotel_pickup as hotel_pickup, BT.hotel_dropoff as hotel_dropoff,
                    BEC.id as bec_id, BEC.name as bec_name, BEC.adult as bec_adult, BEC.child as bec_child, BEC.privates as bec_privates, BEC.type as bec_type,
                    BEC.rate_adult as bec_rate_adult, BEC.rate_child as bec_rate_child, BEC.rate_private as bec_rate_private,
                    EXTRA.id as extra_id,
                    BOT.id as bot_id, BOT.tourist as tourist,
                    ORTRAN.id as ortran_id, ORTRAN.license as license, ORTRAN.travel_date as ortran_travel,
                    CAR.name as car_name,
                    HOTPIK.name as hotel_pickup_name,
                    HOTDRO.name as hotel_dropoff_name,
                    INVCO.inv_full as inv_full,
                    REC.id as rec_id,
                    BOBOAT.id as boboat_id,
                    ORBOAT.id as orboat_id, ORBOAT.travel_date as orboat_travel,
                    BOAT.id as boat_id, BOAT.name as boat_name
                FROM bookings BO
                LEFT JOIN bookings_no BONO
                    ON BO.id = BONO.booking_id
                LEFT JOIN booking_status BSTA
                    ON BO.booking_status_id = BSTA.id
                LEFT JOIN booking_type BTYE
                    ON BO.booking_type_id = BTYE.id
                LEFT JOIN booking_paid BOPA
                        ON BO.id = BOPA.booking_id
                LEFT JOIN booking_payment BOPAY
                    ON BOPA.booking_payment_id = BOPAY.id
                LEFT JOIN companies COMP
                    ON BO.company_id = COMP.id
                LEFT JOIN booking_products BP
                    ON BO.id = BP.booking_id
                LEFT JOIN booking_product_rates BPR
                    ON BP.id = BPR.booking_products_id
                LEFT JOIN booking_transfer BT
                    ON BP.id = BT.booking_products_id
                LEFT JOIN booking_extra_charge BEC
                    ON BO.id = BEC.booking_id
                LEFT JOIN extra_charges EXTRA
                    ON BEC.extra_charge_id = EXTRA.id
                LEFT JOIN booking_order_transfer BOT
                    ON BT.id = BOT.booking_transfer_id
                LEFT JOIN order_transfer ORTRAN 
                    ON BOT.order_id = ORTRAN.id
                LEFT JOIN cars CAR 
                    ON ORTRAN.car_id = CAR.id
                LEFT JOIN hotel HOTPIK
                    ON BT.hotel_pickup_id = HOTPIK.id
                LEFT JOIN hotel HOTDRO
                    ON BT.hotel_dropoff_id = HOTDRO.id
                LEFT JOIN products PROD
                    ON BP.product_id = PROD.id
                LEFT JOIN product_category PRODC
                    ON PROD.id = PRODC.product_id
                LEFT JOIN park PARK
                    ON PROD.PARK_id = PARK.id
                LEFT JOIN customers CUS
                    ON BO.id = CUS.booking_id
                LEFT JOIN invoices INV
                    ON BO.id = INV.booking_id
                    AND INV.is_deleted = 0
                LEFT JOIN invoice_cover INVCO
                    ON INV.cover_id = INVCO.id    
                LEFT JOIN vat_type VAT
                    ON INV.vat_id = VAT.id
                LEFT JOIN receipts REC
                    ON INVCO.id = REC.cover_id
                LEFT JOIN booking_order_boat BOBOAT
                    ON BO.id = BOBOAT.booking_id
                LEFT JOIN order_boat ORBOAT
                    ON BOBOAT.manage_id = ORBOAT.id
                LEFT JOIN colors COLOR 
                    ON ORBOAT.color_id = COLOR.id
                LEFT JOIN boats BOAT
                    ON ORBOAT.boat_id = BOAT.id
                WHERE BO.is_deleted = 0
                AND PROD.id > 0
                AND BP.is_deleted = 0
        ";

        $query .= $date_form != '0000-00-00' ? $date_to != '0000-00-00' ? " AND BP.travel_date BETWEEN '$date_form' AND '$date_to' " : " AND BP.travel_date BETWEEN '$date_form' AND '$date_form' " : '';

        if (!empty($status)) {
            if ($status != 'all') {
                $query .= " AND (";
                for ($i = 0; $i < count($status); $i++) {
                    $query .= $i == 0 ? " BSTA.id = " . $status[$i] : " OR BSTA.id = " . $status[$i];
                }
                $query .= " )";
            } 
        }

        if (!empty($payment)) {
            if ($payment != 'all') {
                $query .= " AND (";
                for ($i = 0; $i < count($payment); $i++) {
                    $query .= $i == 0 ? " BOPAY.id = " . $payment[$i] : " OR BOPAY.id = " . $payment[$i];
                }
                $query .= " )";
            } 
        }

        if (isset($type) && $type != "all") {
            $query .= " AND BO.booking_type_id = ?";
            $bind_types .= "i";
            array_push($params, $type);
        }

        if (isset($agent) && $agent != "all") {
            $query .= " AND COMP.id = ?";
            $bind_types .= "i";
            array_push($params, $agent);
        }

        if (isset($product) && $product != "all") {
            $query .= " AND PROD.id = ?";
            $bind_types .= "i";
            array_push($params, $product);
        }

        $query .= " ORDER BY BO.id DESC, BP.travel_date DESC, BOPA.id ASC";

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';
        $statement->execute();

        // Stream rows (unbuffered) one by one instead of fetch_all() so large date ranges
        // do not load every row into memory. Callers only iterate with foreach.
        // Note: do not run other queries on this connection inside the foreach loop.
        $row = array();
        $bind_row = array();
        $fields = $statement->result_metadata()->fetch_fields();
        foreach ($fields as $field) {
            $bind_row[] = &$row[$field->name];
        }
        $statement->bind_result(...$bind_row);

        try {
            while ($statement->fetch()) {
                // Copy values so each yielded row is independent of the bound references
                $data = array();
                foreach ($row as $key => $value) {
                    $data[$key] = $value;
                }
                yield $data;
            }
        } finally {
            $statement->free_result();
            $statement->close();
        }
    }

    public function showbookingstatus()
    {
        $query = "SELECT id, name, button_class
            FROM booking_status 
            WHERE id > 0
        ";
        $query .= " ORDER BY id ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function showbookingpayment()
    {
        $query = "SELECT *
            FROM booking_payment 
            WHERE id > 0
            AND type != 2
        ";
        $query .= " ORDER BY name ASC";
        $statement = $this->connection->prepare($query);
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
            WHERE companies.is_deleted = 0 
            AND companies.company_type_id != 1
        ";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function showlistproduct()
    {
        $query = "SELECT *
            FROM products 
            WHERE is_deleted = 0
        ";
        $query .= " ORDER BY id ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

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

    public function sumbectotal(int $bo_id)
    {
        $query = "SELECT ((adult * rate_adult) + (child * rate_child) + (infant * rate_infant) + (privates * rate_private)) as sum_rate_total
            FROM booking_extra_charge 
            WHERE booking_id = " . $bo_id;
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();

        return $data;
    }

    public function get_value(string $select, string $from, int $id)
    {
        $query = "SELECT $select
            FROM $from 
            WHERE id = ?
        ";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = $statement->get_result();
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
        } else {
            $data = false;
        }

        return $data;
    }
}
