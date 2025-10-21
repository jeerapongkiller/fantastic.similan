<?php
include_once('../../../config/env.php');
include_once('../../../controllers/Order.php');

$manageObj = new Order();

if (isset($_POST['action']) && $_POST['action'] == "create" && (!empty($_POST['manage_id']))) {
    $response = false;
    $manage_id = !empty($_POST['manage_id']) ? $_POST['manage_id'] : 0;
    $booking = !empty($_POST['booking_arr']) ? json_decode($_POST['booking_arr'], true) : '';
    $manage = !empty($_POST['manage_arr']) ? json_decode($_POST['manage_arr'], true) : '';
    $before = !empty($_POST['before_arr']) ? json_decode($_POST['before_arr'], true) : '';
    // $booking = !empty($_POST['booking']) ? $_POST['booking'] : '';
    // $manage_bo = !empty($_POST['manage_bo']) ? $_POST['manage_bo'] : '';
    // $before = !empty($_POST['before']) ? $_POST['before'] : '';
    // $data = json_decode($booking, true);
    // $bo_data = json_decode($manage_bo, true);
    // $before_data = json_decode($before, true);

    # --- insert booking manage --- #
    if ($manage_id != false && $manage_id > 0 && !empty($booking)) {
        for ($i = 0; $i < count($booking); $i++) {
            $response = $manageObj->insert_booking_manage_boat(0, $booking[$i], $manage_id);
        }
    }

    # --- update booking transfer manage --- #
    $arrange = 1;
    if ($manage_id != false && $manage_id > 0 && !empty($manage)) {
        for ($i = 0; $i < count($manage); $i++) {
            $response = $manageObj->update_booking_manage_boat($arrange, $manage[$i], $manage_id, 0);
            $arrange++;
        }
    }

    # --- delete booking manage --- #
    if ($manage_id != false && $manage_id > 0 && !empty($before)) {
        for ($i = 0; $i < count($before); $i++) {
            if ((in_array($before[$i], $manage) == false)) {
                echo 'Delete : ' . $before[$i];
                $response = $manageObj->delete_booking_manage_boat(0, $before[$i], $manage_id);
            }
        }
    }

    echo $response;
} else {
    echo $response = FALSE;
}
