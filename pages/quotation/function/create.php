<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Quotation.php';

$quotObj = new Quotation();

function setNumberLength($num, $length)
{
    $sumstr = strlen($num);
    $zero = str_repeat("0", $length - $sumstr);
    $results = $zero . $num;

    return $results;
}

if (isset($_POST['action']) && $_POST['action'] == "create") {
    // get value from ajax
    $cus_name = $_POST['cus_name'] != "" ? $_POST['cus_name'] : '';
    $title = $_POST['title'] ? $_POST['title'] : 0;
    $bank_id = $_POST['bank_id'] ? $_POST['bank_id'] : 0;
    $name = $_POST['name'] != "" ? $_POST['name'] : '';
    $date_quo = $_POST['date_quo'] != "" ? $_POST['date_quo'] : '0000-00-00';
    $seller = $_POST['seller'] != "" ? $_POST['seller'] : '';
    $details = !empty($_POST['group-a']) ? $_POST['group-a'] : [];

     # --- invoice no full --- #
    $qt['no'] = '';
    $qt['full'] = '';
    $quo_no = $quotObj->checkinvno();
    $no = !empty($quo_no['max_quo_no']) ? $quo_no['max_quo_no'] + 1 : 1;
    $qt['full'] = 'QT-' . setNumberLength($no, 7);
    $qt['no'] = $no;

    $quotation_id = $quotObj->insert_data($qt['no'], $qt['full'], $title, $name, $date_quo, $seller, $cus_name, $bank_id);

    for ($i = 0; $i < count($details); $i++) {
        if ($quotation_id > 0) {
            $response = $quotObj->insert_quotation_detail(
                $details[$i]['name'], 
                $details[$i]['detail'], 
                $details[$i]['qty'], 
                !empty($details[$i]['cost']) ? preg_replace('(,)', '', $details[$i]['cost']) : 0, 
                !empty($details[$i]['discount']) ? preg_replace('(,)', '', $details[$i]['discount']) : 0,
                $quotation_id);
        }
    }

    echo $quotation_id;
} else {
    echo $response = false;
}
