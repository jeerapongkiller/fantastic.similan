<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$manageObj = new Order();

if (isset($_POST['action']) && $_POST['action'] == "search" && isset($_POST['travel_date'])) {
    // get value from ajax
    $search_travel_date = !empty($_POST['travel_date']) ? $_POST['travel_date'] : '0000-00-00';
    $manage_id = !empty($_POST['manage_id']) ? $_POST['manage_id'] : 0;
    $search_boat = !empty($_POST['search_boat']) ? $_POST['search_boat'] : 'all';
    $search_status = $_POST['search_status'] != "" ? $_POST['search_status'] : 'all';
    $search_agent = $_POST['search_agent'] != "" ? $_POST['search_agent'] : 'all';
    $search_product = $_POST['search_product'] != "" ? $_POST['search_product'] : 'all';
    $search_voucher_no = $_POST['voucher_no'] != "" ? $_POST['voucher_no'] : '';
    $refcode = $_POST['refcode'] != "" ? $_POST['refcode'] : '';
    $name = $_POST['name'] != "" ? $_POST['name'] : '';

    $manage = $manageObj->fetch_all_manageboat($search_travel_date, $search_boat, $manage_id);
    $categorys_array = array();
    $all_bookings = $manageObj->fetch_all_bookingboat('all', $search_travel_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, $manage_id);
    foreach ($all_bookings as $key => $categorys) {
        $categorys_array[] = $categorys['id'];
        $category_name[$categorys['id']][] = $categorys['category_name'];
    }
?>
    <div class="text-center mb-50">
        <div class="badge-light-sky"><?php echo $manage[0]['boat_name']; ?></div>
        <div class="badge-light-purple"><?php echo $manage[0]['guide_name']; ?></div>
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
            $all_bookings = $manageObj->fetch_all_bookingboat('booking', $search_travel_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, $manage_id);
            foreach ($all_bookings as $key => $bookings) {
                if (in_array($bookings['id'], $booking_array) == false) {
                    $booking_array[] = $bookings['id'];
                    $total_adult += !empty($bookings['adult']) ? $bookings['adult'] : 0;
                    $total_child += !empty($bookings['child']) ? $bookings['child'] : 0;
                    $total_infant += !empty($bookings['infant']) ? $bookings['infant'] : 0;
                    $total_foc += !empty($bookings['foc']) ? $bookings['foc'] : 0;
                    $total_tourist += $bookings['adult'] + $bookings['child'] + $bookings['infant'] + $bookings['foc'];
                    $tourist = $bookings['adult'] + $bookings['child'] + $bookings['infant'] + $bookings['foc'];
                    $text_hotel = '';
                    $text_hotel = (!empty($bookings['hotelp_name'])) ? '<b>Pickup : </b>' . $bookings['hotelp_name'] : '<b>Pickup : </b>' . $bookings['outside_pickup'];
                    $text_hotel .= (!empty($bookings['zonep_name'])) ? ' (' . $bookings['zonep_name'] . ')</br>' : '</br>';
                    $text_hotel .= (!empty($bookings['hoteld_name'])) ? '<b>Dropoff : </b>' . $bookings['hoteld_name'] : '<b>Dropoff : </b>' . $bookings['outside_dropoff'];
                    $text_hotel .= (!empty($bookings['zoned_name'])) ? ' (' . $bookings['zoned_name'] . ')' : '';
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
                        <td class="text-center"><?php echo !empty($bookings['adult']) ? $bookings['adult'] : 0; ?></td>
                        <td class="text-center"><?php echo !empty($bookings['child']) ? $bookings['child'] : 0; ?></td>
                        <td class="text-center"><?php echo !empty($bookings['infant']) ? $bookings['infant'] : 0; ?></td>
                        <td class="text-center"><?php echo !empty($bookings['foc']) ? $bookings['foc'] : 0; ?></td>
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
            $all_bookings = $manageObj->fetch_all_bookingboat('manage', $search_travel_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, $manage_id);
            foreach ($all_bookings as $bookings) {
                if (in_array($bookings['bomange_id'], $bomange_arr) == false) {
                    $bomange_arr[] = $bookings['bomange_id'];
                    $total_adult += !empty($bookings['adult']) ? $bookings['adult'] : 0;
                    $total_child += !empty($bookings['child']) ? $bookings['child'] : 0;
                    $total_infant += !empty($bookings['infant']) ? $bookings['infant'] : 0;
                    $total_foc += !empty($bookings['foc']) ? $bookings['foc'] : 0;
                    $total_tourist += $bookings['adult'] + $bookings['child'] + $bookings['infant'] + $bookings['foc'];
                    $tourist = $bookings['adult'] + $bookings['child'] + $bookings['infant'] + $bookings['foc'];
                    $text_hotel = '';
                    $text_hotel = (!empty($bookings['hotelp_name'])) ? '<b>Pickup : </b>' . $bookings['hotelp_name'] : '<b>Pickup : </b>' . $bookings['outside_pickup'];
                    $text_hotel .= (!empty($bookings['zonep_name'])) ? ' (' . $bookings['zonep_name'] . ')</br>' : '</br>';
                    $text_hotel .= (!empty($bookings['hoteld_name'])) ? '<b>Dropoff : </b>' . $bookings['hoteld_name'] : '<b>Dropoff : </b>' . $bookings['outside_dropoff'];
                    $text_hotel .= (!empty($bookings['zoned_name'])) ? ' (' . $bookings['zoned_name'] . ')' : '';
            ?>
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <!-- onclick="sum_checkbox();" -->
                                <input class="custom-control-input dt-checkboxes checkbox-manage" type="checkbox" id="checkbox<?php echo $bookings['id']; ?>" name="manages[]" value="<?php echo $bookings['id']; ?>" data-tourist="<?php echo $tourist; ?>" checked>
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
                        <td class="text-center"><?php echo !empty($bookings['adult']) ? $bookings['adult'] : 0; ?></td>
                        <td class="text-center"><?php echo !empty($bookings['child']) ? $bookings['child'] : 0; ?></td>
                        <td class="text-center"><?php echo !empty($bookings['infant']) ? $bookings['infant'] : 0; ?></td>
                        <td class="text-center"><?php echo !empty($bookings['foc']) ? $bookings['foc'] : 0; ?></td>
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
    <!---- End Manafement boat | booking ----->
    <!----------------------------------------------------------------------------->
    <hr>
    <div class="d-flex justify-content-between">
        <span></span>
        <button type="submit" class="btn btn-primary" onclick="submit_booking_manage(<?php echo $manage_id; ?>);">
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