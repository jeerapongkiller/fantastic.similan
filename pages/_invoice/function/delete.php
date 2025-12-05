<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Invoice.php';

$invObj = new Invoice();

if (isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['cover_id'])) {
    // get value from ajax
    $cover_id = !empty($_POST['cover_id']) ? $_POST['cover_id'] : 0;

    $bookings = $invObj->get_value(
        'bookings.id as id ',
        ' bookings LEFT JOIN invoices ON bookings.id = invoices.booking_id LEFT JOIN booking_paid ON bookings.id = booking_paid.booking_id ',
        ' invoices.cover_id = ' . $cover_id,
        1
    );

    $response = $invObj->delete_data_cover($cover_id);
    $response = $invObj->delete_data($cover_id);
    if (!empty($bookings)) {
        $array_booking = array();
        foreach ($bookings as $booking) {
            if (in_array($booking['id'], $array_booking) == false) {
                $array_booking[] = $booking['id'];
                $response = $invObj->delete_booking_paid($booking['id']);
            }
        }
    }

    echo $response;
} else {
    echo $response = false;
}
