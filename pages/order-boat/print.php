<?php
require_once 'controllers/Order.php';

$today = date("Y-m-d");
$tomorrow = new DateTime('tomorrow');
$manageObj = new Order();

if (isset($_GET['action']) && $_GET['action'] == "print" && !empty($_GET['date_travel'])) {
    // get value from ajax
    $date_travel = $_GET['date_travel'] != "" ? $_GET['date_travel'] : '0000-00-00';
    $search_boat = $_GET['search_boat'] != "" ? $_GET['search_boat'] : 'all';

    # --- show list boats booking --- #
    $first_booking = array();
    $first_prod = array();
    $first_cus = array();
    $first_program = array();
    $first_ext = array();
    $first_bomanage = array();
    $first_bo = [];
    $first_trans = [];
    $bookings = $manageObj->showlistboats('list', 0, $date_travel, $search_boat, 'all');
    # --- Check products --- #
    if (!empty($bookings)) {
        foreach ($bookings as $booking) {
            # --- get value Programe --- #
            if (in_array($booking['product_id'], $first_prod) == false) {
                $first_prod[] = $booking['product_id'];
                $programe_id[] = !empty($booking['product_id']) ? $booking['product_id'] : 0;
                $programe_name[] = !empty($booking['product_name']) ? $booking['product_name'] : '';
                $programe_type[] = !empty($booking['pg_type_name']) ? $booking['pg_type_name'] : '';
                $programe_pier[] = !empty($booking['pier_name']) ? $booking['pier_name'] : '';
            }
            # --- get value booking --- #
            if (in_array($booking['id'], $first_booking) == false) {
                $first_booking[] = $booking['id'];
                $bo_id[] = !empty($booking['id']) ? $booking['id'] : 0;
                $status_by_name[$booking['id']] = !empty($booking['status_by']) ? $booking['stabyFname'] . ' ' . $booking['stabyLname'] : '';
                $status[$booking['id']] = '<span class="badge badge-pill ' . $booking['booksta_class'] . ' text-capitalized"> ' . $booking['booksta_name'] . ' </span>';
                $category_name[$booking['id']] = !empty($booking['category_name']) ? $booking['category_name'] : '';
                $adult[$booking['id']] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                $child[$booking['id']] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                $infant[$booking['id']] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                $foc[$booking['id']] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
                $cate_transfer[$booking['id']] = !empty($booking['category_transfer']) ? $booking['category_transfer'] : 0;
                $cate_transfer[$booking['id']] = !empty($booking['category_transfer']) ? $booking['category_transfer'] : 0;
                $hotel_name[$booking['id']] = !empty($booking['pickup_name']) ? $booking['pickup_name'] : '';
                $zone_pickup[$booking['id']] = !empty($booking['zonep_name']) ? ' (' . $booking['zonep_name'] . ')' : '';
                $dropoff_name[$booking['id']] = !empty($booking['dropoff_name']) ? $booking['dropoff_name'] : '';
                $zone_dropoff[$booking['id']] = !empty($booking['zoned_name']) ? ' (' . $booking['zoned_name'] . ')' : '';
                $room_no[$booking['id']] = !empty($booking['room_no']) ? $booking['room_no'] : '';
                $start_pickup[$booking['id']] = !empty($booking['start_pickup']) && $booking['start_pickup'] != '00:00' ? $booking['start_pickup'] : '00:00';
                $outside[$booking['id']] = !empty($booking['outside']) ? $booking['outside'] : '';
                $outside_dropoff[$booking['id']] = !empty($booking['outside_dropoff']) ? $booking['outside_dropoff'] : '';
                $pickup_type[$booking['id']] = !empty($booking['pickup_type']) ? $booking['pickup_type'] : 0;
                $sender[$booking['id']] = !empty($booking['sender']) ? $booking['sender'] : '';
                $note[$booking['id']] = !empty($booking['bp_note']) ? $booking['bp_note'] : '';
                $bp_id[$booking['id']] = !empty($booking['bp_id']) ? $booking['bp_id'] : 0;
                $cot[$booking['id']] = !empty($booking['total_paid']) ? $booking['total_paid'] : 0;
                $book_full[$booking['id']] = !empty($booking['book_full']) ? $booking['book_full'] : '';
                $voucher_no[$booking['id']] = !empty(!empty($booking['voucher_no_agent'])) ? $booking['voucher_no_agent'] : '';
                $travel_date[$booking['id']] = !empty(!empty($booking['travel_date'])) ? $booking['travel_date'] : '0000-00-00';
                $product_name[$booking['id']] = !empty(!empty($booking['product_name'])) ? $booking['product_name'] : '';
                $agent_name[$booking['id']] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
                $mange_id[$booking['id']] = !empty($booking['mange_id']) ? $booking['mange_id'] : 0;
                $bo_mange_id[$booking['id']] = !empty($booking['boman_id']) ? $booking['boman_id'] : 0;
                $boat_id[$booking['id']] = !empty($booking['boat_id']) ? $booking['boat_id'] : '';
                $boat_name[$booking['id']] = !empty($booking['boat_name']) ? $booking['boat_name'] : '';
                $color_id[$booking['id']] = !empty($booking['color_id']) ? $booking['color_id'] : '';
                # --- array programe --- #
                $check_mange[$booking['product_id']][] = !empty($booking['mange_id']) ? $booking['mange_id'] : 0;
                $prod_adult[$booking['product_id']][] = !empty($booking['bp_adult']) && $booking['mange_id'] == 0 ? $booking['bp_adult'] : 0;
                $prod_child[$booking['product_id']][] = !empty($booking['bp_child']) && $booking['mange_id'] == 0 ? $booking['bp_child'] : 0;
                $prod_infant[$booking['product_id']][] = !empty($booking['bp_infant']) && $booking['mange_id'] == 0 ? $booking['bp_infant'] : 0;
                $prod_foc[$booking['product_id']][] = !empty($booking['bp_foc']) && $booking['mange_id'] == 0 ? $booking['bp_foc'] : 0;
            }
            # --- get value customer --- #
            if (in_array($booking['cus_id'], $first_cus) == false) {
                $first_cus[] = $booking['cus_id'];
                $cus_id[$booking['id']][] = !empty($booking['cus_id']) ? $booking['cus_id'] : 0;
                $cus_name[$booking['id']][] = !empty($booking['cus_name']) ? $booking['cus_name'] : '';
                $passport[$booking['id']][] = !empty($booking['id_card']) ? $booking['id_card'] : '';
                $birth_date[$booking['id']][] = !empty($booking['birth_date']) && $booking['birth_date'] != '0000-00-00' ? date('j F Y', strtotime($booking['birth_date'])) : '';
                $nation_name[$booking['id']][] = !empty($booking['nation_name']) ? $booking['nation_name'] : '';
            }

            if (in_array($booking['id'], $first_bo) == false) {
                $first_bo[] = $booking['id'];
                $book['id'][$booking['mange_id']][] = !empty($booking['id']) ? $booking['id'] : 0;
                $book['voucher'][$booking['mange_id']][] = !empty($booking['voucher_no_agent']) ? $booking['voucher_no_agent'] : '';
                $book['book_full'][$booking['mange_id']][] = !empty($booking['book_full']) ? $booking['book_full'] : '';
                $book['sender'][$booking['mange_id']][] = !empty($booking['sender']) ? $booking['sender'] : '';
                $book['start_pickup'][$booking['mange_id']][] = !empty($booking['start_pickup']) ? date('H:i', strtotime($booking['start_pickup'])) : '';
                $book['end_pickup'][$booking['mange_id']][] = !empty($booking['end_pickup']) ? date('H:i', strtotime($booking['end_pickup'])) : '';
                $book['hotel'][$booking['mange_id']][] = !empty($booking['pickup_name']) ? $booking['pickup_name'] : '';
                $book['room_no'][$booking['mange_id']][] = !empty($booking['room_no']) ? $booking['room_no'] : '';
                $book['cus_name'][$booking['mange_id']][] = !empty($booking['cus_name']) ? $booking['cus_name'] : '';
                $book['comp_name'][$booking['mange_id']][] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
                $book['adult'][$booking['mange_id']][] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                $book['child'][$booking['mange_id']][] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                $book['infant'][$booking['mange_id']][] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                $book['foc'][$booking['mange_id']][] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
                $book['rate_adult'][$booking['mange_id']][] = !empty($booking['rate_adult']) ? $booking['rate_adult'] : 0;
                $book['rate_child'][$booking['mange_id']][] = !empty($booking['rate_child']) ? $booking['rate_child'] : 0;
                $book['rate_infant'][$booking['mange_id']][] = !empty($booking['rate_infant']) ? $booking['rate_infant'] : 0;
                $book['rate_private'][$booking['mange_id']][] = !empty($booking['rate_private']) ? $booking['rate_private'] : 0;
                $book['discount'][$booking['mange_id']][] = !empty(!empty($booking['bp_discount'])) ? $booking['bp_discount'] : 0;
                $book['note'][$booking['mange_id']][] = !empty($booking['bp_note']) ? $booking['bp_note'] : '';
                $book['cot'][$booking['mange_id']][] = !empty($booking['total_paid']) ? $booking['total_paid'] : 0;
                $book['total'][$booking['mange_id']][] = $booking['booktye_id'] == 1 ? ($booking['bp_adult'] * $booking['rate_adult']) + ($booking['bp_child'] * $booking['rate_child']) + ($booking['rate_infant'] * $booking['rate_infant']) : $booking['rate_private'];
                $book['bo_mange_id'][$booking['mange_id']][] = !empty($booking['boman_id']) ? $booking['boman_id'] : 0;
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

            if (in_array($booking['bomanage_id'], $first_bomanage) == false) {
                $first_managet[] = $booking['bomanage_id'];
                $retrun_t = !empty($booking['pickup']) ? 1 : 2;
                $managet['bomanage_id'][$booking['id']][$retrun_t] = !empty($booking['bomanage_id']) ? $booking['bomanage_id'] : 0;
                $managet['id'][$booking['id']][$retrun_t] = !empty($booking['manget_id']) ? $booking['manget_id'] : 0;
                $managet['car'][$booking['id']][$retrun_t] = !empty($booking['car_name']) ? $booking['car_name'] : '';
                $managet['pickup'][$booking['id']][] = !empty($booking['pickup']) ? $booking['pickup'] : 0;
                $managet['dropoff'][$booking['id']][] = !empty($booking['dropoff']) ? $booking['dropoff'] : 0;
            }
        }
    }
    # --- show list boats manage --- #
    $first_manage = array();
    $manages = $manageObj->show_manage_boat($date_travel, $search_boat);
    if (!empty($manages)) {
        foreach ($manages as $manage) {
            if (in_array($manage['id'], $first_manage) == false) {
                $first_manage[] = $manage['id'];
                $mange['id'][] = !empty($manage['id']) ? $manage['id'] : 0;
                $mange['color_id'][] = !empty($manage['color_id']) ? $manage['color_id'] : 0;
                $mange['color_name'][] = !empty($manage['color_name_th']) ? $manage['color_name_th'] : '';
                $mange['color_hex'][] = !empty($manage['color_hex']) ? $manage['color_hex'] : '';
                $mange['time'][] = !empty($manage['time']) ? date('H:i', strtotime($manage['time'])) : '00:00';
                $mange['boat_id'][] = !empty($manage['boat_id']) ? $manage['boat_id'] : 0;
                $mange['boat_name'][] = !empty($manage['boat_id']) ? !empty($manage['boat_name']) ? $manage['boat_name'] : '' : $manage['outside_boat'];
                $mange['guide_id'][] = !empty($manage['guide_id']) ? $manage['guide_id'] : 0;
                $mange['guide_name'][] = !empty($manage['guide_id']) ? $manage['guide_name'] : '';
                $mange['captain_id'][] = !empty($manage['captain_id']) ? $manage['captain_id'] : 0;
                $mange['captain_name'][] = !empty($manage['captain_id']) ?  $manage['captain_name'] : '';
                $mange['crewf_id'][] = !empty($manage['crewf_id']) ? $manage['crewf_id'] : 0;
                $mange['crews_id'][] = !empty($manage['crews_id']) ? $manage['crews_id'] : 0;
                $mange['crewf_name'][] = !empty($manage['crewf_id']) ? $manage['crewf_name'] : '';
                $mange['crews_name'][] = !empty($manage['crews_id']) ? $manage['crews_name'] : '';
                $mange['pier_name'][] = !empty($manage['pier_name']) ? $manage['pier_name'] : '';
                $mange['note'][] = !empty($manage['note']) ? $manage['note'] : '';
                $mange['outside_boat'][] = !empty($manage['outside_boat']) ? $manage['outside_boat'] : '';
                $mange['guide'][] = !empty($manage['guide']) ? $manage['guide'] : '';
            }
        }
?>
        <div id="div-boat-job-image" style="background-color: #FFF;">
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
                    <h4 class="font-weight-bolder">ใบจัดเรือ</h4>
                    <div class="badge badge-pill badge-light-danger">
                        <h5 class="m-0 pl-1 pr-1 text-danger"><?php echo date('j F Y', strtotime($date_travel)); ?></h5>
                    </div>
                </div>
            </div>
            <!-- Header ends -->
            <!-- Body starts -->
            <?php
            if (!empty($mange['id'])) {
                for ($i = 0; $i < count($mange['id']); $i++) {
            ?>
                    <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 pt-1">
                        <div class="col-4 text-left text-bold h4"></div>
                        <div class="col-4 text-center text-bold h4"><?php echo $mange['boat_name'][$i]; ?></div>
                        <div class="col-4 text-right mb-50"></div>
                    </div>

                    <table class="tableprint">
                        <thead class="">
                            <tr>
                                <th width="5%">เวลารับ</th>
                                <th width="5%">Driver</th>
                                <th width="15%">เอเยนต์</th>
                                <th width="15%">ชื่อลูกค้า</th>
                                <th width="5%">V/C</th>
                                <th width="24%">โรงแรม</th>
                                <th width="5%">ห้อง</th>
                                <th class="text-center" width="1%">A</th>
                                <th class="text-center" width="1%">C</th>
                                <th class="text-center" width="1%">Inf</th>
                                <th class="text-center" width="1%">FOC</th>
                                <th class="text-center">รวม</th>
                                <th width="5%">COT</th>
                                <th width="5%">Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_tourist = 0;
                            $total_adult = 0;
                            $total_child = 0;
                            $total_infant = 0;
                            $total_foc = 0;
                            if (!empty($book['id'][$mange['id'][$i]])) {
                                for ($a = 0; $a < count($book['id'][$mange['id'][$i]]); $a++) {
                                    $total_tourist = $total_tourist + $book['adult'][$mange['id'][$i]][$a] + $book['child'][$mange['id'][$i]][$a] + $book['infant'][$mange['id'][$i]][$a] + $book['foc'][$mange['id'][$i]][$a];
                                    $total_adult = $total_adult + $book['adult'][$mange['id'][$i]][$a];
                                    $total_child = $total_child + $book['child'][$mange['id'][$i]][$a];
                                    $total_infant = $total_infant + $book['infant'][$mange['id'][$i]][$a];
                                    $total_foc = $total_foc + $book['foc'][$mange['id'][$i]][$a];
                                    $id = $book['id'][$mange['id'][$i]][$a];
                                    $class_tr = ($a % 2 == 1) ? 'table-active' : '';
                            ?>
                                    <tr class="<?php echo $class_tr; ?>">
                                        <td><?php echo $book['start_pickup'][$mange['id'][$i]][$a] != '00:00' ? $book['start_pickup'][$mange['id'][$i]][$a] . ' - ' . $book['end_pickup'][$mange['id'][$i]][$a] : ''; ?></td>
                                        <td style="padding: 5px;"><?php echo (!empty($managet['car'][$id][1])) ? $managet['car'][$id][1] : ''; ?></td>
                                        <td><?php echo $book['comp_name'][$mange['id'][$i]][$a]; ?></td>
                                        <td><?php echo $book['cus_name'][$mange['id'][$i]][$a]; ?></td>
                                        <td><?php echo !empty($book['voucher'][$mange['id'][$i]][$a]) ? $book['voucher'][$mange['id'][$i]][$a] : $book['book_full'][$mange['id'][$i]][$a]; ?></td>
                                        <td style="padding: 5px;">
                                            <?php if ($pickup_type[$id] == 1) {
                                                echo (!empty($hotel_name[$id])) ? '<b>Pickup : </b>' . $hotel_name[$id] . $zone_pickup[$id] . '</br>' : '<b>Pickup : </b>' . $outside[$id] . $zone_pickup[$id] . '</br>';
                                                echo (!empty($dropoff_name[$id])) ? '<b>Dropoff : </b>' . $dropoff_name[$id] . $zone_dropoff[$id] : '<b>Dropoff : </b>' . $outside_dropoff[$id]  . $zone_dropoff[$id];
                                            } else {
                                                echo 'เดินทางมาเอง';
                                            } ?>
                                        </td>
                                        <td><?php echo $book['room_no'][$mange['id'][$i]][$a]; ?></td>
                                        <td class="text-center"><?php echo $book['adult'][$mange['id'][$i]][$a] . ' X ' . number_format($book['rate_adult'][$mange['id'][$i]][$a]); ?></td>
                                        <td class="text-center"><?php echo !empty($book['child'][$mange['id'][$i]][$a]) ? $book['child'][$mange['id'][$i]][$a] . ' X ' . number_format($book['rate_child'][$mange['id'][$i]][$a]) : 0; ?></td>
                                        <td class="text-center"><?php echo $book['infant'][$mange['id'][$i]][$a]; ?></td>
                                        <td class="text-center"><?php echo $book['foc'][$mange['id'][$i]][$a]; ?></td>
                                        <td class="text-center"><?php echo !empty($bec_rate_total[$id]) ? number_format($book['total'][$mange['id'][$i]][$a] + array_sum($bec_rate_total[$id])) : number_format($book['total'][$mange['id'][$i]][$a]); ?></td>
                                        <td class="text-nowrap"><b class="text-danger"><?php echo $book['cot'][$mange['id'][$i]][$a] > 0 ? number_format($book['cot'][$mange['id'][$i]][$a]) : ''; ?></b></td>
                                        <td><b class="text-info">
                                                <?php if (!empty($bec_id[$id])) {
                                                    for ($e = 0; $e < count($bec_name[$id]); $e++) {
                                                        echo $bec_name[$id][$e] . ' : ';
                                                        if ($bec_type[$id][$e] == 1) {
                                                            echo 'A ' . $bec_adult[$id][$e] . ' X ' . $bec_rate_adult[$id][$e];
                                                            echo !empty($bec_child[$id][$e]) ? ' C ' . $bec_child[$id][$e] . ' X ' . $bec_rate_child[$id][$e] : '';
                                                        } elseif ($bec_type[$id][$e] == 2) {
                                                            echo $bec_privates[$id][$e] . ' X ' . $bec_rate_total[$id][$e] . ' ';
                                                        }
                                                    }
                                                }
                                                echo !empty($book['note'][$mange['id'][$i]][$a]) ? ' / ' . $book['note'][$mange['id'][$i]][$a] : ''; ?>
                                            </b></td>
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

                    <div class="pagebreak"></div>
            <?php }
            } ?>
            <input type="hidden" id="name_img" name="name_img" value="<?php echo 'ใบจัดเรือ - ' . date('j F Y', strtotime($date_travel)); ?>">
            <!-- Body ends -->
        </div>
<?php
    }
}
