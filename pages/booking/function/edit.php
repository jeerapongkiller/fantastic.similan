<?php
include_once('../../../config/env.php');
include_once('../../../controllers/Booking.php');

$bookObj = new Booking();

if (isset($_POST['action']) && $_POST['action'] == "edit" && isset($_POST['bo_id']) && $_POST['bo_id'] > 0) {
    # --- setting system --- #
    $open_rates = !empty('open_rates') ? $_POST['open_rates'] : 0;
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
    $adult = !empty($_POST['adult']) ? $_POST['adult'] : 0;
    $child = !empty($_POST['child']) ? $_POST['child'] : 0;
    $infant = !empty($_POST['infant']) ? $_POST['infant'] : 0;
    $foc = !empty($_POST['foc']) ? $_POST['foc'] : 0;
    $bp_note = !empty($_POST['bp_note']) ? $_POST['bp_note'] : '';
    # --- get value booking product rate form --- #
    $bpr_id = !empty($_POST['bpr_id']) ? $_POST['bpr_id'] : 0;
    $pror_id = !empty($_POST['pror_id']) ? $_POST['pror_id'] : 0;
    $rate_adult = !empty($_POST['rate_adult']) && $book_type == 1 ? preg_replace('(,)', '', $_POST['rate_adult']) : 0;
    $rate_child = !empty($_POST['rate_child']) && $book_type == 1 ? preg_replace('(,)', '', $_POST['rate_child']) : 0;
    $rate_infant = !empty($_POST['rate_infant']) && $book_type == 1 ? preg_replace('(,)', '', $_POST['rate_infant']) : 0;
    $rate_total = !empty($_POST['rate_total']) ? preg_replace('(,)', '', $_POST['rate_total']) : 0;
    # --- get value customer form --- #
    $cus_id = !empty($_POST['cus_id']) ? $_POST['cus_id'] : 0;
    $cus_name = !empty($_POST['cus_name']) ? $_POST['cus_name'] : '';
    $cus_telephone = !empty($_POST['cus_telephone']) ? $_POST['cus_telephone'] : '';
    # --- get value Transfer from --- #
    $bt_id = !empty($_POST['bt_id']) ? $_POST['bt_id'] : 0;
    $pickup_type = !empty($_POST['pickup_type']) ? $_POST['pickup_type'] : 0;
    $transfer_type = !empty($_POST['transfer_type']) ? $_POST['transfer_type'] : 0;
    $tran_adult = !empty($_POST['tran_adult_pax']) ? $_POST['tran_adult_pax'] : 0;
    $tran_child = !empty($_POST['tran_child_pax']) ? $_POST['tran_child_pax'] : 0;
    $tran_infant = !empty($_POST['tran_infant_pax']) ? $_POST['tran_infant_pax'] : 0;
    $tran_foc = !empty($_POST['tran_foc_pax']) ? $_POST['tran_foc_pax'] : 0;
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
    # --- get value rate transfer (Join) --- #
    $btr_id = !empty($_POST['btr_id']) ? $_POST['btr_id'] : 0;
    $tran_rate_adult = !empty($_POST['tran_adult']) && $transfer_type == 1 ? preg_replace('(,)', '', $_POST['tran_adult']) : 0;
    $tran_rate_child = !empty($_POST['tran_child']) && $transfer_type == 1 ? preg_replace('(,)', '', $_POST['tran_child']) : 0;
    $tran_rate_infant = !empty($_POST['tran_infant']) && $transfer_type == 1 ? preg_replace('(,)', '', $_POST['tran_infant']) : 0;
    $tran_total_price = !empty($_POST['tran_total_price']) ? preg_replace('(,)', '', $_POST['tran_total_price']) : 0;
    # --- get value rate transfer (Private) --- #
    $transfers = !empty($_POST['transfers']) ? $_POST['transfers'] : '';
    if ($transfers) {
        for ($i = 0; $i < count($transfers); $i++) {
            $array_btr_id[] = !empty($transfers[$i]['array_btr_id']) ? $transfers[$i]['array_btr_id'] : 0;
        }
    }
    $before_btr_id = !empty($_POST['before_btr_id']) ? $_POST['before_btr_id'] : '';
    # --- get value extra chang from --- #
    $extra_charge = !empty($_POST['extra-charge']) ? $_POST['extra-charge'] : '';
    $bec_id = array();
    if ($extra_charge) {
        for ($i = 0; $i < count($extra_charge); $i++) {
            $bec_id[] = !empty($extra_charge[$i]['bec_id']) ? $extra_charge[$i]['bec_id'] : 0;
        }
    }
    $before_bec_id = !empty($_POST['before_bec_id']) ? $_POST['before_bec_id'] : '';

    if ($agent == 'outside' && !empty($_POST['agent_outside'])) {
        $agent_out_id = $bookObj->insert_agent($_POST['agent_outside']);
    }

    $response = $bookObj->update_data($bo_id, $book_status, $voucher_no, $sender, ($agent == 'outside' && !empty($_POST['agent_outside'])) ? $agent_out_id : $agent, $book_type, $discount); // update data booking

    $response = ($response > 0 && $response != false) ? $bookObj->update_booking_product($bp_id, $travel_date, $adult, $child, $infant, $foc, $bp_note, $product_id, $category_id) : false; // update data booking product

    if ($bpr_id == 0) {
        $response = ($bp_id > 0 && $bp_id != FALSE) ? $bookObj->insert_booking_rate($rate_adult, $rate_child, $rate_infant, $rate_total, $bp_id, $pror_id) : false; // insert booking products rate
    } else {
        $response = ($response > 0 && $response != false) ? $bookObj->update_booking_rate($bpr_id, $rate_adult, $rate_child, $rate_infant, $rate_total, $pror_id) : false; // update data booking product rate
    }

    if ($cus_id > 0) {
        $response = ($response > 0 && $response != false) ? $bookObj->update_customer($cus_id, $cus_name, '0000-00-00', '', $cus_telephone, 1, 1, 0) : false; // update data customers
    } elseif ($cus_id == 0 && (!empty($cus_name))) {
        $response = ($response > 0 && $response != false) ? $bookObj->insert_customer($cus_name, '0000-00-00', '', $cus_telephone, $address = '', 1, 0, $email = '', 1, $bo_id, 0) : $response;
    }

    if ($bt_id > 0) {
        $response = ($response != false && $response > 0) ? $bookObj->update_booking_transfer($bt_id, $tran_adult, $tran_child, $tran_infant, $tran_foc, $start_pickup, $end_pickup, $hotel_pickup_outside, empty($hotel_dropoff_outside) ? $hotel_pickup_outside : $hotel_dropoff_outside, $room_no, $trans_note, $pickup, $dropoff, $hotel_pickup, !empty($hotel_dropoff) ? $hotel_dropoff : $hotel_pickup, $transfer_type, $pickup_type, $category_id) : false; // update booking transfer
    } elseif ($bt_id == 0) {
        $response = ($response > 0 && $response != false) ? $bookObj->insert_booking_transfer($tran_adult, $tran_child, $tran_infant, $tran_foc, $start_pickup, $end_pickup, $hotel_pickup_outside, empty($hotel_dropoff_outside) ? $hotel_pickup_outside : $hotel_dropoff_outside, $room_no, $trans_note, $pickup, $dropoff, $hotel_pickup, !empty($hotel_dropoff) ? $hotel_dropoff : $hotel_pickup, $transfer_type, $pickup_type, $bp_id, $category_id) : false; // insert booking transfer
    }

    if ($btr_id > 0) {
        $response = ($response != false && $response > 0) ? $bookObj->update_transfer_rate($btr_id, $tran_rate_adult, $tran_rate_child, $tran_rate_infant, $tran_total_price, $cars_category = 0) : false; // update data booking transfer rate (join)
    } elseif ($btr_id == 0) {
        $response = ($response > 0 && $response != false) ? $bookObj->insert_transfer_rate($tran_rate_adult, $tran_rate_child, $tran_rate_infant, $tran_total_price, $bt_id, $cars_category = 0) : false; // insert booking transfer rate (join)
    }

    if ($cot_id == 0 && $cot > 0) {
        $response = ($response > 0 && $response != false) ? $bookObj->insert_booking_paid($bo_id, '0000-00-00', $cot, '', '', 0, 0, 4, $fileArray = []) : $response; // insert booking payment (paid)
    } else {
        $response = ($response > 0 && $response != false) ? $bookObj->update_booking_paid($cot_id, '0000-00-00', $cot, '', '', 0, 0, $cot > 0 ? 4 : 2, $fileArray = []) : $response; // update booking extra charge
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

    $response = $bookObj->delete_confirm($confirm_id);

    echo $response != FALSE && $response > 0 ? $response : FALSE;
} else {
    echo $response = FALSE;
}
