<?php
require_once 'controllers/Report.php';

$repObj = new Report();
$today = date("Y-m-d");
$times = date("H:i:s");

require_once "app-assets/vendors/excel/Classes/PHPExcel.php"; //เรียกใช้ library สำหรับอ่านไฟล์ excel
$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);

if (isset($_GET['action']) && $_GET['action'] == "print" && isset($_GET['type'])) {
    # --- get value --- #
    $search_status = !empty($_GET["search_status"]) ? $_GET["search_status"] : 'all';
    $search_payment = !empty($_GET["search_payment"]) ? $_GET["search_payment"] : 'all';
    $search_travel = !empty($_GET["search_travel"]) ? $_GET["search_travel"] : $today;
    $date_form = substr($search_travel, 0, 10) != '' ? substr($search_travel, 0, 10) : '0000-00-00';
    $date_to = substr($search_travel, 14, 10) != '' ? substr($search_travel, 14, 10) : $date_form;
    $search_agent = $_GET['search_agent'] != "" ? $_GET['search_agent'] : 'all';
    $search_product = $_GET['search_product'] != "" ? $_GET['search_product'] : 'all';

    $text_detail = '';
    $text_detail .= $date_form != '0000-00-00' ? $date_to != '0000-00-00' ? 'วันที่ ' . date('j F Y', strtotime($date_form)) . ' ถึง ' . date('j F Y', strtotime($date_to)) : 'วันที่ ' . date('j F Y', strtotime($date_form)) : '';
    $text_detail .= $search_agent != 'all' ? ' เอเยนต์ ' . $repObj->get_value('name', 'companies', $search_agent)['name'] : ' เอเยนต์ทั้งหมด';
    $text_detail .= $search_product != 'all' ? ' โปรแกรม ' . $repObj->get_value('name', 'products', $search_product)['name'] : ' โปรแกรมทั้งหมด';

    # --- get data --- #
    $count_boboat = 0;
    $count_bot = 0;
    $paid = 0;
    $not_issued = 0;
    $issued_inv = 0;
    $first_book = array();
    $first_agent = array();
    $first_bpr = array();
    $first_ortran = array();
    $first_bot = array();
    $first_boboat = array();
    $first_pay = array();
    $first_extar = array();
    $bookings = $repObj->showlist($search_status, $date_form, $date_to, $search_agent, $search_product, $search_payment);
    foreach ($bookings as $booking) {
        # --- get value booking --- #
        if (in_array($booking['id'], $first_book) == false) {
            $first_book[] = $booking['id'];
            # --- get value booking --- #
            $bo_id[] = !empty($booking['id']) ? $booking['id'] : 0;
            $status[] = $booking['booksta_name'];
            $rec_id[] = !empty($booking['rec_id']) ? $booking['rec_id'] : 0;
            $book_full[] = !empty($booking['book_full']) ? $booking['book_full'] : '';
            $voucher_no_agent[] = !empty($booking['voucher_no_agent']) ? $booking['voucher_no_agent'] : '';
            $inv_full[] = !empty($booking['inv_full']) ? $booking['inv_full'] : '';
            $travel_date[] = !empty($booking['travel_date']) ? $booking['travel_date'] : '0000-00-00';
            $bo_status[] = !empty($booking['booksta_id']) ? $booking['booksta_id'] : 0;
            $sender[] = !empty($booking['sender']) ? $booking['sender'] : '';
            # --- get value booking products --- #
            $discount[$booking['id']] = !empty($booking['discount']) ? $booking['discount'] : 0;
            $comp_discount[$booking['comp_id']][] = !empty($booking['discount']) ? $booking['discount'] : 0;
            # --- get value booking products --- #
            $hotel_pickup_name[] = !empty($booking['hotel_pickup_name']) ? $booking['hotel_pickup_name'] : $booking['hotel_pickup'];
            $hotel_dropoff_name[] = !empty($booking['hotel_dropoff_name']) ? $booking['hotel_dropoff_name'] : $booking['hotel_dropoff'];
            # --- get value customers --- #
            $cus_name[] = !empty($booking['cus_name']) && $booking['cus_head'] == 1 ? $booking['cus_name'] : '';
            # --- Agent --- #
            $comp_id[] = !empty($booking['comp_id']) ? $booking['comp_id'] : 0;
            $comp_name[] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
            # --- Programe --- #
            $prod_id[] = !empty($booking['product_id']) ? $booking['product_id'] : 0;
            $product_name[$booking['product_id']] = !empty($booking['product_name']) ? $booking['product_name'] : '';
            $category_name[$booking['product_id']] = !empty($booking['category_name']) ? $booking['category_name'] : '';
            # --- order boat --- #
            if (!empty(!empty($booking['orboat_id'])) && !empty($booking['orboat_id']) > 0) {
                $orboat_id[$booking['orboat_id']][] = !empty($booking['id']) ? $booking['id'] : 0;
                $orboat_travel[$booking['orboat_id']] = !empty($booking['orboat_travel']) ? $booking['orboat_travel'] : 0;
                # --- Boat --- #
                $boat_id[$booking['orboat_id']][] = !empty($booking['boat_id']) ? $booking['boat_id'] : 0;
                $boat_name[$booking['boat_id']] = !empty($booking['boat_name']) ? $booking['boat_name'] : '';
                $boat_order_id[$booking['orboat_id']][] = !empty($booking['boat_id']) ? $booking['boat_id'] : 0;
                $boat_product[$booking['orboat_id']] = !empty($booking['product_id']) ? $booking['product_id'] : 0;
            }
        }
        # --- get value booking product rates --- #
        if (in_array($booking['bpr_id'], $first_bpr) == false) {
            $first_bpr[] = $booking['bpr_id'];
            $adult[$booking['id']][] = !empty($booking['adult']) ? $booking['adult'] : 0;
            $child[$booking['id']][] = !empty($booking['child']) ? $booking['child'] : 0;
            $infant[$booking['id']][] = !empty($booking['infant']) ? $booking['infant'] : 0;
            $foc[$booking['id']][] = !empty($booking['foc']) ? $booking['foc'] : 0;
            $tourist_max[$booking['id']][] = $booking['adult'] + $booking['child'] + $booking['infant'] + $booking['foc'];

            $total = $booking['booking_type_id'] == 1 ? ($booking['adult'] * $booking['rates_adult']) + ($booking['child'] * $booking['rates_child']) : $booking['rates_private'];

            $array_total[] = $total;
            $array_amount[$booking['id']][] = $total;

            $comp_amount[$booking['comp_id']][] = $total;
            $comp_revenue[$booking['comp_id']][] = !empty($booking['rec_id']) ? $total : 0;
            $comp_adult[$booking['comp_id']][] = !empty($booking['adult']) ? $booking['adult'] : 0;
            $comp_child[$booking['comp_id']][] = !empty($booking['child']) ? $booking['child'] : 0;
            $comp_infant[$booking['comp_id']][] = !empty($booking['infant']) ? $booking['infant'] : 0;
            $comp_foc[$booking['comp_id']][] = !empty($booking['foc']) ? $booking['foc'] : 0;
            $comp_sum[$booking['comp_id']][] = $booking['adult'] + $booking['child'] + $booking['infant'] + $booking['foc'];

            $product_adult[$booking['product_id']][] = !empty($booking['adult']) ? $booking['adult'] : 0;
            $product_child[$booking['product_id']][] = !empty($booking['child']) ? $booking['child'] : 0;
            $product_infant[$booking['product_id']][] = !empty($booking['infant']) ? $booking['infant'] : 0;
            $product_foc[$booking['product_id']][] = !empty($booking['foc']) ? $booking['foc'] : 0;
        }
        # --- get value agent company --- #
        if (in_array($booking['comp_id'], $first_agent) == false) {
            $first_agent[] = $booking['comp_id'];
            $agent_id[] = !empty($booking['comp_id']) ? $booking['comp_id'] : 0;
            $agent_name[] = !empty($booking['comp_name']) ? $booking['comp_name'] : 'ไม่ได้ระบุ';
            $agent_logo[] = !empty($booking['comp_logo']) ? $booking['comp_logo'] : '';
        }
        # --- get value booking order transfer --- #
        if (in_array($booking['ortran_id'], $first_ortran) == false && !empty($booking['ortran_id'])) {
            $first_ortran[] = $booking['ortran_id'];
            $ortran_id[] = !empty($booking['ortran_id']) ? $booking['ortran_id'] : 0;
            $car_name[] = !empty($booking['car_name']) ? $booking['car_name'] : '';
            $car_registration[] = !empty($booking['license']) ? $booking['license'] : '';
            $driver_name[] = !empty($booking['driver_name']) ? $booking['driver_name'] : '';
            $ortran_travel[] = !empty($booking['ortran_travel']) ? $booking['ortran_travel'] : 0;

            $count_bot++;
        }
        if (in_array($booking['bot_id'], $first_bot) == false && !empty($booking['bot_id'])) {
            $first_bot[] = $booking['bot_id'];
            $bot_id[$booking['ortran_id']][] = !empty($booking['bot_id']) ? $booking['bot_id'] : 0;
            $tourist[$booking['ortran_id']][] = !empty($booking['tourist']) ? $booking['tourist'] : 0;
            $bot_bo[$booking['ortran_id']][] = $booking['id'];
        }
        # --- get value booking order boat --- #
        if (in_array($booking['boboat_id'], $first_boboat) == false && !empty($booking['orboat_id']) && !empty($booking['boboat_id'])) {
            $first_boboat[] = $booking['boboat_id'];
            $boboat_id[$booking['orboat_id']][] = $booking['boboat_id'];
            $count_boboat++;
        }
        # --- get value booking payment --- #
        if ((in_array($booking['bopa_id'], $first_pay) == false) && !empty($booking['bopa_id'])) {
            # --- in array get value booking payment --- #
            $first_pay[] = $booking['bopa_id'];
            $bopay_id[$booking['id']] = !empty($booking['bopay_id']) ? $booking['bopay_id'] : 0;
            $bopay_name_class[$booking['id']] = !empty($booking['bopay_name_class']) ? $booking['bopay_name_class'] : '';
            $bopay_paid_name[$booking['id']] = $booking['bopay_id'] == 4 || $booking['bopay_id'] == 5 ? $booking['bopay_name'] . '</br>(' . number_format($booking['total_paid']) . ')' : $booking['bopay_name'];

            $pay_id[$booking['id']][] = !empty($booking['bopay_id']) ? $booking['bopay_id'] : 0;
            $pay_name[$booking['id']][] = !empty($booking['bopay_name']) ? $booking['bopay_name'] : 0;
            $cot[$booking['id']][] = !empty($booking['total_paid']) ? $booking['total_paid'] : 0;
        }
        # --- get value booking --- #
        if (in_array($booking['bec_id'], $first_extar) == false && (!empty($booking['extra_id']) || !empty($booking['bec_name']))) {
            $first_extar[] = $booking['bec_id'];
            $ext_total = $booking['bec_type'] == 1 ? ($booking['bec_adult'] * $booking['bec_rate_adult']) + ($booking['bec_child'] * $booking['bec_rate_child']) : ($booking['bec_privates'] * $booking['bec_rate_private']);
            $extar_total[$booking['id']][] = $ext_total;
            $extar_total_agent[$booking['comp_id']][] = $ext_total;
        }
    }
    # ------ calculate booking paid ------ #
    if (!empty($bopay_id)) {
        foreach ($bopay_id as $x => $val) {
            # --- calculator booking --- #
            $total = !empty($array_amount[$x]) ? array_sum($array_amount[$x]) : 0; // booking
            $total -= !empty($discount[$x]) ? $discount[$x] : 0; // - discount
            $total += !empty($extar_total[$x]) ? array_sum($extar_total[$x]) : 0; // + extar

            $not_issued += (!empty($pay_id[$x]) && (in_array(6, $pay_id[$x]) == false) && (in_array(3, $pay_id[$x]) == false)) ?  $total : 0;
            $issued_inv += (!empty($pay_id[$x]) && (in_array(6, $pay_id[$x]) == true) && (in_array(3, $pay_id[$x]) == false)) ?  $total : 0;
            $paid += (!empty($pay_id[$x]) && (in_array(3, $pay_id[$x]) == true)) ? $total : 0;
        }
    }

    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Filter');
    $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Agent');
    $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Programe');
    $objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Travel Date');

    $objPHPExcel->getActiveSheet()->SetCellValue('B2', $search_agent != 'all' ? $repObj->get_value('name', 'companies', $search_agent)['name'] : 'all');
    $objPHPExcel->getActiveSheet()->SetCellValue('D2', $search_product != 'all' ? $repObj->get_value('name', 'products', $search_product)['name'] : 'all');
    $objPHPExcel->getActiveSheet()->SetCellValue('B3', $search_travel);

    $columnName = [];

    if ($_GET['type'] == 'booking') {
        $columnName[] = ['Status', 'Payment', 'Voucher No.', 'Agent', 'Travel Date', 'Programe', 'Pax', 'Hotel', 'Customer', 'Booker',];
        if (!empty($bo_id)) {
            for ($i = 0; $i < count($bo_id); $i++) {
                $columnName[] = [
                    $status[$i],
                    !empty($bopay_id[$bo_id[$i]]) ? $bopay_paid_name[$bo_id[$i]] : 'ไม่ได้ระบุ',
                    !empty($voucher_no_agent[$i]) ? $voucher_no_agent[$i] : $book_full[$i],
                    $comp_name[$i],
                    (!empty($travel_date[$i])) ? date('j F Y', strtotime($travel_date[$i])) : '',
                    $product_name[$prod_id[$i]],
                    !empty($tourist_max[$bo_id[$i]]) ? array_sum($tourist_max[$bo_id[$i]]) : 0,
                    $hotel_pickup_name[$i],
                    $cus_name[$i],
                    $sender[$i],
                ];
            }
        }
    } elseif ($_GET['type'] == 'agent') {
        $columnName[] = ['Agent', 'Booking', 'AD', 'CHD', 'INF', 'FOC', 'TOTAL', 'Amount', 'Income', 'Overdue',];
        if (!empty($agent_id)) {
            $amount_comp = 0;
            $revenue_comp = 0;
            for ($i = 0; $i < count($agent_id); $i++) {
                $amount_comp = !empty($comp_amount[$agent_id[$i]]) ? array_sum($comp_amount[$agent_id[$i]]) : 0;
                $amount_comp -= !empty($comp_discount[$agent_id[$i]]) ? array_sum($comp_discount[$agent_id[$i]]) : 0;
                $amount_comp += !empty($extar_total_agent[$agent_id[$i]]) ? array_sum($extar_total_agent[$agent_id[$i]]) : 0;

                $revenue_comp = !empty($comp_revenue[$agent_id[$i]]) ? array_sum($comp_revenue[$agent_id[$i]]) : 0;
                $revenue_comp -= (!empty($comp_discount[$agent_id[$i]]) && $revenue_comp > 0) ? array_sum($comp_discount[$agent_id[$i]]) : 0;
                $revenue_comp += (!empty($extar_total_agent[$agent_id[$i]]) && $revenue_comp > 0) ? array_sum($extar_total_agent[$agent_id[$i]]) : 0;

                $columnName[] = [
                    $agent_name[$i],
                    array_count_values($comp_id)[$agent_id[$i]],
                    !empty($comp_adult[$agent_id[$i]]) ? array_sum($comp_adult[$agent_id[$i]]) : 0,
                    !empty($comp_child[$agent_id[$i]]) ? array_sum($comp_child[$agent_id[$i]]) : 0,
                    !empty($comp_infant[$agent_id[$i]]) ? array_sum($comp_infant[$agent_id[$i]]) : 0,
                    !empty($comp_foc[$agent_id[$i]]) ? array_sum($comp_foc[$agent_id[$i]]) : 0,
                    !empty($comp_sum[$agent_id[$i]]) ? array_sum($comp_sum[$agent_id[$i]]) : 0,
                    !empty($amount_comp) ? number_format($amount_comp) : 0,
                    !empty($revenue_comp) ? number_format($revenue_comp) : 0,
                    number_format($amount_comp - $revenue_comp),
                ];
            }
        }
        $columnName[] = ['', '', '', '', '', '', '', '', '', '',];
        $columnName[] = [
            'ยอดขายทั้งหมด',
            !empty($array_total) ? !empty($extar_arr_total) ? number_format(array_sum($array_total) + array_sum($extar_arr_total)) : number_format(array_sum($array_total)) : 0,
            'รับเงินทั้งหมด',
            !empty($paid) ? number_format($paid) : 0,
            'แบ่งเป็น Cash On Tour',
            !empty($bo_cot) ? number_format(array_sum($bo_cot)) : 0,
            '',
            '',
            '',
            '',
        ];
        $columnName[] = [
            'ค้างจ่ายที่ยังไม่ได้ออก Invoice',
            !empty($not_issued) ? number_format($not_issued) : 0,
            'ค้างจ่าย',
            !empty($issued_inv) ? number_format($issued_inv) : 0,
            '',
            '',
            '',
            '',
            '',
            '',
        ];
    } elseif ($_GET['type'] == 'programe') {
        $columnName[] = ['Programe Name', 'AD', 'CHD', 'INF', 'FOC', 'TOTAL',];
        if (!empty($prod_id)) {
            $age = array_count_values($prod_id);
            arsort($age);
            foreach ($age as $x => $x_value) {
                $columnName[] = [$product_name[$x], !empty($product_adult[$x]) ? array_sum($product_adult[$x]) : 0, !empty($product_child[$x]) ? array_sum($product_child[$x]) : 0, !empty($product_infant[$x]) ? array_sum($product_infant[$x]) : 0, !empty($product_foc[$x]) ? array_sum($product_foc[$x]) : 0, !empty($product_adult[$x]) && !empty($product_child[$x]) && !empty($product_infant[$x]) && !empty($product_foc[$x]) ? array_sum($product_adult[$x]) + array_sum($product_child[$x]) + array_sum($product_infant[$x]) + array_sum($product_foc[$x]) : 0,];
            }
        }
    } elseif ($_GET['type'] == 'transfer') {
        $columnName[] = ['Car & Driver', 'Travel Date', 'Booking', 'AD', 'CHD', 'INF', 'FOC', 'TOTAL',];
        if (!empty($ortran_id)) {
            foreach ($ortran_id as $x => $val) {
                $adult_car = 0;
                $child_car = 0;
                $infant_car = 0;
                $foc_car = 0;
                $tourist_car = 0;
                foreach ($bot_bo[$val] as $bo_id) {
                    $adult_car += !empty($adult[$bo_id]) ? array_sum($adult[$bo_id]) : 0;
                    $child_car += !empty($child[$bo_id]) ? array_sum($child[$bo_id]) : 0;
                    $infant_car += !empty($infant[$bo_id]) ? array_sum($infant[$bo_id]) : 0;
                    $foc_car += !empty($foc[$bo_id]) ? array_sum($foc[$bo_id]) : 0;
                    $tourist_car += !empty($tourist_max[$bo_id]) ? array_sum($tourist_max[$bo_id]) : 0;
                }

                $columnName[] = [
                    !empty($car_name[$x]) ? !empty($driver_name[$x]) ? $car_name[$x] . ' / ' . $driver_name[$x] : $car_name[$x] : '',
                    (!empty($ortran_travel[$x])) ? date('j F Y', strtotime($ortran_travel[$x])) : '',
                    !empty($bot_id[$val]) ? count($bot_id[$val]) : 0,
                    $adult_car,
                    $child_car,
                    $infant_car,
                    $foc_car,
                    $tourist_car,
                ];
            }
        }
    } elseif ($_GET['type'] == 'boat') {
        $columnName[] = ['Boat & Captain', 'Programe', 'Travel Date', 'Booking', 'AD', 'CHD', 'INF', 'FOC', 'TOTAL',];
        if (!empty($boat_order_id)) {
            foreach ($boat_order_id as $x => $val) {
                $adult_boat = 0;
                $child_boat = 0;
                $infant_boat = 0;
                $foc_boat = 0;
                $tourist_boat = 0;
                foreach ($orboat_id[$x] as $bo_id) {
                    $adult_boat += !empty($adult[$bo_id]) ? array_sum($adult[$bo_id]) : 0;
                    $child_boat += !empty($child[$bo_id]) ? array_sum($child[$bo_id]) : 0;
                    $infant_boat += !empty($infant[$bo_id]) ? array_sum($infant[$bo_id]) : 0;
                    $foc_boat += !empty($foc[$bo_id]) ? array_sum($foc[$bo_id]) : 0;
                    $tourist_boat += !empty($tourist_max[$bo_id]) ? array_sum($tourist_max[$bo_id]) : 0;
                }

                $columnName[] = [
                    $boat_name[$val[0]],
                    $product_name[$boat_product[$x]],
                    (!empty($orboat_travel[$x])) ? date('j F Y', strtotime($orboat_travel[$x])) : '',
                    count($boboat_id[$x]),
                    $adult_boat,
                    $child_boat,
                    $infant_boat,
                    $foc_boat,
                    $tourist_boat
                ];
            }
        }
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
