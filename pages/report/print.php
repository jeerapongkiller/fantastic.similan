<?php
require_once 'controllers/Report.php';

$repObj = new Report();
$today = date("Y-m-d");
$times = date("H:i:s");

if (isset($_GET['action']) && $_GET['action'] == "print" && isset($_GET['type'])) {
    $search_travel = !empty($_GET["search_travel"]) ? $_GET["search_travel"] : '0000-00-00';
    $date_form = substr($search_travel, 0, 10) != '' ? substr($search_travel, 0, 10) : '0000-00-00';
    $date_to = substr($search_travel, 14, 10) != '' ? substr($search_travel, 14, 10) : $date_form;
    $search_agent = $_GET['search_agent'] != "" ? $_GET['search_agent'] : 'all';
    $search_product = $_GET['search_product'] != "" ? $_GET['search_product'] : 'all';

    $text_detail = '';
    $text_detail .= $date_form != '0000-00-00' ? $date_to != '0000-00-00' ? 'วันที่ ' . date('j F Y', strtotime($date_form)) . ' ถึง ' . date('j F Y', strtotime($date_to)) : 'วันที่ ' . date('j F Y', strtotime($date_form)) : '';
    $text_detail .= $search_agent != 'all' ? ' เอเยนต์ ' . $repObj->get_value('name', 'companies', $search_agent)['name'] : ' เอเยนต์ทั้งหมด';
    $text_detail .= $search_product != 'all' ? ' โปรแกรม ' . $repObj->get_value('name', 'products', $search_product)['name'] : ' โปรแกรมทั้งหมด';

    $inv_no = 0;
    $no_rec = 0;
    $balance = 0;
    $count_boboat = 0;
    $count_bot = 0;
    $bo_paid = 0;
    $first_book = array();
    $first_agent = array();
    $first_prod = array();
    $first_bot = array();
    $first_boboat = array();
    $first_pay = array();
    $bookings = $repObj->showlist($date_form, $date_to, $search_agent, $search_product);
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
            $payment[] = !empty($booking['bookpay_name']) ? $booking['bookpay_name'] : 'ไม่ได้ระบุ';
            $payment_paid[] = !empty($booking['payment_paid']) ? $booking['payment_paid'] : 0;
            // $inv_status[] = (diff_date($today, $booking['rec_date'])['day'] > 0) ? '<span class="badge badge-pill badge-light-success text-capitalized">ครบกำหนดชำระในอีก ' . diff_date($today, $booking['rec_date'])['num'] . ' วัน</span>' : '<span class="badge badge-pill badge-light-danger text-capitalized">เกินกำหนดชำระ</span>';
            $bo_status[] = !empty($booking['booksta_id']) ? $booking['booksta_id'] : 0;
            $sender[] = !empty($booking['sender']) ? $booking['sender'] : '';
            # --- get value booking products --- #
            $adult[] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
            $child[] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
            $infant[] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
            $foc[] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
            # --- get value booking products --- #
            $hotel_pickup_name[] = !empty($booking['hotel_pickup_name']) ? $booking['hotel_pickup_name'] : '';
            $hotel_dropoff_name[] = !empty($booking['hotel_dropoff_name']) ? $booking['hotel_dropoff_name'] : '';
            # --- get value customers --- #
            $cus_name[] = !empty($booking['cus_name']) && $booking['cus_head'] == 1 ? $booking['cus_name'] : '';
            # --- calculate amount booking --- #
            $total = $booking['rate_total'];
            $total = ($booking['transfer_type'] == 1) ? $total + ($booking['bt_adult'] * $booking['btr_rate_adult']) + ($booking['bt_child'] * $booking['btr_rate_child']) + ($booking['bt_infant'] * $booking['btr_rate_infant']) : $total;
            $total = ($booking['transfer_type'] == 2) ? $repObj->sumbtrprivate($booking['bt_id'])['sum_rate_private'] + $total : $total;
            // $total = $repObj->sumbectotal($booking['id'])['sum_rate_total'] + $total;

            $amount = $total;
            $array_total[] = $total;
            if ($booking['vat_id'] == 1) {
                $vat_total = $total * 100 / 107;
                $vat_cut = $vat_total;
                $vat_total = $total - $vat_total;
                $withholding_total = $booking['withholding'] > 0 ? ($vat_cut * $booking['withholding']) / 100 : 0;
                $amount = $total - $withholding_total;
            } elseif ($booking['vat_id'] == 2) {
                $vat_total = ($total * 7) / 100;
                $total = $total + $vat_total;
                $withholding_total = $booking['withholding'] > 0 ? ($total - $vat_total) * $booking['withholding'] / 100 : 0;
                $amount = $total - $withholding_total;
            }
            $array_amount[$booking['id']] = $amount;

            $inv_no = !empty($booking['inv_id']) ? $inv_no + 1 : $inv_no;
            // $over_due = (diff_date($today, $booking['rec_date'])['day'] <= 0) && !empty($booking['inv_id']) && empty($booking['rec_id']) ? $over_due + 1 : $over_due;
            $no_rec = !empty($booking['rec_id']) ? $no_rec + 1 : $no_rec;
            $balance = !empty($booking['rec_id']) ? $balance + $total : $balance;
            $bo_rec[] = !empty($booking['rec_id']) ? $total : 0;
            $revenue[] = $total;
            # --- Agent --- #
            $comp_id[] = !empty($booking['comp_id']) ? $booking['comp_id'] : 0;
            $comp_name[] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
            $comp_amount[$booking['comp_id']][] = $amount;
            $comp_revenue[$booking['comp_id']][] = !empty($booking['rec_id']) ? $total : 0;
            $comp_adult[$booking['comp_id']][] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
            $comp_child[$booking['comp_id']][] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
            $comp_infant[$booking['comp_id']][] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
            $comp_foc[$booking['comp_id']][] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
            $comp_sum[$booking['comp_id']][] = $booking['bp_adult'] + $booking['bp_child'] + $booking['bp_infant'] + $booking['bp_foc'];
            # --- Programe --- #
            $prod_id[] = !empty($booking['product_id']) ? $booking['product_id'] : 0;
            $product_name[$booking['product_id']] = !empty($booking['product_name']) ? $booking['product_name'] : '';
            $category_name[$booking['product_id']] = !empty($booking['category_name']) ? $booking['category_name'] : '';
            $product_adult[$booking['product_id']][] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
            $product_child[$booking['product_id']][] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
            $product_infant[$booking['product_id']][] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
            $product_foc[$booking['product_id']][] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
            # --- order boat --- #
            if (!empty(!empty($booking['orboat_id'])) && !empty($booking['orboat_id']) > 0) {
                $total_park = 0;
                $park_name[$booking['park_id']] = !empty($booking['park_name']) ? $booking['park_name'] : '';
                $orboat_id[$booking['orboat_id']][] = !empty($booking['id']) ? $booking['id'] : 0;
                $orboat_travel[$booking['orboat_id']] = !empty($booking['orboat_travel']) ? $booking['orboat_travel'] : 0;
                $park_id[$booking['orboat_id']] = !empty($booking['park_id']) ? $booking['park_id'] : 0;
                $park_adult_eng[$booking['orboat_id']] = !empty($booking['adult_eng']) ? $booking['adult_eng'] : 0;
                $park_child_eng[$booking['orboat_id']] = !empty($booking['child_eng']) ? $booking['child_eng'] : 0;
                $park_adult_th[$booking['orboat_id']] = !empty($booking['adult_th']) ? $booking['adult_th'] : 0;
                $park_child_th[$booking['orboat_id']] = !empty($booking['child_th']) ? $booking['child_th'] : 0;
                # --- Boat --- #
                $boat_id[$booking['orboat_id']][] = !empty($booking['boat_id']) ? $booking['boat_id'] : 0;
                $boat_color[$booking['orboat_id']] = !empty($booking['orboat_color']) ? $booking['orboat_color'] : 0;
                $boat_name[$booking['boat_id']] = !empty($booking['boat_name']) ? $booking['boat_name'] : '';
                $boat_order_id[$booking['orboat_id']][] = !empty($booking['boat_id']) ? $booking['boat_id'] : 0;
                $boat_product[$booking['orboat_id']] = !empty($booking['product_id']) ? $booking['product_id'] : 0;
                $boat_adult[$booking['orboat_id']][] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                $boat_child[$booking['orboat_id']][] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                $boat_infant[$booking['orboat_id']][] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                $bp_foc[$booking['orboat_id']][] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
            }
            # --- order car --- #
            if (!empty(!empty($booking['ortran_id'])) && !empty($booking['ortran_id']) > 0) {
                $ortran_id[$booking['ortran_id']] = !empty($booking['ortran_id']) ? $booking['ortran_id'] : 0;
                $ortran_retrun[$booking['ortran_id']] = !empty($booking['ortran_retrun']) ? $booking['ortran_retrun'] : 0;
                $car_name[$booking['ortran_id']] = !empty($booking['car_name']) ? $booking['car_name'] : '';
                $car_registration[$booking['ortran_id']] = !empty($booking['license']) ? $booking['license'] : '';
                $driver_name[$booking['ortran_id']] = !empty($booking['driver_name']) ? $booking['driver_name'] : '';
                $ortran_product[$booking['ortran_id']] = !empty($booking['product_id']) ? $booking['product_id'] : 0;
                $ortran_travel[$booking['ortran_id']] = !empty($booking['ortran_travel']) ? $booking['ortran_travel'] : 0;
                $bt_id[$booking['ortran_id']][] = !empty($booking['bt_id']) ? $booking['bt_id'] : 0;
                $car_adult[$booking['ortran_id']][] = !empty($booking['bt_adult']) ? $booking['bt_adult'] : 0;
                $car_child[$booking['ortran_id']][] = !empty($booking['bt_child']) ? $booking['bt_child'] : 0;
                $car_infant[$booking['ortran_id']][] = !empty($booking['bt_infant']) ? $booking['bt_infant'] : 0;
                $car_foc[$booking['ortran_id']][] = !empty($booking['bt_foc']) ? $booking['bt_foc'] : 0;
            }
            # --- Park --- #
            $bo_park[] = !empty($booking['park_id']) ? $booking['park_id'] : 0;
        }
        # --- get value agent company --- #
        if (in_array($booking['comp_id'], $first_agent) == false) {
            $first_agent[] = $booking['comp_id'];
            $agent_id[] = !empty($booking['comp_id']) ? $booking['comp_id'] : 0;
            $agent_name[] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
            $agent_logo[] = !empty($booking['comp_logo']) ? $booking['comp_logo'] : '';
        }
        # --- get value booking products --- #
        if (in_array($booking['product_id'], $first_prod) == false) {
            $first_prod[] = $booking['product_id'];
            $product_id[] = !empty($booking['product_id']) ? $booking['product_id'] : 0;
        }
        # --- get value booking order transfer --- #
        if (in_array($booking['bt_id'], $first_bot) == false && !empty($booking['ortran_id']) && !empty($booking['bt_id'])) {
            $first_bot[] = $booking['bt_id'];
            $bot_id[$booking['ortran_id']][] = $booking['bt_id'];
            $count_bot++;
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
            $bopay_name[$booking['id']] = !empty($booking['bopay_name']) ? $booking['bopay_name'] : '';
            $total_paid[$booking['id']] = !empty($booking['total_paid']) ? $booking['total_paid'] : '';
            $bo_cot[$booking['id']] = !empty($booking['bopay_id']) && $booking['bopay_id'] == 4 ? !empty($booking['total_paid']) ? $booking['total_paid'] : 0 : 0;
            $bo_dep[$booking['id']] = !empty($booking['bopay_id']) && $booking['bopay_id'] == 5 ? !empty($booking['total_paid']) ? $booking['total_paid'] : 0 : 0;
            $bopay_name_class[$booking['id']] = !empty($booking['bopay_name_class']) ? $booking['bopay_name_class'] : '';
            $bopay_paid_name[$booking['id']] = $booking['bopay_id'] == 4 || $booking['bopay_id'] == 5 ? $booking['bopay_name'] . '</br>(' . number_format($booking['total_paid']) . ')' : $booking['bopay_name'];
        }
    }
    # ------ calculate booking paid ------ #
    if (!empty($bopay_id)) {
        foreach ($bopay_id as $x => $val) {
            $bo_paid = $val == 3 ? $bo_paid + $array_amount[$x] : $bo_paid;
        }
    }
    if ($_GET['type'] == 'booking') {
?>
        <h3 class="text-center pt-1">รายงาน <span class="text-warning font-weight-bolder">Booking</span></h3>
        <h5 class="text-center"><?php echo $text_detail; ?></h5>
        <div class="table-responsive table-striped table-vouchure-t2">
            <table>
                <thead>
                    <tr>
                        <th class="text-center">Status</th>
                        <th class="text-center">Payment</th>
                        <th class="text-center">Voucher No.</th>
                        <th>Agent</th>
                        <th>Travel Date</th>
                        <th>Programe</th>
                        <th class="text-center">Pax</th>
                        <th>Hotel </br><small>(Pickup)</small></th>
                        <th>Customer</th>
                        <th>Booker</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bo_id)) {
                        for ($i = 0; $i < count($bo_id); $i++) { ?>
                            <tr>
                                <td class="text-center p-25 m-25"><?php echo $status[$i]; ?></td>
                                <td class="text-center p-25 m-25"><?php echo !empty($bopay_id[$bo_id[$i]]) ? $bopay_paid_name[$bo_id[$i]] : 'ไม่ได้ระบุ'; ?></td>
                                <td class="text-center p-25 m-25"><?php echo !empty($voucher_no_agent[$i]) ? $voucher_no_agent[$i] : $book_full[$i]; ?></td>
                                <td class="p-25 m-25"><?php echo $comp_name[$i]; ?></td>
                                <td class="text-nowrap p-25 m-25"><?php echo (!empty($travel_date[$i])) ? date('j F Y', strtotime($travel_date[$i])) : ''; ?></td>
                                <td class="p-25 m-25">
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bolder text-primary"><?php echo $product_name[$prod_id[$i]]; ?></span>
                                        <span class="font-small-3"><?php echo $category_name[$prod_id[$i]]; ?></span>
                                    </div>
                                </td>
                                <td class="text-center p-25 m-25"><?php echo $adult[$i] + $child[$i] + $infant[$i] + $foc[$i]; ?></td>
                                <td class="p-25 m-25"><?php echo $hotel_pickup_name[$i]; ?></td>
                                <td class="p-25 m-25"><?php echo $cus_name[$i]; ?></td>
                                <td class="p-25 m-25"><?php echo $sender[$i]; ?></td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    <?php } elseif ($_GET['type'] == 'agent') { ?>
        <h3 class="text-center pt-1">รายงาน <span class="text-warning font-weight-bolder">Agent</span></h3>
        <h5 class="text-center"><?php echo $text_detail; ?></h5>
        <div class="table-responsive table-striped table-vouchure-t2">
            <table>
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th class="text-center p-25 m-25">Booking</th>
                        <th class="text-center p-25 m-25">A</th>
                        <th class="text-center p-25 m-25">C</th>
                        <th class="text-center p-25 m-25">INF</th>
                        <th class="text-center p-25 m-25">FOC</th>
                        <th class="text-center p-25 m-25">TOTAL</th>
                        <th class="text-center p-25 m-25">Amount</br><small>(THB)</small></th>
                        <th class="text-center p-25 m-25">Income</br><small>(THB)</small></th>
                        <th class="text-center p-25 m-25">Overdue</br><small>(THB)</small></th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($agent_id); $i++) {  ?>
                        <tr>
                            <td>
                                <span class="font-weight-bolder text-primary"><?php echo $agent_name[$i]; ?></span>
                            </td>
                            <td class="text-center font-weight-bolder p-25 m-25"><?php echo array_count_values($comp_id)[$agent_id[$i]] ?></td>
                            <td class="text-center font-weight-bolder p-25 m-25"><?php echo !empty($comp_adult[$agent_id[$i]]) ? array_sum($comp_adult[$agent_id[$i]]) : 0; ?></td>
                            <td class="text-center font-weight-bolder p-25 m-25"><?php echo !empty($comp_child[$agent_id[$i]]) ? array_sum($comp_child[$agent_id[$i]]) : 0; ?></td>
                            <td class="text-center font-weight-bolder p-25 m-25"><?php echo !empty($comp_infant[$agent_id[$i]]) ? array_sum($comp_infant[$agent_id[$i]]) : 0; ?></td>
                            <td class="text-center font-weight-bolder p-25 m-25"><?php echo !empty($comp_foc[$agent_id[$i]]) ? array_sum($comp_foc[$agent_id[$i]]) : 0; ?></td>
                            <td class="text-center font-weight-bolder p-25 m-25"><?php echo !empty($comp_sum[$agent_id[$i]]) ? array_sum($comp_sum[$agent_id[$i]]) : 0; ?></td>
                            <td class="text-nowrap text-center font-weight-bolder p-25 m-25">
                                <div class="d-flex flex-column">
                                    <span class="font-weight-bolder mb-25 text-warning"><?php echo number_format(array_sum($comp_amount[$agent_id[$i]])); ?></span>
                                </div>
                            </td>
                            <td class="text-nowrap text-center font-weight-bolder p-25 m-25">
                                <div class="d-flex flex-column">
                                    <span class="font-weight-bolder mb-25 text-success"><?php echo number_format(array_sum($comp_revenue[$agent_id[$i]])); ?></span>
                                </div>
                            </td>
                            <td class="text-nowrap text-center font-weight-bolder p-25 m-25">
                                <div class="d-flex flex-column">
                                    <span class="font-weight-bolder mb-25 text-danger"><?php echo number_format(array_sum($comp_amount[$agent_id[$i]]) - array_sum($comp_revenue[$agent_id[$i]])); ?></span>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } elseif ($_GET['type'] == 'programe') { ?>
        <h3 class="text-center pt-1">รายงาน <span class="text-warning font-weight-bolder">Programe</span></h3>
        <h5 class="text-center"><?php echo $text_detail; ?></h5>
        <div class="table-responsive table-striped table-vouchure-t2">
            <table>
                <thead class="bg-light">
                    <tr>
                        <th>Programe Name</th>
                        <th class="text-center p-25 m-25">AD</th>
                        <th class="text-center p-25 m-25">CHD</th>
                        <th class="text-center p-25 m-25">INF</th>
                        <th class="text-center p-25 m-25">FOC</th>
                        <th class="text-center p-25 m-25">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $age = array_count_values($prod_id);
                    arsort($age);
                    foreach ($age as $x => $x_value) {
                    ?>
                        <tr>
                            <td>
                                <div class="font-weight-bolder text-primary"><?php echo $product_name[$x] ?></div>
                            </td>
                            <td class="text-center font-weight-bolder p-25 m-25"><?php echo !empty($product_adult[$x]) ? array_sum($product_adult[$x]) : 0; ?></td>
                            <td class="text-center font-weight-bolder p-25 m-25"><?php echo !empty($product_child[$x]) ? array_sum($product_child[$x]) : 0; ?></td>
                            <td class="text-center font-weight-bolder p-25 m-25"><?php echo !empty($product_infant[$x]) ? array_sum($product_infant[$x]) : 0; ?></td>
                            <td class="text-center font-weight-bolder p-25 m-25"><?php echo !empty($product_foc[$x]) ? array_sum($product_foc[$x]) : 0; ?></td>
                            <td class="text-center font-weight-bolder p-25 m-25"><?php echo !empty($product_adult[$x]) && !empty($product_child[$x]) && !empty($product_infant[$x]) && !empty($product_foc[$x]) ? array_sum($product_adult[$x]) + array_sum($product_child[$x]) + array_sum($product_infant[$x]) + array_sum($product_foc[$x]) : 0; ?></td>
                        </tr>
                    <?php
                    } ?>
                </tbody>
            </table>
        </div>
    <?php } elseif ($_GET['type'] == 'transfer') { ?>
        <h3 class="text-center pt-1">รายงาน <span class="text-warning font-weight-bolder">Transfer</span></h3>
        <h5 class="text-center"><?php echo $text_detail; ?></h5>
        <div class="table-responsive table-striped table-vouchure-t2">
            <table>
                <thead class="bg-light">
                    <tr>
                        <th>Car & Driver</th>
                        <th>Travel Date</th>
                        <th class="text-center p-25 m-25">Booking</th>
                        <th class="text-center p-25 m-25">A</th>
                        <th class="text-center p-25 m-25">C</th>
                        <th class="text-center p-25 m-25">Inf</th>
                        <th class="text-center p-25 m-25">FOC</th>
                        <th class="text-center p-25 m-25">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($ortran_id)) {
                        foreach ($ortran_id as $x => $val) {  ?>
                            <tr>
                                <td>
                                    <div class="font-weight-bolder text-primary"><?php echo !empty($car_name[$ortran_id[$x]]) ? !empty($driver_name[$ortran_id[$x]]) ? $car_name[$ortran_id[$x]] . ' / ' . $driver_name[$ortran_id[$x]] : $car_name[$ortran_id[$x]] : ''; ?></div>
                                </td>
                                <td><?php echo (!empty($ortran_travel[$ortran_id[$x]])) ? date('j F Y', strtotime($ortran_travel[$ortran_id[$x]])) : ''; ?></td>
                                <td class="text-center p-25 m-25"><?php echo !empty($bot_id[$val]) ? count($bot_id[$val]) : 0; ?></td>
                                <td class="text-center p-25 m-25"><?php echo !empty($car_adult[$x]) ? array_sum($car_adult[$x]) : 0; ?></td>
                                <td class="text-center p-25 m-25"><?php echo !empty($car_child[$x]) ? array_sum($car_child[$x]) : 0; ?></td>
                                <td class="text-center p-25 m-25"><?php echo !empty($car_infant[$x]) ? array_sum($car_infant[$x]) : 0; ?></td>
                                <td class="text-center p-25 m-25"><?php echo !empty($car_foc[$x]) ? array_sum($car_foc[$x]) : 0; ?></td>
                                <td class="text-center p-25 m-25"><?php echo array_sum($car_adult[$x]) + array_sum($car_child[$x]) + array_sum($car_infant[$x]) + array_sum($car_foc[$x]); ?></td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    <?php } elseif ($_GET['type'] == 'boat') { ?>
        <h3 class="text-center pt-1">รายงาน <span class="text-warning font-weight-bolder">Boat</span></h3>
        <h5 class="text-center"><?php echo $text_detail; ?></h5>
        <div class="table-responsive table-striped table-vouchure-t2">
            <table>
                <thead class="bg-light">
                    <tr>
                        <th>Boat & Captain</th>
                        <th>Programe</th>
                        <th>Travel Date</th>
                        <th class="text-center p-25 m-25">Booking</th>
                        <th class="text-center p-25 m-25">A</th>
                        <th class="text-center p-25 m-25">C</th>
                        <th class="text-center p-25 m-25">Inf</th>
                        <th class="text-center p-25 m-25">FOC</th>
                        <th class="text-center p-25 m-25">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($boat_order_id)) {
                        foreach ($boat_order_id as $x => $val) {
                            switch ($boat_color[$x]) {
                                case 'blue':
                                    $hex_color = '#00cfe8';
                                    break;
                                case 'red':
                                    $hex_color = '#ea5455';
                                    break;
                                case 'yellow':
                                    $hex_color = '#ffc107';
                                    break;
                                case 'brown':
                                    $hex_color = '#8B4513';
                                    break;
                                case 'purple':
                                    $hex_color = '#7367f0';
                                    break;
                                case 'pink':
                                    $hex_color = '#d63384';
                                    break;
                                case 'orange':
                                    $hex_color = '#ff9f43';
                                    break;
                                case 'green':
                                    $hex_color = '#28c76f';
                                    break;
                                case 'deepskyblue':
                                    $hex_color = '#1E90FF';
                                    break;
                            }
                    ?>
                            <tr>
                                <td style="color: <?php echo $hex_color; ?>; font-weight: bold;"><?php echo $boat_name[$val[0]]; ?></span></td>
                                <td>
                                    <div class="text-primary font-weight-bolder"><?php echo $product_name[$boat_product[$x]]; ?></div>
                                </td>
                                <td><?php echo (!empty($orboat_travel[$x])) ? date('j F Y', strtotime($orboat_travel[$x])) : ''; ?></td>
                                <td class="text-center p-25 m-25"><?php echo count($boboat_id[$x]); ?></td>
                                <td class="text-center p-25 m-25"><?php echo !empty($boat_adult[$x]) ? array_sum($boat_adult[$x]) : 0; ?></td>
                                <td class="text-center p-25 m-25"><?php echo !empty($boat_child[$x]) ? array_sum($boat_child[$x]) : 0; ?></td>
                                <td class="text-center p-25 m-25"><?php echo !empty($boat_infant[$x]) ? array_sum($boat_infant[$x]) : 0; ?></td>
                                <td class="text-center p-25 m-25"><?php echo !empty($boat_foc[$x]) ? array_sum($boat_foc[$x]) : 0; ?></td>
                                <td class="text-center p-25 m-25"><?php echo array_sum($boat_adult[$x]) + array_sum($boat_child[$x]) + array_sum($boat_infant[$x]); ?></td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
<?php }
}
