<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$manageObj = new Order();

if (isset($_POST['action']) && $_POST['action'] == "search" && isset($_POST['search_travel_date']) && isset($_POST['manage_id'])) {
    // get value from ajax
    $manage = $_POST['manage_id'];
    $search_status = $_POST['search_status'] != "" ? $_POST['search_status'] : 'all';
    $search_agent = $_POST['search_agent'] != "" ? $_POST['search_agent'] : 'all';
    $search_product = $_POST['search_product'] != "" ? $_POST['search_product'] : 'all';
    $search_car = !empty($_POST['search_car']) ? $_POST['search_car'] : 'all';
    $search_driver = !empty($_POST['search_driver']) ? $_POST['search_driver'] : 'all';
    $search_travel_date = !empty($_POST['search_travel_date']) ? $_POST['search_travel_date'] : '0000-00-00';
    $refcode = $_POST['refcode'] != "" ? $_POST['refcode'] : '';
    $search_voucher_no = $_POST['voucher_no'] != "" ? $_POST['voucher_no'] : '';
    $name = $_POST['name'] != "" ? $_POST['name'] : '';
    $hotel = $_POST['hotel'] != "" ? $_POST['hotel'] : '';
    $car = $_POST['car'] != "" ? $_POST['car'] : '';
    $driver = $_POST['driver'] != "" ? $_POST['driver'] : '';
    $seat = !empty($_POST['seat']) ? $_POST['seat'] : 0;

    $manages = $manageObj->fetch_all_manage($search_car, $search_driver, $search_travel_date, $manage);
    $head_name = !empty($manages[0]['car_name']) ? $manages[0]['car_name'] : '';
    $head_name = !empty($manages[0]['driver_name']) ? $manages[0]['driver_name'] : $head_name;

    // $all_programed = $manageObj->fetch_all_booking('booking', $search_status, $search_agent, $search_product, $refcode, $search_voucher_no, $name, $hotel, $search_travel_date, $manage);

    $bo_arr = array();
    $bomange_arr = array();
    $categorys_array = array();
    $cars_arr = array();
    $extra_arr = array();
    $bpr_arr = array();
    $manages_arr = array();
    $programe_arr = array();
    $all_programed = $manageObj->fetch_all_booking('booking', $search_status, $search_agent, $search_product, $refcode, $search_voucher_no, $name, $hotel, $search_travel_date, 0);
    foreach ($all_programed as $categorys) {
        if (in_array($categorys['manage_id'], $manages_arr) == false && !empty($categorys['manage_id'])) {
            $manages_arr[] = $categorys['manage_id'];
            $manage_id[] = $categorys['manage_id'];
        }

        if (in_array($categorys['product_id'], $programe_arr) == false && empty($categorys['manage_id'])) {
            $programe_arr[] = $categorys['product_id'];
            $product_id[] = $categorys['product_id'];
            $product_name[] = $categorys['product_name'];
        }

        if (in_array($categorys['bpr_id'], $bpr_arr) == false) {
            $bpr_arr[] = $categorys['bpr_id'];
            $categorys_array[] = $categorys['id'];
            $category_name[$categorys['id']][] = $categorys['category_name'];
            $adult[$categorys['id']][] = $categorys['adult'];
            $child[$categorys['id']][] = $categorys['child'];
            $infant[$categorys['id']][] = $categorys['infant'];
            $foc[$categorys['id']][] = $categorys['foc'];
            $tourist_array[$categorys['id']][] = $categorys['adult'] + $categorys['child'] + $categorys['infant'] + $categorys['foc'];
        }

        if (in_array($categorys['bomange_id'], $bomange_arr) == false) {
            $bomange_arr[] = $categorys['bomange_id'];
            $booking_id[$categorys['manage_id']][] = $categorys['id'];
        }

        if (in_array($categorys['id'], $bo_arr) == false) {
            $bo_arr[] = $categorys['id'];
            $bo_id[] = $categorys['id'];
            $booking_prod[$categorys['product_id']][] = $categorys['id'];
            $bt_id[$categorys['id']] = $categorys['bt_id'];
            $booking_manage[$categorys['id']] = $categorys['manage_id'];
            $hotelp_name[$categorys['id']] = $categorys['hotelp_name'];
            $outside_pickup[$categorys['id']] = $categorys['outside_pickup'];
            $zonep_name[$categorys['id']] = $categorys['zonep_name'];
            $hoteld_name[$categorys['id']] = $categorys['hoteld_name'];
            $zoned_name[$categorys['id']] = $categorys['zoned_name'];
            $outside_dropoff[$categorys['id']] = $categorys['outside_dropoff'];
            $start_pickup[$categorys['id']] = $categorys['start_pickup'];
            $end_pickup[$categorys['id']] = $categorys['end_pickup'];
            $telephone[$categorys['id']] = $categorys['telephone'];
            $cus_name[$categorys['id']] = $categorys['cus_name'];
            $voucher_no_agent[$categorys['id']] = $categorys['voucher_no_agent'];
            $book_full[$categorys['id']] = $categorys['book_full'];
            $room_no[$categorys['id']] = $categorys['room_no'];
            $bp_note[$categorys['id']] = $categorys['bp_note'];
            $agent_name[$categorys['id']] = $categorys['agent_name'];
            $status_name[$categorys['id']] = $categorys['status_name'];
            $booksta_class[$categorys['id']] = $categorys['booksta_class'];
            $sender[$categorys['id']] = $categorys['sender'];
            $cot[$categorys['id']] = $categorys['cot'];
        }

        if (in_array($categorys['bec_id'], $extra_arr) == false) {
            $extra_arr[] = $categorys['bec_id'];
            $extra_name[$categorys['id']][] = $categorys['extra_name'];
        }
    }
?>
    <div class="text-center mb-50">
        <div class="badge-light-sky"><?php echo $driver; ?></div>
        <div class="badge-light-purple"><?php echo $car; ?></div>
        <div class="badge-light-orange"><?php echo date('j F Y', strtotime($search_travel_date)); ?></div>
    </div>

    <div class="row border-top border-bottom text-center mx-0">
        <div class="col-3 border-right py-1">
            <p class="card-text text-muted mb-0">Booking ที่ไม่ได้เลือก</p>
            <h3 class="font-weight-bolder mb-0" id="booking-false">0</h3>
        </div>
        <div class="col-3 border-right py-1">
            <p class="card-text text-muted mb-0">Total ที่ไม่ได้เลือก</p>
            <h3 class="font-weight-bolder mb-0" id="toc-false">0</h3>
        </div>
        <div class="col-3 border-right py-1">
            <p class="card-text text-muted mb-0">Booking ที่เลือก</p>
            <h3 class="font-weight-bolder mb-0" id="booking-true">0</h3>
        </div>
        <div class="col-3 py-1">
            <p class="card-text text-muted mb-0">Total ที่เลือก</p>
            <h3 class="font-weight-bolder mb-0"><span id="toc-true">0</span>/<?php echo $seat; ?></h3>
        </div>
    </div>
    <!----------------------------------------------------------------------------->
    <!---- Start booking transfer | booking ----->
    <?php if (!empty($product_id)) {
        for ($i = 0; $i < count($product_id); $i++) { ?>
            <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-2 mb-50">
                <div class="col-lg-12 col-xl-12 text-center text-bold h3"><?php echo $product_name[$i]; ?></div>
            </div>
            <table class="table table-bordered table-striped table-vouchure-t2 text-black" style="font-size: 16px;">
                <thead class="thead-light">
                    <tr class="text-black">
                        <th class="cell-fit text-center">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input dt-checkboxes" type="checkbox" id="<?php echo 'checkbo_all' . $product_id[$i]; ?>" name="<?php echo 'checkbo_all' . $product_id[$i]; ?>" onclick="checkbox('booking', <?php echo $product_id[$i]; ?>);">
                                <label class="custom-control-label" for="<?php echo 'checkbo_all' . $product_id[$i]; ?>"></label>
                            </div>
                        </th>
                        <th class="text-center" width="6%" hidden>Total</th>
                        <th>Time</th>
                        <th>Zone</th>
                        <th>Hotel</th>
                        <th class="cell-fit text-center" hidden>Other</th>
                        <th class="cell-fit text-center">A</th>
                        <th class="cell-fit text-center">C</th>
                        <th class="cell-fit text-center">INF</th>
                        <th class="cell-fit text-center">FOC</th>
                        <th width="5%">category</th>
                        <th>Name</th>
                        <th class="text-nowrap">Agent</th>
                        <th class="text-nowrap">V/C</th>
                        <th>REMARKE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_adult = 0;
                    $total_child = 0;
                    $total_infant = 0;
                    $total_foc = 0;
                    $total_tourist = 0;
                    for ($b = 0; $b < count($booking_prod[$product_id[$i]]); $b++) {
                        $id = $booking_prod[$product_id[$i]][$b];
                        if (empty($booking_manage[$id])) {

                            $total_adult += !empty($adult[$id]) ? array_sum($adult[$id]) : 0;
                            $total_child += !empty($child[$id]) ? array_sum($child[$id]) : 0;
                            $total_infant += !empty($infant[$id]) ? array_sum($infant[$id]) : 0;
                            $total_foc += !empty($foc[$id]) ? array_sum($foc[$id]) : 0;
                            $total_tourist += !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;
                            $tourist = !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;

                            // $text_hotel = '';
                            // $text_hotel = (!empty($hotelp_name[$id])) ? '<b>Pickup : </b>' . $hotelp_name[$id] : '<b>Pickup : </b>' . $outside_pickup[$id];
                            // $text_hotel .= (!empty($zonep_name[$id])) ? ' (' . $zonep_name[$id] . ')</br>' : '</br>';
                            // $text_hotel .= (!empty($hoteld_name[$id])) ? '<b>Dropoff : </b>' . $hoteld_name[$id] : '<b>Dropoff : </b>' . $outside_dropoff[$id];
                            // $text_hotel .= (!empty($zoned_name[$id])) ? ' (' . $zoned_name[$id] . ')' : '';

                            $text_zone = $zonep_name[$id] != $zoned_name[$id] ? $zonep_name[$id] . '<br>(D: ' . $zoned_name[$id] . ')' : $zonep_name[$id];
                            if (!empty($hotelp_name[$id])) {
                                $text_hotel = $hotelp_name[$id] != $hoteld_name[$id] ? $hotelp_name[$id] . '<br>(D: ' . $hoteld_name[$id] . ')' : $hotelp_name[$id];
                            } else {
                                $text_hotel = $outside_pickup[$id] != $outside_dropoff[$id] ? $outside_pickup[$id] . '<br>(D: ' . $outside_dropoff[$id] . ')' : $outside_pickup[$id];
                            }
                    ?>
                            <tr class="<?php echo ($a % 2 == 1) ? 'table-active' : 'bg-white'; ?>">
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input dt-checkboxes checkbox-<?php echo $product_id[$i]; ?> checkbox-book" type="checkbox" id="checkbox<?php echo $bt_id[$id]; ?>" name="bt_id[]" value="<?php echo $bt_id[$id]; ?>" onclick="sum_checkbox();">
                                        <label class="custom-control-label" for="checkbox<?php echo $bt_id[$id]; ?>"></label>
                                    </div>
                                </td>
                                <td class="text-center" hidden>
                                    <input id="tourist<?php echo $bt_id[$id]; ?>" name="tourist[]" class="form-control form-control-sm numeral-mask" type="text" value="<?php echo $tourist; ?>" onchange="sum_checkbox();" />
                                </td>
                                <td class="cell-fit"><?php echo date('H:i', strtotime($start_pickup[$id])) . ' - ' . date('H:i', strtotime($end_pickup[$id])); ?></td>
                                <td><?php echo $text_zone; ?></td>
                                <td><?php echo $text_hotel; ?></td>
                                <td class="text-center" hidden><?php echo !empty($bookings['tourist']) ? $bookings['tourist'] : 0; ?></td>
                                <td class="text-center"><?php echo !empty($adult[$id]) ? array_sum($adult[$id]) : 0; ?></td>
                                <td class="text-center"><?php echo !empty($child[$id]) ? array_sum($child[$id]) : 0; ?></td>
                                <td class="text-center"><?php echo !empty($infant[$id]) ? array_sum($infant[$id]) : 0; ?></td>
                                <td class="text-center"><?php echo !empty($foc[$id]) ? array_sum($foc[$id]) : 0; ?></td>
                                <td class="cell-fit">
                                    <?php if (!empty($category_name[$id])) {
                                        for ($c = 0; $c < count($category_name[$id]); $c++) {
                                            echo $c == 0 ? $category_name[$id][$c] : ', ' . $category_name[$id][$c];
                                        }
                                    } ?>
                                </td>
                                <td><?php echo !empty($telephone[$id]) ? $cus_name[$id] . ' <br>(' . $telephone[$id] . ')' : $cus_name[$id]; ?></td>
                                <td><?php echo $agent_name[$id]; ?></td>
                                <td><?php echo !empty($voucher_no_agent[$id]) ? $voucher_no_agent[$id] : $book_full[$id]; ?></td>
                                <td>
                                    <b class="text-info">
                                        <?php
                                        if (!empty($extra_name[$id])) {
                                            for ($e = 0; $e < count($extra_name[$id]); $e++) {
                                                echo $e == 0 ? $extra_name[$id][$e] : ' : ' . $extra_name[$id][$e];
                                            }
                                        }
                                        echo $bp_note[$id]; ?>
                                    </b>
                                </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
    <?php }
    } ?>
    <!---- End booking transfer | booking ----->
    <!----------------------------------------------------------------------------->
    <div class="divider divider-dark">
        <div class="divider-text p-0"></div>
    </div>
    <!----------------------------------------------------------------------------->
    <!---- Start Manafement transfer | booking ----->
    <table class="table table-bordered table-striped table-vouchure-t2 text-black" style="font-size: 16px;" id="list-group">
        <thead class="thead-light">
            <tr>
                <th class="cell-fit text-center">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input dt-checkboxes" type="checkbox" id="checkmanage_all" name="checkmanage_all" onclick="checkbox('manage');" checked>
                        <label class="custom-control-label" for="checkmanage_all"></label>
                    </div>
                </th>
                <th class="text-center" width="6%" hidden>Total</th>
                <th class="cell-fit text-center" hidden>Other</th>
                <th class="cell-fit text-center">A</th>
                <th class="cell-fit text-center">C</th>
                <th class="cell-fit text-center">INF</th>
                <th class="cell-fit text-center">FOC</th>
                <th>Time</th>
                <th>Program</th>
                <th>Zone</th>
                <th>Hotel</th>
                <th>Name</th>
                <th class="text-nowrap">Agent</th>
                <th class="text-nowrap">V/C</th>
                <th>REMARKE</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $bomange_arr = array();
            $all_bookings = $manageObj->fetch_all_booking('manage', $search_status, $search_agent, $search_product, $refcode, $search_voucher_no, $name, $hotel, '', $manage);

            // $bpr_array = array();
            // foreach ($all_bookings as $categorys) {
            //     if (in_array($categorys['bpr_id'], $bpr_array) == false) {
            //         $bpr_array[] = $categorys['bpr_id'];
            //         $adult[$categorys['id']][] = $categorys['adult'];
            //         $child[$categorys['id']][] = $categorys['child'];
            //         $infant[$categorys['id']][] = $categorys['infant'];
            //         $foc[$categorys['id']][] = $categorys['foc'];
            //     }
            // }

            $total_adult = 0;
            $total_child = 0;
            $total_infant = 0;
            $total_foc = 0;
            $total_tourist = 0;
            foreach ($all_bookings as $bookings) {
                $id = $bookings['id'];
                if (in_array($bookings['bomange_id'], $bomange_arr) == false) {
                    $bomange_arr[] = $bookings['bomange_id'];
                    $text_hotel = '';
                    $text_zone = '';
                    if ($bookings['category_transfer'] == 1) {
                        if (!empty($bookings['zonep_name'])) {
                            $text_zone = $bookings['zonep_name'] != $bookings['zoned_name'] ? $bookings['zonep_name'] . '<br>(D: ' . $bookings['zoned_name'] . ')' : $bookings['zonep_name'];
                        }
                        if (!empty($bookings['hotelp_name'])) {
                            $text_hotel = $bookings['hotelp_name'] != $bookings['hoteld_name'] ? $bookings['hotelp_name'] . '<br>(D: ' . $bookings['hoteld_name'] . ')' : $bookings['hotelp_name'];
                        } else {
                            $text_hotel = $bookings['outside_pickup'] != $bookings['outside_dropoff'] ? $bookings['outside_pickup'] . '<br>(D: ' . $bookings['outside_dropoff'] . ')' : $bookings['outside_pickup'];
                        }
                    } else {
                        $text_hotel = 'เดินทางมาเอง';
                        $text_zone = 'เดินทางมาเอง';
                    }

                    $total_adult += !empty($adult[$id]) ? array_sum($adult[$id]) : 0;
                    $total_child += !empty($child[$id]) ? array_sum($child[$id]) : 0;
                    $total_infant += !empty($infant[$id]) ? array_sum($infant[$id]) : 0;
                    $total_foc += !empty($foc[$id]) ? array_sum($foc[$id]) : 0;
                    $total_tourist += !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;
                    $tourist = !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;

                    // $tourist_all = $manageObj->get_values('tourist', 'booking_order_transfer', 'booking_transfer_id = ' . $bookings['bt_id'] . ' AND order_id != ' . $manage, 1);
                    // $tourist_all = !empty($tourist_all) ? array_column($tourist_all, 'tourist') : []; // Extracts all 'price' values
            ?>
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input dt-checkboxes checkbox-manage" type="checkbox" id="checkbox<?php echo $bookings['bt_id']; ?>" name="manage_bt[]" value="<?php echo $bookings['bt_id']; ?>" onclick="sum_checkbox();" checked>
                                <label class="custom-control-label" for="checkbox<?php echo $bookings['bt_id']; ?>"></label>
                            </div>
                        </td>
                        <td class="text-center" hidden>
                            <input type="hidden" name="before_manage[]" value="<?php echo $bookings['bomange_id']; ?>">
                            <input id="toc-manage<?php echo $bookings['bt_id']; ?>" name="manage_tourist[]" class="form-control form-control-sm numeral-mask" type="text" value="<?php echo $bookings['tourist']; ?>" oninput="check_max_tourist(<?php echo $bookings['bt_id']; ?>, <?php echo !empty($tourist_all) ? array_sum($tourist_all) : 0; ?>, <?php echo !empty($bookings['max_tourist']) ? $bookings['max_tourist'] : 0; ?>);" />
                        </td>
                        
                        <td class="text-center"><?php echo !empty($adult[$id]) ? array_sum($adult[$id]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($child[$id]) ? array_sum($child[$id]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($infant[$id]) ? array_sum($infant[$id]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($foc[$id]) ? array_sum($foc[$id]) : 0; ?></td>
                        <td><?php echo $bookings['start_pickup'] != '00:00' ? date('H:i', strtotime($bookings['start_pickup'])) . ' - ' . date('H:i', strtotime($bookings['end_pickup'])) : ''; ?></td>
                        <td><span class="fw-bold"><?php echo $bookings['product_name']; ?></span></td>
                        <td><?php echo $text_zone; ?></td>
                        <td><?php echo $text_hotel; ?></td>
                        <td><span class="fw-bold"><?php echo $bookings['cus_name']; ?></span></td>
                        <td><?php echo $bookings['agent_name']; ?></td>
                        <td><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['book_full']; ?></td>
                        <td>
                            <b class="text-info">
                                <?php
                                $e = 0;
                                $extra_charges = $manageObj->get_extra_charge($bookings['id']);
                                if (!empty($extra_charges)) {
                                    foreach ($extra_charges as $extra_charge) {
                                        echo $e == 0 ? $extra_charge['extra_name'] : ' : ' . $extra_charge['extra_name'];
                                        $e++;
                                    }
                                }
                                echo $bookings['bp_note']; ?></b>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
    <!---- End Manafement transfer | booking ----->
    <!----------------------------------------------------------------------------->
    <hr>
    <div class="d-flex justify-content-between">
        <span></span>
        <button type="submit" class="btn btn-primary" onclick="submit_booking_manage(<?php echo $manage; ?>);">Submit</button>
    </div>
<?php
} else {
    echo false;
}
?>