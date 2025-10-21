<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$manageObj = new Order();
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime(" +1 day"));
$times = date("H:i:s");


if (isset($_POST['action']) && $_POST['action'] == "search") {
    // get value from ajax
    $search_status = $_POST['search_status'] != "" ? $_POST['search_status'] : 'all';
    $search_agent = $_POST['search_agent'] != "" ? $_POST['search_agent'] : 'all';
    $search_product = $_POST['search_product'] != "" ? $_POST['search_product'] : 'all';
    $search_car = !empty($_POST['search_car']) ? $_POST['search_car'] : 'all';
    $search_driver = !empty($_POST['search_driver']) ? $_POST['search_driver'] : 'all';
    $search_travel_date = !empty($_POST['search_travel_date']) ? $_POST['search_travel_date'] : $tomorrow;
    $refcode = $_POST['refcode'] != "" ? $_POST['refcode'] : '';
    $search_voucher_no = $_POST['voucher_no'] != "" ? $_POST['voucher_no'] : '';
    $name = $_POST['name'] != "" ? $_POST['name'] : '';
    $hotel = $_POST['hotel'] != "" ? $_POST['hotel'] : '';

    $href = "./?pages=order-driver/print";
    $href .= "&search_travel_date=" . $search_travel_date;
    $href .= "&search_car=" . $search_car;
    $href .= "&search_status=" . $search_status;
    $href .= "&search_agent=" . $search_agent;
    $href .= "&search_product=" . $search_product;
    $href .= "&search_voucher_no=" . $search_voucher_no;
    $href .= "&refcode=" . $refcode;
    $href .= "&name=" . $name;
    $href .= "&hotel=" . $hotel;
    $href .= "&action=print";

    $all_manages = $manageObj->fetch_all_manage($search_car, $search_driver, $search_travel_date, 0);
?>
    <div class="bs-stepper horizontal-wizard-example">
        <div class="bs-stepper-header">
            <div class="step" data-target="#preview-pickup">
                <button type="button" class="step-trigger">
                    <span class="bs-stepper-box">1</span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">ใบจัดรถ Pickup</span>
                        <span class="bs-stepper-subtitle">จัดการรถ</span>
                    </span>
                </button>
            </div>
            <div class="step" data-target="#preview-dropoff">
                <button type="button" class="step-trigger">
                    <span class="bs-stepper-box">2</span>
                    <span class="bs-stepper-label">
                        <span class="bs-stepper-title">ใบจัดรถ Drop off</span>
                        <span class="bs-stepper-subtitle">Preview เอกสารการจัดรถ</span>
                    </span>
                </button>
            </div>
        </div>
        <div class="bs-stepper-content p-0">
            <!-- Management Car (Pickup) -->
            <div id="preview-pickup" class="content">
                <div class="card-body pt-0 p-50">
                    <a href='<?php echo $href; ?>&type=pickup' target="_blank"><button class="btn btn-info" id="print-btn">Print</button></a>
                    <button type="button" class="btn btn-info waves-effect waves-float waves-light btn-page-block-spinner" onclick="download_image(1);">Image</button>
                </div>
                <div id="div-driver-job-pickup" style="background-color: #FFF;">
                    <!-- Header starts -->
                    <div class="card-body pb-0 pt-0">
                        <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing">
                            <span class="brand-logo"><img src="app-assets/images/logo/logo-500.png" height="50"></span>
                            <span style="color: #000;">
                                โทร : 062-3322800 / 084-7443000 / 083-1757444 </br>
                                Email : Fantasticsimilantravel11@gmail.com
                            </span>
                        </div>
                        <div class="text-center card-text">
                            <h4 class="font-weight-bolder">ใบจัดรถ (Pickup)</h4>
                            <div class="badge badge-pill badge-light-danger">
                                <h5 class="m-0 pl-1 pr-1 text-danger"><?php echo date('j F Y', strtotime($search_travel_date)); ?></h5>
                            </div>
                        </div>
                    </div>
                    </br>
                    <!-- Header ends -->
                    <!-- Body starts -->
                    <?php
                    if (!empty($all_manages)) {
                        foreach ($all_manages as $manages) {
                    ?>
                            <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                                <div class="col-4 text-left text-bold h4"></div>
                                <div class="col-4 text-center"><span class="h4 badge-light-purple"><?php echo $manages['car_name']; ?></span></div>
                                <div class="col-4 text-right mb-50"></div>
                            </div>

                            <table class="table table-striped text-uppercase table-vouchure-t2">
                                <thead class="bg-light">
                                    <tr>
                                        <th colspan="3">คนขับ : <?php echo $manages['driver_name']; ?></th>
                                        <th colspan="4">ป้ายทะเบียน : <?php echo $manages['license']; ?></th>
                                        <th colspan="5">โทรศัพท์ : <?php echo $manages['telephone']; ?></th>
                                    </tr>
                                    <tr>
                                        <th width="5%">เวลารับ</th>
                                        <th width="15%">โปรแกรม</th>
                                        <th width="10%">เอเยนต์</th>
                                        <th width="10%" class="text-center">V/C</th>
                                        <th width="15%">โรงแรม</th>
                                        <th width="6%">ห้อง</th>
                                        <th width="15%">ชื่อลูกค้า</th>
                                        <th class="cell-fit text-center">Tourist</th>
                                        <th width="15%">Remark</th>
                                    </tr>
                                </thead>
                                <?php
                                $total_tourist = 0;
                                $bomange_arr = array();
                                $all_bookings = $manageObj->fetch_all_booking('manage', $search_status, $search_agent, $search_product, $refcode, $search_voucher_no, $name, $hotel, $search_travel_date, $manages['id']);
                                if ($all_bookings) { ?>
                                    <tbody>
                                        <?php
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
                                                $tourist_all = $manageObj->get_values('tourist', 'booking_order_transfer', 'booking_transfer_id = ' . $bookings['bt_id'] . ' AND order_id != ' . $manages['id'], 1);
                                                $tourist_all = !empty($tourist_all) ? array_column($tourist_all, 'tourist') : []; // Extracts all 'price' values
                                                $total_tourist += $bookings['tourist'];
                                        ?>
                                                <tr>
                                                    <td><?php echo $bookings['start_pickup'] != '00:00' ? date('H:i', strtotime($bookings['start_pickup'])) . ' - ' . date('H:i', strtotime($bookings['end_pickup'])) : ''; ?></td>
                                                    <td><?php echo $bookings['product_name']; ?></td>
                                                    <td><?php echo !empty($bookings['agent_name']) ? $bookings['agent_name'] : '-'; ?></td>
                                                    <td class="text-center"><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['book_full']; ?></td>
                                                    <td><?php echo $text_hotel . ' (' . $text_zone . ')'; ?></td>
                                                    <td><?php echo $bookings['room_no']; ?></td>
                                                    <td><?php echo $bookings['cus_name']; ?></td>
                                                    <td class="text-center"><?php echo $bookings['tourist']; ?></td>
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
                                <?php } ?>
                            </table>

                            <div class="text-center mt-2 pb-5">
                                <h4>
                                    <div class="badge badge-pill badge-light-warning">
                                        <b class="text-danger">TOTAL <?php echo $total_tourist; ?></b>
                                    </div>
                                </h4>
                            </div>

                    <?php }
                    } ?>
                    <input type="hidden" id="name_img_pickup" name="name_img_pickup" value="<?php echo 'ใบจัดรถ pickup - ' . date('j F Y', strtotime($search_travel_date)); ?>">
                    <!-- Body ends -->
                </div>
            </div>

            <!-- Preview (Dropoff) Job Driver -->
            <div id="preview-dropoff" class="content">
                <div class="card-body pt-0 p-50">
                    <a href='<?php echo $href; ?>&type=dropoff' target="_blank"><button class="btn btn-info" id="print-btn">Print</button></a>
                    <button type="button" class="btn btn-info waves-effect waves-float waves-light btn-page-block-spinner" onclick="download_image(2);">Image</button>
                </div>
                <div id="div-driver-job-dropoff" style="background-color: #FFF;">
                    <!-- Header starts -->
                    <div class="card-body pb-0 pt-0">
                        <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing">
                            <span class="brand-logo"><img src="app-assets/images/logo/logo-500.png" height="50"></span>
                            <span style="color: #000;">
                                โทร : 062-3322800 / 084-7443000 / 083-1757444 </br>
                                Email : Fantasticsimilantravel11@gmail.com
                            </span>
                        </div>
                        <div class="text-center card-text">
                            <h4 class="font-weight-bolder">ใบจัดรถ (Dropoff)</h4>
                            <div class="badge badge-pill badge-light-danger">
                                <h5 class="m-0 pl-1 pr-1 text-danger"><?php echo date('j F Y', strtotime($search_travel_date)); ?></h5>
                            </div>
                        </div>
                    </div>
                    </br>
                    <!-- Header ends -->
                    <!-- Body starts -->
                    <?php
                    $all_programed = $manageObj->fetch_all_booking('dropoff', $search_status, $search_agent, $search_product, $refcode, $search_voucher_no, $name, $hotel, $search_travel_date, 0);
                    if (!empty($all_programed)) {
                        $programe_array = array();
                        foreach ($all_programed as $programed) {
                            if (in_array($programed['product_id'], $programe_array) == false) {
                                $programe_array[] = $programed['product_id']; ?>
                                <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                                    <div class="col-4 text-left text-bold h4"></div>
                                    <div class="col-4 text-center text-bold h4"><?php echo $programed['product_name']; ?></div>
                                    <div class="col-4 text-right mb-50"></div>
                                </div>

                                <table class="table table-striped text-uppercase table-vouchure-t2">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="5%">เวลารับ</th>
                                            <!-- <th width="15%">โปรแกรม</th> -->
                                            <th width="5%">รถ</th>
                                            <th width="15%">เอเยนต์</th>
                                            <th width="10%" class="text-center">V/C</th>
                                            <th width="20%">โรงแรม</th>
                                            <th width="6%">ห้อง</th>
                                            <th width="20%">ชื่อลูกค้า</th>
                                            <th width="1%" class="text-center">A</th>
                                            <th width="1%" class="text-center">C</th>
                                            <th width="1%" class="text-center">Inf</th>
                                            <th width="1%" class="text-center">FOC</th>
                                            <th width="15%">Remark</th>
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
                                        $all_bookings = $manageObj->fetch_all_booking('dropoff', $search_status, $search_agent, $programed['product_id'], $refcode, $search_voucher_no, $name, $hotel, $search_travel_date, 0);
                                        foreach ($all_bookings as $bookings) {
                                            if (in_array($bookings['id'], $bookings_arr) == false && ($bookings['outside_pickup'] != $bookings['outside_dropoff'])) {
                                                $bookings_arr[] = $bookings['id'];
                                                $total_adult += $bookings['adult'];
                                                $total_child += $bookings['child'];
                                                $total_infant += $bookings['infant'];
                                                $total_foc += $bookings['foc'];
                                                $total_tourist += $bookings['adult'] + $bookings['child'] + $bookings['infant'] + $bookings['foc'];
                                                $cars = $manageObj->get_values(
                                                    'cars.name as name',
                                                    'booking_order_transfer 
                                                                    LEFT JOIN order_transfer ON order_transfer.id = booking_order_transfer.order_id 
                                                                    LEFT JOIN cars ON order_transfer.car_id = cars.id',
                                                    'booking_order_transfer.booking_transfer_id = ' . $bookings['bt_id'],
                                                    1
                                                );
                                        ?>
                                                <tr>
                                                    <td><?php echo $bookings['start_pickup'] != '00:00' ? date('H:i', strtotime($bookings['start_pickup'])) . ' - ' . date('H:i', strtotime($bookings['end_pickup'])) : ''; ?></td>
                                                    <td><?php if (!empty($cars)) {
                                                            foreach ($cars as $car) {
                                                                echo '<div class="badge badge-light-success">' . $car['name'] . '</div>';
                                                            }
                                                        } ?></td>
                                                    <td><?php echo $bookings['agent_name']; ?></td>
                                                    <td class="text-center"><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['book_full']; ?></td>
                                                    <td>
                                                        <?php
                                                        echo $bookings['outside_dropoff'] . ' (' . $bookings['zoned_name'] . ')';
                                                        echo $bookings['pickup_type'] == 3 ? '</br><small class="text-warning">เอารถขากลับ</small>' : '';
                                                        ?>
                                                    </td>
                                                    <td><?php echo $bookings['room_no']; ?></td>
                                                    <td><?php echo $bookings['cus_name']; ?></td>
                                                    <td class="text-center"><?php echo !empty($bookings['adult']) ? $bookings['adult'] : 0; ?></td>
                                                    <td class="text-center"><?php echo !empty($bookings['child']) ? $bookings['child'] : 0; ?></td>
                                                    <td class="text-center"><?php echo !empty($bookings['infant']) ? $bookings['infant'] : 0; ?></td>
                                                    <td class="text-center"><?php echo !empty($bookings['foc']) ? $bookings['foc'] : 0; ?></td>
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

                                <div class="text-center mt-2 pb-5">
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
                        }
                    } ?>
                    <input type="hidden" id="name_img_dropoff" name="name_img_dropoff" value="<?php echo 'ใบจัดรถ dropoff - ' . date('j F Y', strtotime($search_travel_date)); ?>">

                    <!-- Body ends -->
                </div>
            </div>
        </div>
    </div>
<?php
} else {
    return false;
}
