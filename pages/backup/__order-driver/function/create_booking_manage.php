<?php
include_once('../../../config/env.php');
include_once('../../../controllers/Order.php');

$manageObj = new Order();

if (isset($_POST['action']) && $_POST['action'] == "create" && (!empty($_POST['manage_id']))) {
    $response = false;
    $manage_id = !empty($_POST['manage_id']) ? $_POST['manage_id'] : 0;
    $bt_array = !empty($_POST['bt_array']) ? json_decode($_POST['bt_array'], true) : [];
    $tourist_array = !empty($_POST['tourist_array']) ? json_decode($_POST['tourist_array'], true) : [];
    $manage_array = !empty($_POST['manage_array']) ? json_decode($_POST['manage_array'], true) : [];
    $manage_tourist_array = !empty($_POST['manage_tourist_array']) ? json_decode($_POST['manage_tourist_array'], true) : [];
    $before_array = !empty($_POST['before_array']) ? json_decode($_POST['before_array'], true) : [];
    $after_array = !empty($_POST['after_array']) ? json_decode($_POST['after_array'], true) : [];

    # --- delete booking manage --- #
    if ($manage_id != false && $manage_id > 0) {
        if (!empty($before_array)) {
            for ($i = 0; $i < count($before_array); $i++) {
                if ((in_array($before_array[$i], $after_array) == false)) {
                    $response = $manageObj->delete_manage_booking($before_array[$i]);
                }
            }
        }
    }
    # --- update booking transfer manage --- #
    $arrange = 1;
    if ($manage_id != false && $manage_id > 0 && !empty($manage_array)) {
        for ($i = 0; $i < count($manage_array); $i++) {
            $response = $manageObj->update_manage_booking($arrange, $manage_tourist_array[$i], $manage_id, $before_array[$i]);
            $arrange++;
        }
    }
    # --- insert booking transfer manage --- #
    if ($manage_id != false && $manage_id > 0 && !empty($bt_array)) {
        for ($i = 0; $i < count($bt_array); $i++) {
            $response = $manageObj->insert_manage_booking($arrange, $tourist_array[$i], $manage_id, $bt_array[$i]);
            $arrange++;
        }
    }

    echo $response;
} else {
    echo $response = FALSE;
}
