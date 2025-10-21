<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$manageObj = new Order();

if (isset($_POST['action']) && $_POST['action'] == "search" && isset($_POST['search_travel_date']) && isset($_POST['manage_id'])) {
    // get value from ajax
    $manage_id = $_POST['manage_id'];
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
    $seat = $_POST['seat'] != "" ? $_POST['seat'] : 0;

    $manages = $manageObj->fetch_all_manage($search_car, $search_driver, $search_travel_date, $manage_id);
    $head_name = !empty($manages[0]['car_name']) ? $manages[0]['car_name'] : '';
    $head_name = !empty($manages[0]['driver_name']) ? $manages[0]['driver_name'] : $head_name;

    $all_programed = $manageObj->fetch_all_booking('booking', $search_status, $search_agent, $search_product, $refcode, $search_voucher_no, $name, $hotel, $search_travel_date, $manage_id);
    $categorys_array = array();
    foreach ($all_programed as $key => $categorys) {
        if (in_array($categorys['id'], $categorys_array) == false) {
            $categorys_array[] = $categorys['id'];
            $category_name[$categorys['id']][] = $categorys['category_name'];
            $category_transfer[$categorys['id']][] = $categorys['category_transfer'];
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
    <?php if (!empty($all_programed)) {
        $programe_array = array();
        foreach ($all_programed as $programed) {
            if (in_array($programed['product_id'], $programe_array) == false) {
                $programe_array[] = $programed['product_id']; ?>
                <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-2 mb-50">
                    <div class="col-lg-12 col-xl-12 text-center text-bold h3"><?php echo $programed['product_name']; ?></div>
                </div>
                <table class="table table-bordered table-striped table-vouchure-t2">
                    <thead class="thead-light">
                        <tr>
                            <th class="cell-fit text-center">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input dt-checkboxes" type="checkbox" id="<?php echo 'checkbo_all' . $programed['product_id']; ?>" name="<?php echo 'checkbo_all' . $programed['product_id']; ?>" onclick="checkbox('booking', <?php echo $programed['product_id']; ?>);">
                                    <label class="custom-control-label" for="<?php echo 'checkbo_all' . $programed['product_id']; ?>"></label>
                                </div>
                            </th>
                            <th class="text-center" width="6%">Total</th>
                            <th class="cell-fit text-center">Other</th>
                            <th class="cell-fit text-center">A</th>
                            <th class="cell-fit text-center">C</th>
                            <th class="cell-fit text-center">INF</th>
                            <th class="cell-fit text-center">FOC</th>
                            <th width="10%">Time</th>
                            <th width="5%">category</th>
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
                        $bookings_arr = array();
                        $total_tourist = 0;
                        $total_adult = 0;
                        $total_child = 0;
                        $total_infant = 0;
                        $total_foc = 0;
                        $all_bookings = $manageObj->fetch_all_booking('booking', $search_status, $search_agent, $programed['product_id'], $refcode, $search_voucher_no, $name, $hotel, $search_travel_date, $manage_id);
                        foreach ($all_bookings as $bookings) {
                            if (in_array($bookings['id'], $bookings_arr) == false) {
                                $bookings_arr[] = $bookings['id'];
                                $total_adult += !empty($bookings['adult']) ? $bookings['adult'] : 0;
                                $total_child += !empty($bookings['child']) ? $bookings['child'] : 0;
                                $total_infant += !empty($bookings['infant']) ? $bookings['infant'] : 0;
                                $total_foc += !empty($bookings['foc']) ? $bookings['foc'] : 0;
                                $total_tourist += $bookings['adult'] + $bookings['child'] + $bookings['infant'] + $bookings['foc'];
                                $tourist = $bookings['adult'] + $bookings['child'] + $bookings['infant'] + $bookings['foc'];
                                $tourist -= $bookings['tourist'];
                                $check_bt = $manageObj->get_values('id', 'booking_order_transfer', 'booking_transfer_id = ' . $bookings['bt_id'] . ' AND order_id = ' . $manage_id, 1);
                                if ($tourist > 0 && empty($check_bt)) {
                                    $text_hotel = '';
                                    $text_zone = '';
                                    if (in_array(1, $category_transfer[$bookings['id']]) == true) {
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
                        ?>
                                    <tr class="<?php echo ($a % 2 == 1) ? 'table-active' : 'bg-white'; ?>">
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input dt-checkboxes checkbox-<?php echo $programed['product_id']; ?> checkbox-book" type="checkbox" id="checkbox<?php echo $bookings['bt_id']; ?>" name="bt_id[]" value="<?php echo $bookings['bt_id']; ?>" onclick="sum_checkbox();">
                                                <label class="custom-control-label" for="checkbox<?php echo $bookings['bt_id']; ?>"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <input id="tourist<?php echo $bookings['bt_id']; ?>" name="tourist[]" class="form-control form-control-sm numeral-mask" type="text" value="<?php echo $tourist; ?>" onchange="sum_checkbox();" />
                                        </td>
                                        <td class="text-center"><?php echo !empty($bookings['tourist']) ? $bookings['tourist'] : 0; ?></td>
                                        <td class="text-center"><?php echo !empty($bookings['adult']) ? $bookings['adult'] : 0; ?></td>
                                        <td class="text-center"><?php echo !empty($bookings['child']) ? $bookings['child'] : 0; ?></td>
                                        <td class="text-center"><?php echo !empty($bookings['infant']) ? $bookings['infant'] : 0; ?></td>
                                        <td class="text-center"><?php echo !empty($bookings['foc']) ? $bookings['foc'] : 0; ?></td>
                                        <td><?php echo $bookings['start_pickup'] != '00:00' ? date('H:i', strtotime($bookings['start_pickup'])) . ' - ' . date('H:i', strtotime($bookings['end_pickup'])) : ''; ?></td>
                                        <td><?php echo $bookings['category_name']; ?></td>
                                        <td><?php echo $text_zone; ?></td>
                                        <td><?php echo $text_hotel; ?></td>
                                        <td><?php echo $bookings['cus_name']; ?></td>
                                        <td><?php echo $bookings['agent_name']; ?></td>
                                        <td><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['book_full']; ?></td>
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
                            }
                        } ?>
                    </tbody>
                </table>
    <?php }
        }
    } ?>
    <!---- End booking transfer | booking ----->
    <!----------------------------------------------------------------------------->
    <div class="divider divider-dark">
        <div class="divider-text p-0"></div>
    </div>
    <!----------------------------------------------------------------------------->
    <!---- Start Manafement transfer | booking ----->
    <table class="table table-bordered table-striped table-vouchure-t2">
        <thead class="thead-light">
            <tr>
                <th class="cell-fit text-center">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input dt-checkboxes" type="checkbox" id="checkmanage_all" name="checkmanage_all" onclick="checkbox('manage');" checked>
                        <label class="custom-control-label" for="checkmanage_all"></label>
                    </div>
                </th>
                <th class="text-center" width="6%">Total</th>
                <th class="cell-fit text-center">Other</th>
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
            $all_bookings = $manageObj->fetch_all_booking('manage', $search_status, $search_agent, $search_product, $refcode, $search_voucher_no, $name, $hotel, '', $manage_id);
            foreach ($all_bookings as $bookings) {
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
                    $tourist_all = $manageObj->get_values('tourist', 'booking_order_transfer', 'booking_transfer_id = ' . $bookings['bt_id'] . ' AND order_id != ' . $manage_id, 1);
                    $tourist_all = !empty($tourist_all) ? array_column($tourist_all, 'tourist') : []; // Extracts all 'price' values
            ?>
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input dt-checkboxes checkbox-manage" type="checkbox" id="checkbox<?php echo $bookings['bt_id']; ?>" name="manage_bt[]" value="<?php echo $bookings['bt_id']; ?>" onclick="sum_checkbox();" checked>
                                <label class="custom-control-label" for="checkbox<?php echo $bookings['bt_id']; ?>"></label>
                            </div>
                        </td>
                        <td class="text-center">
                            <input type="hidden" name="before_manage[]" value="<?php echo $bookings['bomange_id']; ?>">
                            <input id="toc-manage<?php echo $bookings['bt_id']; ?>" name="manage_tourist[]" class="form-control form-control-sm numeral-mask" type="text" value="<?php echo $bookings['tourist']; ?>" oninput="check_max_tourist(<?php echo $bookings['bt_id']; ?>, <?php echo !empty($tourist_all) ? array_sum($tourist_all) : 0; ?>, <?php echo !empty($bookings['max_tourist']) ? $bookings['max_tourist'] : 0; ?>);" />
                        </td>
                        <td class="text-center"><?php echo array_sum($tourist_all); ?> / <b><span class="" id="span<?php echo $bookings['bt_id']; ?>"><?php echo $bookings['max_tourist'] - (array_sum($tourist_all) + $bookings['tourist']); ?></span></b></td>
                        <td class="text-center"><?php echo !empty($bookings['adult']) ? $bookings['adult'] : 0; ?></td>
                        <td class="text-center"><?php echo !empty($bookings['child']) ? $bookings['child'] : 0; ?></td>
                        <td class="text-center"><?php echo !empty($bookings['infant']) ? $bookings['infant'] : 0; ?></td>
                        <td class="text-center"><?php echo !empty($bookings['foc']) ? $bookings['foc'] : 0; ?></td>
                        <td><?php echo $bookings['start_pickup'] != '00:00' ? date('H:i', strtotime($bookings['start_pickup'])) . ' - ' . date('H:i', strtotime($bookings['end_pickup'])) : ''; ?></td>
                        <td><span class="fw-bold"><?php echo $bookings['product_name']; ?></span></td>
                        <td><?php echo $text_zone; ?></td>
                        <td><?php echo $text_hotel; ?></td>
                        <td><span class="fw-bold"><?php echo $bookings['cus_name']; ?></span></td>
                        <!-- <td class="text-center" id="toc-manage<?php // echo $manage_bt[$manage_id][$c]; 
                                                                    ?>"><?php //echo $bookings['bt_adult'] + $bookings['bt_child'] + $bookings['bt_infant'] + $bookings['bt_foc']; 
                                                                        ?></td>
                        <td class="text-center"><?php //echo $bookings['bt_adult']; 
                                                ?></td>
                        <td class="text-center"><?php //echo $bookings['bt_child']; 
                                                ?></td>
                        <td class="text-center"><?php //echo $bookings['bt_infant']; 
                                                ?></td>
                        <td class="text-center"><?php //echo $bookings['bt_foc']; 
                                                ?></td> -->
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
        <button type="submit" class="btn btn-primary" onclick="submit_booking_manage(<?php echo $manage_id; ?>);">Submit</button>
    </div>
<?php
} else {
    echo false;
}
?>

<!-- <td class="text-center">
    <input id="adult<?php // echo $bookings['bt_id']; 
                    ?>" name="adult[]" class="form-control form-control-sm numeral-mask" type="text" value="<?php // echo !empty($bookings['adult']) ? $bookings['adult'] : 0; 
                                                                                                            ?>" onchange="sum_checkbox();" />
</td>
<td class="text-center">
    <input id="child<?php // echo $bookings['bt_id']; 
                    ?>" name="child[]" class="form-control form-control-sm numeral-mask" type="text" value="<?php // echo !empty($bookings['child']) ? $bookings['child'] : 0; 
                                                                                                            ?>" onchange="sum_checkbox();" />
</td>
<td class="text-center">
    <input id="infant<?php // echo $bookings['bt_id']; 
                        ?>" name="infant[]" class="form-control form-control-sm numeral-mask" type="text" value="<?php // echo !empty($bookings['infant']) ? $bookings['infant'] : 0; 
                                                                                                                    ?>" onchange="sum_checkbox();" />
</td>
<td class="text-center">
    <input id="foc<?php // echo $bookings['bt_id']; 
                    ?>" name="foc[]" class="form-control form-control-sm numeral-mask" type="text" value="<?php // echo !empty($bookings['foc']) ? $bookings['foc'] : 0; 
                                                                                                            ?>" onchange="sum_checkbox();" />
</td> -->