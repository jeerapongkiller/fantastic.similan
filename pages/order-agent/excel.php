<?php
require_once 'controllers/Order.php';

$bookingObj = new Order();
$today = date("Y-m-d");
$times = date("H:i:s");

require_once "app-assets/vendors/excel/Classes/PHPExcel.php"; //เรียกใช้ library สำหรับอ่านไฟล์ excel
$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);

if (isset($_GET['action']) && $_GET['action'] == "excel") {
    # --- get value --- #wwwwwwwwww
    $travel_date = !empty($_GET["travel_date"]) ? $_GET["travel_date"] : '0000-00-00';
    $date_form = substr($travel_date, 0, 10) != '' ? substr($travel_date, 0, 10) : '0000-00-00';
    $date_to = substr($travel_date, 14, 10) != '' ? substr($travel_date, 14, 10) : $date_form;
    $agent_id = $_GET['agent_id'] != "" ? $_GET['agent_id'] : 'all';

    $text_detail = '';
    $text_detail .= $date_form != '0000-00-00' ? $date_to != '0000-00-00' ? 'วันที่ ' . date('j F Y', strtotime($date_form)) . ' ถึง ' . date('j F Y', strtotime($date_to)) : 'วันที่ ' . date('j F Y', strtotime($date_form)) : '';
    $text_detail .= $agent_id != 'all' ? ' เอเยนต์ ' . $bookingObj->get_value('name', 'companies', $agent_id)['name'] : ' เอเยนต์ทั้งหมด';

    # --- get data --- #
    $first_booking = array();
    $first_ext = array();
    $bookings = $bookingObj->showlistboats('agent', $agent_id, $travel_date, 'all', 'all', 'all', 'all', 'all', '', '', '', '');
    if (!empty($bookings)) {
        foreach ($bookings as $booking) {
            # --- get value booking --- #
            if (in_array($booking['id'], $first_booking) == false) {
                $first_booking[] = $booking['id'];
                $bo_id[] = !empty($booking['id']) ? $booking['id'] : 0;
                $bo_travel_date[] = !empty($booking['travel_date']) ? $booking['travel_date'] : 0;
                $agent_name[] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
                $adult[] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                $child[] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                $infant[] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                $foc[] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
                // $rate_adult[] = !empty($booking['rate_adult']) ? $booking['rate_adult'] : 0;
                // $rate_child[] = !empty($booking['rate_child']) ? $booking['rate_child'] : 0;
                $cot[] = !empty($booking['total_paid']) ? $booking['total_paid'] : 0;
                $start_pickup[] = !empty($booking['start_pickup']) ? date('H:i', strtotime($booking['start_pickup'])) : '00:00:00';
                $end_pickup[] = !empty($booking['end_pickup']) ? date('H:i', strtotime($booking['end_pickup'])) : '00:00:00';
                // $car_name[] = !empty($booking['car_name']) ? $booking['car_name'] : '';
                $cus_name[] = !empty($booking['cus_name']) ? $booking['cus_name'] : '';
                $telephone[] = !empty($booking['telephone']) ? $booking['telephone'] : '';
                $book_full[] = !empty($booking['book_full']) ? $booking['book_full'] : '';
                $voucher_no[] = !empty($booking['voucher_no_agent']) ? $booking['voucher_no_agent'] : '';
                $pickup_type[] = !empty($booking['pickup_type']) ? $booking['pickup_type'] : 0;
                $room_no[] = !empty($booking['room_no']) ? $booking['room_no'] : '-';
                $hotel_pickup[] = !empty($booking['pickup_name']) ? $booking['pickup_name'] : $booking['outside'];
                $zone_pickup[] = !empty($booking['zonep_name']) ? ' (' . $booking['zonep_name'] . ')' : '';
                $hotel_dropoff[] = !empty($booking['dropoff_name']) ? $booking['dropoff_name'] : $booking['outside_dropoff'];
                $zone_dropoff[] = !empty($booking['zoned_name']) ? ' (' . $booking['zoned_name'] . ')' : '';
                $bp_note[] = !empty($booking['bp_note']) ? $booking['bp_note'] : '';
                $product_name[] = !empty($booking['product_name']) ? $booking['product_name'] : '';
                $total[] = $booking['booktye_id'] == 1 ? ($booking['bp_adult'] * $booking['rate_adult']) + ($booking['bp_child'] * $booking['rate_child']) + ($booking['rate_infant'] * $booking['rate_infant']) : $booking['rate_private'];
            }
            # --- get value booking extra chang --- #
            if ((in_array($booking['bec_id'], $first_ext) == false) && !empty($booking['bec_id'])) {
                $first_ext[] = $booking['bec_id'];
                $bec_id[$booking['id']][] = !empty($booking['bec_id']) ? $booking['bec_id'] : 0;
                $bec_name[$booking['id']][] = !empty($booking['bec_name']) ? $booking['bec_name'] : $booking['extra_name'];
                // $bec_type[$booking['id']][] = !empty($booking['bec_type']) ? $booking['bec_type'] : 0;
                // $bec_adult[$booking['id']][] = !empty($booking['bec_adult']) ? $booking['bec_adult'] : 0;
                // $bec_child[$booking['id']][] = !empty($booking['bec_child']) ? $booking['bec_child'] : 0;
                // $bec_infant[$booking['id']][] = !empty($booking['bec_infant']) ? $booking['bec_infant'] : 0;
                // $bec_privates[$booking['id']][] = !empty($booking['bec_privates']) ? $booking['bec_privates'] : 0;
                // $bec_rate_adult[$booking['id']][] = !empty($booking['bec_rate_adult']) ? $booking['bec_rate_adult'] : 0;
                // $bec_rate_child[$booking['id']][] = !empty($booking['bec_rate_child']) ? $booking['bec_rate_child'] : 0;
                // $bec_rate_infant[$booking['id']][] = !empty($booking['bec_rate_infant']) ? $booking['bec_rate_infant'] : 0;
                // $bec_rate_private[$booking['id']][] = !empty($booking['bec_rate_private']) ? $booking['bec_rate_private'] : 0;
                $bec_rate_total[$booking['id']][] = $booking['bec_type'] > 0 ? $booking['bec_type'] == 1 ? (($booking['bec_adult'] * $booking['bec_rate_adult']) + ($booking['bec_child'] * $booking['bec_rate_child']) + ($booking['bec_infant'] * $booking['bec_rate_infant'])) : ($booking['bec_privates'] * $booking['bec_rate_private']) : 0;
            }
        }
    }


    // $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Filter');
    // $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Agent');
    // $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Travel Date');

    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Re Confirm Agent');
    $objPHPExcel->getActiveSheet()->SetCellValue('A2', $agent_id != 'all' ? $bookingObj->get_value('name', 'companies', $agent_id)['name'] : 'all');
    $objPHPExcel->getActiveSheet()->SetCellValue('A3', date('j F Y', strtotime($date_form)) . ' - ' . date('j F Y', strtotime($date_to)));

    $columnName = [];
    $columnName = [];

    $columnName[] = ['Travel Date', 'Time', 'Programe', 'Name', 'V/C', 'Hotel', 'Room', 'A', 'C', 'INF', 'FOC', 'COT', 'Remark',];
    $total_tourist = 0;
    $total_adult = 0;
    $total_child = 0;
    $total_infant = 0;
    $total_foc = 0;
    if (!empty($bo_id)) {
        for ($i = 0; $i < count($bo_id); $i++) {
            $total_tourist = $total_tourist + $adult[$i] + $child[$i] + $infant[$i] + $foc[$i];
            $total_adult = $total_adult + $adult[$i];
            $total_child = $total_child + $child[$i];
            $total_infant = $total_infant + $infant[$i];
            $total_foc = $total_foc + $foc[$i];
            $hotel_name = '';
            if ($pickup_type[$i] == 1) {
                $hotel_name .= (!empty($hotel_pickup[$i])) ? 'Pickup : ' . $hotel_pickup[$i] . $zone_pickup[$i] : '';
                $hotel_name .= (!empty($hotel_dropoff[$i])) ? 'Dropoff : ' . $hotel_dropoff[$i] . $zone_dropoff[$i] : '';
            } else {
                $hotel_name .= 'เดินทางมาเอง';
            }
            $remark = '';
            if (!empty($bec_id[$bo_id[$i]])) {
                for ($e = 0; $e < count($bec_name[$bo_id[$i]]); $e++) {
                    $remark = $e == 0 ? $bec_name[$bo_id[$i]][$e] : ' : ' . $bec_name[$bo_id[$i]][$e];
                }
            }
            $remark .= !empty($bp_note[$i]) ? ' / ' . $bp_note[$i] : '';
            $columnName[] = [
                (!empty($bo_travel_date[$i])) ? date('j F Y', strtotime($bo_travel_date[$i])) : '',
                $start_pickup[$i] . ' - ' . $end_pickup[$i],
                $product_name[$i],
                !empty($telephone[$i]) ? $cus_name[$i] . ' <br>(' . $telephone[$i] . ')' : $cus_name[$i],
                !empty($voucher_no[$i]) ? $voucher_no[$i] : $book_full[$i],
                $hotel_name,
                $room_no[$i],
                $adult[$i] > 0 ? $adult[$i] : '0',
                $child[$i] > 0 ? $child[$i] : '0',
                $infant[$i] > 0 ? $infant[$i] : '0',
                $foc[$i] > 0 ? $foc[$i] : '0',
                !empty($cot[$i]) ? number_format($cot[$i]) : ''
            ];
        }
        $columnName[] = ['TOTAL', $total_tourist, 'Adult', $total_adult, 'Child', $total_child, 'Infant', $total_infant, 'FOC', $total_foc, '', '', '',];
    }

    $objPHPExcel->getActiveSheet()->getStyle('A4:R4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A4:R4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A4:R4')->getFont()->setSize(16);

    $objPHPExcel->getActiveSheet()->fromArray($columnName, null, 'A4');

    // ชื่อไฟล์
    $file_export = "Excel-" . date("dmY-Hs");

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $file_export . '.xlsx"');
    ob_end_clean();
    $objWriter->save('php://output');
    exit();
}
