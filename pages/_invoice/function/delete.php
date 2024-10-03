<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Invoice.php';

$invObj = new Invoice();

if (isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['inv_id'])) {
    // get value from ajax
    $cover_id = !empty($_POST['cover_id']) ? $_POST['cover_id'] : 0;
    $inv_id = !empty($_POST['inv_id']) ? $cover_id > 0 ? json_decode($_POST['inv_id']) : $_POST['inv_id'] : 0;
    $bo_id = !empty($_POST['bo_id']) ? $cover_id > 0 ? json_decode($_POST['bo_id']) : $_POST['bo_id'] : 0;

    if ($cover_id > 0) {
        $response = $invObj->delete_data_cover($cover_id);
        for ($i=0; $i < count($inv_id); $i++) { 
            $response = $invObj->delete_data($inv_id[$i]);
            $response = $invObj->delete_booking_paid($bo_id[$i]);
        }
    } else {
        $response = $invObj->delete_data($inv_id);
        $response = $invObj->delete_booking_paid($bo_id);
    }

    echo $response;
} else {
    echo $response = false;
}
