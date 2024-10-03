<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Driver.php';

$drvObj = new Driver();

if (isset($_POST['action']) && $_POST['action'] == "edit" && isset($_POST['driver_id']) && $_POST['driver_id'] > 0) {
    // get value from ajax
    $driver_id = $_POST['driver_id'] > 0 ? $_POST['driver_id'] : 0;
    $is_approved = !empty($_POST['is_approved']) ? $_POST['is_approved'] : 0;
    $id_card = $_POST['id_card'] != "" ? $_POST['id_card'] : '';
    $name = $_POST['name'] != "" ? $_POST['name'] : '';
    $birth_date = $_POST['birth_date'] != "" ? $_POST['birth_date'] : '0000-00-00';
    $gender = $_POST['gender'] != "" ? $_POST['gender'] : 0;
    $telephone = $_POST['telephone'] != "" ? $_POST['telephone'] : '';
    $issue_date = $_POST['issue_date'] != "" ? $_POST['issue_date'] : '0000-00-00';
    $expire_date = $_POST['expire_date'] != "" ? $_POST['expire_date'] : '0000-00-00';
    $address = $_POST['address'] != "" ? $_POST['address'] : '';

    // get details of the uploaded file
    $countfiles = count($_FILES['pic']['name']);
    $picArray = array();

    for ($i = 0; $i < $countfiles; $i++) {
        $picArray['fileTmpPath'][$i] = $_FILES['pic']['tmp_name'][$i];
        $picArray['fileName'][$i] = $_FILES['pic']['name'][$i];
        $picArray['fileSize'][$i] = $_FILES['pic']['size'][$i];
        $picArray['fileBefore'][$i] = !empty($_POST['before_pic'][$i]) ? $_POST['before_pic'][$i] : '';
        $picArray['fileDelete'][$i] = !empty($_POST['delete_pic'][$i]) && $_POST['delete_pic'][$i] == 1 ? $_POST['delete_pic'][$i] : 0;
        $picArray['fileDir'][$i] = '../../../storage/uploads/drivers/pic/';
    }

    // get details of the uploaded file
    $countfiles = count($_FILES['pic_dl']['name']);
    $pic_dlArray = array();

    for ($i = 0; $i < $countfiles; $i++) {
        $pic_dlArray['fileTmpPath'][$i] = $_FILES['pic_dl']['tmp_name'][$i];
        $pic_dlArray['fileName'][$i] = $_FILES['pic_dl']['name'][$i];
        $pic_dlArray['fileSize'][$i] = $_FILES['pic_dl']['size'][$i];
        $pic_dlArray['fileBefore'][$i] = !empty($_POST['before_pic_dl'][$i]) ? $_POST['before_pic_dl'][$i] : '';
        $pic_dlArray['fileDelete'][$i] = !empty($_POST['delete_pic_dl'][$i]) && $_POST['delete_pic_dl'][$i] == 1 ? $_POST['delete_pic_dl'][$i] : 0;
        $pic_dlArray['fileDir'][$i] = '../../../storage/uploads/drivers/pic-dl/';
    }

    if ($driver_id > 0) {
        $response = $drvObj->update_data($is_approved, $id_card, $name, $telephone, $address, $gender, $birth_date, $issue_date, $expire_date, $picArray, $pic_dlArray, $driver_id);
    }

    echo $response;
} else {
    echo $response = false;
}
