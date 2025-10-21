<?php
include_once('../../../config/env.php');
include_once('../../../controllers/Booking.php');

$bookObj = new Booking();

if (isset($_POST['action']) && $_POST['action'] == "edit" && isset($_POST['bo_id']) && $_POST['bo_id'] > 0) {
    # --- get value booking form --- #
    $bo_id = !empty($_POST['bo_id']) ? $_POST['bo_id'] : 0;
    $book_status = !empty($_POST['book_status']) ? $_POST['book_status'] : 0;
    $book_type = !empty($_POST['booking_type_id']) ? $_POST['booking_type_id'] : 0;
    $agent = !empty($_POST['agent']) ? $_POST['agent'] : 0;
    $voucher_no = !empty($_POST['voucher_no_agent']) ? $_POST['voucher_no_agent'] : '';
    $sender = !empty($_POST['sender']) ? $_POST['sender'] : '';
    $cot_id = !empty($_POST['cot_id']) ? $_POST['cot_id'] : 0;
    $cot = !empty($_POST['cot']) ? preg_replace('(,)', '', $_POST['cot']) : 0;
    $discount = !empty($_POST['discount']) ? preg_replace('(,)', '', $_POST['discount']) : 0;
    $confirm_id = !empty($_POST['confirm_id']) ? $_POST['confirm_id'] : 0;
    # --- get value booking management form --- #
    $mange_transfer_id = !empty($_POST['mange_transfer_id']) ? $_POST['mange_transfer_id'] : 0;
    $mange_transfer = !empty($_POST['mange_transfer']) ? $_POST['mange_transfer'] : 0;
    $mange_boat_id = !empty($_POST['mange_boat_id']) ? $_POST['mange_boat_id'] : 0;
    $mange_boat = !empty($_POST['mange_boat']) ? $_POST['mange_boat'] : 0;
    # --- get value booking product form --- #
    $bp_id = !empty($_POST['bp_id']) ? $_POST['bp_id'] : 0;
    $travel_date = !empty($_POST['travel_date']) ? $_POST['travel_date'] : '0000-00-00';
    $before_travel = !empty($_POST['travel']) ? $_POST['travel'] : '0000-00-00';
    $product_id = !empty($_POST['product_id']) ? $_POST['product_id'] : 0;
    $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : 0;
    $adult = !empty($_POST['adult']) ? $_POST['adult'] : [];
    $child = !empty($_POST['child']) ? $_POST['child'] : [];
    $infant = !empty($_POST['infant']) ? $_POST['infant'] : [];
    $foc = !empty($_POST['foc']) ? $_POST['foc'] : [];
    $bp_note = !empty($_POST['bp_note']) ? $_POST['bp_note'] : '';
    # --- get value booking product rate form --- #
    $before_bpr = !empty($_POST['before_bpr']) ? $_POST['before_bpr'] : [];
    $bpr_id = !empty($_POST['bpr_id']) ? $_POST['bpr_id'] : [];
    $prodrid = !empty($_POST['prodrid']) ? $_POST['prodrid'] : [];
    $rates_adult = !empty($_POST['rates_adult']) && $book_type == 1 ? $_POST['rates_adult'] : [];
    $rates_child = !empty($_POST['rates_child']) && $book_type == 1 ? $_POST['rates_child'] : [];
    $rates_infant = !empty($_POST['rates_infant']) && $book_type == 1 ? $_POST['rates_infant'] : [];
    $rates_private = !empty($_POST['rates_private']) && $book_type == 2 ? $_POST['rates_private'] : [];
    # --- get value customer form --- #
    $cus_id = !empty($_POST['cus_id']) ? $_POST['cus_id'] : 0;
    $cus_name = !empty($_POST['cus_name']) ? $_POST['cus_name'] : '';
    $cus_telephone = !empty($_POST['cus_telephone']) ? $_POST['cus_telephone'] : '';
    # --- get value Transfer from --- #
    $bt_id = !empty($_POST['bt_id']) ? $_POST['bt_id'] : 0;
    $pickup_type = !empty($_POST['pickup_type']) ? $_POST['pickup_type'] : 0;
    $transfer_type = !empty($_POST['transfer_type']) ? $_POST['transfer_type'] : 0;
    // $bt_adult = !empty($_POST['bt_adult']) ? $_POST['bt_adult'] : 0;
    // $bt_child = !empty($_POST['bt_child']) ? $_POST['bt_child'] : 0;
    // $bt_infant = !empty($_POST['bt_infant']) ? $_POST['bt_infant'] : 0;
    // $bt_foc = !empty($_POST['bt_foc']) ? $_POST['bt_foc'] : 0;
    $start_pickup = !empty($_POST['start_pickup']) ? $_POST['start_pickup'] : '00:00:00';
    $end_pickup = !empty($_POST['end_pickup']) ? $_POST['end_pickup'] : '00:00:00';
    $hotel_pickup = !empty($_POST['hotel_pickup']) ? $_POST['hotel_pickup'] : 0;
    $pickup = !empty($_POST['pickup']) ? $_POST['pickup'] : 0;
    $hotel_pickup_outside = !empty($_POST['hotel_pickup_outside']) ? $_POST['hotel_pickup_outside'] : '';
    $hotel_dropoff = empty($_POST['hotel_dropoff']) ? !empty($_POST['hotel_pickup']) ? $_POST['hotel_pickup'] : 0 : $_POST['hotel_dropoff'];
    $dropoff = empty($_POST['dropoff']) ? !empty($_POST['pickup']) ? $_POST['pickup'] : 0 : $_POST['dropoff'];
    $hotel_dropoff_outside = empty($_POST['hotel_dropoff_outside']) ? !empty($_POST['hotel_pickup_outside']) ? $_POST['hotel_pickup_outside'] : '' : $_POST['hotel_dropoff_outside'];
    $room_no = !empty($_POST['room_no']) ? $_POST['room_no'] : '';
    $trans_note = !empty($_POST['trans_note']) ? $_POST['trans_note'] : '';
    # --- get value extra chang from --- #
    $extra_charge = !empty($_POST['extra-charge']) ? $_POST['extra-charge'] : '';
    $bec_id = array();
    if ($extra_charge) {
        for ($i = 0; $i < count($extra_charge); $i++) {
            $bec_id[] = !empty($extra_charge[$i]['bec_id']) ? $extra_charge[$i]['bec_id'] : 0;
        }
    }
    $before_bec_id = !empty($_POST['before_bec_id']) ? $_POST['before_bec_id'] : '';

    $agent_id = ($agent == 'outside' && !empty($_POST['agent_outside'])) ? $bookObj->insert_agent($_POST['agent_outside']) : $agent;

    $response = $bookObj->update_data($bo_id, $book_status, $voucher_no, $sender, $agent_id, $book_type, $discount); // update data booking

    $response = ($response > 0 && $response != false) ? $bookObj->update_booking_product($bp_id, $travel_date, $bp_note, $product_id) : $response; // update data booking product

    $response = ($bo_id != FALSE && $bo_id > 0) ? $bookObj->insert_log('แก้ใข Booking', '', $bo_id, 2, date("Y-m-d H:i:s")) : FALSE; // insert log booking

    for ($i = 0; $i < count($before_bpr); $i++) {
        if (in_array($before_bpr[$i], $bpr_id) == false) {
            $response = $bookObj->delete_booking_rate($before_bpr[$i]);
        }
    }

    for ($i = 0; $i < count($bpr_id); $i++) {
        if (in_array($bpr_id[$i], $before_bpr) == true && $bpr_id[$i] > 0) {
            $response = $bookObj->update_booking_rate(
                $bpr_id[$i],
                !empty($adult[$i]) ? $adult[$i] : 0,
                !empty($child[$i]) ? $child[$i] : 0,
                !empty($infant[$i]) ? $infant[$i] : 0,
                !empty($foc[$i]) ? $foc[$i] : 0,
                !empty($rates_adult[$i]) ? preg_replace('(,)', '', $rates_adult[$i]) : 0,
                !empty($rates_child[$i]) ? preg_replace('(,)', '', $rates_child[$i]) : 0,
                !empty($rates_infant[$i]) ? preg_replace('(,)', '', $rates_infant[$i]) : 0,
                !empty($rates_private[$i]) ? preg_replace('(,)', '', $rates_private[$i]) : 0,
                $category_id[$i],
                !empty($prodrid[$i]) ? $prodrid[$i] : 0,
            );
        } else {
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
            ) : $response;
        }
    }

    if ($cus_id > 0) {
        $response = ($response > 0 && $response != false) ? $bookObj->update_customer($cus_id, $cus_name, '0000-00-00', '', $cus_telephone, 1, 1, 0) : false; // update data customers
    } elseif ($cus_id == 0 && (!empty($cus_name))) {
        $response = ($response > 0 && $response != false) ? $bookObj->insert_customer($cus_name, '0000-00-00', '', $cus_telephone, $address = '', 1, 0, $email = '', 1, $bo_id, 0) : $response;
    }

    if ($bt_id > 0) {
        $response = ($response != false && $response > 0) ? $bookObj->update_booking_transfer($bt_id, $start_pickup, $end_pickup, $hotel_pickup_outside, empty($hotel_dropoff_outside) ? $hotel_pickup_outside : $hotel_dropoff_outside, $room_no, $trans_note, $pickup, $dropoff, $hotel_pickup, !empty($hotel_dropoff) ? $hotel_dropoff : $hotel_pickup, $transfer_type, $pickup_type) : false; // update booking transfer
    } elseif ($bt_id == 0) {
        $response = ($response > 0 && $response != false) ? $bookObj->insert_booking_transfer($start_pickup, $end_pickup, $hotel_pickup_outside, empty($hotel_dropoff_outside) ? $hotel_pickup_outside : $hotel_dropoff_outside, $room_no, $trans_note, $pickup, $dropoff, $hotel_pickup, !empty($hotel_dropoff) ? $hotel_dropoff : $hotel_pickup, $transfer_type, $pickup_type, $bp_id) : false; // insert booking transfer
    }

    if ($cot_id == 0 && $cot > 0) {
        $response = ($response > 0 && $response != false) ? $bookObj->insert_booking_paid($cot, $cot > 0 ? 4 : 2, $bo_id) : $response; // insert booking payment (paid)
    } else {
        $response = ($response > 0 && $response != false) ? $bookObj->update_booking_paid($cot_id, $cot, $cot > 0 ? 4 : 2) : $response; // update booking extra charge
    }

    if ($before_bec_id) {
        for ($i = 0; $i < count($before_bec_id); $i++) {
            if (!in_array($before_bec_id[$i], $bec_id)) {
                $response = ($response > 0 && $response != false) ? $bookObj->delete_booking_extra($before_bec_id[$i]) : false; // delete data customers 
            }
        }
    }

    if ($extra_charge) {
        for ($i = 0; $i < count($extra_charge); $i++) {
            $bec_id = !empty($extra_charge[$i]['bec_id']) ? $extra_charge[$i]['bec_id'] : 0;
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

            if ($extra_charge[$i]['bec_id'] == '') {
                $response = ($response > 0 && $response != false) && ($extra > 0 || !empty($extra_name)) && ($extra_type > 0) ? $bookObj->insert_booking_extra($bo_id, $extra, $extra_name, $extra_type, $extra_adult, $extra_rate_adult, $extra_child, $extra_rate_child, $extra_infant, $extra_rate_infant, $extra_num_private, $extra_rate_private) : $response; // insert booking extra charge
            } else {
                $response = ($response > 0 && $response != false) ? $bookObj->update_booking_extra($bec_id, $extra, $extra_name, $extra_type, $extra_adult, $extra_rate_adult, $extra_child, $extra_rate_child, $extra_infant, $extra_rate_infant, $extra_num_private, $extra_rate_private) : $response; // update booking extra charge
            }
        }
    }

    if ($travel_date != $before_travel) {
        $response = $bookObj->delete_booking_manage_transfer($mange_transfer, $bt_id, $mange_transfer_id);
        $response = $bookObj->delete_booking_manage_boat($mange_boat, $bo_id, $mange_boat_id);
    }

    $response = ($confirm_id > 0) ? $bookObj->delete_confirm($confirm_id) : $response;

    echo $response != FALSE && $response > 0 ? $response : FALSE;
} else {
    echo $response = FALSE;
}
