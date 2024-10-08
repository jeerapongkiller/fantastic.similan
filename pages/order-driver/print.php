<?php
require_once 'controllers/Order.php';

$today = date("Y-m-d");
$tomorrow = new DateTime('tomorrow');
$manageObj = new Order();

if (isset($_GET['action']) && $_GET['action'] == "print" && !empty($_GET['date_travel'])) {
    // get value from ajax
    $date_travel = $_GET['date_travel'] != "" ? $_GET['date_travel'] : '0000-00-00';
    $search_car = $_GET['search_car'] != "" ? $_GET['search_car'] : 'all';
    $search_retrun = $_GET['retrun'] != "" ? $_GET['retrun'] : 0;

    $sum_ad = 0;
    $sum_chd = 0;
    $sum_inf = 0;
    $sum_foc = 0;
    # --- get data show list boats manage --- #
    # --- show list boats booking --- #
    $first_booking = array();
    $first_prod = array();
    $first_cus = array();
    $first_program = array();
    $frist_bt[1] = [];
    $first_manage = [];
    $first_bo = [];
    $first_trans = [];
    $frist_bomange = [];
    $bookings = $manageObj->showlisttransfers('all', 1, $date_travel, $search_car, 'all');
    # --- Check products --- #
    if (!empty($bookings)) {
        foreach ($bookings as $booking) {
            # --- get value Programe --- #
            if (in_array($booking['product_id'], $first_prod) == false) {
                $first_prod[] = $booking['product_id'];
                $programe_id[] = !empty($booking['product_id']) ? $booking['product_id'] : 0;
                $programe_name[] = !empty($booking['product_name']) ? $booking['product_name'] : '';
                $programe_type[] = !empty($booking['pg_type_name']) ? $booking['pg_type_name'] : '';
            }

            # --- get value booking --- #
            if (in_array($booking['id'], $first_booking) == false) {
                $first_booking[] = $booking['id'];
                $bo_id[$booking['product_id']][] = !empty($booking['id']) ? $booking['id'] : 0;
                $bo_type[$booking['id']] = !empty($booking['booktye_name']) ? $booking['booktye_name'] : '';
                $status_by_name[$booking['id']] = !empty($booking['status_by']) ? $booking['stabyFname'] . ' ' . $booking['stabyLname'] : '';
                $status[$booking['id']] = '<span class="badge badge-pill ' . $booking['booksta_class'] . ' text-capitalized"> ' . $booking['booksta_name'] . ' </span>';
                $category_name[$booking['id']] = !empty($booking['category_name']) ? $booking['category_name'] : '';
                $adult[$booking['id']] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                $child[$booking['id']] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                $infant[$booking['id']] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                $foc[$booking['id']] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
                $cate_transfer[$booking['id']] = !empty($booking['category_transfer']) ? $booking['category_transfer'] : 0;
                $cus_name[$booking['id']][] = !empty($booking['cus_name']) ? $booking['cus_name'] : '';
                $sender[$booking['id']] = !empty($booking['sender']) ? $booking['sender'] : '';
                $note[$booking['id']] = !empty($booking['bp_note']) ? $booking['bp_note'] : '';
                $bp_id[$booking['id']] = !empty($booking['bp_id']) ? $booking['bp_id'] : 0;
                $cot[$booking['id']] = !empty($booking['cot']) ? $booking['cot'] : 0;
                $book_full[$booking['id']] = !empty($booking['book_full']) ? $booking['book_full'] : '';
                $voucher_no[$booking['id']] = !empty(!empty($booking['voucher_no_agent'])) ? $booking['voucher_no_agent'] : '';
                $travel_date[$booking['id']] = !empty(!empty($booking['travel_date'])) ? $booking['travel_date'] : '0000-00-00';
                $product_id[$booking['id']] = !empty(!empty($booking['product_id'])) ? $booking['product_id'] : '';
                $product_name[$booking['id']] = !empty(!empty($booking['product_name'])) ? $booking['product_name'] : '';
                $pier_name[$booking['id']] = !empty(!empty($booking['pier_name'])) ? $booking['pier_name'] : '';
                $agent_name[$booking['id']] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
                $boat_name[$booking['id']] = !empty($booking['boat_name']) ? $booking['boat_name'] : $booking['outside_boat'];
                # --- array programe --- #
                // $check_mange[$booking['product_id']][] = !empty($booking['mange_id']) ? $booking['mange_id'] : 0;
                $prod_adult[$booking['product_id']][] = !empty($booking['bp_adult']) && $booking['mange_id'] == 0 ? $booking['bp_adult'] : 0;
                $prod_child[$booking['product_id']][] = !empty($booking['bp_child']) && $booking['mange_id'] == 0 ? $booking['bp_child'] : 0;
                $prod_infant[$booking['product_id']][] = !empty($booking['bp_infant']) && $booking['mange_id'] == 0 ? $booking['bp_infant'] : 0;
                $prod_foc[$booking['product_id']][] = !empty($booking['bp_foc']) && $booking['mange_id'] == 0 ? $booking['bp_foc'] : 0;
            }

            if (in_array($booking['id'], $first_bo) == false) {
                $first_bo[] = $booking['id'];
                $book['id'][$booking['mange_id']][] = !empty($booking['id']) ? $booking['id'] : 0;
                $book['bt_id'][$booking['mange_id']][] = !empty($booking['bt_id']) ? $booking['bt_id'] : 0;
                $book['product_id'][$booking['mange_id']][] = !empty($booking['product_id']) ? $booking['product_id'] : 0;
                $book['product_name'][$booking['mange_id']][] = !empty($booking['product_name']) ? $booking['product_name'] : '';
                $book['pier_name'][$booking['mange_id']][] = !empty($booking['pier_name']) ? $booking['pier_name'] : '';
                $book['agent_name'][$booking['mange_id']][] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
                $book['sender'][$booking['mange_id']][] = !empty($booking['sender']) ? $booking['sender'] : '';
                $book['cus_name'][$booking['mange_id']][] = !empty($booking['cus_name']) ? $booking['cus_name'] : '';
                $book['voucher'][$booking['mange_id']][] = !empty($booking['voucher_no_agent']) ? $booking['voucher_no_agent'] : '';
                $book['book_full'][$booking['mange_id']][] = !empty($booking['book_full']) ? $booking['book_full'] : '';
                $book['bp_note'][$booking['mange_id']][] = !empty($booking['bp_note']) ? $booking['bp_note'] : '';
                $book['start_pickup'][$booking['mange_id']][] = !empty($booking['start_pickup']) && $booking['start_pickup']  != '00:00:00' ? date('H:i', strtotime($booking['start_pickup'])) : '00:00';
                $book['end_pickup'][$booking['mange_id']][] = !empty($booking['end_pickup']) && $booking['end_pickup']  != '00:00:00' ? date('H:i', strtotime($booking['end_pickup'])) : '00:00';
                $book['hotel_name'][$booking['mange_id']][] = !empty($booking['hotel_name']) ? $booking['hotel_name'] : 'ไม่ได้ระบุ';
                $book['room_no'][$booking['mange_id']][] = !empty($booking['room_no']) ? $booking['room_no'] : 'ไม่ได้ระบุ';
                $book['bt_adult'][$booking['mange_id']][] = !empty($booking['bt_adult']) ? $booking['bt_adult'] : 0;
                $book['bt_child'][$booking['mange_id']][] = !empty($booking['bt_child']) ? $booking['bt_child'] : 0;
                $book['bt_infant'][$booking['mange_id']][] = !empty($booking['bt_infant']) ? $booking['bt_infant'] : 0;
                $book['bt_foc'][$booking['mange_id']][] = !empty($booking['bt_foc']) ? $booking['bt_foc'] : 0;
                $book['adult'][$booking['mange_id']][] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                $book['child'][$booking['mange_id']][] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                $book['infant'][$booking['mange_id']][] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                $book['foc'][$booking['mange_id']][] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
                $book['driver'][$booking['mange_id']][] = !empty($booking['driver_id']) ? $booking['driver_name'] : '';
                $book['note'][$booking['mange_id']][] = !empty($booking['bp_note']) ? $booking['bp_note'] : '';
                $book['boat_id'][$booking['mange_id']][] = !empty($booking['boat_id']) ? $booking['boat_id'] : 0;
                $book['boat_name'][$booking['mange_id']][] = !empty($booking['boat_name']) ? $booking['boat_name'] : $booking['outside_boat'];
                $book['color_id'][$booking['mange_id']][] = !empty($booking['color_id']) ? $booking['color_id'] : '';
            }

            # --- get value booking transfer --- #
            if ((in_array($booking['bt_id'], $frist_bt[1]) == false)) {
                $frist_bt[1][] = $booking['bt_id'];
                $bt_id[$booking['id']][1] = !empty($booking['bt_id']) ? $booking['bt_id'] : 0;
                $mange_id[$booking['id']][1] = !empty($booking['mange_id']) ? $booking['mange_id'] : 0;
                $arrange[$booking['id']][1] = !empty($booking['arrange']) ? $booking['arrange'] : 0;
                $bt_adult[$booking['id']][1] = !empty($booking['bt_adult']) ? $booking['bt_adult'] : 0;
                $bt_child[$booking['id']][1] = !empty($booking['bt_child']) ? $booking['bt_child'] : 0;
                $bt_infant[$booking['id']][1] = !empty($booking['bt_infant']) ? $booking['bt_infant'] : 0;
                $bt_foc[$booking['id']][1] = !empty($booking['bt_foc']) ? $booking['bt_foc'] : 0;
                $hotel_name[$booking['id']][1] = !empty($booking['pickup_name']) ? $booking['pickup_name'] : '';
                $hotel_name[$booking['id']][2] = !empty($booking['dropoff_name']) ? $booking['dropoff_name'] : '';
                $room_no[$booking['id']][1] = !empty($booking['room_no']) ? $booking['room_no'] : '';
                $start_pickup[$booking['id']][1] = !empty($booking['start_pickup']) && $booking['start_pickup'] != '00:00' ? $booking['start_pickup'] : '00:00';
                $end_pickup[$booking['id']][1] = !empty($booking['end_pickup']) && $booking['end_pickup'] != '00:00' ? $booking['end_pickup'] : '00:00';
                $outside[$booking['id']][1] = !empty($booking['outside']) ? $booking['outside'] : '';
                $outside[$booking['id']][2] = !empty($booking['outside_dropoff']) ? $booking['outside_dropoff'] : '';
                $zone_name[$booking['id']][1] = !empty($booking['zonep_name']) ? $booking['zonep_name'] : '';
                $zone_name[$booking['id']][2] = !empty($booking['zoned_name']) ? $booking['zoned_name'] : '';

                $check_mange[$booking['product_id']][1][] = !empty($booking['mange_id']) ? $booking['mange_id'] : 0;

                if (($booking['pickup_id'] != $booking['dropoff_id']) || ($booking['outside'] != $booking['outside_dropoff'])) {
                    $check_dropoff[$booking['product_id']][] = !empty($booking['id']) ? $booking['id'] : 0;
                }

                if ($booking['mange_id'] > 0) {
                    $bo_manage['id'][$booking['mange_id']][1][] = !empty($booking['id']) ? $booking['id'] : 0;
                }
            }

            if ($booking['mange_id'] > 0 && (in_array($booking['bomange_id'], $frist_bomange) == false)) {
                $frist_bomange[] = $booking['bomange_id'];
                $reteun = $booking['mange_pickup'] > 0 ? 1 : 2;
                $bomange_id[$booking['mange_id']][] = !empty($booking['bomange_id']) ? $booking['bomange_id'] : 0;
                $bomange_bo[$booking['mange_id']][] = !empty($booking['id']) ? $booking['id'] : 0;
                $check_book[$reteun][] = !empty($booking['id']) ? $booking['id'] : 0;
                $check_bt[$reteun][] = !empty($booking['bt_id']) ? $booking['bt_id'] : 0;
                $check_mange[$reteun][$booking['product_id']][] = !empty($booking['mange_id']) ? $booking['mange_id'] : 0;
            }
        }
    }

    $manages = $manageObj->show_manage_transfer($date_travel, 1);
    foreach ($manages as $manage) {
        if (in_array($manage['id'], $first_manage) == false) {
            $first_manage[] = $manage['id'];
            $mange['id'][] = !empty($manage['id']) ? $manage['id'] : 0;
            $mange['pickup'][] = !empty($manage['pickup']) ? $manage['pickup'] : 0;
            $mange['dropoff'][] = !empty($manage['dropoff']) ? $manage['dropoff'] : 0;
            $mange['car'][] = !empty($manage['car_id']) ? $manage['car_name'] : '';
            $mange['driver'][] = !empty($manage['driver']) ? $manage['driver'] : '';
            $mange['license'][] = !empty($manage['license']) ? $manage['license'] : '';
            $mange['telephone'][] = !empty($manage['telephone']) ? $manage['telephone'] : '';
            $mange['driver_id'][] = !empty($manage['driver_id']) ? $manage['driver_id'] : 0;
            $mange['driver_name'][] = !empty($manage['driver_id']) ? $manage['driver_name'] : '';
            $mange['guide_id'][] = !empty($manage['guide_id']) ? $manage['guide_id'] : 0;
            $mange['guide_name'][] = !empty($manage['guide_id']) ? $manage['guide_name'] : '';
            $mange['car_id'][] = !empty($manage['car_id']) ? $manage['car_id'] : 0;
            $mange['car_name'][] = !empty($manage['car_id']) ? $manage['car_name'] : $manage['outside_car'];
            $mange['outside_car'][] = !empty($manage['outside_car']) ? $manage['outside_car'] : '';
            $mange['note'][] = !empty($manage['note']) ? $manage['note'] : '';
        }
    }
?>
    <div id="div-driver-job-image" style="background-color: #FFF;">
        <!-- Header starts -->
        <div class="card-body pb-0">
            <div class="row">
                <span class="col-6 brand-logo"><img src="app-assets/images/logo/logo-500.png" height="50"></span>
                <span class="col-6 text-right" style="color: #000;">
                    โทร : 062-3322800 / 084-7443000 / 083-1757444 </br>
                    Email : Fantasticsimilantravel11@gmail.com
                </span>
            </div>
            <div class="text-center card-text">
                <h4 class="font-weight-bolder">ใบจัดรถ</h4>
                <div class="badge badge-pill badge-light-danger">
                    <h5 class="m-0 pl-1 pr-1 text-danger"><?php echo date('j F Y', strtotime($date_travel)); ?></h5>
                </div>
            </div>
        </div>
        <!-- Header ends -->
        <!-- Body starts -->
        <?php
        if (!empty($mange['id']) && $search_retrun == 1) {
            for ($i = 0; $i < count($mange['id']); $i++) {
                $return = $search_retrun == 1 ? $mange['pickup'][$i] == 1 ? true : false : false;
                $return = $search_retrun == 2 ? $mange['dropoff'][$i] == 1 ? true : false : $return;
                $text_retrun = $mange['pickup'][$i] == $search_retrun ? 'Pickup' : 'Dropoff';
                $mange_retrun = 1;
                if ($bomange_bo[$mange['id'][$i]] && $return == true) {
        ?>
                    <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 pt-1">
                        <div class="col-4 text-left text-bold h4"></div>
                        <div class="col-4 text-center text-bold h4"><?php echo !empty($mange['car'][$i]) ? $mange['car'][$i] : ''; ?></div>
                        <div class="col-4 text-right mb-50"></div>
                    </div>

                    <div class="table-responsive" id="order-job-search-table">

                        <table class="tableprint">
                            <thead class="">
                                <tr>
                                    <th colspan="4" style="border-bottom: 1px solid #fff;">ป้ายทะเบียน : <?php echo $mange['license'][$i]; ?></th>
                                    <th colspan="8" style="border-bottom: 1px solid #fff;">โทรศัพท์ : <?php echo $mange['telephone'][$i]; ?></th>
                                </tr>
                                <tr>
                                    <th width="5%">เวลารับ</th>
                                    <th width="15%">โปรแกรม</th>
                                    <th width="10%">เอเยนต์</th>
                                    <th width="10%" class="text-center">V/C</th>
                                    <th width="15%">โรงแรม</th>
                                    <th width="6%">ห้อง</th>
                                    <th width="15%">ชื่อลูกค้า</th>
                                    <th width="1%" class="text-center">A</th>
                                    <th width="1%" class="text-center">C</th>
                                    <th width="1%" class="text-center">Inf</th>
                                    <th width="1%" class="text-center">FOC</th>
                                    <th width="15%">Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_tourist = 0;
                                $total_adult = 0;
                                $total_child = 0;
                                $total_infant = 0;
                                $total_foc = 0;
                                for ($a = 0; $a < count($bomange_bo[$mange['id'][$i]]); $a++) {
                                    $id = $bomange_bo[$mange['id'][$i]][$a];
                                    $total_tourist = $total_tourist + $bt_adult[$id][$mange_retrun] + $bt_child[$id][$mange_retrun] + $bt_infant[$id][$mange_retrun] + $bt_foc[$id][$mange_retrun];
                                    $total_adult = $total_adult + $bt_adult[$id][$mange_retrun];
                                    $total_child = $total_child + $bt_child[$id][$mange_retrun];
                                    $total_infant = $total_infant + $bt_infant[$id][$mange_retrun];
                                    $total_foc = $total_foc + $bt_foc[$id][$mange_retrun];
                                ?>
                                    <tr>
                                        <td><?php echo $start_pickup[$id][$mange_retrun] != '00:00' ? date('H:i', strtotime($start_pickup[$id][$mange_retrun])) . ' - ' . date('H:i', strtotime($end_pickup[$id][$mange_retrun])) : ''; ?></td>
                                        <td><?php echo $product_name[$id]; ?></td>
                                        <td><?php echo $agent_name[$id]; ?></td>
                                        <td class="text-center"><?php echo !empty($voucher_no[$id]) ? $voucher_no[$id] : $book_full[$id]; ?></td>
                                        <td><?php echo $mange['pickup'][$i] == 1 ? !empty($outside[$id][1]) ? $outside[$id][1] . ' (' . $zone_name[$id][1] . ')' : $hotel_name[$id][1] . ' (' . $zone_name[$id][1] . ')' : $outside[$id][2]; ?></td>
                                        <td><?php echo $room_no[$id][$mange_retrun]; ?></td>
                                        <td><?php echo $cus_name[$id][0]; ?></td>
                                        <td class="text-center"><?php echo $bt_adult[$id][$mange_retrun]; ?></td>
                                        <td class="text-center"><?php echo $bt_child[$id][$mange_retrun]; ?></td>
                                        <td class="text-center"><?php echo $bt_infant[$id][$mange_retrun]; ?></td>
                                        <td class="text-center"><?php echo $bt_foc[$id][$mange_retrun]; ?></td>
                                        <td><?php echo $note[$id]; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <div class="text-center mt-1 pb-2">
                            <h4>
                                <div class="badge badge-pill badge-light-warning">
                                    <b class="text-danger">TOTAL <?php echo $total_tourist; ?></b> | <?php echo $total_adult; ?> <?php echo $total_child; ?> <?php echo $total_infant; ?> <?php echo $total_foc; ?>
                                </div>
                            </h4>
                        </div>
                    </div>

                    <div class="pagebreak"></div>
            <?php }
            } ?>
            <?php } elseif (!empty($programe_id) && $search_retrun == 2) {
            $retrun = 1;
            for ($a = 0; $a < count($programe_id); $a++) {
                if (!empty($check_dropoff[$programe_id[$a]])) {
            ?>
                    <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 pt-1">
                        <div class="col-4 text-left text-bold h4"></div>
                        <div class="col-4 text-center text-bold h4"><?php echo $programe_name[$a];  ?></div>
                        <div class="col-4 text-right mb-50"></div>
                    </div>

                    <table class="tableprint">
                        <thead class="">
                            <tr>
                                <th width="5%">เวลารับ</th>
                                <!-- <th width="15%">โปรแกรม</th> -->
                                <th width="10%">เอเยนต์</th>
                                <th width="10%" class="text-center">V/C</th>
                                <th width="15%">โรงแรม</th>
                                <th width="6%">ห้อง</th>
                                <th width="15%">ชื่อลูกค้า</th>
                                <th width="1%" class="text-center">A</th>
                                <th width="1%" class="text-center">C</th>
                                <th width="1%" class="text-center">Inf</th>
                                <th width="1%" class="text-center">FOC</th>
                                <th width="15%">Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_tourist = 0;
                            $total_adult = 0;
                            $total_child = 0;
                            $total_infant = 0;
                            $total_foc = 0;
                            for ($i = 0; $i < count($check_dropoff[$programe_id[$a]]); $i++) {
                                if (empty($check_book[2]) || (!empty($check_book[2]) && in_array($check_dropoff[$programe_id[$a]][$i], $check_book[2]) == false)) {
                                    $id = $check_dropoff[$programe_id[$a]][$i];
                                    $total_tourist = $total_tourist + $adult[$id] + $child[$id] + $infant[$id] + $foc[$id];
                                    $total_adult = $total_adult + $adult[$id];
                                    $total_child = $total_child + $child[$id];
                                    $total_infant = $total_infant + $infant[$id];
                                    $total_foc = $total_foc + $foc[$id];
                            ?>
                                    <tr>
                                        <td><?php echo !empty($start_pickup[$id][$retrun]) ? date("H:i", strtotime($start_pickup[$id][$retrun])) . ' - ' . date('H:i', strtotime($end_pickup[$id][$mange_retrun])) : '00:00'; ?></td>
                                        <td><?php echo $agent_name[$id]; ?></a></td>
                                        <td><?php echo !empty($voucher_no[$id]) ? $voucher_no[$id] : $book_full[$id]; ?></td>
                                        <td><?php echo (!empty($outside[$id][2])) ? $outside[$id][2] . ' (' . $zone_name[$id][2] . ')' : $hotel_name[$id][2] . ' (' . $zone_name[$id][2] . ')'; ?></td>
                                        <td><?php echo (!empty($room_no[$id][$retrun])) ? $room_no[$id][$retrun] : ''; ?></td>
                                        <td><?php echo !empty($cus_name[$id][0]) ? $cus_name[$id][0] : ''; ?></td>
                                        <td class="text-center"><?php echo $bt_adult[$id][$retrun]; ?></td>
                                        <td class="text-center"><?php echo $bt_child[$id][$retrun]; ?></td>
                                        <td class="text-center"><?php echo $bt_infant[$id][$retrun]; ?></td>
                                        <td class="text-center"><?php echo $bt_foc[$id][$retrun]; ?></td>
                                        <td><?php echo $note[$id]; ?></td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>

                    <div class="text-center mt-1 pb-2">
                        <h4>
                            <div class="badge badge-pill badge-light-warning">
                                <b class="text-danger">TOTAL <?php echo $total_tourist; ?></b> | <?php echo $total_adult; ?> <?php echo $total_child; ?> <?php echo $total_infant; ?> <?php echo $total_foc; ?>
                            </div>
                        </h4>
                    </div>
            <?php
                }
            }
            ?>
        <?php } ?>
        <!-- Body ends -->
    </div>
    <input type="hidden" id="name_img" name="name_img" value="<?php echo 'ใบจัดรถ - ' . date('j F Y', strtotime($date_travel)); ?>">
<?php
}
?>