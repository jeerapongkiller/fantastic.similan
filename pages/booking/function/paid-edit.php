<?php
include_once('../../../config/env.php');
include_once('../../../controllers/Booking.php');

$bookObj = new Booking();
$response = FALSE;

if (isset($_POST['paid_action']) && $_POST['paid_action'] == "edit" && isset($_POST['paid_id'])) {
    // get value from ajax
    $paid_id = !empty($_POST['paid_id']) ? $_POST['paid_id'] : 0;
    $paid_detail_id = !empty($_POST['paid_detail_id']) ? $_POST['paid_detail_id'] : 0;
    $book_payment = !empty($_POST['book_payment']) ? $_POST['book_payment'] : 0;
    $total_paid = !empty($_POST['total_paid']) ? preg_replace('(,)', '', $_POST['total_paid']) : 0;
    $paid_date = !empty($_POST['paid_date']) ? $_POST['paid_date'] : 0;
    $paid_time = !empty($_POST['paid_time']) ? $_POST['paid_time'] : 0;
    $payments_type = $_POST['payments_type'] != "" ? $_POST['payments_type'] : 0;
    $bank_account = !empty($_POST['bank_account']) ? $_POST['bank_account'] : 0;
    $card_no = !empty($_POST['card_no']) ? $_POST['card_no'] : 0;
    $note = $_POST['note'] != "" ? $_POST['note'] : '';
    $paymnet_detail = !empty($_POST['paymnet_detail']) ? 1 : 0;

    // get details of the uploaded file
    $countfiles = count($_FILES['photo']['name']);
    $fileArray = array();

    for ($i = 0; $i < $countfiles; $i++) {
        $fileArray['fileTmpPath'][$i] = $_FILES['photo']['tmp_name'][$i];
        $fileArray['fileName'][$i] = $_FILES['photo']['name'][$i];
        $fileArray['fileSize'][$i] = $_FILES['photo']['size'][$i];
        $fileArray['fileDir'][$i] = '../../../storage/uploads/booking/paid/';
        $fileArray['fileBefore'][$i] = $_POST['before_file'][$i];
        $fileArray['fileDelete'][$i] = 0;
    }

    $response = $bookObj->update_booking_paid($paid_id, $book_payment);
    if ($paymnet_detail > 0) {
        if ($paid_detail_id > 0) {
            $response = ($response != FALSE && $response > 0) ? $bookObj->update_booking_paid_detail($paid_detail_id, $paid_date, $paid_time, $total_paid, $card_no, $note, $payments_type, $bank_account, $fileArray) : FALSE;
        } else {
            $response = ($response != FALSE && $response > 0) ? $bookObj->insert_booking_paid_detail($paid_id, $paid_date, $paid_time, $total_paid, $card_no, $note, $payments_type, $bank_account, $fileArray) : FALSE;
        }
    } else {
        $response = ($response != FALSE && $response > 0) ? $bookObj->datete_booking_paid_detail($paid_detail_id) : FALSE;
    }

    echo ($response != FALSE && $response > 0) ? $response : FALSE;
} else {
    echo $response = FALSE;
}