<?php
include_once('../../../config/env.php');
include_once('../../../controllers/Booking.php');

$bookObj = new Booking();

if (isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['bo_id']) && $_POST['bo_id'] > 0) {
    // get value from ajax
    $bo_id = $_POST['bo_id'] > 0 ? $_POST['bo_id'] : 0;

    $response = $bookObj->delete_data($bo_id);

    $response = ($bo_id != FALSE && $bo_id > 0) ? $bookObj->insert_log('Delete Booking', '', $bo_id, 3, date("Y-m-d H:i:s")) : FALSE; // insert log booking

    echo $response;
} else {
    echo $response = false;
}
