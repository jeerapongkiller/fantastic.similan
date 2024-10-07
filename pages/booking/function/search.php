<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Booking.php';

$prodObj = new Booking();

if (isset($_POST['action']) && $_POST['action'] == "search") {
    // get value from ajax
    $search_status = $_POST['search_status'] != "" ? $_POST['search_status'] : 'all';
    $search_payment = $_POST['search_payment'] != "" ? $_POST['search_payment'] : 'all';
    $search_agent = $_POST['search_agent'] != "" ? $_POST['search_agent'] : 'all';
    $search_product = $_POST['search_product'] != "" ? $_POST['search_product'] : 'all';
    $search_travel = $_POST['search_travel'] != "" ? $_POST['search_travel'] : '0000-00-00';
    $search_voucher_no = $_POST['voucher_no'] != "" ? $_POST['voucher_no'] : '';
    $refcode = $_POST['refcode'] != "" ? $_POST['refcode'] : '';
    $name = $_POST['name'] != "" ? $_POST['name'] : '';

    # --- get data --- #
    $first_book = array();
    $first_btr = array();
    $first_pay = array();
    $first_ext = array();
    $frist_bomange = array();
    $total_sum = 0;
    $revenue = 0;
    $count_confirm = 0;
    $count_noshow = 0;
    $count_cancel = 0;
    $bookings = $prodObj->showlist($_SESSION["supplier"]["id"], $search_status, $search_payment, $search_agent, $search_product, $search_travel, $search_voucher_no, $refcode, $name);
    # --- Check products --- #
    if (!empty($bookings)) {
        foreach ($bookings as $booking) {
            # --- get value booking --- #
            if (in_array($booking['id'], $first_book) == false) {
                $first_book[] = $booking['id'];
                $bo_id[] = !empty($booking['id']) ? $booking['id'] : 0;
                $bo_type[] = !empty($booking['booktye_id']) ? $booking['booktye_id'] : 0;
                $bp_id[] = !empty($booking['bp_id']) ? $booking['bp_id'] : 0;
                $book_full[] = !empty($booking['book_full']) ? $booking['book_full'] : '';
                $voucher_no[] = !empty(!empty($booking['voucher_no_agent'])) ? $booking['voucher_no_agent'] : '';
                $discount[] = !empty(!empty($booking['discount'])) ? $booking['discount'] : 0;
                $travel_date[] = !empty(!empty($booking['travel_date'])) ? $booking['travel_date'] : '0000-00-00';
                $product_name[] = !empty(!empty($booking['product_name'])) ? $booking['product_name'] : '';
                $agent_name[] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
                $cus_name[] = !empty($booking['cus_name']) ? $booking['cus_name'] : '';
                $hotel_pickup[] = !empty($booking['hotel_pickup']) ? $booking['hotel_pickup'] : $booking['hotel_pickup_name'];
                $pickup_name[] = !empty($booking['pickup_name']) ? $booking['pickup_name'] : '';
                $room_no[] = !empty($booking['room_no']) ? $booking['room_no'] : '';
                $start_pickup[] = !empty($booking['start_pickup']) ? !empty($booking['end_pickup']) ? date('H:i', strtotime($booking['start_pickup'])) . '-' . date('H:i', strtotime($booking['end_pickup'])) : date('H:i', strtotime($booking['start_pickup'])) : '';
                $booker_name[] = !empty($booking['booker_id']) ? $booking['booker_fname'] . ' ' . $booking['booker_lname'] : '';
                $status_by_name[] = !empty($booking['status_by']) ? $booking['stabyFname'] . ' ' . $booking['stabyLname'] : '';
                $status[] = '<span class="badge badge-pill ' . $booking['booksta_class'] . ' text-capitalized"> ' . $booking['booksta_name'] . ' </span>';
                $category_transfer[] = !empty(!empty($booking['category_transfer'])) ? $booking['category_transfer'] : 0;
                $adult[] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                $child[] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                $infant[] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                $foc[] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
                $note[] = !empty($booking['bp_note']) ? $booking['bp_note'] : '';
                $rate_adult[] = !empty($booking['rate_adult']) ? $booking['rate_adult'] : 0;
                $rate_child[] = !empty($booking['rate_child']) ? $booking['rate_child'] : 0;
                $created_at[] = !empty(!empty($booking['created_at'])) ? $booking['created_at'] : '0000-00-00';
                // $payment[] = !empty($booking['bookpay_name']) ? !empty($booking['paid_id']) ? '<span class="badge badge-pill badge-light-success text-capitalized"> ' . $booking['bookpay_name'] . '<br> ชำระเงินแล้ว </span>' : '<span class="badge badge-pill ' . $booking['bookpay_name_class'] . ' text-capitalized"> ' . $booking['bookpay_name'] . ' </span>' : '<span class="badge badge-pill badge-light-primary text-capitalized"> Add Payment </span></br>';
                $rate_total[] = !empty($booking['rate_total']) ? $booking['rate_total'] : 0;
                $transfer_type[] = !empty($booking['transfer_type']) ? $booking['transfer_type'] : 0;
                $btr_rate_adult[] = !empty($booking['transfer_type']) && $booking['transfer_type'] == 1 ? $booking['bt_adult'] * $booking['btr_rate_adult'] : 0;
                $btr_rate_child[] = !empty($booking['transfer_type']) && $booking['transfer_type'] == 1 ? $booking['bt_child'] * $booking['btr_rate_child'] : 0;
                $btr_rate_infant[] = !empty($booking['transfer_type']) && $booking['transfer_type'] == 1 ? $booking['bt_infant'] * $booking['btr_rate_infant'] : 0;
                switch ($booking['booksta_id']) {
                    case '1':
                        $count_confirm = $count_confirm + 1;
                        break;
                    case '3':
                        $count_cancel = $count_cancel + 1;
                        break;
                    case '4':
                        $count_noshow = $count_noshow + 1;
                        break;
                }
                # --- Programe --- #
                $prod_id[] = !empty($booking['product_id']) ? $booking['product_id'] : 0;
                $prod_name[$booking['product_id']] = !empty($booking['product_name']) ? $booking['product_name'] : '';
                $prod_adult[$booking['product_id']][] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                $prod_child[$booking['product_id']][] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                $prod_infant[$booking['product_id']][] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                $prod_foc[$booking['product_id']][] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
                # --- order boat --- #
                if (!empty(!empty($booking['mange_id'])) && !empty($booking['mange_id']) > 0) {
                    # --- Boat --- #
                    $mange_id[$booking['mange_id']][] = !empty($booking['id']) ? $booking['id'] : 0;
                    $boat_id[$booking['mange_id']][] = !empty($booking['boat_id']) ? $booking['boat_id'] : 0;
                    $boat_name[$booking['boat_id']] = !empty($booking['boat_name']) ? $booking['boat_name'] : '';
                    $capt_name[$booking['boat_id']] = !empty($booking['capt_name']) ? $booking['capt_name'] : '';
                    $boat_order_id[$booking['mange_id']][] = !empty($booking['boat_id']) ? $booking['boat_id'] : 0;
                    $boat_product[$booking['mange_id']] = !empty($booking['product_id']) ? $booking['product_id'] : 0;
                    $boat_adult[$booking['mange_id']][] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                    $boat_child[$booking['mange_id']][] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                    $boat_infant[$booking['mange_id']][] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                    $boat_foc[$booking['mange_id']][] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
                }
            }
            # --- get value manage transfer --- #
            if (!empty($booking['manget_id']) && (in_array($booking['bomange_id'], $frist_bomange) == false)) {
                $frist_bomange[] = $booking['bomange_id'];
                $manget_id[$booking['manget_id']] = !empty($booking['manget_id']) ? $booking['manget_id'] : 0;
                $ortran_product[$booking['manget_id']] = !empty($booking['product_id']) ? $booking['product_id'] : 0;
                $car_name[$booking['manget_id']] = !empty($booking['car_name']) ? $booking['car_name'] : '';
                $car_adult[$booking['manget_id']][] = !empty($booking['bt_adult']) ? $booking['bt_adult'] : 0;
                $car_child[$booking['manget_id']][] = !empty($booking['bt_child']) ? $booking['bt_child'] : 0;
                $car_infant[$booking['manget_id']][] = !empty($booking['bt_infant']) ? $booking['bt_infant'] : 0;
                $car_foc[$booking['manget_id']][] = !empty($booking['bt_foc']) ? $booking['bt_foc'] : 0;
            }
            # --- get value booking extra chang --- #
            if ((in_array($booking['bec_id'], $first_ext) == false) && !empty($booking['bec_id'])) {
                $first_ext[] = $booking['bec_id'];
                $bec_id[$booking['id']][] = !empty($booking['bec_id']) ? $booking['bec_id'] : 0;
                $bec_name[$booking['id']][] = !empty($booking['bec_name']) ? $booking['bec_name'] : $booking['extra_name'];
                $bec_type[$booking['id']][] = !empty($booking['bec_type']) ? $booking['bec_type'] : 0;
                $bec_adult[$booking['id']][] = !empty($booking['bec_adult']) ? $booking['bec_adult'] : 0;
                $bec_child[$booking['id']][] = !empty($booking['bec_child']) ? $booking['bec_child'] : 0;
                $bec_infant[$booking['id']][] = !empty($booking['bec_infant']) ? $booking['bec_infant'] : 0;
                $bec_privates[$booking['id']][] = !empty($booking['bec_privates']) ? $booking['bec_privates'] : 0;
                $bec_rate_adult[$booking['id']][] = !empty($booking['bec_rate_adult']) ? $booking['bec_rate_adult'] : 0;
                $bec_rate_child[$booking['id']][] = !empty($booking['bec_rate_child']) ? $booking['bec_rate_child'] : 0;
                $bec_rate_infant[$booking['id']][] = !empty($booking['bec_rate_infant']) ? $booking['bec_rate_infant'] : 0;
                $bec_rate_private[$booking['id']][] = !empty($booking['bec_rate_private']) ? $booking['bec_rate_private'] : 0;
                $bec_rate_total[$booking['id']][] = $booking['bec_type'] > 0 ? $booking['bec_type'] == 1 ? (($booking['bec_adult'] * $booking['bec_rate_adult']) + ($booking['bec_child'] * $booking['bec_rate_child']) + ($booking['bec_infant'] * $booking['bec_rate_infant'])) : ($booking['bec_privates'] * $booking['bec_rate_private']) : 0;
            }
            # --- get value booking transfer rate --- #
            if ((in_array($booking['btr_id'], $first_btr) == false) && (!empty($booking['transfer_type']) && $booking['transfer_type'] == 2)) {
                $first_btr[] = $booking['btr_id'];
                $rate_private[$booking['id']][] = $booking['rate_private'];
            }
            # --- get value booking payment --- #
            if ((in_array($booking['bopa_id'], $first_pay) == false) && !empty($booking['bopa_id'])) {
                $first_pay[] = $booking['bopa_id'];
                $bopay_id[$booking['id']] = !empty($booking['bopay_id']) ? $booking['bopay_id'] : 0;
                $bopay_name[$booking['id']] = !empty($booking['bopay_name']) ? $booking['bopay_name'] : '';
                $bopay_name_class[$booking['id']] = !empty($booking['bopay_name_class']) ? $booking['bopay_name_class'] : '';
                $bopay_paid_name[$booking['id']] = $booking['bopay_id'] == 4 || $booking['bopay_id'] == 5 ? $booking['bopay_name'] . '</br>(' . number_format($booking['total_paid']) . ')' : $booking['bopay_name'];
                $bopay_total_paid[$booking['id']] = !empty($booking['total_paid']) ? $booking['total_paid'] : 0;
            }
        }
    }
?>

    <div hidden>
        <div class="row match-height">
            <!-- Status Card -->
            <div class="col-xl-6 col-md-6 col-6">
                <div class="card card-statistics">
                    <div class="card-header p-1">
                        <h4 class="card-title">Booking Status</h4>
                    </div>
                    <div class="card-body statistics-body border-top">
                        <div class="row">
                            <div class="col-4 mb-2 mb-xl-0">
                                <div class="media">
                                    <div class="avatar bg-light-success mr-2">
                                        <div class="avatar-content">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="media-body my-auto">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $count_confirm; ?></h4>
                                        <p class="card-text font-small-3 mb-0">Confirm</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 mb-2 mb-xl-0">
                                <div class="media">
                                    <div class="avatar bg-light-danger mr-2">
                                        <div class="avatar-content">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="media-body my-auto">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $count_cancel; ?></h4>
                                        <p class="card-text font-small-3 mb-0">Cancel</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 mb-2 mb-xl-0">
                                <div class="media">
                                    <div class="avatar bg-light-secondary mr-2">
                                        <div class="avatar-content">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="media-body my-auto">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $count_noshow; ?></h4>
                                        <p class="card-text font-small-3 mb-0">No Show</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Status Card -->

            <!-- Customer no Card -->
            <div class="col-6">
                <div class="card">
                    <div class="card-header p-1">
                        <h5 class="card-title">จำนวนลูกค้า</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="row text-center mx-0">
                            <div class="col-4 border-top border-right py-2 pb-4">
                                <p class="card-text text-muted mb-0">ทั้งหมด</p>
                                <h3 class="font-weight-bolder mb-0">
                                    <?php $total = 0;
                                    $total = !empty($adult) ? $total + array_sum($adult) : $total;
                                    $total = !empty($child) ? $total + array_sum($child) : $total;
                                    $total = !empty($infant) ? $total + array_sum($infant) : $total;
                                    $total = !empty($foc) ? $total + array_sum($foc) : $total;
                                    echo $total; ?>
                                </h3>
                            </div>
                            <div class="col-2 border-top border-right py-2 pb-4">
                                <p class="card-text text-muted mb-0">Adult</p>
                                <h3 class="font-weight-bolder mb-0"><?php echo !empty($adult) ? array_sum($adult) : 0; ?></h3>
                            </div>
                            <div class="col-2 border-top border-right py-2 pb-4">
                                <p class="card-text text-muted mb-0">Children</p>
                                <h3 class="font-weight-bolder mb-0"><?php echo !empty($child) ? array_sum($child) : 0; ?></h3>
                            </div>
                            <div class="col-2 border-top border-right py-2 pb-4">
                                <p class="card-text text-muted mb-0">Infant</p>
                                <h3 class="font-weight-bolder mb-0"><?php echo !empty($infant) ? array_sum($infant) : 0; ?></h3>
                            </div>
                            <div class="col-2 border-top py-2 pb-4">
                                <p class="card-text text-muted mb-0">FOC</p>
                                <h3 class="font-weight-bolder mb-0"><?php echo !empty($foc) ? array_sum($foc) : 0; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Customer no Card -->
        </div>

        <div class="row match-height">
            <!-- Boat Table Card -->
            <div class="col-6">
                <div class="card card-boat-table">
                    <div class="card-header p-1">
                        <h5 class="card-title">Boats</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Boat Name</th>
                                        <!-- <th>Captain</th> -->
                                        <th>Programe</th>
                                        <th>AD</th>
                                        <th>CHD</th>
                                        <th>INF</th>
                                        <th>FOC</th>
                                        <th>รวม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($boat_order_id)) {
                                        foreach ($boat_order_id as $x => $val) { ?>
                                            <tr>
                                                <td><span class="font-weight-bolder"><?php echo $boat_name[$val[0]]; ?></span></td>
                                                <!-- <td><span class="font-weight-bolder"><?php echo $capt_name[$val[0]]; ?></span></td> -->
                                                <td><span class="font-weight-bolder"><?php echo $prod_name[$boat_product[$x]]; ?></span></td>
                                                <td><span class="font-weight-bolder"><?php echo !empty($boat_adult[$x]) ? array_sum($boat_adult[$x]) : 0; ?></span></td>
                                                <td><span class="font-weight-bolder"><?php echo !empty($boat_child[$x]) ? array_sum($boat_child[$x]) : 0; ?></span></td>
                                                <td><span class="font-weight-bolder"><?php echo !empty($boat_infant[$x]) ? array_sum($boat_infant[$x]) : 0; ?></span></td>
                                                <td><span class="font-weight-bolder"><?php echo !empty($boat_foc[$x]) ? array_sum($boat_foc[$x]) : 0; ?></span></td>
                                                <td><span class="font-weight-bolder">
                                                        <?php
                                                        $total = 0;
                                                        $total = !empty($boat_adult) ? $total + array_sum($boat_adult) : $total;
                                                        $total = !empty($boat_child) ? $total + array_sum($boat_child) : $total;
                                                        $total = !empty($boat_infant) ? $total + array_sum($boat_infant) : $total;
                                                        $total = !empty($boat_foc) ? $total + array_sum($boat_foc) : $total;
                                                        echo $total; ?>
                                                    </span></td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Boat Table Card -->

            <!-- Car Table Card -->
            <div class="col-6">
                <div class="card card-car-table">
                    <div class="card-header p-1">
                        <h5 class="card-title">รถ (Pickup)</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Cars</th>
                                        <!-- <th>Driver</th> -->
                                        <th>Programe</th>
                                        <th>AD</th>
                                        <th>CHD</th>
                                        <th>INF</th>
                                        <th>FOC</th>
                                        <th>รวม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($manget_id)) {
                                        foreach ($manget_id as $x => $val) {  ?>
                                            <tr>
                                                <td><span class="font-weight-bolder"><?php echo !empty($car_name[$x]) ? $car_name[$x] : 'ไม่ได้ระบุ'; ?></span></td>
                                                <!-- <td><span class="font-weight-bolder"><?php echo !empty($driver_name[$x]) ? $driver_name[$x] : 'ไม่ได้ระบุ'; ?></span></td> -->
                                                <td><span class="font-weight-bolder"><?php echo !empty($ortran_product[$x]) ? $prod_name[$ortran_product[$x]] : ''; ?></span></td>
                                                <td><span class="font-weight-bolder"><?php echo !empty($car_adult[$x]) ? array_sum($car_adult[$x]) : 0; ?></span></td>
                                                <td><span class="font-weight-bolder"><?php echo !empty($car_child[$x]) ? array_sum($car_child[$x]) : 0; ?></span></td>
                                                <td><span class="font-weight-bolder"><?php echo !empty($car_infant[$x]) ? array_sum($car_infant[$x]) : 0; ?></span></td>
                                                <td><span class="font-weight-bolder"><?php echo !empty($car_foc[$x]) ? array_sum($car_foc[$x]) : 0; ?></span></td>
                                                <td><span class="font-weight-bolder"><?php echo array_sum($car_adult[$x]) + array_sum($car_child[$x]) + array_sum($car_infant[$x]) + array_sum($car_foc[$x]); ?></span></td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Car Table Card -->
        </div>

        <div class="row match-height">
            <!-- Products Table Card -->
            <div class="col-12">
                <div class="card card-company-table">
                    <div class="card-header p-1">
                        <h5 class="card-title">Programe</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Programe Name</th>
                                        <th>จำนวน</th>
                                        <th>AD</th>
                                        <th>CHD</th>
                                        <th>INF</th>
                                        <th>FOC</th>
                                        <th>รวม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($prod_id)) {
                                        $age = array_count_values($prod_id);
                                        arsort($age);
                                        foreach ($age as $x => $x_value) {
                                            if (!empty($prod_name[$x])) {
                                    ?>
                                                <tr>
                                                    <td><span class="font-weight-bolder"><?php echo $prod_name[$x]; ?></span></td>
                                                    <td><span class="font-weight-bolder"><?php echo $x_value; ?></span></td>
                                                    <td><span class="font-weight-bolder"><?php echo !empty($prod_adult[$x]) ? array_sum($prod_adult[$x]) : 0; ?></span></td>
                                                    <td><span class="font-weight-bolder"><?php echo !empty($prod_child[$x]) ? array_sum($prod_child[$x]) : 0; ?></span></td>
                                                    <td><span class="font-weight-bolder"><?php echo !empty($prod_infant[$x]) ? array_sum($prod_infant[$x]) : 0; ?></span></td>
                                                    <td><span class="font-weight-bolder"><?php echo !empty($prod_foc[$x]) ? array_sum($prod_foc[$x]) : 0; ?></span></td>
                                                    <td><span class="font-weight-bolder"><?php echo !empty($prod_foc[$x]) &&  !empty($prod_adult[$x]) && !empty($prod_child[$x]) && !empty($prod_infant[$x]) ? array_sum($prod_adult[$x]) + array_sum($prod_child[$x]) + array_sum($prod_infant[$x])  + array_sum($prod_foc[$x]) : 0; ?></span></td>
                                                </tr>
                                    <?php }
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Products Table Card -->
        </div>
    </div>

    <div class="card">
        <div class="card-datatable pt-0">
            <table class="booking-list-table table table-responsive">
                <thead class="thead-light">
                    <tr>
                        <th class="cell-fit">STATUS</th>
                        <th class="cell-fit">PAYMENT</th>
                        <th>โปรแกรม</th>
                        <th>TRAVEL DATE / BOOKING DATE</th>
                        <th>AGENT NAME</th>
                        <th>ชื่อลูกค้า</th>
                        <th>โรงแรม</th>
                        <th>ห้อง</th>
                        <th class="text-center">A</th>
                        <th class="text-center">C</th>
                        <!-- <th rowspan="2" class="text-center">รวม</th> -->
                        <th>เวลารับ</th>
                        <th>VOUCHER NO.</th>
                        <th>Remark</th>
                        <th>BOKING NO.</th>
                    </tr>
                    <!-- <tr>
                        <th>Adult</th>
                        <th>Child</th>
                    </tr> -->
                </thead>
                <?php if ($bookings) { ?>
                    <tbody>
                        <?php
                        $total_t = 0;
                        for ($i = 0; $i < count($bo_id); $i++) {
                            $href = 'href="./?pages=booking/edit&id=' . $bo_id[$i] . '" style="color:#6E6B7B" class="btn-page-block-spinner"';
                        ?>
                            <tr>
                                <td><a <?php echo $href; ?>>
                                        <?php echo $status[$i]; ?>
                                    </a>
                                </td>
                                <td><a <?php echo $href; ?>>
                                        <?php echo !empty($bopay_id[$bo_id[$i]]) ? '<span class="badge badge-pill ' . $bopay_name_class[$bo_id[$i]] . ' text-capitalized"> ' . $bopay_paid_name[$bo_id[$i]] . ' </span>' : '<span class="badge badge-pill badge-light-primary text-capitalized"> ไม่ได้ระบุ </span>'; ?>
                                    </a>
                                </td>
                                <td>
                                    <a <?php echo $href; ?>>
                                        <?php echo (!empty($bp_id[$i])) ? $product_name[$i] : 'ไม่มีสินค้า'; ?>
                                    </a>
                                </td>
                                <td>
                                    <a <?php echo $href; ?>>
                                        <span class="text-nowrap">
                                            <?php echo (!empty($bp_id[$i])) ? date('j F Y', strtotime($travel_date[$i])) . ' </br><small>' . date('j F Y', strtotime($created_at[$i])) . '</small>' : 'ไม่มีสินค้า'; ?>
                                        </span>
                                    </a>
                                </td>
                                <td>
                                    <a <?php echo $href; ?>>
                                        <?php echo $agent_name[$i]; ?></a>
                                    </a>
                                </td>
                                <td>
                                    <a <?php echo $href; ?>>
                                        <?php echo $cus_name[$i]; ?></a>
                                    </a>
                                </td>
                                <td>
                                    <a <?php echo $href; ?>>
                                        <?php echo !empty($pickup_name[$i]) ? $hotel_pickup[$i] . ' (' . $pickup_name[$i] . ')' : $hotel_pickup[$i]; ?></a>
                                    </a>
                                </td>
                                <td>
                                    <a <?php echo $href; ?>>
                                        <?php echo $room_no[$i]; ?>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a <?php echo $href; ?>>
                                        <?php echo $adult[$i]; ?></a>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a <?php echo $href; ?>>
                                        <?php echo $child[$i]; ?></a>
                                    </a>
                                </td>
                                <!-- <td class="text-center">
                                    <a <?php echo $href; ?>>
                                        <?php
                                        $total_sum = $rate_total[$i];
                                        // $total_sum = $transfer_type[$i] == 1 ? $total_sum + ($btr_rate_adult[$i] + $btr_rate_child[$i] + $btr_rate_infant[$i]) : $total_sum;
                                        // $total_sum = $transfer_type[$i] == 2 ? $total_sum + array_sum($rate_private[$bo_id[$i]]) : $total_sum;
                                        $total_sum = !empty($bec_id[$bo_id[$i]]) ? $total_sum + array_sum($bec_rate_total[$bo_id[$i]]) : $total_sum;
                                        $total_t = $total_t + $total_sum;
                                        $total_sum = !empty($discount[$i]) ? $total_sum - $discount[$i] : $total_sum;
                                        echo number_format($total_sum);
                                        ?></a>
                                    </a>
                                </td> -->
                                <td>
                                    <a <?php echo $href; ?>>
                                        <?php echo $start_pickup[$i]; ?>
                                    </a>
                                </td>
                                <td>
                                    <a <?php echo $href; ?>>
                                        <?php echo $voucher_no[$i]; ?>
                                    </a>
                                </td>
                                <td>
                                    <a <?php echo $href; ?>>
                                        <?php if (!empty($bec_id[$bo_id[$i]])) {
                                            for ($e = 0; $e < count($bec_name[$bo_id[$i]]); $e++) {
                                                echo $e == 0  ? $bec_name[$bo_id[$i]][$e] : ' : ' . $bec_name[$bo_id[$i]][$e];
                                            }
                                        }
                                        echo !empty($note[$i]) ? ' / ' . $note[$i] : ''; ?>
                                    </a>
                                </td>
                                <td>
                                    <a <?php echo $href; ?>>
                                        <?php echo $book_full[$i]; ?>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                <?php }
                // echo $total_t; echo ' : ' .  array_sum($discount); ?>
            </table>
        </div>
    </div>

<?php
    // $total_sum = $rate_total[$i];
    // $total_sum = $transfer_type[$i] == 1 ? $total_sum + ($btr_rate_adult[$i] + $btr_rate_child[$i] + $btr_rate_infant[$i]) : $total_sum;
    // $total_sum = $transfer_type[$i] == 2 ? $total_sum + array_sum($rate_private[$bo_id[$i]]) : $total_sum;
    // $total_sum = !empty($bec_id[$bo_id[$i]]) ? $total_sum + array_sum($bec_rate_total[$bo_id[$i]]) : $total_sum;
    // $total_sum = !empty($discount[$i]) ? $total_sum - $discount[$i] : $total_sum;
    // echo number_format($total_sum);
} else {
    echo $products = false;
}
