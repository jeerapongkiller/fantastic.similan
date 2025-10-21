<?php
include_once('../../../config/env.php');
include_once('../../../controllers/Booking.php');

$bookObj = new Booking();
$response = FALSE;

if (isset($_POST['action']) && $_POST['action'] == "create") {
    # --- get value booking form --- #
    $quick = !empty($_POST['quick']) ? $_POST['quick'] : 0;
    $book_status = !empty($_POST['book_status']) ? $_POST['book_status'] : 0;
    $book_type = !empty($_POST['booking_type']) ? $_POST['booking_type'] : 0;
    $book_date = $_POST['book_date'] != "" ? $_POST['book_date'] : '0000-00-00';
    $book_time = $_POST['book_time'] != "" ? $_POST['book_time'] : '00:00:00';
    $agent = !empty($_POST['agent']) ? $_POST['agent'] : 0;
    $voucher_no = !empty($_POST['voucher_no']) ? $_POST['voucher_no'] : '';
    $sender = !empty($_POST['sender']) ? $_POST['sender'] : '';
    $discount = !empty($_POST['discount']) ? preg_replace('(,)', '', $_POST['discount']) : 0;
    $cot = !empty($_POST['cot']) ? preg_replace('(,)', '', $_POST['cot']) : 0;
    # --- get value booking no. --- #
    if (!empty($book_date)) {
        $book_no = $bookObj->create_booking_no($book_date);
        $bo_title = $book_no['bo_title'] != "" ? $book_no['bo_title'] : '';
        $bo_date = $book_no['bo_date'] != "" ? $book_no['bo_date'] : '';
        $bo_year = $book_no['bo_year'] != "" ? $book_no['bo_year'] : 0;
        $bo_year_th = $book_no['bo_year_th'] != "" ? $book_no['bo_year_th'] : 0;
        $bo_month = $book_no['bo_month'] != "" ? $book_no['bo_month'] : 0;
        $bo_no = $book_no['bo_no'] != "" ? $book_no['bo_no'] : 0;
        $bo_full = $book_no['bo_full'] != "" ? $book_no['bo_full'] : '';
    }
    # --- get value booking product form --- #
    $travel_date = !empty($_POST['travel_date']) ? $_POST['travel_date'] : '0000-00-00';
    $product_id = !empty($_POST['product_id']) ? $_POST['product_id'] : 0;
    $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : [];
    $adult = !empty($_POST['adult']) ? $_POST['adult'] : [];
    $child = !empty($_POST['child']) ? $_POST['child'] : [];
    $infant = !empty($_POST['infant']) ? $_POST['infant'] : [];
    $foc = !empty($_POST['foc']) ? $_POST['foc'] : [];
    $bp_note = !empty($_POST['bp_note']) ? $_POST['bp_note'] : '';
    # --- get value booking product rate form --- #
    $prodrid = !empty($_POST['prodrid']) ? $_POST['prodrid'] : [];
    $rates_adult = !empty($_POST['rates_adult']) && $book_type == 1 ? $_POST['rates_adult'] : [];
    $rates_child = !empty($_POST['rates_child']) && $book_type == 1 ? $_POST['rates_child'] : [];
    $rates_infant = !empty($_POST['rates_infant']) && $book_type == 1 ? $_POST['rates_infant'] : [];
    $rates_private = !empty($_POST['rates_private']) && $book_type == 2 ? $_POST['rates_private'] : [];
    # --- get value customer form --- #
    $cus_name = !empty($_POST['cus_name']) ? $_POST['cus_name'] : '';
    # --- get value Transfer from --- #
    $pickup_type = !empty($_POST['pickup_type']) ? $_POST['pickup_type'] : 0;
    $transfer_type = !empty($_POST['transfer_type']) ? $_POST['transfer_type'] : 0;
    $start_pickup = !empty($_POST['start_pickup']) ? $_POST['start_pickup'] : '00:00:00';
    $end_pickup = !empty($_POST['end_pickup']) ? $_POST['end_pickup'] : '00:00:00';
    $hotel_pickup = !empty($_POST['hotel_pickup']) ? $_POST['hotel_pickup'] : 0;
    $zone_pickup = !empty($_POST['zone_pickup']) ? $_POST['zone_pickup'] : 0;
    $pickup = !empty($_POST['pickup']) ? $_POST['pickup'] : 0;
    $hotel_outside = !empty($_POST['hotel_outside']) ? $_POST['hotel_outside'] : '';
    $zone_dropoff = !empty($_POST['zone_dropoff']) ? $_POST['zone_dropoff'] : 0;
    $dropoff_outside = !empty($_POST['dropoff_outside']) ? $_POST['dropoff_outside'] : '';
    $hotel_pickup_outside = !empty($_POST['hotel_pickup_outside']) ? $_POST['hotel_pickup_outside'] : '';
    $hotel_dropoff = empty($_POST['hotel_dropoff']) ? !empty($_POST['hotel_pickup']) ? $_POST['hotel_pickup'] : 0 : $_POST['hotel_dropoff'];
    $dropoff = empty($_POST['dropoff']) ? !empty($_POST['pickup']) ? $_POST['pickup'] : 0 : $_POST['dropoff'];
    $hotel_dropoff_outside = empty($_POST['hotel_dropoff_outside']) ? !empty($_POST['hotel_pickup_outside']) ? $_POST['hotel_pickup_outside'] : '' : $_POST['hotel_dropoff_outside'];
    $room_no = !empty($_POST['room_no']) ? $_POST['room_no'] : '';
    $trans_note = !empty($_POST['trans_note']) ? $_POST['trans_note'] : '';
    $include = !empty($_POST['include']) ? $_POST['include'] : 0;
    # --- get value extra chang from --- #
    $extra_charge = !empty($_POST['extra-charge']) ? $_POST['extra-charge'] : '';
    # --- check confirm agent --- #
    if (($agent != 'outside' && $agent > 0) && ($travel_date != '0000-00-00')) {
        $confirm_id = $bookObj->get_values('id', 'confirm_agent', 'agent_id = ' . $agent . ' AND travel_date	= "' . $travel_date . '"', 0);
        if ($confirm_id != false && $confirm_id['id'] > 0) {
            $response = $bookObj->delete_confirm($confirm_id['id']);
        }
    }

    # --- chack insert agent --- #
    if ($agent == 'outside' && !empty($_POST['agent_outside'])) {
        $agent_out_id = $bookObj->insert_agent($_POST['agent_outside']);
    }

    $bo_id = $bookObj->insert_data($book_status, $book_type, $book_date, $book_time, ($agent == 'outside' && !empty($_POST['agent_outside'])) ? $agent_out_id : $agent, $voucher_no, $sender, $discount); // insert bookings

    $response = ($bo_id != FALSE && $bo_id > 0) ? $bookObj->insert_booking_no($bo_id, $bo_date, $bo_year, $bo_year_th, $bo_month, $bo_no, $bo_full) : FALSE; // insert bookings no

    $response = ($bo_id != FALSE && $bo_id > 0) ? $bookObj->insert_log('สร้าง Booking', 'หมายเลข booking no. ' . $bo_full, $bo_id, 1, date("Y-m-d H:i:s")) : FALSE; // insert log booking

    $bp_id = ($response != FALSE && $response > 0) ? $bookObj->insert_booking_product($travel_date, $bp_note, $bo_id, $product_id) : FALSE; // insert booking products

    for ($i = 0; $i < count($category_id); $i++) {
        $response = ($bp_id > 0 && $bp_id != FALSE) ? $bookObj->insert_booking_rate(
            !empty($adult[$i]) ? $adult[$i] : 0,
            !empty($child[$i]) ? $child[$i] : 0,
            !empty($infant[$i]) ? $infant[$i] : 0,
            !empty($foc[$i]) ? $foc[$i] : 0,
            !empty($rates_adult[$i]) ? preg_replace('(,)', '', $rates_adult[$i]) : 0,
            !empty($rates_child[$i]) ? preg_replace('(,)', '', $rates_child[$i]) : 0,
            !empty($rates_infant[$i]) ? preg_replace('(,)', '', $rates_infant[$i]) : 0,
            !empty($rates_private[$i]) ? preg_replace('(,)', '', $rates_private[$i]) : 0,
            $category_id[$i],
            $bp_id,
            !empty($prodrid[$i]) ? $prodrid[$i] : 0,
        ) : $response; // insert booking products rate
    }

    $response = ($response > 0 && $response != false) ? $bookObj->insert_booking_transfer(
        $start_pickup,
        $end_pickup,
        $hotel_outside,
        (!empty($dropoff_outside)) ? $dropoff_outside : $hotel_outside,
        $room_no,
        '',
        $zone_pickup,
        ($zone_dropoff > 0) ? $zone_dropoff : $zone_pickup,
        ($hotel_pickup != 'outside') ? $hotel_pickup : 0,
        ($hotel_dropoff != 'outside') ? $hotel_dropoff : 0,
        $transfer_type,
        $pickup_type,
        $bp_id
    ) : $response; // insert booking transfer

    $response = ($response > 0 && $response != false) && (!empty($cus_name)) ? $bookObj->insert_customer($cus_name, '0000-00-00', '', !empty($_POST['telephone']) ? $_POST['telephone'] : '', $address = '', 1, 0, $email = '', 1, $bo_id, 0) : $response;

    $response = $bookObj->insert_booking_paid($cot, $cot > 0 ? 4 : 2, $bo_id); // insert booking payment (paid)

    if (!empty($extra_charge) && count($extra_charge) > 0 && $bo_id > 0) {
        for ($i = 0; $i < count($extra_charge); $i++) {
            $extra = !empty($extra_charge[$i]['extra_charge']) ? $extra_charge[$i]['extra_charge'] : 0;
            $extra_name = !empty($extra_charge[$i]['extc_name']) ? $extra_charge[$i]['extc_name'] : '';
            $extra_type = !empty($extra_charge[$i]['extra_type']) ? $extra_charge[$i]['extra_type'] : 0;
            $extra_adult = !empty($extra_charge[$i]['extra_adult']) ? $extra_charge[$i]['extra_adult'] : 0;
            $extra_rate_adult = !empty($extra_charge[$i]['extra_rate_adult']) ? preg_replace('(,)', '', $extra_charge[$i]['extra_rate_adult']) : 0;
            $extra_child = !empty($extra_charge[$i]['extra_child']) ? $extra_charge[$i]['extra_child'] : 0;
            $extra_rate_child = !empty($extra_charge[$i]['extra_rate_child']) ? preg_replace('(,)', '', $extra_charge[$i]['extra_rate_child']) : 0;
            $extra_infant = !empty($extra_charge[$i]['extra_infant']) ? $extra_charge[$i]['extra_infant'] : 0;
            $extra_rate_infant = !empty($extra_charge[$i]['extra_rate_infant']) ? preg_replace('(,)', '', $extra_charge[$i]['extra_rate_infant']) : 0;
            $extra_num_private = !empty($extra_charge[$i]['extra_num_private']) ? $extra_charge[$i]['extra_num_private'] : 0;
            $extra_rate_private = !empty($extra_charge[$i]['extra_rate_private']) ? preg_replace('(,)', '', $extra_charge[$i]['extra_rate_private']) : 0;

            $response = ($response > 0 && $response != false) && ($extra > 0 || !empty($extra_name)) && ($extra_type > 0) ? $bookObj->insert_booking_extra($bo_id, $extra, $extra_name, $extra_type, $extra_adult, $extra_rate_adult, $extra_child, $extra_rate_child, $extra_infant, $extra_rate_infant, $extra_num_private, $extra_rate_private) : $response;
        }
    }

    echo $response != FALSE && $response > 0 ? $bo_id : FALSE;
} else {
    echo $response = FALSE;
}
