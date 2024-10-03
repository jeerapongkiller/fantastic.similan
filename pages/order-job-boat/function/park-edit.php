<?php
include_once('../../../config/env.php');
include_once('../../../controllers/Order.php');

$orderObj = new Order();

if (isset($_POST['action_park']) && $_POST['action_park'] == "edit" && !empty($_POST['orboat_park_id'])) {
    // get value from ajax
    $response = FALSE;
    $orboat_park_id = !empty($_POST['orboat_park_id']) ? $_POST['orboat_park_id'] : 0;
    $park_id = !empty($_POST['parks']) ? $_POST['parks'] : 0;
    $adult_eng = !empty($_POST['rate_adult_eng']) ? preg_replace('(,)', '', $_POST['rate_adult_eng']) : 0;
    $child_eng = !empty($_POST['rate_child_eng']) ? preg_replace('(,)', '', $_POST['rate_child_eng']) : 0;
    $adult_th = !empty($_POST['rate_adult_th']) ? preg_replace('(,)', '', $_POST['rate_adult_th']) : 0;
    $child_th = !empty($_POST['rate_child_th']) ? preg_replace('(,)', '', $_POST['rate_child_th']) : 0;
    $total = !empty($_POST['total_park']) ? preg_replace('(,)', '', $_POST['total_park']) : 0;
    $note = !empty($_POST['note']) ? $_POST['note'] : '';

    $response = $orderObj->update_order_park($adult_eng, $child_eng, $adult_th, $child_th, $total, $note, $park_id, $orboat_park_id);

    echo $response != false && $response > 0 ? true : false;
} else {
    echo $response = FALSE;
}

// echo '# --- order park --- #';
// echo '</br>orboat_park_id : ' . $orboat_park_id;
// echo '</br>park_id : ' . $park_id;
// echo '</br>adult_eng : ' . $adult_eng;
// echo '</br>child_eng : ' . $child_eng;
// echo '</br>adult_th : ' . $adult_th;
// echo '</br>child_th : ' . $child_th;
// echo '</br>total : ' . $total;
// echo '</br>note : ' . $note;