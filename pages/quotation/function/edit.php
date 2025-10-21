<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Quotation.php';

$quotObj = new Quotation();

if (isset($_POST['action']) && $_POST['action'] == "edit" && isset($_POST['quotation_id'])) {
    // get value from ajax
    $quotation_id = $_POST['quotation_id'];
    $cus_name = $_POST['cus_name'] != "" ? $_POST['cus_name'] : '';
    $title = $_POST['title'] ? $_POST['title'] : 0;
    $bank_id = $_POST['bank_id'] ? $_POST['bank_id'] : 0;
    $name = $_POST['name'] != "" ? $_POST['name'] : '';
    $date_quo = $_POST['date_quo'] != "" ? $_POST['date_quo'] : '0000-00-00';
    $seller = $_POST['seller'] != "" ? $_POST['seller'] : '';
    $before = !empty($_POST['before_detail']) ? $_POST['before_detail'] : [];
    $details = !empty($_POST['group-a']) ? $_POST['group-a'] : [];
    $after = array();

    $response = $quotObj->update_data($title, $name, $date_quo, $seller, $cus_name, $bank_id, $quotation_id);

    foreach ($details as $detail) {
        $after[] = $detail['detail_id'];
        if (in_array($detail['detail_id'], $before) == false) {
            $response = $quotObj->insert_quotation_detail(
                $detail['name'],
                $detail['detail'],
                $detail['qty'],
                !empty($detail['cost']) ? preg_replace('(,)', '', $detail['cost']) : 0,
                !empty($detail['discount']) ?preg_replace('(,)', '', $detail['discount']) : 0,
                $quotation_id
            );
        } elseif (in_array($detail['detail_id'], $before) == true) {
            $response = $quotObj->update_quotation_detail(
                $detail['name'], 
                $detail['detail'],
                $detail['qty'],
                !empty($detail['cost']) ?preg_replace('(,)', '', $detail['cost']) : 0,
                !empty($detail['discount']) ?preg_replace('(,)', '', $detail['discount']) : 0,
                $detail['detail_id']
            );
        }
    }

    for ($i = 0; $i < count($before); $i++) {
        if (in_array($before[$i], $after) == false) {
            $response = $quotObj->delete_quotation_detail($before[$i]);
        }
    }

    echo $response;
} else {
    echo $response = false;
}
