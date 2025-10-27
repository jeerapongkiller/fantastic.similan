<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$manageObj = new Order();
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime(" +1 day"));

if (isset($_POST['action']) && $_POST['action'] == "search") {
    // get value from ajax
    $get_date = !empty($_POST['date_travel_form']) ? $_POST['date_travel_form'] : $tomorrow; // $tomorrow->format("Y-m-d")
    $search_boat = !empty($_POST['search_boat']) ? $_POST['search_boat'] : 'all';
    $search_status = $_POST['search_status'] != "" ? $_POST['search_status'] : 'all';
    $search_agent = $_POST['search_agent'] != "" ? $_POST['search_agent'] : 'all';
    $search_product = $_POST['search_product'] != "" ? $_POST['search_product'] : 'all';
    $search_guide = $_POST['search_guide'] != "" ? $_POST['search_guide'] : 'all';
    $search_voucher_no = $_POST['voucher_no'] != "" ? $_POST['voucher_no'] : '';
    $refcode = $_POST['refcode'] != "" ? $_POST['refcode'] : '';
    $name = $_POST['name'] != "" ? $_POST['name'] : '';
    $hotel = '';
    $manage_id = 0;

    $href = "./?pages=order-guide/print";
    $href .= "&date_travel_form=" . $get_date;
    $href .= "&search_boat=" . $search_boat;
    $href .= "&search_status=" . $search_status;
    $href .= "&search_agent=" . $search_agent;
    $href .= "&search_product=" . $search_product;
    $href .= "&search_guide=" . $search_guide;
    $href .= "&search_voucher_no=" . $search_voucher_no;
    $href .= "&refcode=" . $refcode;
    $href .= "&name=" . $name;
    $href .= "&hotel=" . $hotel;
    $href .= "&action=print";

    # --- get data --- #
    $all_manages = $manageObj->fetch_all_manageboat($get_date, $search_boat, $search_guide, $manage_id);

    $categorys_array = array();
    $all_bookings = $manageObj->fetch_all_bookingboat('all', $get_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, $hotel, 0);
    foreach ($all_bookings as $categorys) {
        $categorys_array[] = $categorys['id'];
        $category_name[$categorys['id']][] = $categorys['category_name'];
    }

    $name_img = 'ใบงาน [' . date('j F Y', strtotime($get_date)) . ']';
?>
    <div class="content-header">
        <div class="pl-1 pt-0 pb-0">
            <a href='<?php echo $href; ?>' target="_blank" class="btn btn-info">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Print
            </a>
            <a href="javascript:void(0)">
                <button type="button" class="btn btn-info" value="image" onclick="download_image();">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    Image
                </button>
            </a>
        </div>
    </div>
    <hr class="pb-0 pt-0">
    <div id="order-guide-image-table" style="background-color: #FFF;">
        <!-- Header starts -->
        <div class="card-body pb-0">
            <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing">
                <span class="brand-logo"><img src="app-assets/images/logo/logo-500.png" height="50"></span>
                <span style="color: #000;">
                    โทร : 062-3322800 / 084-7443000 / 083-1757444 </br>
                    Email : Fantasticsimilantravel11@gmail.com
                </span>
            </div>
            <div class="text-center card-text">
                <h4 class="font-weight-bolder">ใบไกด์ - Daily Guide Report</h4>
                <div class="badge badge-pill badge-light-danger">
                    <h5 class="m-0 pl-1 pr-1 text-danger"><?php echo date('j F Y', strtotime($get_date)); ?></h5>
                </div>
            </div>
        </div>
        </br>
        <!-- Header ends -->
        <!-- Body starts -->
        <div id="div-guide-list">
            <?php
            if ($all_manages) {
                foreach ($all_manages as $key => $manages) {
            ?>
                    <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                        <div class="col-4 text-left text-bold h4"></div>
                        <div class="col-4 text-center"><span class="h4 badge-light-purple"><?php echo $manages['boat_name']; ?></span></div>
                        <div class="col-4 text-right mb-50"></div>
                    </div>

                    <table class="table table-striped text-uppercase table-vouchure-t2">
                        <thead class="bg-light">
                            <tr>
                                <th colspan="5">ไกด์ : <?php echo $manages['guide_name']; ?></th>
                                <th colspan="6">เคาน์เตอร์ : <?php echo $manages['counter']; ?></th>
                                <th colspan="4" style="background-color: <?php echo $manages['color_hex']; ?>; <?php echo $manages['text_color'] != '' ? 'color: ' . $manages['text_color'] . ';' : ''; ?>">
                                    สี : <?php echo $manages['color_name_th']; ?>
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center" width="1%">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input dt-checkboxes" type="checkbox" id="checkall<?php echo $manages['id']; ?>" onclick="checkbox(<?php echo $manages['id']; ?>);" <?php echo !empty($checkall) ? $checkall : ''; ?> />
                                        <label class="custom-control-label" for="checkall<?php echo $manages['id']; ?>"></label>
                                    </div>
                                </th>
                                <th width="5%">เวลารับ</th>
                                <th width="5%">Driver</th>
                                <th width="15%">เอเยนต์</th>
                                <th width="15%">ชื่อลูกค้า</th>
                                <th width="5%">V/C</th>
                                <th width="20%">โรงแรม</th>
                                <th width="9%">ห้อง</th>
                                <th class="text-center" width="1%">รวม</th>
                                <th class="text-center" width="1%">A</th>
                                <th class="text-center" width="1%">C</th>
                                <th class="text-center" width="1%">Inf</th>
                                <th class="text-center" width="1%">FOC</th>
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
                            $bomange_arr = array();
                            $all_bookings = $manageObj->fetch_all_bookingboat('manage', $get_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, $hotel, $manages['id']);
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

                                    $cars = $manageObj->get_values(
                                        'cars.name as name',
                                        'booking_order_transfer 
                                                            LEFT JOIN order_transfer ON order_transfer.id = booking_order_transfer.order_id 
                                                            LEFT JOIN cars ON order_transfer.car_id = cars.id',
                                        'booking_order_transfer.booking_transfer_id = ' . $bookings['bt_id'],
                                        1
                                    );

                                    $check_in = $manageObj->get_values('id', 'check_in', 'booking_id = ' . $bookings['id'] . ' AND type = 2', 0);
                            ?>
                                    <tr>
                                        <td class="text-center">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input dt-checkboxes checkbox-<?php echo $manages['id']; ?>" type="checkbox"
                                                    data-check="<?php echo !empty($check_in['id']) ? $check_in['id'] : 0; ?>"
                                                    data-mange="<?php echo $manages['id']; ?>"
                                                    id="checkbox<?php echo $bookings['id']; ?>"
                                                    value="<?php echo $bookings['id']; ?>"
                                                    onclick="submit_check_in('only', this);"
                                                    <?php echo (!empty($check_in['id']) && $check_in['id'] > 0) ? 'checked' : ''; ?> />
                                                <label class="custom-control-label" for="checkbox<?php echo $bookings['id']; ?>"></label>
                                            </div>
                                        </td>
                                        <td class="cell-fit"><?php echo date('H:i', strtotime($bookings['start_pickup'])) . ' - ' . date('H:i', strtotime($bookings['end_pickup'])); ?></td>
                                        <td class="cell-fit">
                                            <?php if (!empty($cars)) {
                                                foreach ($cars as $key => $car) {
                                                    echo $key > 0 ? '<br>' : '';
                                                    echo '<div class="badge badge-light-success">' . $car['name'] . '</div>';
                                                }
                                            } ?>
                                        </td>
                                        <td><?php echo $bookings['agent_name']; ?></td>
                                        <td><?php echo $bookings['cus_name']; ?></td>
                                        <td><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['book_full']; ?></td>
                                        <td style="padding: 5px;"><?php echo $text_hotel; ?></td>
                                        <td class="cell-fit"><?php echo $bookings['room_no']; ?></td>
                                        <td class="cell-fit text-center"><?php echo $tourist; ?></td>
                                        <td class="cell-fit text-center"><?php echo !empty($bookings['adult']) ? $bookings['adult'] : 0; ?></td>
                                        <td class="cell-fit text-center"><?php echo !empty($bookings['child']) ? $bookings['child'] : 0; ?></td>
                                        <td class="cell-fit text-center"><?php echo !empty($bookings['infant']) ? $bookings['infant'] : 0; ?></td>
                                        <td class="cell-fit text-center"><?php echo !empty($bookings['foc']) ? $bookings['foc'] : 0; ?></td>
                                        <td class="cell-fit text-nowrap"><b class="text-warning"><?php echo number_format($bookings['cot']); ?></b></td>
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
                                                echo $bookings['bp_note']; ?>
                                            </b>
                                        </td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>

                    <div class="text-center mt-1 pb-2">
                        <h4>
                            <div class="badge badge-pill badge-light-warning">
                                <b class="text-danger">TOTAL <?php echo $total_tourist; ?></b> |
                                Adult : <?php echo $total_adult; ?>
                                Child : <?php echo $total_child; ?>
                                Infant : <?php echo $total_infant; ?>
                                FOC : <?php echo $total_foc; ?>
                            </div>
                        </h4>
                    </div>
            <?php }
            } ?>
        </div>
        <!-- Body ends -->
        <input type="hidden" id="name_img" name="name_img" value="<?php echo $name_img; ?>">
    </div>
<?php
} else {
    echo FALSE;
}
