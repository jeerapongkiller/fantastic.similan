<?php
require_once __DIR__ . '/DB.php';

class Order extends DB
{
    public $response = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function show_cars()
    {
        $query = "SELECT *
            FROM cars
            ORDER BY 
                CASE
                    WHEN name LIKE 'Phuket%' THEN 1
                    WHEN name LIKE 'Khaolak%' THEN 2
                    WHEN name LIKE 'Krabi%' THEN 3
                    ELSE 4
                END,
                CAST(SUBSTRING(name, LOCATE(' ', name) + 1) AS UNSIGNED)
        ";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function show_drivers()
    {
        $query = "SELECT *
            FROM drivers
            WHERE is_approved = 1
        ";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function show_guides()
    {
        $query = "SELECT *
            FROM guides
            WHERE is_approved = 1
        ";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function show_crew()
    {
        $query = "SELECT *
            FROM crews
            WHERE is_approved = 1
        ";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function show_boats()
    {
        $query = "SELECT *
            FROM boats
            WHERE is_approved = 1
        ";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function show_captain()
    {
        $query = "SELECT *
            FROM captains
            WHERE is_approved = 1
        ";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function show_color()
    {
        $query = "SELECT *
            FROM colors
            WHERE is_approved = 1
        ";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function showliststatus()
    {
        $query = "SELECT id, name, button_class
            FROM booking_status 
            WHERE id > 0
        ";
        $query .= " ORDER BY name ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function show_booking_payment()
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

    // Order
    // --------------------------------------------------------------------
    public function showlist($search_period, $search_agent, $search_product, $search_payment, string $date_travel_form)
    {
        $bind_types = "";
        $params = array();

        $query = "SELECT BO.*,
                    BONO.bo_full as book_full,
                    BSTA.id as booksta_id, BSTA.name as booksta_name, BSTA.name_class as booksta_class, BSTA.button_class as booksta_button,
                    BTYE.id as booktye_id, BTYE.name as booktye_name,
                    COMP.id as comp_id, COMP.name as comp_name,
                    BOPA.id as bopa_id, BOPA.date_paid as date_paid, BOPA.total_paid as total_paid, BOPA.card_no as card_no, BOPA.photo as bopa_photo, BOPA.note as bopa_note, BOPA.payment_type_id as payment_type_id,
                    BOPAY.id as bopay_id, BOPAY.name as bopay_name, BOPAY.name_class as bopay_name_class, BOPAY.created_at as bopay_created,
                    CUS.id as cus_id, CUS.name as cus_name, CUS.birth_date as birth_date, CUS.id_card as id_card, CUS.telephone as telephone, CUS.head as cus_head, CUS.nationality_id as nationality_id,
                    NATION.id as nation_id, NATION.name as nation_name,
                    BP.id as bp_id, BP.travel_date as travel_date, BP.adult as bp_adult, BP.child as bp_child, BP.infant as bp_infant, BP.foc as bp_foc, BP.note as bp_note,
                    BPR.id as bpr_id, BPR.rate_adult as rate_adult, BPR.rate_child as rate_child, BPR.rate_infant as rate_infant, BPR.rate_total as rate_total,
                    PROD.id as product_id, PROD.name as product_name,
                    CATE.id as category_id, CATE.name as category_name, CATE.transfer as category_transfer, 
                    BT.id as bt_id, BT.adult as bt_adult, BT.child as bt_child, BT.infant as bt_infant, BT.foc as bt_foc, BT.start_pickup as start_pickup, BT.end_pickup as end_pickup,
                    BT.room_no as room_no, BT.note as bt_note, BT.transfer_type as transfer_type, BT.pickup_type, pickup_type, BT.hotel_pickup as outside, BT.hotel_dropoff as outside_dropoff,
                    BTR.id as btr_id, BTR.rate_adult as btr_rate_adult, BTR.rate_child as btr_rate_child, BTR.rate_infant as btr_rate_infant, BTR.cars_category_id as cars_category,
                    BTR.rate_private as rate_private,
                    PICKUP.id as pickup_id, PICKUP.name_th as pickup_name,
                    DROPOFF.id as dropoff_id, DROPOFF.name_th as dropoff_name,
                    ZONE_P.id as zonep_id, ZONE_P.name_th as zonep_name,
                    ZONE_D.id as zoned_id, ZONE_D.name_th as zoned_name,
                    BEC.id as bec_id, BEC.name as bec_name, BEC.adult as bec_adult, BEC.child as bec_child, BEC.infant as bec_infant, BEC.privates as bec_privates, BEC.type as bec_type,
                    BEC.rate_adult as bec_rate_adult, BEC.rate_child as bec_rate_child, BEC.rate_infant as bec_rate_infant, BEC.rate_private as bec_rate_private, 
                    EXTRA.id as extra_id, EXTRA.name as extra_name, EXTRA.unit as extra_unit,
                    BOMANGE.id as bomanage_id,
                    MANGET.id as manget_id, MANGET.pickup as pickup, MANGET.dropoff as dropoff,
                    CAR.id as car_id, CAR.name as car_name,
                    BOOKER.id as booker_id, BOOKER.firstname as booker_fname, BOOKER.lastname as booker_lname,
                    BORDB.id as boman_id, BORDB.arrange as boman_arrange, 
                    MANGE.id as mange_id, MANGE.time as manage_time,
                    COLOR.id as color_id, COLOR.name as color_name, COLOR.name_th as color_name_th, COLOR.hex_code as color_hex, 
                    GUIDE.id as guide_id, GUIDE.name as guide_name,
                    BOAT.id as boat_id, BOAT.name as boat_name, BOAT.refcode as boat_refcode
                    -- MANGET.id as manget_id, MANGET.driver as driver,
                    -- CAR.id as car_id, CAR.name as car_name,
                    -- BOOKER.id as booker_id, BOOKER.firstname as booker_fname, BOOKER.lastname as booker_lname,
                    -- BORDB.id as boman_id, BORDB.arrange as boman_arrange, 
                    -- MANGE.id as mange_id, MANGE.time as manage_time,
                    -- COLOR.id as color_id, COLOR.name as color_name, COLOR.name_th as color_name_th, COLOR.hex_code as color_hex, 
                    -- CAPT.id as captain_id, CAPT.name as captain_name,
                    -- BOAT.id as boat_id, BOAT.name as boat_name, BOAT.refcode as boat_refcode,
                    -- GUIDE.id as guide_id, GUIDE.name as guide_name,
                    -- CREWF.id as crewf_id, CREWF.name as crewf_name,
                    -- CREWS.id as crews_id, CREWS.name as crews_name
                FROM bookings BO
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
                LEFT JOIN customers CUS
                    ON BO.id = CUS.booking_id
                LEFT JOIN nationalitys NATION
                    ON CUS.nationality_id = NATION.id
                LEFT JOIN booking_products BP
                    ON BO.id = BP.booking_id
                LEFT JOIN booking_product_rates BPR
                    ON BP.id = BPR.booking_products_id
                LEFT JOIN products PROD
                    ON BP.product_id = PROD.id
                LEFT JOIN product_periods PROP
                    ON BP.category_id = PROP.product_category_id
                    AND PROP.period_from <= BP.travel_date
                    AND PROP.period_to >= BP.travel_date
                LEFT JOIN product_category CATE
                    ON BP.category_id = CATE.id
                LEFT JOIN booking_transfer BT
                    ON BP.id = BT.booking_products_id
                LEFT JOIN booking_transfer_rates BTR
                    ON BT.id = BTR.booking_transfer_id
                LEFT JOIN hotel PICKUP
                    ON BT.hotel_pickup_id = PICKUP.id
                LEFT JOIN hotel DROPOFF
                    ON BT.hotel_dropoff_id = DROPOFF.id
                LEFT JOIN zones ZONE_P
                    ON BT.pickup_id = ZONE_P.id
                LEFT JOIN zones ZONE_D
                    ON BT.dropoff_id = ZONE_D.id
                LEFT JOIN booking_extra_charge BEC
                    ON BO.id = BEC.booking_id
                LEFT JOIN extra_charges EXTRA
                    ON BEC.extra_charge_id = EXTRA.id
                LEFT JOIN booking_order_transfer BOMANGE
                    ON BT.id = BOMANGE.booking_transfer_id
                LEFT JOIN order_transfer MANGET 
                    ON BOMANGE.order_id = MANGET.id
                    -- AND MANGET.pickup = 1
                LEFT JOIN cars CAR 
                    ON MANGET.car_id = CAR.id
                LEFT JOIN users BOOKER 
                    ON BO.booker_id = BOOKER.id
                LEFT JOIN booking_order_boat BORDB
                    ON BO.id = BORDB.booking_id
                LEFT JOIN order_boat MANGE 
                    ON BORDB.manage_id = MANGE.id
                LEFT JOIN colors COLOR 
                    ON MANGE.color_id = COLOR.id
                LEFT JOIN guides GUIDE
                    ON MANGE.guide_id = GUIDE.id
                LEFT JOIN boats BOAT
                    ON MANGE.boat_id = BOAT.id
                -- LEFT JOIN order_transfer MANGET 
                --     ON BT.manage_id = MANGET.id
                -- LEFT JOIN cars CAR 
                --     ON MANGET.car_id = CAR.id
                -- LEFT JOIN users BOOKER 
                --     ON BO.booker_id = BOOKER.id
                -- LEFT JOIN booking_order_boat BORDB
                --     ON BO.id = BORDB.booking_id
                -- LEFT JOIN order_boat MANGE 
                --     ON BORDB.manage_id = MANGE.id
                -- LEFT JOIN colors COLOR 
                --     ON MANGE.color_id = COLOR.id
                -- LEFT JOIN captains CAPT
                --     ON MANGE.captain_id = CAPT.id
                -- LEFT JOIN boats BOAT
                --     ON MANGE.boat_id = BOAT.id
                -- LEFT JOIN guides GUIDE
                --     ON MANGE.guide_id = GUIDE.id
                -- LEFT JOIN crews CREWF
                --     ON MANGE.crew_first_id = CREWF.id
                -- LEFT JOIN crews CREWS
                --     ON MANGE.crew_second_id = CREWS.id
                WHERE BO.id > 0
                AND BO.booking_status_id = 1
        ";

        if (isset($search_period) && $search_period != "all") {
            $query .= " AND BP.travel_date = ?";
            $bind_types .= "s";
            array_push($params, $date_travel_form);
        }

        if (isset($search_agent) && $search_agent != "all") {
            $query .= " AND COMP.id = ?";
            $bind_types .= "i";
            array_push($params, $search_agent);
        }

        if (isset($search_product) && $search_product != "all") {
            $query .= " AND BP.product_id = ?";
            $bind_types .= "i";
            array_push($params, $search_product);
        }

        if (isset($search_payment) && $search_payment != "all") {
            $query .= " AND BO.payment_id = ?";
            $bind_types .= "i";
            array_push($params, $search_payment);
        }

        $query .= " ORDER BY BP.travel_date DESC, PROD.id DESC, MANGET.pickup DESC ";
        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';
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

    public function show_park()
    {
        $query = "SELECT *
            FROM park
            WHERE is_approved = 1
        ";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function showlistagent()
    {
        $query = "SELECT companies.*, companies_type.id as comptypeId, companies_type.name as comptypeName
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

    public function showlistpayment()
    {
        $query = "SELECT *
            FROM booking_payment 
            WHERE id > 0
        ";
        $query .= " ORDER BY id ASC";
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

    public function get_data(string $select, string $from, int $id)
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

    // Management Transfer
    // --------------------------------------------------------------------
    public function get_values(string $select, string $from, string $where, int $type)
    {
        $query = "SELECT $select
                FROM $from
                WHERE $where
        ";
        // echo $query . '<br>';
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $type == 0 ? $result->fetch_assoc() : $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function fetch_all_manage($car, $driver, string $travel_date, int $mange_id)
    {
        $query = "SELECT order_transfer.*,
                cars.name as car_name,
                drivers.name as driver_name, drivers.telephone as driver_telephone, drivers.number_plate as number_plate, drivers.seat as seat
                FROM order_transfer
                LEFT JOIN cars
                    ON order_transfer.car_id = cars.id
                LEFT JOIN drivers
                    ON order_transfer.driver_id = drivers.id
                WHERE order_transfer.id > 0
        ";

        $query .= (!empty($mange_id) && $mange_id > 0) ? " AND order_transfer.id = " . $mange_id : "";
        $query .= (!empty($car) && $car != 'all') ? " AND cars.id = " . $car : "";
        $query .= (!empty($driver) && $driver != 'all') ? " AND drivers.id = " . $driver : "";
        $query .= (isset($travel_date) && $travel_date != '0000-00-00') ? " AND order_transfer.travel_date = '" . $travel_date . "'" : "";

        $query .= " ORDER BY order_transfer.pickup DESC,
                    CASE
                        WHEN cars.name LIKE 'Phuket%' THEN 1
                        WHEN cars.name LIKE 'Khaolak%' THEN 2
                        WHEN cars.name LIKE 'Krabi%' THEN 3
                        ELSE 4
                    END,
                    CAST(SUBSTRING(cars.name, LOCATE(' ', cars.name) + 1) AS UNSIGNED),
                    order_transfer.id ASC";

        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function fetch_all_booking($type, $status, $agent, $product, $refcode, $voucher_no, $name, $hotel, $travel_date, int $manages_id)
    {
        // Customer Name
        $name = trim(preg_replace('/\s+/', ' ', $name));
        $parts = explode(' ', $name);
        $nameisThai = preg_match('/[\x{0E00}-\x{0E7F}]/u', $name);
        $match_query = !empty($name) ? '+' . implode(' +', $parts) : '';

        // Hotel Name
        $keyword_hotel = trim(preg_replace('/\s+/', ' ', $hotel));
        $words = explode(' ', $keyword_hotel);
        $isThai = preg_match('/[\x{0E00}-\x{0E7F}]/u', $keyword_hotel);

        if ($isThai) {
            $match = !empty($hotel) ? " AND BT.hotel_pickup LIKE '%" . $hotel . "%'" : '';
        } else {
            $match = !empty($hotel) ? " AND MATCH(BT.hotel_pickup) AGAINST(+'" . implode(' +', $words) . "' IN BOOLEAN MODE)" : '';
        }

        $query = "SELECT BO.*,
                    BONO.bo_full as book_full,
                    BSTA.name_class as booksta_class, BSTA.name as status_name,
                    BOPA.total_paid as cot,
                    COMP.name as agent_name,
                    PROD.id as product_id, PROD.name as product_name,
                    CATE.id as category_id, CATE.name as category_name, CATE.transfer as category_transfer,
                    CUS.name as cus_name,
                    BP.id as bp_id, BP.note as bp_note,
                    BPRS.adult, BPRS.child, BPRS.infant, BPRS.foc, BPRS.max_tourist,
                    BT.id as bt_id, BT.start_pickup as start_pickup, BT.end_pickup as end_pickup,
                    BT.room_no as room_no, BT.note as bt_note, BT.hotel_pickup as outside_pickup, 
                    BT.hotel_dropoff as outside_dropoff, BT.pickup_type as pickup_type,
                    HOTELP.name_th as hotelp_name,
                    HOTELD.name_th as hoteld_name,
                    ZONE_P.name_th as zonep_name,
                    ZONE_D.name_th as zoned_name,
                    BOMANGE.id as bomange_id, BOMANGE.arrange as arrange, BOMANGE.tourist as tourist,
                    MANGE.id as manage_id
                FROM bookings BO
                LEFT JOIN bookings_no BONO
                    ON BO.id = BONO.booking_id
                LEFT JOIN booking_status BSTA
                    ON BO.booking_status_id = BSTA.id
                LEFT JOIN booking_paid BOPA
                    ON BO.id = BOPA.booking_id
                    AND BOPA.booking_payment_id = 4
                LEFT JOIN companies COMP
                    ON BO.company_id = COMP.id
                LEFT JOIN customers CUS
                    ON BO.id = CUS.booking_id
                LEFT JOIN booking_products BP
                    ON BO.id = BP.booking_id
                LEFT JOIN products PROD
                    ON BP.product_id = PROD.id
                LEFT JOIN (
                    SELECT BP.booking_id, BPR.category_id,
                        SUM(BPR.adult) AS adult,
                        SUM(BPR.child) AS child,
                        SUM(BPR.infant) AS infant,
                        SUM(BPR.foc) AS foc,
                        SUM(BPR.adult) + SUM(BPR.child) + SUM(BPR.infant) + SUM(BPR.foc) AS max_tourist
                    FROM booking_products BP
                    JOIN booking_product_rates BPR 
                        ON BP.id = BPR.booking_products_id
                    GROUP BY BP.booking_id, BPR.category_id
                ) BPRS 
                    ON BPRS.booking_id = BO.id
                LEFT JOIN product_category CATE 
                    ON BPRS.category_id = CATE.id
                LEFT JOIN booking_transfer BT
                    ON BP.id = BT.booking_products_id
                LEFT JOIN hotel HOTELP
                    ON BT.hotel_pickup_id = HOTELP.id
                LEFT JOIN hotel HOTELD
                    ON BT.hotel_dropoff_id = HOTELD.id
                LEFT JOIN zones ZONE_P
                    ON BT.pickup_id = ZONE_P.id
                LEFT JOIN zones ZONE_D
                    ON BT.dropoff_id = ZONE_D.id
                LEFT JOIN booking_order_transfer BOMANGE
                    ON BT.id = BOMANGE.booking_transfer_id
                LEFT JOIN order_transfer MANGE 
                    ON BOMANGE.order_id = MANGE.id
                WHERE BO.id > 0
                AND BO.booking_status_id != 3
                AND BO.booking_status_id != 4
        ";

        $query .= (!empty($type) && ($type == 'manage' || $type == 'booking')) ? " AND BT.pickup_type = 1 " : " AND BT.pickup_type != 2 ";
        $query .= (!empty($travel_date) && $travel_date != '') ? " AND BP.travel_date = '" . $travel_date . "'" : "";
        $query .= (!empty($type) && $type == 'manage') ? " AND MANGE.id = " . $manages_id : " AND (MANGE.id != " . $manages_id . " OR MANGE.id IS NULL)";
        $query .= (!empty($status) && $status != 'all') ? " AND BSTA.id = " . $status : "";
        $query .= (!empty($agent) && $agent != 'all') ? " AND COMP.id = " . $agent : "";
        $query .= (!empty($product) && $product != 'all') ? " AND PROD.id = " . $product : "";
        $query .= (!empty($voucher_no)) ? " AND BO.voucher_no_agent LIKE '%" . $voucher_no . "%' " : "";
        $query .= (!empty($refcode)) ? " AND BONO.bo_full LIKE '%" . $refcode . "%' " : "";
        $query .= (!empty($match_query)) ? (!empty($nameisThai)) ? " AND CUS.name LIKE '%" . $name . "%' " : " AND MATCH(CUS.name) AGAINST('$match_query' IN BOOLEAN MODE)" : '';
        $query .= (!empty($hotel)) ? $match : "";

        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function get_extra_charge(int $bo_id)
    {
        $query = "SELECT booking_extra_charge.*,
                    extra_charges.name as extra_name
                FROM booking_extra_charge 
                LEFT JOIN extra_charges
                    ON booking_extra_charge.extra_charge_id = extra_charges.id
                WHERE booking_extra_charge.id > 0
                AND booking_extra_charge.booking_id = $bo_id
        ";

        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function showlistreport(string $travel_date)
    {
        $bind_types = "";
        $params = array();

        $query = "SELECT BO.id as id,
                    PROD.id as product_id, PROD.name as product_name,
                    BPR.id as bpr_id, BPR.adult as adult, BPR.child as child, BPR.infant as infant, BPR.foc as foc, 
                    PICKUP.id as pickup_id, PICKUP.name_th as pickup_name, PICKUP.provinces as province_id,
                    DROPOFF.id as dropoff_id, DROPOFF.name_th as dropoff_name,
                    MANGE.id as mange_id,
                    CAR.id as car_id, CAR.name as car_name
                FROM bookings BO
                LEFT JOIN booking_products BP
                    ON BO.id = BP.booking_id
                LEFT JOIN booking_product_rates BPR
                    ON BP.id = BPR.booking_products_id
                LEFT JOIN products PROD
                    ON BP.product_id = PROD.id
                LEFT JOIN product_category CATE
                    ON BPR.category_id = CATE.id
                LEFT JOIN booking_transfer BT
                    ON BP.id = BT.booking_products_id
                LEFT JOIN zones PICKUP
                    ON BT.pickup_id = PICKUP.id
                LEFT JOIN zones DROPOFF
                    ON BT.dropoff_id = DROPOFF.id
                LEFT JOIN booking_order_transfer BOMANGE
                    ON BT.id = BOMANGE.booking_transfer_id
                LEFT JOIN order_transfer MANGE 
                    ON BOMANGE.order_id = MANGE.id
                LEFT JOIN cars CAR
                    ON MANGE.car_id = CAR.id
                WHERE BO.id > 0
                AND BO.booking_status_id != 3
                AND BO.booking_status_id != 4
                AND CATE.transfer > 0
                AND BT.pickup_type = 1
        ";

        $query .= " AND BP.is_deleted = 0 ";

        if (isset($travel_date) && $travel_date != '0000-00-00') {
            $query .= " AND BP.travel_date  = ?";
            $bind_types .= "s";
            array_push($params, $travel_date);
        }

        // $query .= (!empty($status) && $status != 'all') ? " AND BSTA.id = " . $status : "";
        // $query .= (!empty($agent) && $agent != 'all') ? " AND COMP.id = " . $agent : "";
        // $query .= (!empty($product) && $product != 'all') ? " AND PROD.id = " . $product : "";
        // $query .= (!empty($voucher_no)) ? " AND BO.voucher_no_agent LIKE '%" . $voucher_no . "%' " : "";
        // $query .= (!empty($refcode)) ? " AND BONO.bo_full LIKE '%" . $refcode . "%' " : "";
        // $query .= (!empty($name)) ? " AND CUS.name LIKE '%" . $name . "%' " : "";
        // $query .= (!empty($hotel)) ? " AND BT.hotel_pickup LIKE '%" . $hotel . "%' " : "";

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function showlisttransfers($type, string $travel_date, $car, $programe, $status, $agent, $product, $voucher_no, $refcode, $name, $hotel)
    {
        $bind_types = "";
        $params = array();

        $query = "SELECT BO.*,
                    BONO.bo_full as book_full,
                    BSTA.id as booksta_id, BSTA.name as booksta_name, BSTA.name_class as booksta_class, BSTA.button_class as booksta_button,
                    BTYE.id as booktye_id, BTYE.name as booktye_name,
                    COMP.id as comp_id, COMP.name as comp_name,
                    BOPA.id as bopa_id, BOPA.total_paid as total_paid,
                    CUS.id as cus_id, CUS.name as cus_name, CUS.telephone as telephone, CUS.head as cus_head, 
                    BP.id as bp_id, BP.travel_date as travel_date, BP.note as note,
                    PROD.id as product_id, PROD.name as product_name,
                    CATE.id as category_id, CATE.name as category_name, CATE.transfer as category_transfer,
                    BPR.id as bpr_id, BPR.adult as adult, BPR.child as child, BPR.infant as infant, BPR.foc as foc, 
                    BPR.rates_adult as rates_adult, BPR.rates_child as rates_child, BPR.rates_infant as rates_infant, BPR.rates_private as rates_private, 
                    BT.id as bt_id, BT.start_pickup as start_pickup, BT.end_pickup as end_pickup,
                    BT.room_no as room_no, BT.note as bt_note, BT.hotel_pickup as outside, BT.hotel_dropoff as outside_dropoff,
                    PICKUP.id as pickup_id, PICKUP.name_th as pickup_name,
                    DROPOFF.id as dropoff_id, DROPOFF.name_th as dropoff_name,
                    ZONE_P.id as zonep_id, ZONE_P.name_th as zonep_name, ZONE_P.provinces as province_id,
                    ZONE_D.id as zoned_id, ZONE_D.name_th as zoned_name,
                    BEC.id as bec_id, BEC.name as bec_name, BEC.adult as bec_adult, BEC.child as bec_child, BEC.infant as bec_infant, BEC.privates as bec_privates, BEC.type as bec_type,
                    BEC.rate_adult as bec_rate_adult, BEC.rate_child as bec_rate_child, BEC.rate_infant as bec_rate_infant, BEC.rate_private as bec_rate_private, 
                    EXTRA.id as extra_id, EXTRA.name as extra_name, EXTRA.unit as extra_unit,
                    BOMANGE.id as bomange_id, BOMANGE.arrange as arrange, BOMANGE.tourist as tourist,
                    MANGE.id as mange_id, MANGE.pickup as mange_pickup, MANGE.dropoff as mange_dropoff, MANGE.note as mange_note, MANGE.license as license, MANGE.seat as seat,
                    CAR.id as car_id, CAR.name as car_name,
                    MANGEB.id as mangeb_id,
                    BOAT.id as boat_id, BOAT.name as boat_name, BOAT.refcode as boat_refcode
                FROM bookings BO
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
                    AND BOPA.booking_payment_id = 4
                LEFT JOIN customers CUS
                    ON BO.id = CUS.booking_id
                LEFT JOIN nationalitys NATION
                    ON CUS.nationality_id = NATION.id
                LEFT JOIN booking_products BP
                    ON BO.id = BP.booking_id
                LEFT JOIN booking_product_rates BPR
                    ON BP.id = BPR.booking_products_id
                LEFT JOIN products PROD
                    ON BP.product_id = PROD.id
                LEFT JOIN product_category CATE
                    ON BPR.category_id = CATE.id
                LEFT JOIN booking_transfer BT
                    ON BP.id = BT.booking_products_id
                LEFT JOIN hotel PICKUP
                    ON BT.hotel_pickup_id = PICKUP.id
                LEFT JOIN hotel DROPOFF
                    ON BT.hotel_dropoff_id = DROPOFF.id
                LEFT JOIN zones ZONE_P
                    ON BT.pickup_id = ZONE_P.id
                LEFT JOIN zones ZONE_D
                    ON BT.dropoff_id = ZONE_D.id
                LEFT JOIN booking_extra_charge BEC
                    ON BO.id = BEC.booking_id
                LEFT JOIN extra_charges EXTRA
                    ON BEC.extra_charge_id = EXTRA.id
                LEFT JOIN booking_order_transfer BOMANGE
                    ON BT.id = BOMANGE.booking_transfer_id
                LEFT JOIN order_transfer MANGE 
                    ON BOMANGE.order_id = MANGE.id
                LEFT JOIN cars CAR
                    ON MANGE.car_id = CAR.id
                LEFT JOIN booking_order_boat BORDB
                    ON BO.id = BORDB.booking_id
                LEFT JOIN order_boat MANGEB 
                    ON BORDB.manage_id = MANGEB.id
                LEFT JOIN boats BOAT
                    ON MANGEB.boat_id = BOAT.id
                WHERE BO.id > 0
                AND BO.booking_status_id != 3
                AND BO.booking_status_id != 4
                AND CATE.transfer > 0
                AND BT.pickup_type = 1
        ";

        $query .= (!empty($status) && $status != 'all') ? " AND BSTA.id = " . $status : "";
        $query .= (!empty($agent) && $agent != 'all') ? " AND COMP.id = " . $agent : "";
        $query .= (!empty($product) && $product != 'all') ? " AND PROD.id = " . $product : "";
        $query .= (!empty($voucher_no)) ? " AND BO.voucher_no_agent LIKE '%" . $voucher_no . "%' " : "";
        $query .= (!empty($refcode)) ? " AND BONO.bo_full LIKE '%" . $refcode . "%' " : "";
        $query .= (!empty($name)) ? " AND CUS.name LIKE '%" . $name . "%' " : "";
        $query .= (!empty($hotel)) ? " AND BT.hotel_pickup LIKE '%" . $hotel . "%' " : "";

        if (!empty($type) && $type == 'manage') {

            $query .= " AND BP.is_deleted = 0 ";

            if (isset($travel_date) && $travel_date != '0000-00-00') {
                $query .= " AND BP.travel_date  = ?";
                $bind_types .= "s";
                array_push($params, $travel_date);
            }
            // if (isset($return) && $return > 0) {
            //     $query .= " AND BT.return_type = ?";
            //     $bind_types .= "i";
            //     array_push($params, $return);
            // }
            // if (isset($programe) && $programe != 'all') {
            //     $query .= " AND PROD.id  = ?";
            //     $bind_types .= "i";
            //     array_push($params, $programe);
            // }
            // $query .= " ORDER BY BP.travel_date DESC, PROD.id DESC, zones.id DESC, hotel.id DESC ";
            $query .= " ORDER BY PROD.id DESC, BOMANGE.arrange ASC, BT.pickup_id ASC, BT.start_pickup ASC, BT.hotel_pickup ASC ";
        }

        if (!empty($type) && $type == 'list') {
            if (isset($travel_date) && $travel_date != '0000-00-00') {
                $query .= " AND BP.travel_date  = ?";
                $bind_types .= "s";
                array_push($params, $travel_date);
            }
            // if (isset($programe) && $programe != 'all') {
            //     $query .= " AND PROD.id  = ?";
            //     $bind_types .= "i";
            //     array_push($params, $programe);
            // }
        }

        if (!empty($type) && $type == 'all') {

            $query .= " AND BP.is_deleted = 0 ";

            if (isset($travel_date) && $travel_date != '0000-00-00') {
                $query .= " AND BP.travel_date  = ?";
                $bind_types .= "s";
                array_push($params, $travel_date);
            }

            if (isset($car) && $car != 'all') {
                $query .= " AND MANGE.car_id  = ?";
                $bind_types .= "i";
                array_push($params, $car);
            }
            if (isset($programe) && $programe != 'all') {
                $query .= " AND PROD.id  = ?";
                $bind_types .= "i";
                array_push($params, $programe);
            }
            $query .= " ORDER BY PROD.id DESC, BOMANGE.arrange ASC, CATE.id ASC ";
        }

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function show_manage_transfer(string $travel_date)
    {
        $query = "SELECT manage.*,
                BOMAN.arrange as arrange, BOMAN.booking_transfer_id as boman_bt,
                CAR.id as car_id, CAR.name as car_name, CAR.car_registration as registration,
                DRIVER.id as driver_id, DRIVER.name as driver_name, DRIVER.seat as driver_seat
            FROM order_transfer manage
            LEFT JOIN booking_order_transfer BOMAN
                ON manage.id = BOMAN.order_id
            LEFT JOIN cars CAR
                ON manage.car_id = CAR.id
            LEFT JOIN drivers DRIVER
                ON manage.driver_id = DRIVER.id
            WHERE manage.id > 0
            AND manage.travel_date = ?
        ";

        $query .= " ORDER BY manage.pickup DESC,
                    CASE
                        WHEN CAR.name LIKE 'Phuket%' THEN 1
                        WHEN CAR.name LIKE 'Khaolak%' THEN 2
                        WHEN CAR.name LIKE 'Krabi%' THEN 3
                        ELSE 4
                    END,
                    CAST(SUBSTRING(CAR.name, LOCATE(' ', CAR.name) + 1) AS UNSIGNED),
                    manage.id ASC";

        $statement = $this->connection->prepare($query);
        $statement->bind_param("s", $travel_date);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function get_data_transfers(int $id)
    {
        $query = "SELECT BP.*,
                CUS.id as cusID, CUS.firstname as cusFname, CUS.lastname as cusLname, CUS.head as cusHead, CUS.telephone as cusTel
                FROM booking_products BP
                LEFT JOIN bookings BO
                ON BP.booking_id = BO.id
                LEFT JOIN customers CUS
                    ON BO.id = CUS.booking_id
                WHERE BP.id = ? 
                AND CUS.head = 1
                AND BP.is_deleted = 0
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

    public function insert_manage_transfer(string $outside_driver, int $car, int $seat, int $driver, string $license, string $telephone, string $date_travel, string $note, int $pickup, int $dropoff)
    {
        $bind_types = "";
        $params = array();

        $query = "INSERT INTO `order_transfer`(`outside_driver`, `license`, `telephone`, `travel_date`, `note`, `pickup`, `dropoff`, `seat`, `driver_id`, `car_id`, `created_at`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $bind_types .= "s";
        array_push($params, $outside_driver);

        $bind_types .= "s";
        array_push($params, $license);

        $bind_types .= "s";
        array_push($params, $telephone);

        $bind_types .= "s";
        array_push($params, $date_travel);

        $bind_types .= "s";
        array_push($params, $note);

        $bind_types .= "i";
        array_push($params, $pickup);

        $bind_types .= "i";
        array_push($params, $dropoff);

        $bind_types .= "i";
        array_push($params, $seat);

        $bind_types .= "i";
        array_push($params, $driver);

        $bind_types .= "i";
        array_push($params, $car);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = $this->connection->insert_id;
        }

        return $this->response;
    }

    public function update_manage_transfer(string $outside_driver, int $car, int $seat, int $driver, string $license, string $telephone, string $note, int $id)
    {
        $bind_types = "";
        $params = array();

        $query = "UPDATE order_transfer SET";

        $query .= " car_id = ? ,";
        $bind_types .= "i";
        array_push($params, $car);

        $query .= " driver_id = ? ,";
        $bind_types .= "i";
        array_push($params, $driver);

        $query .= " outside_driver = ? ,";
        $bind_types .= "s";
        array_push($params, $outside_driver);

        $query .= " license = ? ,";
        $bind_types .= "s";
        array_push($params, $license);

        $query .= " telephone = ? ,";
        $bind_types .= "s";
        array_push($params, $telephone);

        $query .= " note = ? ,";
        $bind_types .= "s";
        array_push($params, $note);

        $query .= " seat = ?";
        $bind_types .= "i";
        array_push($params, $seat);

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

    public function update_booking_transfer(int $manage_id, int $arrange, int $id, int $return, int $manage)
    {
        $bind_types = "";
        $params = array();

        $query = "UPDATE booking_transfer SET";

        $query .= " manage_id = ? ,";
        $bind_types .= "i";
        array_push($params, $manage_id);

        $query .= " arrange = ?";
        $bind_types .= "i";
        array_push($params, $arrange);

        if ($manage > 0 && $arrange == 0) {
            $query .= " WHERE manage_id = ?";
            $bind_types .= "i";
            array_push($params, $manage);
        } else {
            $query .= " WHERE id = ?";
            $bind_types .= "i";
            array_push($params, $id);
        }

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = true;
        }

        return $this->response;
    }

    public function delete_manage_transfer(int $manage_id)
    {
        $query = "DELETE FROM order_transfer WHERE id = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("i", $manage_id);
        $statement->execute();

        if ($statement->execute()) {
            $this->response = true;
        }

        return $this->response;
    }

    public function insert_manage_booking(int $arrange, int $tourist, int $order_id, int $booking_transfer_id)
    {
        $bind_types = "";
        $params = array();

        $query = "INSERT INTO `booking_order_transfer`(`arrange`, `tourist`, `order_id`, `booking_transfer_id`, `created_at`)
        VALUES (?, ?, ?, ?, ?)";

        $bind_types .= "i";
        array_push($params, $arrange);

        $bind_types .= "i";
        array_push($params, $tourist);

        $bind_types .= "i";
        array_push($params, $order_id);

        $bind_types .= "i";
        array_push($params, $booking_transfer_id);

        $bind_types .= "s";
        array_push($params, date("Y-m-d H:i:s"));

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = $this->connection->insert_id;
        }

        return $this->response;
    }

    public function update_manage_booking(int $arrange, int $tourist, int $manage_id, int $id)
    {
        $bind_types = "";
        $params = array();

        $query = "UPDATE `booking_order_transfer` SET";

        $query .= " arrange = ?, ";
        $bind_types .= "i";
        array_push($params, $arrange);

        $query .= " tourist = ?";
        $bind_types .= "i";
        array_push($params, $tourist);

        $query .= " WHERE id = ?";
        $bind_types .= "i";
        array_push($params, $id);

        // $query .= " AND order_id = ?";
        // $bind_types .= "i";
        // array_push($params, $manage_id);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = true;
        }

        return $this->response;
    }

    public function delete_manage_booking(int $id)
    {
        $query = "DELETE FROM `booking_order_transfer` WHERE id = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("i", $id);
        $statement->execute();

        if ($statement->execute()) {
            $this->response = true;
        }

        return $this->response;
    }

    // Management Boat
    // --------------------------------------------------------------------
    public function showlistboats($type, int $id, string $travel_date, $boat, $guide, $status, $agent, $product, $voucher_no, $refcode, $name, $hotel)
    {
        $bind_types = "";
        $params = array();

        $query = "SELECT BO.*,
                    BONO.bo_full as book_full,
                    BSTA.id as booksta_id, BSTA.name as booksta_name, BSTA.name_class as booksta_class, BSTA.button_class as booksta_button,
                    BTYE.id as booktye_id, BTYE.name as booktye_name,
                    COMP.id as comp_id, COMP.name as comp_name,
                    BOPA.id as bopa_id, BOPA.total_paid as total_paid, BOPA.updated_at as bopa_updated,
                    BOPAY.id as bopay_id, BOPAY.name as bopay_name, BOPAY.name_class as bopay_name_class, BOPAY.created_at as bopay_created,
                    CUS.id as cus_id, CUS.name as cus_name, CUS.birth_date as birth_date, CUS.id_card as id_card, CUS.telephone as telephone, CUS.head as cus_head, CUS.nationality_id as nationality_id,
                    NATION.id as nation_id, NATION.name as nation_name,
                    BP.id as bp_id, BP.travel_date as travel_date, BP.note as bp_note,
                    BPR.id as bpr_id, BPR.adult as adult, BPR.child as child, BPR.infant as infant, BPR.foc as foc, 
                    BPR.rates_adult as rates_adult, BPR.rates_child as rates_child, BPR.rates_infant as rates_infant, BPR.rates_private as rates_private,   
                    PROD.id as product_id, PROD.name as product_name,
                    CATE.id as category_id, CATE.name as category_name, CATE.transfer as category_transfer, 
                    BT.id as bt_id, BT.start_pickup as start_pickup, BT.end_pickup as end_pickup,
                    BT.pickup_type, pickup_type, BT.room_no as room_no, BT.note as bt_note, BT.hotel_pickup as outside, BT.hotel_dropoff as outside_dropoff,
                    PICKUP.id as pickup_id, PICKUP.name_th as pickup_name,
                    DROPOFF.id as dropoff_id, DROPOFF.name_th as dropoff_name,
                    ZONE_P.id as zonep_id, ZONE_P.name_th as zonep_name,
                    ZONE_D.id as zoned_id, ZONE_D.name_th as zoned_name,
                    BEC.id as bec_id, BEC.name as bec_name, BEC.adult as bec_adult, BEC.child as bec_child, BEC.infant as bec_infant, BEC.privates as bec_privates, BEC.type as bec_type,
                    BEC.rate_adult as bec_rate_adult, BEC.rate_child as bec_rate_child, BEC.rate_infant as bec_rate_infant, BEC.rate_private as bec_rate_private, 
                    EXTRA.id as extra_id, EXTRA.name as extra_name, EXTRA.unit as extra_unit,
                    BOMANGE.id as bomanage_id,
                    MANGET.id as manget_id, MANGET.pickup as pickup, MANGET.dropoff as dropoff,
                    CAR.id as car_id, CAR.name as car_name,
                    BOOKER.id as booker_id, BOOKER.firstname as booker_fname, BOOKER.lastname as booker_lname,
                    BORDB.id as boman_id, BORDB.arrange as boman_arrange, 
                    MANGE.id as mange_id, MANGE.time as manage_time, MANGE.counter as manage_counter,
                    COLOR.id as color_id, COLOR.name as color_name, COLOR.name_th as color_name_th, COLOR.hex_code as color_hex, COLOR.text_color as text_color, 
                    GUIDE.id as guide_id, GUIDE.name as guide_name,
                    BOAT.id as boat_id, BOAT.name as boat_name, BOAT.refcode as boat_refcode,
                    CHECKIN.id as check_id,
                    CONFIRM.id as confirm_id
                FROM bookings BO
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
                    AND BOPA.booking_payment_id = 4
                LEFT JOIN booking_payment BOPAY
                    ON BOPA.booking_payment_id = BOPAY.id
                LEFT JOIN customers CUS
                    ON BO.id = CUS.booking_id
                LEFT JOIN nationalitys NATION
                    ON CUS.nationality_id = NATION.id
                LEFT JOIN booking_products BP
                    ON BO.id = BP.booking_id
                LEFT JOIN booking_product_rates BPR
                    ON BP.id = BPR.booking_products_id
                LEFT JOIN products PROD
                    ON BP.product_id = PROD.id
                LEFT JOIN product_periods PROP
                    ON BPR.category_id = PROP.product_category_id
                    AND PROP.period_from <= BP.travel_date
                    AND PROP.period_to >= BP.travel_date
                LEFT JOIN product_category CATE
                    ON BPR.category_id = CATE.id
                LEFT JOIN booking_transfer BT
                    ON BP.id = BT.booking_products_id
                LEFT JOIN hotel PICKUP
                    ON BT.hotel_pickup_id = PICKUP.id
                LEFT JOIN hotel DROPOFF
                    ON BT.hotel_dropoff_id = DROPOFF.id
                LEFT JOIN zones ZONE_P
                    ON BT.pickup_id = ZONE_P.id
                LEFT JOIN zones ZONE_D
                    ON BT.dropoff_id = ZONE_D.id
                LEFT JOIN booking_extra_charge BEC
                    ON BO.id = BEC.booking_id
                LEFT JOIN extra_charges EXTRA
                    ON BEC.extra_charge_id = EXTRA.id
                LEFT JOIN booking_order_transfer BOMANGE
                    ON BT.id = BOMANGE.booking_transfer_id
                LEFT JOIN order_transfer MANGET 
                    ON BOMANGE.order_id = MANGET.id
                    AND MANGET.pickup = 1
                LEFT JOIN cars CAR 
                    ON MANGET.car_id = CAR.id
                LEFT JOIN users BOOKER 
                    ON BO.booker_id = BOOKER.id
                LEFT JOIN booking_order_boat BORDB
                    ON BO.id = BORDB.booking_id
                LEFT JOIN order_boat MANGE 
                    ON BORDB.manage_id = MANGE.id
                LEFT JOIN colors COLOR 
                    ON MANGE.color_id = COLOR.id
                LEFT JOIN guides GUIDE
                    ON MANGE.guide_id = GUIDE.id
                LEFT JOIN boats BOAT
                    ON MANGE.boat_id = BOAT.id
                LEFT JOIN check_in CHECKIN
                    ON BO.id = CHECKIN.booking_id
        ";

        $query .= ($type == 'job') ? " AND CHECKIN.type = 1" : "";
        $query .= ($type == 'guide') ? " AND CHECKIN.type = 2" : "";

        $query .= " LEFT JOIN confirm_agent CONFIRM
                        ON COMP.id = CONFIRM.agent_id ";

        $query .= ($type == 'agent' && (isset($travel_date) && $travel_date != '0000-00-00')) ? " AND CONFIRM.travel_date = '$travel_date'" : "";

        $query .= " WHERE BO.id > 0
                        AND BO.booking_status_id != 3
                        AND BO.booking_status_id != 4
                        AND BP.is_deleted = 0 ";

        $query .= (!empty($status) && $status != 'all') ? " AND BSTA.id = " . $status : "";
        $query .= (!empty($agent) && $agent != 'all') ? " AND COMP.id = " . $agent : "";
        $query .= (!empty($product) && $product != 'all') ? " AND PROD.id = " . $product : "";
        $query .= (!empty($voucher_no)) ? " AND BO.voucher_no_agent LIKE '%" . $voucher_no . "%' " : "";
        $query .= (!empty($refcode)) ? " AND BONO.bo_full LIKE '%" . $refcode . "%' " : "";
        $query .= (!empty($name)) ? " AND CUS.name LIKE '%" . $name . "%' " : "";
        $query .= (!empty($hotel)) ? " AND BT.hotel_pickup LIKE '%" . $hotel . "%' " : "";

        if (!empty($type) && ($type == 'list' || $type == 'job' || $type == 'guide')) {

            // $query .= ($type == 'list') ? " AND BO.booking_status_id != 4 " : "";

            if (isset($travel_date) && $travel_date != '0000-00-00') {
                $query .= " AND BP.travel_date  = ?";
                $bind_types .= "s";
                array_push($params, $travel_date);
            }
            if (isset($guide) && $guide != 'all') {
                $query .= " AND GUIDE.id  = ?";
                $bind_types .= "i";
                array_push($params, $guide);
            }
            if (isset($boat) && $boat != 'all') {
                $query .= " AND BOAT.id  = ?";
                $bind_types .= "i";
                array_push($params, $boat);
            }
            $query .= " ORDER BY BT.pickup_type DESC, BORDB.arrange ASC, CATE.name DESC"; // , CHECKIN.id ASC
        }

        if (!empty($type) && $type == 'manage') {

            $query .= " AND BP.is_deleted = 0 ";

            if (isset($travel_date) && $travel_date != '0000-00-00') {
                $query .= " AND BP.travel_date  = ?";
                $bind_types .= "s";
                array_push($params, $travel_date);
            }
            if (isset($boat) && $boat != 'all') {
                $query .= " AND BOAT.id  = ?";
                $bind_types .= "i";
                array_push($params, $boat);
            }
            $query .= " ORDER BY BT.pickup_type DESC, BORDB.arrange ASC, CATE.name DESC";
        }

        if (!empty($type) && $type == 'agent') {
            if (isset($travel_date) && $travel_date != '0000-00-00') {
                $query .= !empty(substr($travel_date, 11, 20)) ? " AND BP.travel_date BETWEEN '" . substr($travel_date, 0, 10) . "' AND '" . substr($travel_date, 11, 20) . "'" : " AND BP.travel_date = '" . $travel_date . "' ";
            }

            if (isset($id) && $id > 0) {
                $query .= " AND COMP.id  = ?";
                $bind_types .= "i";
                array_push($params, $id);
            }

            $query .= " ORDER BY COMP.name ASC, BT.pickup_type DESC, BORDB.arrange ASC, CATE.name DESC";
        }

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function show_manage_boat(string $date_travel, $boat)
    {
        $query = "SELECT manage.*,
                bo_manage.id as bomanage_id,
                boat.id as boat_id, boat.name as boat_name, boat.refcode as boat_refcode,
                color.id as color_id, color.name as color_name, color.name_th as color_name_th, color.hex_code as color_hex, color.text_color as text_color, 
                -- captain.id as captain_id, captain.name as captain_name,
                guide.id as guide_id, guide.name as guide_name
                -- crewf.id as crewf_id, crewf.name as crewf_name,
                -- crews.id as crews_id, crews.name as crews_name,
                -- PROD.id as product_id, PROD.name as product_name
            FROM order_boat manage
            LEFT JOIN booking_order_boat bo_manage
                ON manage.id = bo_manage.manage_id
            LEFT JOIN boats boat
                ON manage.boat_id = boat.id
            LEFT JOIN colors color 
                ON manage.color_id = color.id
            -- LEFT JOIN captains captain
            --     ON manage.captain_id = captain.id
            LEFT JOIN guides guide
                ON manage.guide_id = guide.id
            -- LEFT JOIN crews crewf
            --     ON manage.crew_first_id = crewf.id
            -- LEFT JOIN crews crews
            --     ON manage.crew_second_id = crews.id
            -- LEFT JOIN products PROD
            --     ON manage.programe_id = PROD.id
            WHERE manage.id > 0
        ";

        if (isset($boat) && $boat != 'all') {
            $query .= " AND boat.id = " . $boat;
        }

        $query .= " AND manage.travel_date = ?";

        $query .= " ORDER BY manage.id ASC";

        $statement = $this->connection->prepare($query);
        $statement->bind_param("s", $date_travel);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function fetch_all_manageboat(string $travel_date, $boat, $guide, int $mange_id)
    {
        $query = "SELECT order_boat.*,
                boats.id as boat_id, boats.name as boat_name,
                guides.id as guide_id, guides.name as guide_name,
                colors.id as color_id, colors.name as color_name, colors.name_th as color_name_th, colors.hex_code as color_hex, colors.text_color as text_color
                FROM order_boat
                LEFT JOIN boats 
                    ON order_boat.boat_id = boats.id
                LEFT JOIN guides 
                    ON order_boat.guide_id = guides.id
                LEFT JOIN colors 
                    ON order_boat.color_id = colors.id
                WHERE order_boat.id > 0
        ";

        $query .= (!empty($mange_id) && $mange_id > 0) ? " AND order_boat.id = " . $mange_id : "";
        $query .= (isset($travel_date) && $travel_date != '0000-00-00') ? " AND order_boat.travel_date = '" . $travel_date . "'" : "";
        $query .= (!empty($boat) && $boat != 'all') ? " AND boats.id = " . $boat : "";
        $query .= (!empty($guide) && $guide != 'all') ? " AND guides.id = " . $guide : "";

        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function fetch_all_bookingboat($type, string $travel_date, $status, $agent, $product, string $voucher_no, string $refcode, string $name, string $hotel, int $manages_id)
    {
        $name = trim(preg_replace('/\s+/', ' ', $name));
        $parts = explode(' ', $name);
        $nameisThai = preg_match('/[\x{0E00}-\x{0E7F}]/u', $name);
        $match_query = !empty($name) ? '+' . implode(' +', $parts) : '';

        // Hotel Name
        $keyword_hotel = trim(preg_replace('/\s+/', ' ', $hotel));
        $words = explode(' ', $keyword_hotel);
        $isThai = preg_match('/[\x{0E00}-\x{0E7F}]/u', $keyword_hotel);

        if ($isThai) {
            $match = !empty($hotel) ? " AND BT.hotel_pickup LIKE '%" . $hotel . "%'" : '';
        } else {
            $match = !empty($hotel) ? " AND MATCH(BT.hotel_pickup) AGAINST(+'" . implode(' +', $words) . "' IN BOOLEAN MODE)" : '';
        }

        $query = "SELECT BO.*,
                    BONO.bo_full as book_full,
                    BSTA.name_class as booksta_class, BSTA.name as status_name,
                    BOPA.total_paid as cot,
                    COMP.id as agent_id, COMP.name as agent_name,
                    PROD.id as product_id, PROD.name as product_name,
                    CATE.id as category_id, CATE.name as category_name,
                    CUS.name as cus_name,
                    BP.id as bp_id, BP.note as bp_note,
                    BPRS.adult, BPRS.child, BPRS.infant, BPRS.foc, BPRS.max_tourist,
                    BT.id as bt_id, BT.start_pickup as start_pickup, BT.end_pickup as end_pickup,
                    BT.room_no as room_no, BT.note as bt_note, BT.hotel_pickup as outside_pickup, 
                    BT.hotel_dropoff as outside_dropoff, BT.pickup_type as pickup_type,
                    HOTELP.name_th as hotelp_name,
                    HOTELD.name_th as hoteld_name,
                    ZONE_P.name_th as zonep_name,
                    ZONE_D.name_th as zoned_name,
                    BOMANGE.id as bomange_id,
                    MANGE.id as manage_id
                FROM bookings BO
                LEFT JOIN bookings_no BONO
                    ON BO.id = BONO.booking_id
                LEFT JOIN booking_status BSTA
                    ON BO.booking_status_id = BSTA.id
                LEFT JOIN booking_paid BOPA
                    ON BO.id = BOPA.booking_id
                    AND BOPA.booking_payment_id = 4
                LEFT JOIN companies COMP
                    ON BO.company_id = COMP.id
                LEFT JOIN customers CUS
                    ON BO.id = CUS.booking_id
                LEFT JOIN booking_products BP
                    ON BO.id = BP.booking_id
                LEFT JOIN products PROD
                    ON BP.product_id = PROD.id
                LEFT JOIN (
                    SELECT BP.booking_id, BPR.category_id,
                        SUM(BPR.adult) AS adult,
                        SUM(BPR.child) AS child,
                        SUM(BPR.infant) AS infant,
                        SUM(BPR.foc) AS foc,
                        SUM(BPR.adult) + SUM(BPR.child) + SUM(BPR.infant) + SUM(BPR.foc) AS max_tourist
                    FROM booking_products BP
                    JOIN booking_product_rates BPR 
                        ON BP.id = BPR.booking_products_id
                    GROUP BY BP.booking_id, BPR.category_id
                ) BPRS 
                    ON BPRS.booking_id = BO.id
                LEFT JOIN product_category CATE 
                    ON BPRS.category_id = CATE.id
                LEFT JOIN booking_transfer BT
                    ON BP.id = BT.booking_products_id
                LEFT JOIN hotel HOTELP
                    ON BT.hotel_pickup_id = HOTELP.id
                LEFT JOIN hotel HOTELD
                    ON BT.hotel_dropoff_id = HOTELD.id
                LEFT JOIN zones ZONE_P
                    ON BT.pickup_id = ZONE_P.id
                LEFT JOIN zones ZONE_D
                    ON BT.dropoff_id = ZONE_D.id
                LEFT JOIN booking_order_boat BOMANGE
                    ON BO.id = BOMANGE.booking_id
                LEFT JOIN order_boat MANGE 
                    ON BOMANGE.manage_id = MANGE.id
                WHERE BO.id > 0
                AND BO.booking_status_id != 3
                AND BO.booking_status_id != 4
        ";

        $query .= (!empty($travel_date) && $travel_date != '') ? " AND BP.travel_date = '" . $travel_date . "'" : "";
        $query .= (!empty($type) && $type != 'all') ? ($type == 'manage') ? " AND MANGE.id = " . $manages_id : " AND MANGE.id IS NULL" : '';
        $query .= (!empty($status) && $status != 'all') ? " AND BSTA.id = " . $status : "";
        $query .= (!empty($agent) && $agent != 'all') ? " AND COMP.id = " . $agent : "";
        $query .= (!empty($product) && $product != 'all') ? " AND PROD.id = " . $product : "";
        $query .= (!empty($voucher_no)) ? " AND BO.voucher_no_agent = '" . $voucher_no . "' " : "";
        $query .= (!empty($refcode)) ? " AND BONO.bo_full = '" . $refcode . "' " : "";
        $query .= (!empty($match_query)) ? (!empty($nameisThai)) ? " AND CUS.name LIKE '%" . $name . "%' " : " AND MATCH(CUS.name) AGAINST('$match_query' IN BOOLEAN MODE)" : '';
        $query .= (!empty($hotel)) ? $match : "";
        // $query .= (!empty($match_query)) ? " AND MATCH(CUS.name) AGAINST('$match_query' IN BOOLEAN MODE)" : '';
        // $query .= (!empty($name)) ? " AND CUS.name LIKE '%" . $name . "%' " : "";
        // $query .= (!empty($hotel)) ? " AND BT.hotel_pickup_id LIKE '%" . $hotel . "%' " : "";

        // echo $query;
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function showboatreport(string $travel_date)
    {
        $bind_types = "";
        $params = array();

        $query = "SELECT BO.id as id,
                    BPR.id as bpr_id, BPR.adult as adult, BPR.child as child, BPR.infant as infant, BPR.foc as foc, 
                    GUIDE.id as guide_id, GUIDE.name as guide_name,
                    BOAT.id as boat_id, BOAT.name as boat_name, BOAT.refcode as boat_refcode,
                    MANGE.id as mange_id
                FROM bookings BO
                LEFT JOIN booking_products BP
                    ON BO.id = BP.booking_id
                LEFT JOIN booking_product_rates BPR
                    ON BP.id = BPR.booking_products_id
                LEFT JOIN products PROD
                    ON BP.product_id = PROD.id
                LEFT JOIN product_category CATE
                    ON BPR.category_id = CATE.id
                LEFT JOIN booking_order_boat BORDB
                    ON BO.id = BORDB.booking_id
                LEFT JOIN order_boat MANGE 
                    ON BORDB.manage_id = MANGE.id
                LEFT JOIN colors COLOR 
                    ON MANGE.color_id = COLOR.id
                LEFT JOIN guides GUIDE
                    ON MANGE.guide_id = GUIDE.id
                LEFT JOIN boats BOAT
                    ON MANGE.boat_id = BOAT.id
                WHERE BO.id > 0
                AND BO.booking_status_id != 3
                AND BO.booking_status_id != 4
                AND MANGE.id > 0
        ";

        $query .= " AND BP.is_deleted = 0 ";

        if (isset($travel_date) && $travel_date != '0000-00-00') {
            $query .= " AND BP.travel_date  = ?";
            $bind_types .= "s";
            array_push($params, $travel_date);
        }

        // $query .= (!empty($status) && $status != 'all') ? " AND BSTA.id = " . $status : "";
        // $query .= (!empty($agent) && $agent != 'all') ? " AND COMP.id = " . $agent : "";
        // $query .= (!empty($product) && $product != 'all') ? " AND PROD.id = " . $product : "";
        // $query .= (!empty($voucher_no)) ? " AND BO.voucher_no_agent LIKE '%" . $voucher_no . "%' " : "";
        // $query .= (!empty($refcode)) ? " AND BONO.bo_full LIKE '%" . $refcode . "%' " : "";
        // $query .= (!empty($name)) ? " AND CUS.name LIKE '%" . $name . "%' " : "";
        // $query .= (!empty($hotel)) ? " AND BT.hotel_pickup LIKE '%" . $hotel . "%' " : "";

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function show_manage_programe(string $date_travel)
    {
        $query = "SELECT programe.*
            FROM products programe
            LEFT JOIN booking_products
                ON programe.id = booking_products.product_id
            LEFT JOIN bookings
                ON bookings.id = booking_products.booking_id
            LEFT JOIN booking_status 
                ON bookings.booking_status_id = booking_status.id
            WHERE programe.id > 0
            AND booking_products.travel_date = ?
            AND booking_status.id != 3
        ";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("s", $date_travel);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function check_manage_boat()
    {
        $query = "SELECT boats.*,
            manage.id as manage_id, manage.travel_date as travel_date
            FROM boats
            LEFT JOIN order_boat manage
                ON boats.id = manage.boat_id 
            WHERE boats.is_approved = 1
        ";

        $query .= " ORDER BY boats.id ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function insert_manage_boat(string $travel_date, string $time, string $counter, string $note, int $boat_id, int $guide_id, int $color_id)
    {
        $bind_types = "";
        $params = array();

        $query = "INSERT INTO order_boat(`travel_date`, `time`, `counter`, `note`, `boat_id`, `guide_id`, `color_id`, `created_at`)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

        $bind_types .= "s";
        array_push($params, $travel_date);

        $bind_types .= "s";
        array_push($params, $time);

        $bind_types .= "s";
        array_push($params, $counter);

        $bind_types .= "s";
        array_push($params, $note);

        $bind_types .= "i";
        array_push($params, $boat_id);

        $bind_types .= "i";
        array_push($params, $guide_id);

        $bind_types .= "s";
        array_push($params, $color_id);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = $this->connection->insert_id;
        }

        return $this->response;
    }

    public function update_manage_boat(string $time, string $counter, string $note, int $boat_id, int $guide_id, int $color_id, int $id)
    {
        $bind_types = "";
        $params = array();

        $query = "UPDATE order_boat SET";

        $query .= " time = ? ,";
        $bind_types .= "s";
        array_push($params, $time);

        $query .= " counter = ? ,";
        $bind_types .= "s";
        array_push($params, $counter);

        $query .= " note = ? ,";
        $bind_types .= "s";
        array_push($params, $note);

        $query .= " boat_id = ? ,";
        $bind_types .= "i";
        array_push($params, $boat_id);

        $query .= " guide_id = ? ,";
        $bind_types .= "i";
        array_push($params, $guide_id);

        $query .= " color_id = ? ";
        $bind_types .= "i";
        array_push($params, $color_id);

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

    public function insert_booking_manage_boat(int $arrange, int $booking_id, int $manage_id)
    {
        $bind_types = "";
        $params = array();

        $query = "INSERT INTO booking_order_boat (`arrange`, `booking_id`, `manage_id`, `created_at`)
        VALUES (?, ?, ?, NOW())";

        $bind_types .= "i";
        array_push($params, $arrange);

        $bind_types .= "i";
        array_push($params, $booking_id);

        $bind_types .= "i";
        array_push($params, $manage_id);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = $this->connection->insert_id;
        }

        return $this->response;
    }

    public function update_booking_manage_boat(int $arrange, int $booking_id, int $manage_id, int $id)
    {
        $bind_types = "";
        $params = array();

        $query = "UPDATE booking_order_boat SET";

        if ($id > 0) {
            $query .= " arrange = ?,";
            $bind_types .= "i";
            array_push($params, $arrange);

            $query .= " booking_id = ?,";
            $bind_types .= "i";
            array_push($params, $booking_id);

            $query .= " manage_id = ?";
            $bind_types .= "i";
            array_push($params, $manage_id);

            $query .= " WHERE id = ?";
            $bind_types .= "i";
            array_push($params, $id);
        } elseif ($id == 0) {
            $query .= " arrange = ?";
            $bind_types .= "i";
            array_push($params, $arrange);

            $query .= " WHERE booking_id = ?";
            $bind_types .= "i";
            array_push($params, $booking_id);

            $query .= " AND manage_id = ?";
            $bind_types .= "i";
            array_push($params, $manage_id);
        }

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = true;
        }

        return $this->response;
    }

    public function delete_manage_boat(int $manage_id)
    {
        $query = "DELETE FROM order_boat WHERE id = ? ";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("i", $manage_id);
        $statement->execute();
        if ($statement->execute()) {
            $this->response = true;
        }

        return $this->response;
    }

    public function delete_booking_manage_boat(int $id, int $bo_id, int $manage_id)
    {
        if ($id > 0) {
            $query = "DELETE FROM booking_order_boat WHERE id = ? ";
            $statement = $this->connection->prepare($query);
            $statement->bind_param("i", $id);
        } elseif ($id == 0 && $bo_id == 0 && $manage_id > 0) {
            $query = "DELETE FROM booking_order_boat WHERE manage_id = ? ";
            $statement = $this->connection->prepare($query);
            $statement->bind_param("i", $manage_id);
        } elseif ($id == 0 && $bo_id > 0 && $manage_id > 0) {
            $query = "DELETE FROM booking_order_boat WHERE booking_id = ? AND manage_id = ? ";
            $statement = $this->connection->prepare($query);
            $statement->bind_param("ii", $bo_id, $manage_id);
        }

        $statement->execute();
        if ($statement->execute()) {
            $this->response = true;
        }

        return $this->response;
    }

    function update_remark(int $bo_id, string $note)
    {
        $bind_types = "";
        $params = array();

        $query = "UPDATE booking_products SET";

        $query .= " note = ? ";
        $bind_types .= "s";
        array_push($params, $note);

        $query .= " WHERE id = ?";
        $bind_types .= "i";
        array_push($params, $bo_id);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = true;
        }

        return $this->response;
    }


    public function insert_driver(string $name, string $telephone, string $license, int $seat)
    {
        $bind_types = "";
        $params = array();

        $query = "INSERT INTO `drivers`(`name`, `telephone`, `number_plate`, `seat`, `is_approved`, `is_deleted`, `created_at`, `updated_at`)
        VALUES (?, ?, ?, ?, 1, 0, NOW(), NOW())";

        $bind_types .= "sssi";
        array_push($params, $name, $telephone, $license, $seat);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = $this->connection->insert_id;
        }

        return $this->response;
    }

    // Agent Order
    // --------------------------------------------------------------------
    public function fetch_all_agent(string $travel_date)
    {
        $query = "SELECT BO.*,
                    BONO.bo_full as book_full,
                    BSTA.name_class as booksta_class, BSTA.name as status_name,
                    BOPA.total_paid as cot,
                    COMP.id as agent_id, COMP.name as agent_name
                FROM bookings BO
                LEFT JOIN bookings_no BONO
                    ON BO.id = BONO.booking_id
                LEFT JOIN booking_status BSTA
                    ON BO.booking_status_id = BSTA.id
                LEFT JOIN booking_paid BOPA
                    ON BO.id = BOPA.booking_id
                    AND BOPA.booking_payment_id = 4
                LEFT JOIN companies COMP
                    ON BO.company_id = COMP.id
                LEFT JOIN booking_products BP
                    ON BO.id = BP.booking_id
                WHERE BO.id > 0
                AND BO.booking_status_id != 3
                AND BO.booking_status_id != 4
        ";

        $query .= (!empty($travel_date) && $travel_date != '') ? " AND BP.travel_date = '" . $travel_date . "'" : "";

        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function fetch_all_bookingagent(int $agent_id, string $travel_date)
    {
        $query = "SELECT BO.*,
                    BONO.bo_full as book_full,
                    -- BSTA.name_class as booksta_class, BSTA.name as status_name,
                    BOPA.total_paid as cot,
                    COMP.id as agent_id, COMP.name as agent_name,
                    PROD.id as product_id, PROD.name as product_name,
                    CATE.id as category_id, CATE.name as category_name,
                    CUS.name as cus_name,
                    BP.id as bp_id, BP.note as bp_note,
                    BPRS.adult, BPRS.child, BPRS.infant, BPRS.foc, BPRS.max_tourist,
                    BT.id as bt_id, BT.start_pickup as start_pickup, BT.end_pickup as end_pickup,
                    BT.room_no as room_no, BT.note as bt_note, BT.hotel_pickup as outside_pickup, 
                    BT.hotel_dropoff as outside_dropoff, BT.pickup_type as pickup_type,
                    HOTELP.name_th as hotelp_name,
                    HOTELD.name_th as hoteld_name,
                    ZONE_P.name_th as zonep_name,
                    ZONE_D.name_th as zoned_name
                    -- BOMANGE.id as bomange_id,
                    -- MANGE.id as manage_id
                FROM bookings BO
                LEFT JOIN bookings_no BONO
                    ON BO.id = BONO.booking_id
                LEFT JOIN booking_status BSTA
                    ON BO.booking_status_id = BSTA.id
                LEFT JOIN booking_paid BOPA
                    ON BO.id = BOPA.booking_id
                    AND BOPA.booking_payment_id = 4
                LEFT JOIN companies COMP
                    ON BO.company_id = COMP.id
                LEFT JOIN customers CUS
                    ON BO.id = CUS.booking_id
                LEFT JOIN booking_products BP
                    ON BO.id = BP.booking_id
                LEFT JOIN products PROD
                    ON BP.product_id = PROD.id
                LEFT JOIN (
                    SELECT BP.booking_id, BPR.category_id,
                        SUM(BPR.adult) AS adult,
                        SUM(BPR.child) AS child,
                        SUM(BPR.infant) AS infant,
                        SUM(BPR.foc) AS foc,
                        SUM(BPR.adult) + SUM(BPR.child) + SUM(BPR.infant) + SUM(BPR.foc) AS max_tourist
                    FROM booking_products BP
                    JOIN booking_product_rates BPR 
                        ON BP.id = BPR.booking_products_id
                    GROUP BY BP.booking_id, BPR.category_id
                ) BPRS 
                    ON BPRS.booking_id = BO.id
                LEFT JOIN product_category CATE 
                    ON BPRS.category_id = CATE.id
                LEFT JOIN booking_transfer BT
                    ON BP.id = BT.booking_products_id
                LEFT JOIN hotel HOTELP
                    ON BT.hotel_pickup_id = HOTELP.id
                LEFT JOIN hotel HOTELD
                    ON BT.hotel_dropoff_id = HOTELD.id
                LEFT JOIN zones ZONE_P
                    ON BT.pickup_id = ZONE_P.id
                LEFT JOIN zones ZONE_D
                    ON BT.dropoff_id = ZONE_D.id
                LEFT JOIN booking_order_boat BOMANGE
                    ON BO.id = BOMANGE.booking_id
                LEFT JOIN order_boat MANGE 
                    ON BOMANGE.manage_id = MANGE.id
                WHERE BO.id > 0
                AND BO.booking_status_id != 3
                AND BO.booking_status_id != 4
        ";

        $query .= (!empty($travel_date) && $travel_date != '') ? " AND BP.travel_date = '" . $travel_date . "'" : "";
        $query .= (!empty($agent_id) && $agent_id > 0) ? " AND COMP.id = " . $agent_id : "";

        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    // public function showlistorderagent($search_period, $search_agent, $search_product, string $date_travel_form)
    // {
    //     $bind_types = "";
    //     $params = array();

    //     $query = "SELECT BO.*,
    //                 BONO.bo_full as book_full,
    //                 BSTA.id as booksta_id, BSTA.name as booksta_name, BSTA.name_class as booksta_class, BSTA.button_class as booksta_button,
    //                 BTYE.id as booktye_id, BTYE.name as booktye_name,
    //                 COMP.id as comp_id, COMP.name as comp_name,
    //                 BOPA.id as bopa_id, BOPA.date_paid as date_paid, BOPA.total_paid as total_paid, BOPA.card_no as card_no, BOPA.photo as bopa_photo, BOPA.note as bopa_note, BOPA.payment_type_id as payment_type_id,
    //                 BOPAY.id as bopay_id, BOPAY.name as bopay_name, BOPAY.name_class as bopay_name_class, BOPAY.created_at as bopay_created,
    //                 CUS.id as cus_id, CUS.name as cus_name, CUS.birth_date as birth_date, CUS.id_card as id_card, CUS.telephone as telephone, CUS.address as cus_address,
    //                 CUS.email as cus_email, CUS.head as cus_head, CUS.nationality_id as nationality_id,
    //                 NATION.id as nation_id, NATION.name as nation_name,
    //                 BP.id as bp_id, BP.travel_date as travel_date, BP.adult as bp_adult, BP.child as bp_child, BP.infant as bp_infant, BP.note as bp_note,
    //                 BP.private_type as bp_private_type,
    //                 PROD.id as product_id, PROD.name as product_name,
    //                 CATE.id as category_id, CATE.name as category_name, CATE.transfer as category_transfer,
    //                 BPR.id as bpr_id, BPR.rate_adult as rate_adult, BPR.rate_child as rate_child, BPR.rate_infant as rate_infant, BPR.rate_total as rate_total,   
    //                 BT.id as bt_id, BT.adult as bt_adult, BT.child as bt_child, BT.infant as bt_infant, BT.start_pickup as start_pickup, BT.end_pickup as end_pickup,
    //                 BT.hotel_pickup as hotel_pickup, BT.hotel_dropoff as hotel_dropoff, BT.room_no as room_no, BT.note as bt_note, BT.transfer_type as transfer_type,
    //                 BT.pickup_type as pickup_type,
    //                 BTR.id as btr_id, BTR.rate_adult as btr_rate_adult, BTR.rate_child as btr_rate_child, BTR.rate_infant as btr_rate_infant, BTR.cars_category_id as cars_category,
    //                 BTR.rate_private as rate_private,
    //                 CARC.id as carc_id, CARC.name as carc_name,
    //                 BEC.id as bec_id, BEC.name as bec_name, BEC.adult as bec_adult, BEC.child as bec_child, BEC.infant as bec_infant, BEC.privates as bec_privates, BEC.type as bec_type,
    //                 BEC.rate_adult as bec_rate_adult, BEC.rate_child as bec_rate_child, BEC.rate_infant as bec_rate_infant, BEC.rate_private as bec_rate_private, 
    //                 EXTRA.id as extra_id, EXTRA.name as extra_name,
    //                 BOMANGE.id as bomanage_id,
    //                 MANGET.id as manget_id, MANGET.pickup as pickup, MANGET.dropoff as dropoff,
    //                 CAR.id as car_id, CAR.name as car_name,
    //                 BOOKER.id as booker_id, BOOKER.firstname as booker_fname, BOOKER.lastname as booker_lname,
    //                 BORDB.id as boman_id, BORDB.arrange as boman_arrange, 
    //                 MANGE.id as mange_id, MANGE.time as manage_time,
    //                 COLOR.id as color_id, COLOR.name as color_name, COLOR.name_th as color_name_th, COLOR.hex_code as color_hex, 
    //                 GUIDE.id as guide_id, GUIDE.name as guide_name,
    //                 BOAT.id as boat_id, BOAT.name as boat_name, BOAT.refcode as boat_refcode,
    //                 -- MANGET.id as ortran_id, MANGET.driver as driver_name, MANGET.license as license, MANGET.telephone as ortran_telephone,
    //                 -- BOBOAT.id as boboat_id,
    //                 -- MANGE.id as mange_id, MANGE.time as manage_time,
    //                 -- COLOR.id as color_id, COLOR.name as color_name, COLOR.name_th as color_name_th, COLOR.hex_code as color_hex, 
    //                 -- CAPT.id as captain_id, CAPT.name as captain_name,
    //                 -- BOAT.id as boat_id, BOAT.name as boat_name, BOAT.refcode as boat_refcode,
    //                 -- GUIDE.id as guide_id, GUIDE.name as guide_name,
    //                 -- CREWF.id as crewf_id, CREWF.name as crewf_name,
    //                 -- CREWS.id as crews_id, CREWS.name as crews_name,
    //                 BOOKER.id as booker_id, BOOKER.firstname as booker_fname, BOOKER.lastname as booker_lname,
    //                 HOTPIK.id as hotel_pickup_id, HOTPIK.name as hotel_pickup_name,
    //                 HOTDRO.id as hotel_dropoff_id, HOTDRO.name as hotel_dropoff_name,
    //                 PICK.id as pickup_id, PICK.name as pickup_name, 
    //                 DROF.id as dropoff_id, DROF.name as dropoff_name
    //             FROM bookings BO
    //             LEFT JOIN bookings_no BONO
    //                 ON BO.id = BONO.booking_id
    //             LEFT JOIN booking_status BSTA
    //                 ON BO.booking_status_id = BSTA.id
    //             LEFT JOIN booking_type BTYE
    //                 ON BO.booking_type_id = BTYE.id
    //             LEFT JOIN companies COMP
    //                 ON BO.company_id = COMP.id
    //             LEFT JOIN booking_paid BOPA
    //                 ON BO.id = BOPA.booking_id
    //             LEFT JOIN booking_payment BOPAY
    //                 ON BOPA.booking_payment_id = BOPAY.id
    //             LEFT JOIN customers CUS
    //                 ON BO.id = CUS.booking_id
    //             LEFT JOIN nationalitys NATION
    //                 ON CUS.nationality_id = NATION.id
    //             LEFT JOIN booking_products BP
    //                 ON BO.id = BP.booking_id
    //             LEFT JOIN products PROD
    //                 ON BP.product_id = PROD.id
    //             LEFT JOIN product_category CATE
    //                 ON BP.category_id = CATE.id
    //             LEFT JOIN booking_product_rates BPR
    //                 ON BP.id = BPR.booking_products_id
    //             LEFT JOIN booking_transfer BT
    //                 ON BP.id = BT.booking_products_id
    //             LEFT JOIN booking_transfer_rates BTR
    //                 ON BT.id = BTR.booking_transfer_id
    //             LEFT JOIN cars_category CARC
    //                 ON BTR.cars_category_id = CARC.id 
    //             LEFT JOIN booking_extra_charge BEC
    //                 ON BO.id = BEC.booking_id
    //             LEFT JOIN extra_charges EXTRA
    //                 ON BEC.extra_charge_id = EXTRA.id
    //             LEFT JOIN booking_order_transfer BOMANGE
    //                 ON BT.id = BOMANGE.booking_transfer_id
    //             LEFT JOIN order_transfer MANGET 
    //                 ON BOMANGE.order_id = MANGET.id
    //             LEFT JOIN cars CAR 
    //                 ON MANGET.car_id = CAR.id
    //             LEFT JOIN booking_order_boat BORDB
    //                 ON BO.id = BORDB.booking_id
    //             LEFT JOIN order_boat MANGE 
    //                 ON BORDB.manage_id = MANGE.id
    //             LEFT JOIN colors COLOR 
    //                 ON MANGE.color_id = COLOR.id
    //             LEFT JOIN guides GUIDE
    //                 ON MANGE.guide_id = GUIDE.id
    //             LEFT JOIN boats BOAT
    //                 ON MANGE.boat_id = BOAT.id
    //             LEFT JOIN hotel HOTPIK
    //                 ON BT.hotel_pickup_id = HOTPIK.id
    //             LEFT JOIN hotel HOTDRO
    //                 ON BT.hotel_dropoff_id = HOTDRO.id
    //             LEFT JOIN zones PICK
    //                 ON BT.pickup_id = PICK.id
    //             LEFT JOIN zones DROF
    //                 ON BT.dropoff_id = DROF.id
    //             LEFT JOIN users BOOKER 
    //                 ON BO.booker_id = BOOKER.id
    //             WHERE BO.id > 0
    //             AND BO.booking_status_id = 1
    //     ";

    //     if (isset($search_period) && $search_period != "all") {
    //         $query .= " AND BP.travel_date = ?";
    //         $bind_types .= "s";
    //         array_push($params, $date_travel_form);
    //     }

    //     if (isset($search_agent) && $search_agent != "all") {
    //         $query .= " AND COMP.id = ?";
    //         $bind_types .= "i";
    //         array_push($params, $search_agent);
    //     }

    //     if (isset($search_product) && $search_product != "all") {
    //         $query .= " AND BP.product_id = ?";
    //         $bind_types .= "i";
    //         array_push($params, $search_product);
    //     }

    //     if (isset($search_payment) && $search_payment != "all") {
    //         $query .= " AND BO.payment_id = ?";
    //         $bind_types .= "i";
    //         array_push($params, $search_payment);
    //     }

    //     $query .= " ORDER BY COMP.id ASC, PROD.id DESC, BOPA.id ASC, MANGET.pickup DESC ";
    //     $statement = $this->connection->prepare($query);
    //     !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';
    //     $statement->execute();
    //     $result = $statement->get_result();
    //     $data = $result->fetch_all(MYSQLI_ASSOC);

    //     return $data;
    // }

    public function insert_guide(string $name)
    {
        $bind_types = "";
        $params = array();

        $query = "INSERT INTO guides (name, is_approved, is_deleted, created_at, updated_at)
        VALUES (?, 1, 0, NOW(), NOW())";

        $bind_types .= "s";
        array_push($params, $name);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = $this->connection->insert_id;
        }

        return $this->response;
    }

    public function insert_car(string $name)
    {
        $bind_types = "";
        $params = array();

        $query = "INSERT INTO `cars`(`name`, `is_approved`, `is_deleted`, `created_at`, `updated_at`)
        VALUES (?, 1, 0, NOW(), NOW())";

        $bind_types .= "s";
        array_push($params, $name);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = $this->connection->insert_id;
        }

        return $this->response;
    }

    public function insert_boat(string $name)
    {
        $bind_types = "";
        $params = array();

        $query = "INSERT INTO `boats`(`name`, `is_approved`, `is_deleted`, `created_at`, `updated_at`)
        VALUES (?, 1, 0, NOW(), NOW())";

        $bind_types .= "s";
        array_push($params, $name);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = $this->connection->insert_id;
        }

        return $this->response;
    }

    public function insert_captain(string $name)
    {
        $bind_types = "";
        $params = array();

        $query = "INSERT INTO `captains`(`name`, `is_approved`, `is_deleted`, `created_at`, `updated_at`)
        VALUES (?, 1, 0, NOW(), NOW())";

        $bind_types .= "s";
        array_push($params, $name);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = $this->connection->insert_id;
        }

        return $this->response;
    }

    public function insert_crew(string $name)
    {
        $bind_types = "";
        $params = array();

        $query = "INSERT INTO `crews`(`name`, `is_approved`, `is_deleted`, `created_at`, `updated_at`)
        VALUES (?, 1, 0, NOW(), NOW())";

        $bind_types .= "s";
        array_push($params, $name);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = $this->connection->insert_id;
        }

        return $this->response;
    }

    public function insert_check(int $bo_id, int $type)
    {
        $bind_types = "";
        $params = array();

        $query = "INSERT INTO `check_in`(`booking_id`, `type`, `login_id`, `created_at`) 
        VALUES (?, ?, ?, NOW())";

        $bind_types .= "i";
        array_push($params, $bo_id);

        $bind_types .= "i";
        array_push($params, $type);

        $bind_types .= "i";
        array_push($params, $_SESSION["supplier"]["id"]);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = $this->connection->insert_id;
        }

        return $this->response;
    }

    public function delete_check(int $bo_id, int $type)
    {
        $query = "DELETE FROM `check_in` WHERE `booking_id` = ? AND `type` = ? ";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("ii", $bo_id, $type);
        $statement->execute();
        if ($statement->execute()) {
            $this->response = true;
        }

        return $this->response;
    }

    public function insert_confirm(int $agent_id, string $travel_date)
    {
        $bind_types = "";
        $params = array();

        $query = "INSERT INTO `confirm_agent`(`travel_date`, `agent_id`, `login_id`, `created_at`) 
        VALUES (?, ?, ?, NOW())";

        $bind_types .= "s";
        array_push($params, $travel_date);

        $bind_types .= "i";
        array_push($params, $agent_id);

        $bind_types .= "i";
        array_push($params, $_SESSION["supplier"]["id"]);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = $this->connection->insert_id;
        }

        return $this->response;
    }

    public function delete_confirm(int $agent_id, string $travel_date)
    {
        $query = "DELETE FROM `confirm_agent` WHERE `agent_id` = ? AND `travel_date` = ? ";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("is", $agent_id, $travel_date);
        $statement->execute();
        if ($statement->execute()) {
            $this->response = true;
        }

        return $this->response;
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
}
