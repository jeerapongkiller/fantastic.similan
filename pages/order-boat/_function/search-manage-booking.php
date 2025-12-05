<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$manageObj = new Order();

if (isset($_POST['action']) && $_POST['action'] == "search" && isset($_POST['travel_date'])) {
    // get value from ajax
    $search_travel_date = !empty($_POST['travel_date']) ? $_POST['travel_date'] : '0000-00-00';
    $manage = !empty($_POST['manage_id']) ? $_POST['manage_id'] : 0;
    $search_boat = !empty($_POST['search_boat']) ? $_POST['search_boat'] : 'all';
    $search_status = $_POST['search_status'] != "" ? $_POST['search_status'] : 'all';
    $search_agent = $_POST['search_agent'] != "" ? $_POST['search_agent'] : 'all';
    $search_product = $_POST['search_product'] != "" ? $_POST['search_product'] : 'all';
    $search_voucher_no = $_POST['voucher_no'] != "" ? $_POST['voucher_no'] : '';
    $refcode = $_POST['refcode'] != "" ? $_POST['refcode'] : '';
    $name = $_POST['name'] != "" ? $_POST['name'] : '';
    echo $search_travel_date, $search_boat, 'all', $manage;

    // $categorys_array = array();
    // $all_bookings = $manageObj->fetch_all_bookingboat('booking', $search_travel_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, '', 'all', 'all', $manage_id);

    $bo_arr = array();
    $bomange_arr = array();
    $categorys_array = array();
    $cars_arr = array();
    $extra_arr = array();
    $bpr_arr = array();
    $manages_arr = array();
    $all_bookings = $manageObj->fetch_all_bookingboat('all', $search_travel_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, $hotel = '', $search_boat, $search_guide = 'all', $manage);
    foreach ($all_bookings as $categorys) {
        if (in_array($categorys['manage_id'], $manages_arr) == false && !empty($categorys['manage_id'])) {
            $manages_arr[] = $categorys['manage_id'];
            $manage_id[] = $categorys['manage_id'];
            $manage_time[] = $categorys['manage_time'];
            $boat_id[] = $categorys['boat_id'];
            $boat_name[] = $categorys['boat_name'];
            $guide_id[] = $categorys['guide_id'];
            $guide_name[] = $categorys['guide_name'];
            $counter[] = $categorys['manage_counter'];
            $color_id[] = $categorys['color_id'];
            $color_hex[] = $categorys['color_hex'];
            $text_color[] = $categorys['text_color'];
            $color_name_th[] = $categorys['color_name_th'];
            $manage_note[] = $categorys['manage_note'];
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
            $hotelp_name[$categorys['id']] = $categorys['hotelp_name'];
            $outside_pickup[$categorys['id']] = $categorys['outside_pickup'];
            $zonep_name[$categorys['id']] = $categorys['zonep_name'];
            $hoteld_name[$categorys['id']] = $categorys['hoteld_name'];
            $zoned_name[$categorys['id']] = $categorys['zoned_name'];
            $outside_dropoff[$categorys['id']] = $categorys['outside_dropoff'];
            $start_pickup[$categorys['id']] = $categorys['start_pickup'];
            $end_pickup[$categorys['id']] = $categorys['end_pickup'];
            $product_name[$categorys['id']] = $categorys['product_name'];
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

        if (in_array($categorys['bot_id'], $cars_arr) == false) {
            $cars_arr[] = $categorys['bot_id'];
            $car_name[$categorys['id']][] = $categorys['car_name'];
        }

        if (in_array($categorys['bec_id'], $extra_arr) == false) {
            $extra_arr[] = $categorys['bec_id'];
            $extra_name[$categorys['id']][] = $categorys['extra_name'];
        }
    }
?>
    <div class="text-center mb-50">
        <div class="badge-light-sky"><?php echo $manages[0]['boat_name']; ?></div>
        <div class="badge-light-purple"><?php echo $manages[0]['guide_name']; ?></div>
        <div class="badge-light-orange"><?php echo date('j F Y', strtotime($search_travel_date)); ?></div>
    </div>
    <div class="row text-center mx-0">
        <div class="col-3 border py-1">
            <p class="card-text text-muted mb-0">Booking ที่ไม่ได้เลือก</p>
            <h3 class="font-weight-bolder mb-0" id="booking-false"></h3>
        </div>
        <div class="col-3 border-right border-top py-1">
            <p class="card-text text-muted mb-0">Total ที่ไม่ได้เลือก</p>
            <h3 class="font-weight-bolder mb-0" id="toc-false"></h3>
        </div>
        <div class="col-3 border-right border-top py-1">
            <p class="card-text text-muted mb-0">Booking ที่เลือก</p>
            <h3 class="font-weight-bolder mb-0" id="booking-true"></h3>
        </div>
        <div class="col-3 border-right border-top py-1">
            <p class="card-text text-muted mb-0">Total ที่เลือก</p>
            <h3 class="font-weight-bolder mb-0" id="toc-true"></h3>
        </div>
    </div>
    <!----------------------------------------------------------------------------->
    <!---- Start booking boat | booking ----->
    <table class="table table-bordered table-striped table-vouchure-t2">
        <thead class="thead-light">
            <tr>
                <th class="cell-fit text-center">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input dt-checkboxes" type="checkbox" value="all" id="bookingall" name="bookings[]">
                        <label class="custom-control-label" for="bookingall"></label>
                    </div>
                </th>
                <th>Category</th>
                <th>Hotel</th>
                <th>Name</th>
                <th class="cell-fit text-center">Total</th>
                <th class="cell-fit text-center">A</th>
                <th class="cell-fit text-center">C</th>
                <th class="cell-fit text-center">INF</th>
                <th class="cell-fit text-center">FOC</th>
                <th class="text-nowrap">Agent</th>
                <th class="text-nowrap">V/C</th>
                <th>REMARKE</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_tourist = 0;
            $total_adult = 0;
            $total_child = 0;
            $total_infant = 0;
            $total_foc = 0;
            $booking_array = array();
            $all_bookings = $manageObj->fetch_all_bookingboat('booking', $search_travel_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, '', 'all', 'all', $manage);

            foreach ($all_bookings as $key => $categorys) {
                $category_name[$categorys['id']][] = $categorys['category_name'];
                $adult[$categorys['id']][] = $categorys['adult'];
                $child[$categorys['id']][] = $categorys['child'];
                $infant[$categorys['id']][] = $categorys['infant'];
                $foc[$categorys['id']][] = $categorys['foc'];
                $tourist_array[$categorys['id']][] = $categorys['adult'] + $categorys['child'] + $categorys['infant'] + $categorys['foc'];
            }

            foreach ($all_bookings as $key => $bookings) {
                if (in_array($bookings['id'], $booking_array) == false) {
                    $booking_array[] = $bookings['id'];
                    $total_adult += !empty($adult[$bookings['id']]) ? array_sum($adult[$bookings['id']]) : 0;
                    $total_child += !empty($child[$bookings['id']]) ? array_sum($child[$bookings['id']]) : 0;
                    $total_infant += !empty($infant[$bookings['id']]) ? array_sum($infant[$bookings['id']]) : 0;
                    $total_foc += !empty($foc[$bookings['id']]) ? array_sum($foc[$bookings['id']]) : 0;
                    $total_tourist += !empty($tourist_array[$bookings['id']]) ? array_sum($tourist_array[$bookings['id']]) : 0;
                    $tourist = !empty($tourist_array[$bookings['id']]) ? array_sum($tourist_array[$bookings['id']]) : 0;
                    // $text_hotel = '';
                    // $text_hotel = (!empty($bookings['hotelp_name'])) ? '<b>Pickup : </b>' . $bookings['hotelp_name'] : '<b>Pickup : </b>' . $bookings['outside_pickup'];
                    // $text_hotel .= (!empty($bookings['zonep_name'])) ? ' (' . $bookings['zonep_name'] . ')</br>' : '</br>';
                    // $text_hotel .= (!empty($bookings['hoteld_name'])) ? '<b>Dropoff : </b>' . $bookings['hoteld_name'] : '<b>Dropoff : </b>' . $bookings['outside_dropoff'];
                    // $text_hotel .= (!empty($bookings['zoned_name'])) ? ' (' . $bookings['zoned_name'] . ')' : '';

                    $text_hotel = '';
                    $text_hotel = (!empty($bookings['hotelp_name'])) ? $bookings['hotelp_name'] : $bookings['outside_pickup'];
                    $text_hotel .= (!empty($bookings['zonep_name'])) ? ' (' . $bookings['zonep_name'] . ')' : '</br>';
            ?>
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <!-- onclick="sum_checkbox();" -->
                                <input class="custom-control-input dt-checkboxes checkbox-manage" type="checkbox" id="checkbox<?php echo $bookings['id']; ?>" name="bookings[]" value="<?php echo $bookings['id']; ?>" data-tourist="<?php echo $tourist; ?>">
                                <label class="custom-control-label" for="checkbox<?php echo $bookings['id']; ?>"></label>
                            </div>
                        </td>
                        <td class="cell-fit">
                            <?php if (!empty($category_name[$bookings['id']])) {
                                for ($i = 0; $i < count($category_name[$bookings['id']]); $i++) {
                                    echo $i == 0 ? $category_name[$bookings['id']][$i] : ', ' . $category_name[$bookings['id']][$i];
                                }
                            } ?>
                        </td>
                        <td><?php echo $text_hotel; ?></td>
                        <td><?php echo $bookings['cus_name']; ?></td>
                        <td class="cell-fit text-center"><?php echo $tourist; ?></td>
                        <td class="text-center"><?php echo !empty($adult[$bookings['id']]) ? array_sum($adult[$bookings['id']]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($child[$bookings['id']]) ? array_sum($child[$bookings['id']]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($infant[$bookings['id']]) ? array_sum($infant[$bookings['id']]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($foc[$bookings['id']]) ? array_sum($foc[$bookings['id']]) : 0; ?></td>
                        <td class="text-nowrap"><?php echo $bookings['agent_name']; ?></td>
                        <td class="text-nowrap"><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['book_full']; ?></td>
                        <td><b class="text-info">
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
    <!---- End booking boat | booking ----->
    <!----------------------------------------------------------------------------->
    <div class="divider divider-dark">
        <div class="divider-text p-0"></div>
    </div>
    <!----------------------------------------------------------------------------->
    <!---- Start Manafement boat | booking ----->
    <table class="table table-bordered table-striped table-vouchure-t2">
        <thead class="thead-light">
            <tr>
                <th class="cell-fit text-center">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input dt-checkboxes" type="checkbox" value="all" id="manageall" name="manages[]" checked>
                        <label class="custom-control-label" for="manageall"></label>
                    </div>
                </th>
                <th>Category</th>
                <th>Hotel</th>
                <th>Name</th>
                <th class="cell-fit text-center">Total</th>
                <th class="cell-fit text-center">A</th>
                <th class="cell-fit text-center">C</th>
                <th class="cell-fit text-center">INF</th>
                <th class="cell-fit text-center">FOC</th>
                <th class="text-nowrap">Agent</th>
                <th class="text-nowrap">V/C</th>
                <th>REMARKE</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_tourist = 0;
            $total_adult = 0;
            $total_child = 0;
            $total_infant = 0;
            $total_foc = 0;
            $bomange_arr = array();
            $all_bookings = $manageObj->fetch_all_bookingboat('manage', $search_travel_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, '', 'all', 'all', $manage);
            foreach ($all_bookings as $bookings) {
                $id = $bookings['id'];
                if (in_array($bookings['bomange_id'], $bomange_arr) == false) {
                    $bomange_arr[] = $bookings['bomange_id'];

                    $total_adult += !empty($adult[$id]) ? array_sum($adult[$id]) : 0;
                    $total_child += !empty($child[$id]) ? array_sum($child[$id]) : 0;
                    $total_infant += !empty($infant[$id]) ? array_sum($infant[$id]) : 0;
                    $total_foc += !empty($foc[$id]) ? array_sum($foc[$id]) : 0;
                    $total_tourist += !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;
                    $tourist = !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;

                    $text_hotel = '';
                    $text_hotel = (!empty($hotelp_name[$id])) ? $hotelp_name[$id] : $outside_pickup[$id];
                    $text_hotel .= (!empty($zonep_name[$id])) ? ' (' . $zonep_name[$id] . ')' : '';
            ?>
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <!-- onclick="sum_checkbox();" -->
                                <input class="custom-control-input dt-checkboxes checkbox-manage" type="checkbox" id="checkbox<?php echo $id; ?>" name="manages[]" value="<?php echo $id; ?>" data-tourist="<?php echo $tourist; ?>" checked>
                                <label class="custom-control-label" for="checkbox<?php echo $id; ?>"></label>
                            </div>
                        </td>
                        <td class="cell-fit">
                            <?php if (!empty($category_name[$id])) {
                                for ($i = 0; $i < count($category_name[$id]); $i++) {
                                    echo $i == 0 ? $category_name[$id][$i] : ', ' . $category_name[$id][$i];
                                }
                            } ?>
                        </td>
                        <td><?php echo $text_hotel; ?></td>
                        <td><?php echo $bookings['cus_name']; ?></td>
                        <td class="cell-fit text-center"><?php echo $tourist; ?></td>
                        <td class="text-center"><?php echo !empty($adult[$id]) ? array_sum($adult[$id]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($child[$id]) ? array_sum($child[$id]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($infant[$id]) ? array_sum($infant[$id]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($foc[$id]) ? array_sum($foc[$id]) : 0; ?></td>
                        <td class="text-nowrap"><?php echo $bookings['agent_name']; ?></td>
                        <td class="text-nowrap"><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['book_full']; ?></td>
                        <td><b class="text-info">
                                <?php
                                $e = 0;
                                $extra_charges = $manageObj->get_extra_charge($id);
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
    <!---- End Manafement boat | booking ----->
    <!----------------------------------------------------------------------------->
    <hr>
    <div class="d-flex justify-content-between">
        <span></span>
        <button type="submit" class="btn btn-primary" onclick="submit_booking_manage(<?php echo $manage; ?>);">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Submit
        </button>
    </div>
<?php
} else {
    echo false;
}
?>