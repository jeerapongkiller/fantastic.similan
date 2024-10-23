<?php
include_once('../../../config/env.php');
include_once('../../../controllers/Booking.php');

$bookObj = new Booking();

if (isset($_POST['action']) && $_POST['action'] == "search" && !empty($_POST['category_id'])) {
    # --- get value --- #
    $agent_id = !empty($_POST['agent_id']) && $_POST['agent_id'] != 'outside' ? $_POST['agent_id'] : 0;
    $product_id = !empty($_POST['product_id']) ? $_POST['product_id'] : 0;
    $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : 0;
    $travel_date = !empty($_POST['travel_date']) ? $_POST['travel_date'] : '0000-00-00';
    $rate_arr = array();
    $rates = $bookObj->show_category_rate($agent_id, $category_id, $travel_date);
    if (!empty($rates)) {
        $rate_arr['periodid'] = !empty($rates[0]['periodid']) ? $rates[0]['periodid'] : 0;
        $rate_arr['prodrid'] = !empty($rates[0]['prodrid']) ? $rates[0]['prodrid'] : 0;
        $rate_arr['transfer'] = !empty($rates[0]['transfer']) ? $rates[0]['transfer'] : 1;
        $rate_arr['rate_adult'] = !empty($rates[0]['rate_adult']) ? $rates[0]['rate_adult'] : 0;
        $rate_arr['rate_child'] = !empty($rates[0]['rate_child']) ? $rates[0]['rate_child'] : 0;
        $rate_arr['rate_infant'] = !empty($rates[0]['rate_infant']) ? $rates[0]['rate_infant'] : 0;
        $rate_arr['rate_private'] = !empty($rates[0]['rate_private']) ? $rates[0]['rate_private'] : 0;
    } else {
        switch ($product_id) {
            case 12:
                $rate_arr['periodid'] = 0;
                $rate_arr['prodrid'] = 0;
                $rate_arr['transfer'] = 1;
                $rate_arr['rate_adult'] = ($category_id == 14) ? 1700 : 2100;
                $rate_arr['rate_child'] = ($category_id == 14) ? 1200 : 1500;
                $rate_arr['rate_infant'] = 0;
                $rate_arr['rate_private'] = 0;
                break;
            case 13:
                $rate_arr['periodid'] = 0;
                $rate_arr['prodrid'] = 0;
                $rate_arr['transfer'] = 1;
                $rate_arr['rate_adult'] = ($category_id == 14) ? 1600 : 2000;
                $rate_arr['rate_child'] = ($category_id == 14) ? 1100 : 1400;
                $rate_arr['rate_infant'] = 0;
                $rate_arr['rate_private'] = 0;
                break;
            case 14:
                $rate_arr['periodid'] = 0;
                $rate_arr['prodrid'] = 0;
                $rate_arr['transfer'] = 1;
                $rate_arr['rate_adult'] = ($category_id == 14) ? 2500 : 3000;
                $rate_arr['rate_child'] = ($category_id == 14) ? 1700 : 2700;
                $rate_arr['rate_infant'] = 0;
                $rate_arr['rate_private'] = 0;
                break;
            case 15:
                $rate_arr['periodid'] = 0;
                $rate_arr['prodrid'] = 0;
                $rate_arr['transfer'] = 1;
                $rate_arr['rate_adult'] = ($category_id == 14) ? 1500 : 1900;
                $rate_arr['rate_child'] = ($category_id == 14) ? 1000 : 1300;
                $rate_arr['rate_infant'] = 0;
                $rate_arr['rate_private'] = 0;
                break;
        }
    }

    echo !empty($rate_arr) ? json_encode($rate_arr) : false;
} else {
    echo $response = false;
}
