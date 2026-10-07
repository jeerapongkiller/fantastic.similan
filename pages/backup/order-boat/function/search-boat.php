<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$manageObj = new Order();

if (isset($_POST['action']) && $_POST['action'] == "search" && (!empty($_POST['travel_date']) && $_POST['travel_date'] != '0000-00-00')) {
    // get value from ajax
    $response = array();
    $boat_id = $_POST['boat_id'] != "" ? $_POST['boat_id'] : 0;
    $travel_date = $_POST['travel_date'];
    
    $all_boat = $manageObj->get_values('boat_id', 'order_boat', 'travel_date = "' . $travel_date . '"', 1);
    foreach ($all_boat as $boats) {
        $arr_boats[] = $boats['boat_id'];
    }

    $boats = $manageObj->show_boats();
    foreach ($boats as $boat) {
        if (!empty($arr_boats) && in_array($boat['id'], $arr_boats) == false) {
            $response['id'][] = $boat['id'];
            $response['name'][] = $boat['name'];
            $response['refcode'][] = $boat['refcode'];
        } elseif (empty($arr_boats) || $boat_id == $boat['id']) {
            $response['id'][] = $boat['id'];
            $response['name'][] = $boat['name'];
            $response['refcode'][] = $boat['refcode'];
        }
    }

    echo !empty($response) ? json_encode($response) : false;
} else {
    echo $response = false;
}
