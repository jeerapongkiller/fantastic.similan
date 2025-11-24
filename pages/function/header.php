<?php
require_once '../../config/env.php';
require_once '../../controllers/Header.php';

$headerObj = new Header();

if (isset($_POST['action']) && $_POST['action'] == "search") {
    // get value from ajax

    $bo_array = array();
    $value_arr = array();
    $boats = 0;
    $transfers = 0;
    $response = $headerObj->getvalue();
    foreach ($response as $value) {
        if (in_array($value['id'], $bo_array) == false) {
            $bo_array[] = $value['id'];
            # --- check booking order boat --- #
            if (empty($value['boat_id'])) { // if ($value['boats'] == 1 && empty($value['boat_id'])) {
                $boats++;
            }
            # --- check booking order boat --- #
            if ($value['transfers'] == 1 && $value['pickup_type'] == 1 && empty($value['transfer_id'])) {
                $transfers++;
            }
        }
    }

    $value_arr['boats'] = $boats;
    $value_arr['transfers'] = $transfers;

    echo json_encode($value_arr);
} else {
    echo $response = false;
}
