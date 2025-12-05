<?php
require_once 'controllers/Booking.php';

$bookObj = new Booking();
$today = date("Y-m-d");
$times = date("H:i:s");

if (!empty($_GET['id']) && $_GET['id'] > 0) {
    $get_bo_full = $bookObj->get_value('bo_full', 'bookings_no', $_GET['id']);
    function cal_date_diff($date1, $date2)
    {
        $a = date_create($date1);
        $b = date_create($date2);
        $diff = date_diff($a, $b);
        $day_diff_inv =  $diff->format("%R%a");
        $num_diff_inv =  $diff->format("%a");

        return $day_diff_inv;
    }
    $account = '';
} else {
    header('location:./?pages=booking/list');
}
?>
<div class="close_status">
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Booking</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./?pages=booking/list" class="btn-page-block-spinner">Booking List</a></li>
                                    <li class="breadcrumb-item active"><?php echo $get_bo_full['bo_full']; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <!-- Vertical Wizard -->
                <section class="horizontal-wizard">
                    <div class="bs-stepper horizontal-wizard-example">
                        <div class="bs-stepper-header">
                            <div class="step" data-target="#booking-preview-vertical">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-box">1</span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Preview</span>
                                        <span class="bs-stepper-subtitle">Please fill out</span>
                                    </span>
                                </button>
                            </div>
                            <div class="step" data-target="#program-details-vertical">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-box">2</span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Program Details</span>
                                        <span class="bs-stepper-subtitle">Please fill out</span>
                                    </span>
                                </button>
                            </div>
                            <div class="step" data-target="#history-vertical">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-box">3</span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">History</span>
                                        <span class="bs-stepper-subtitle">Please fill out</span>
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div class="bs-stepper-content">
                            <!-- Booking Preview Vertical -->
                            <div id="booking-preview-vertical" class="content">
                                <?php
                                include 'inc-print.php';
                                if ($bp_id > 0) { ?>
                                    <a href="./?pages=booking/print&id=<?php echo $bo_id; ?>" target="_blank"><button class="btn btn-info" id="print-btn">Print</button></a>
                                    <a href="javascript:void(0)"><button type="button" class="btn btn-info" value="image" onclick="download_image('voucher');">Image</button></a>
                                <?php } ?>
                            </div>
                            <!-- Programs Detail Vertical -->
                            <div id="program-details-vertical" class="content">
                                <form id="booking-edit-form" name="booking-edit-form" method="post" enctype="multipart/form-data">
                                    <!-- Start Form Booking Detail -->
                                    <div class="card shadow-none bg-transparent border-secondary border-lighten-5">
                                        <div class="card-header bg-light-secondary pt-1 pb-50 pl-1">
                                            <h5>Booking Detail</h5>
                                        </div>
                                        <div class="card-body mt-2">
                                            <input type="hidden" id="bo_id" name="bo_id" value="<?php echo $bo_id; ?>">
                                            <input type="hidden" id="bp_id" name="bp_id" value="<?php echo $bp_id; ?>">
                                            <input type="hidden" id="bt_id" name="bt_id" value="<?php echo $bt_id; ?>">
                                            <input type="hidden" name="agent_id" value="<?php echo $agent_id; ?>">
                                            <input type="hidden" name="book_type_id" value="<?php echo $book_type; ?>">
                                            <input type="hidden" name="book_full" value="<?php echo $book_full; ?>">
                                            <input type="hidden" name="mange_transfer_id" value="<?php echo $mange_transfer_id; ?>" /> <!-- manage transfer booking id -->
                                            <input type="hidden" name="mange_transfer" value="<?php echo $mange_transfer; ?>" /> <!-- manage transfer id -->
                                            <input type="hidden" name="mange_boat_id" value="<?php echo $mange_boat_id; ?>" /> <!-- manage boat booking id -->
                                            <input type="hidden" name="mange_boat" value="<?php echo $mange_boat; ?>" /> <!-- manage boat id -->
                                            <input type="hidden" name="confirm_id" value="<?php echo $confirm_id; ?>" /> <!-- confirm agent id -->
                                            <!-- <input type="hidden" id="pror_id" name="pror_id" value="" /> -->
                                            <!-- <input type="hidden" id="bopa_id" name="bopa_id" value="<?php echo $bopa_id; ?>"> -->
                                            <!-- get value default  -->
                                            <input type="hidden" id="json_bpr" name="json_bpr" value='<?php echo json_encode($bpr_id, true); ?>'>
                                            <input type="hidden" id="cate_id" name="cate_id" value='<?php echo json_encode($categorys, true); ?>'>
                                            <input type="hidden" id="travel" name="travel" value="<?php echo $travel_date; ?>">
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <label class="form-label" for="booking_no">Booking No.</label>
                                                    <div class="input-group">
                                                        <p><?php echo $book_full; ?></p>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label class="form-label" for="booking_date">Booking Date</label>
                                                    <div class="input-group">
                                                        <p><?php echo date('j F Y', strtotime($book_date)); ?></p>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label class="form-label" for="customer_type">Booking Type</label>
                                                    <?php
                                                    $books_type = $bookObj->show_booking_type();
                                                    foreach ($books_type as $type) {
                                                        $checked_book = $type['id'] == $book_type ? 'checked' : '';
                                                    ?>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="<?php echo 'booktype' . $type['id']; ?>" name="booking_type_id" class="custom-control-input customer_type" value="<?php echo $type['id']; ?>" <?php echo $checked_book; ?> onchange="check_date();" />
                                                            <label class="custom-control-label" for="<?php echo 'booktype' . $type['id']; ?>"><?php echo $type['name']; ?></label>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <div class="form-group" id="frm-agent">
                                                        <label for="agent">Agent (เอเยนต์)</label>
                                                        <select class="form-control select2" id="agent" name="agent" onchange="search_program();">
                                                            <option value="0">Please Select Agent...</option>
                                                            <option value="outside">กรอกข้อมูลเพิ่มเติม</option>
                                                            <?php
                                                            $agents = $bookObj->show_agent();
                                                            foreach ($agents as $agent) {
                                                                $selected_agent = $agent['id'] == $agent_id ? 'selected' : '';
                                                            ?>
                                                                <option value="<?php echo $agent['id']; ?>" <?php echo $selected_agent; ?>><?php echo $agent['name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group" id="frm-agent-outside" hidden>
                                                        <label for="agent_outside">Agent</label>
                                                        <input type="text" class="form-control" id="agent_outside" name="agent_outside" value="" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <div class="form-group">
                                                        <label for="book_status">Booking Status</label>
                                                        <select class="form-control select2" id="book_status" name="book_status">
                                                            <?php
                                                            $bookstype = $bookObj->show_booking_status();
                                                            foreach ($bookstype as $booktype) {
                                                            ?>
                                                                <option value="<?php echo $booktype['id']; ?>" <?php echo $book_status == $booktype['id'] ? 'selected' : ''; ?>><?php echo $booktype['name']; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label class="form-label" for="voucher_no_agent">Voucher No. (Agent)</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="voucher_no_agent" name="voucher_no_agent" value="<?php echo $voucher_no_agent; ?>" onchange="check_no_agent(this);">
                                                    </div>
                                                    <div class="invalid-feedback" id="invalid-voucher-no">หมายเลข Voucher ซ้ำ.</div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label class="form-label" for="sender">Sender</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="sender" name="sender" value="<?php echo $sender; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <input type="hidden" id="cus_id" name="cus_id" value="<?php echo !empty($customers['cus_id'][0]) ? $customers['cus_id'][0] : 0; ?>">
                                                    <label for="cus_name">Customer Name</label>
                                                    <input type="text" class="form-control" id="cus_name" name="cus_name" value="<?php echo !empty($customers['name'][0]) ? $customers['name'][0] : ''; ?>" />
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="cus_telephone">Telephone</label>
                                                    <input type="text" class="form-control" id="cus_telephone" name="cus_telephone" value="<?php echo !empty($customers['telephone'][0]) ? $customers['telephone'][0] : ''; ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Form Booking Detail -->
                                    <!-- Start Form Booking Product Detail -->
                                    <div id="div-show"></div>
                                    <div class="card shadow-none bg-transparent border-secondary border-lighten-5">
                                        <div class="card-header bg-light-secondary pt-1 pb-50 pl-1">
                                            <h5>Program Detail</h5>
                                        </div>
                                        <div class="card-body mt-2">
                                            <div class="row">
                                                <div class="col-md-3 col-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="travel_date">Travel Date</label><br>
                                                        <input type="text" class="form-control" id="travel_date" name="travel_date" value="<?php echo $travel_date; ?>" onchange="search_program();" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <div class="form-group">
                                                        <label for="product_id">Programe</label>
                                                        <select class="form-control select2" id="product_id" name="product_id" onchange="search_program();">
                                                            <?php
                                                            $prods = $bookObj->show_product();
                                                            foreach ($prods as $prod) {
                                                                $selected_prod = $prod['id'] == $product_id ? 'selected' : '';
                                                            ?>
                                                                <option value="<?php echo $prod['id']; ?>" <?php echo $selected_prod; ?>><?php echo $prod['name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <div class="form-group">
                                                        <label for="category_id">Categorys</label>
                                                        <select class="form-control select2" id="category_id" name="category_id[]" onchange="check_category();" multiple>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!------ start div rates ---->
                                            <div class="row" id="div-rates">
                                            </div>
                                            <!------ end div rates ---->
                                            <div class="row">
                                                <div class="col-md-12 col-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="bp_note">Program (Tour Detail)</label>
                                                        <textarea class="form-control" name="bp_note" id="bp_note" rows="3"><?php echo $note; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="discount">Discount (ส่วนลด)</label>
                                                        <input type="text" class="form-control numeral-mask" id="discount" name="discount" value="<?php echo number_format($discount); ?>" />
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="cot">COT</label>
                                                        <input type="text" class="form-control numeral-mask" id="cot" name="cot" value="<?php echo !empty($cot) ? $cot : 0; ?>" />
                                                        <input type="hidden" id="cot_id" name="cot_id" value="<?php echo !empty($cot_id) ? $cot_id : 0; ?>" />
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3" id="div-total">
                                                    <div class="form-group">
                                                        <label class="form-label" for="rate_total">Total Price (Program)</label>
                                                        <input type="text" class="form-control numeral-mask" id="rate_total" name="rate_total" value="" readonly />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Form Booking Product Detail -->
                                    <!-- Start Form Booking Transfer Detail -->
                                    <div class="card shadow-none bg-transparent border-secondary border-lighten-5">
                                        <div class="card-header bg-light-secondary pt-1 pb-50 pl-1">
                                            <h5>Transfer Detail</h5>
                                        </div>
                                        <div class="card-body mt-2">
                                            <div class="row">
                                                <div class="form-group col-md-4 col-12">
                                                    <label class="form-label">Transfer</label>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="pickup_type_1" name="pickup_type" class="custom-control-input" value="1" <?php echo $pickup_type == 1 || $pickup_type == 0 ? 'checked' : ''; ?> onclick="check_transfer();" />
                                                        <label class="custom-control-label" for="pickup_type_1">เอารถรับส่ง</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="pickup_type_2" name="pickup_type" class="custom-control-input" value="2" <?php echo $pickup_type == 2 ? 'checked' : ''; ?> onclick="check_transfer();" />
                                                        <label class="custom-control-label" for="pickup_type_2">เดินทางมาเอง</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="pickup_type_3" name="pickup_type" class="custom-control-input" value="3" <?php echo $pickup_type == 3 ? 'checked' : ''; ?> onclick="check_transfer();" />
                                                        <label class="custom-control-label" for="pickup_type_3">เอารถขากลับ</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4 col-12">
                                                    <label class="form-label">Transfer Type </label>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="transfer_type_join" name="transfer_type" class="custom-control-input" value="1" <?php echo $transfer_type == 1 || $transfer_type == 0 ? 'checked' : ''; ?> onchange="check_transfer_type();" />
                                                        <label class="custom-control-label" for="transfer_type_join">Join</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="transfer_type_private" name="transfer_type" class="custom-control-input" value="2" <?php echo $transfer_type == 2 ? 'checked' : ''; ?> onchange="check_transfer_type();" />
                                                        <label class="custom-control-label" for="transfer_type_private">Private</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <!-- <div class="form-group col-md-2 col-12">
                                                    <label class="form-label" for="bt_adult">Adult (ผู้ใหญ่)</label>
                                                    <input type="text" class="form-control numeral-mask" id="bt_adult" name="bt_adult" value="<?php echo $bt_adult; ?>" />
                                                </div>
                                                <div class="form-group col-md-2 col-12">
                                                    <label class="form-label" for="bt_child">Children (เด็ก)</label>
                                                    <input type="text" class="form-control numeral-mask" id="bt_child" name="bt_child" value="<?php echo $bt_child; ?>" />
                                                </div>
                                                <div class="form-group col-md-2 col-12">
                                                    <label class="form-label" for="bt_infant">Infant (ทารก)</label>
                                                    <input type="text" class="form-control numeral-mask" id="bt_infant" name="bt_infant" value="<?php echo $bt_infant; ?>" />
                                                </div>
                                                <div class="form-group col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="bt_foc">FOC</label>
                                                        <input type="text" class="form-control numeral-mask" id="bt_foc" name="bt_foc" value="<?php echo $bt_foc; ?>" />
                                                    </div>
                                                </div> -->
                                            </div>
                                            <div class="row" id="div-transfer">
                                                <div class="form-group col-md-3 div-transfer-pickup">
                                                    <div class="form-group">
                                                        <label for="pickup">Pickup Zone (โซนรับ)</label>
                                                        <select class="form-control select2" id="zone_pickup" name="pickup" onchange="check_time('zone_pickup');">
                                                            <option value="0">Please Select pickup...</option>
                                                            <?php
                                                            $zones = $bookObj->show_zone();
                                                            foreach ($zones as $zone) {
                                                                $select_zp = $zone['id'] == $pickup_id ? 'selected' : '';
                                                            ?>
                                                                <option value="<?php echo $zone['id']; ?>" <?php echo $select_zp; ?> data-start-pickup="<?php echo date("H:i", strtotime($zone['start_pickup'])); ?>" data-end-pickup="<?php echo date("H:i", strtotime($zone['end_pickup'])); ?>"><?php echo $zone['name']; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3 div-transfer-pickup">
                                                    <div class="form-group">
                                                        <label class="form-label" for="hotel_pickup_outside">Pickup Outside (ระบุสถานที่นอก)</label>
                                                        <input type="text" id="hotel_pickup_outside" name="hotel_pickup_outside" class="form-control" value="<?php echo $hotel_pickup_outside; ?>" />
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <table>
                                                        <tr>
                                                            <td width="100%" colspan="3"><label for="start_pickup">Pickup Time (เวลารับ)</label></td>
                                                        </tr>
                                                        <tr>
                                                            <td width="45%">
                                                                <input type="text" id="start_pickup" name="start_pickup" class="form-control time-mask text-left" placeholder="HH:MM" value="<?php echo date("H:i", strtotime($start_pickup)); ?>" />
                                                            </td>
                                                            <td width="10%" align="center"> - </td>
                                                            <td width="45%">
                                                                <input type="text" id="end_pickup" name="end_pickup" class="form-control time-mask text-left" placeholder="HH:MM" value="<?php echo date("H:i", strtotime($end_pickup)); ?>" />
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="room_no">Room No. (ห้อง)</label>
                                                        <input type="text" id="room_no" name="room_no" class="form-control" value="<?php echo $room_no; ?>" />
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <div class="form-group">
                                                        <label for="dropoff">Zone Dropoff (โซนส่ง)</label>
                                                        <select class="form-control select2" id="dropoff" name="dropoff">
                                                            <option value="0">Please Select dropoff...</option>
                                                            <?php
                                                            $dropoffs = $bookObj->show_zone('dropoff');
                                                            foreach ($dropoffs as $dropoff) {
                                                                $select_zd = $dropoff['id'] == $dropoff_id ? 'selected' : '';
                                                            ?>
                                                                <option value="<?php echo $dropoff['id']; ?>" <?php echo $select_zd; ?>><?php echo $dropoff['name']; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label" for="hotel_dropoff_outside">Dropoff Outside (ระบุสถานที่นอก)</label>
                                                        <input type="text" id="hotel_dropoff_outside" name="hotel_dropoff_outside" class="form-control" placeholder="ที่เดียวกับสถานที่รับ" value="<?php echo $hotel_dropoff_outside; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="trans_note">Note (Transfer Detail)</label>
                                                        <textarea class="form-control" name="trans_note" id="trans_note" rows="3"><?php echo $bt_note; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Form Booking Transfer Detail -->
                                    <!-- Start Form Booking Extra Charge Detail -->
                                    <div class="card shadow-none bg-transparent border-secondary border-lighten-5">
                                        <div class="card-header bg-light-secondary pt-1 pb-50 pl-1">
                                            <h5>Extra Charge Detail</h5>
                                        </div>
                                        <?php
                                        $extras = $bookObj->show_extra_charge();
                                        foreach ($extras as $extra) {
                                            echo '<input type="hidden" class="small" id="extar_ad' . $extra['id'] . '" value="' . $extra['rate_adult'] . '">';
                                            echo '<input type="hidden" class="small" id="extar_chd' . $extra['id'] . '" value="' . $extra['rate_child'] . '">';
                                            echo '<input type="hidden" class="small" id="extar_inf' . $extra['id'] . '" value="' . $extra['rate_infant'] . '">';
                                            echo '<input type="hidden" class="small" id="extar_total' . $extra['id'] . '" value="' . $extra['rate_total'] . '">';
                                        }
                                        ?>
                                        <div class="card-body mt-2">
                                            <div class="extra-charge-repeater">
                                                <div data-repeater-list="extra-charge">
                                                    <?php if ($extar) {
                                                        for ($i = 0; $i < count($extar['bec_id']); $i++) { ?>
                                                            <input type="hidden" name="before_bec_id[]" value="<?php echo $extar['bec_id'][$i]; ?>">
                                                            <div data-repeater-item>
                                                                <input type="hidden" name="bec_id" value="<?php echo $extar['bec_id'][$i]; ?>">
                                                                <div id="div-start-extra-charge">
                                                                    <div class="row d-flex align-items-start">
                                                                        <div class="col-md-3 col-12">
                                                                            <div class="form-group">
                                                                                <label for="extra_charge">Extra Charge (ค่าใช้จ่ายเพิ่มเติม)</label>
                                                                                <select class="form-control" name="extra_charge" data-extra-repeater="select2" onchange="chang_extra_charge(this);">
                                                                                    <option value="0">Please Select Extra Charge...</option>
                                                                                    <?php
                                                                                    foreach ($extras as $extra) {
                                                                                        $select_extra = $extra['id'] == $extar['extra_id'][$i] ? 'selected' : '';
                                                                                    ?>
                                                                                        <option value="<?php echo $extra['id']; ?>" <?php echo $select_extra; ?>><?php echo $extra['name']; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3 col-12">
                                                                            <div class="form-group">
                                                                                <label for="extc_name">Custom Extra Charge (กำหนดเองค่าใช้จ่ายเพิ่มเติม)</label>
                                                                                <input type="text" class="form-control" name="extc_name" aria-describedby="extc_name" value="<?php echo $extar['bec_name'][$i]; ?>" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3 col-12">
                                                                            <div class="form-group">
                                                                                <label for="extra_type">Extra Charge Type (ค่าใช้จ่ายประเภท)</label>
                                                                                <select class="form-control" name="extra_type" data-extra-repeater="select2" onchange="check_extar_type(this);">
                                                                                    <option value="0" <?php echo $extar['bec_type'][$i] == 0 ? 'selected' : ''; ?>>Please Select Type...</option>
                                                                                    <option value="1" <?php echo $extar['bec_type'][$i] == 1 ? 'selected' : ''; ?>>Per Pax</option>
                                                                                    <option value="2" <?php echo $extar['bec_type'][$i] == 2 ? 'selected' : ''; ?>>Total</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2 col-12">
                                                                            <div class="form-group">
                                                                                <label for="extc_total">Total (รวมทั้งหมด)</label><br>
                                                                                <span name="extc_total" class="text-danger text-bold h5"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1 col-12 mb-50 mt-2">
                                                                            <div class="form-group">
                                                                                <button class="btn btn-outline-danger text-nowrap px-1" data-repeater-delete type="button">
                                                                                    <i data-feather="x"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row d-flex align-items-start">
                                                                        <div class="col-md-3 col-12" name="div_extar_perpax" hidden>
                                                                            <table>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="form-group">
                                                                                            <label class="form-label" for="extra_adult">Adult (ผู้ใหญ่)</label>
                                                                                            <input type="number" class="form-control" name="extra_adult" oninput="checke_rate_extar();" value="<?php echo $extar['bec_adult'][$i]; ?>" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td <?php echo $account; ?>><i data-feather='x' class="m-1 font-medium-4"></i></td>
                                                                                    <td <?php echo $account; ?>>
                                                                                        <div class="form-group">
                                                                                            <label class="form-label" for="extra_rate_adult">Rate Adult (ราคาผู้ใหญ่)</label>
                                                                                            <input type="text" name="extra_rate_adult" class="form-control numeral-mask" value="<?php echo number_format($extar['bec_rate_adult'][$i]); ?>" oninput="checke_rate_extar();">
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                        <div class="col-md-3 col-12" name="div_extar_perpax" hidden>
                                                                            <table>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="form-group">
                                                                                            <label class="form-label" for="extra_child">Children (เด็ก)</label>
                                                                                            <input type="number" class="form-control" name="extra_child" oninput="checke_rate_extar();" value="<?php echo $extar['bec_child'][$i]; ?>" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td <?php echo $account; ?>><i data-feather='x' class="m-1 font-medium-4"></i></td>
                                                                                    <td <?php echo $account; ?>>
                                                                                        <div class="form-group">
                                                                                            <label class="form-label" for="extra_rate_child">Rate Children (ราคาเด็ก)</label>
                                                                                            <input type="text" name="extra_rate_child" class="form-control numeral-mask" value="<?php echo number_format($extar['bec_rate_child'][$i]); ?>" oninput="checke_rate_extar();">
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                        <div class="col-md-3 col-12" name="div_extar_perpax" hidden>
                                                                            <table>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="form-group">
                                                                                            <label class="form-label" for="extra_infant">Infant (ทารก)</label>
                                                                                            <input type="number" class="form-control" name="extra_infant" oninput="checke_rate_extar();" value="<?php echo $extar['bec_infant'][$i]; ?>" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td <?php echo $account; ?>><i data-feather='x' class="m-1 font-medium-4"></i></td>
                                                                                    <td <?php echo $account; ?>>
                                                                                        <div class="form-group">
                                                                                            <label class="form-label" for="extra_rate_infant">Rate Infant (ราคาทารก)</label>
                                                                                            <input type="text" name="extra_rate_infant" class="form-control numeral-mask" value="<?php echo number_format($extar['bec_rate_infant'][$i]); ?>" oninput="checke_rate_extar();">
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                        <div class="col-md-3 col-12" name="div_extar_total" hidden>
                                                                            <table>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="form-group">
                                                                                            <label class="form-label" for="extra_num_private">Private (จำนวน)</label>
                                                                                            <input type="number" class="form-control" name="extra_num_private" oninput="checke_rate_extar();" value="<?php echo $extar['bec_privates'][$i]; ?>" />
                                                                                        </div>
                                                                                    </td>
                                                                                    <td <?php echo $account; ?>><i data-feather='x' class="m-1 font-medium-4"></i></td>
                                                                                    <td <?php echo $account; ?>>
                                                                                        <div class="form-group">
                                                                                            <label class="form-label" for="extra_rate_private">Rate Private (ราคา/จำนวน)</label>
                                                                                            <input type="text" name="extra_rate_private" class="form-control numeral-mask" value="<?php echo number_format($extar['bec_rate_private'][$i]); ?>" oninput="checke_rate_extar();">
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <hr />
                                                            </div>
                                                        <?php }
                                                    } else { ?>
                                                        <div data-repeater-item>
                                                            <input type="hidden" name="bec_id" value="">
                                                            <div id="div-extra-charge">
                                                                <div class="row d-flex align-items-start">
                                                                    <div class="col-md-3 col-12">
                                                                        <div class="form-group">
                                                                            <label for="extra_charge">Extra Charge (ค่าใช้จ่ายเพิ่มเติม)</label>
                                                                            <select class="form-control" name="extra_charge" data-extra-repeater="select2" onchange="chang_extra_charge(this);">
                                                                                <option value="0">Please Select Extra Charge...</option>
                                                                                <?php
                                                                                foreach ($extras as $extra) {
                                                                                ?>
                                                                                    <option value="<?php echo $extra['id']; ?>"><?php echo $extra['name']; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 col-12">
                                                                        <div class="form-group">
                                                                            <label for="extc_name">Custom Extra Charge (กำหนดเองค่าใช้จ่ายเพิ่มเติม)</label>
                                                                            <input type="text" class="form-control" name="extc_name" aria-describedby="extc_name" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 col-12">
                                                                        <div class="form-group">
                                                                            <label for="extra_type">Extra Charge Type (ค่าใช้จ่ายประเภท)</label>
                                                                            <select class="form-control" name="extra_type" data-extra-repeater="select2" onchange="check_extar_type(this);">
                                                                                <option value="0">Please Select Type...</option>
                                                                                <option value="1">Per Pax</option>
                                                                                <option value="2">Total</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2 col-12" <?php echo $account; ?>>
                                                                        <div class="form-group">
                                                                            <label for="extc_total">Total (รวมทั้งหมด)</label><br>
                                                                            <span name="extc_total" class="text-danger text-bold h5"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1 col-12 mb-50 mt-2">
                                                                        <div class="form-group">
                                                                            <button class="btn btn-outline-danger text-nowrap px-1" data-repeater-delete type="button">
                                                                                <i data-feather="x"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row d-flex align-items-start">
                                                                    <div class="col-md-3 col-12" name="div_extar_perpax" hidden>
                                                                        <table>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="extra_adult">Adult (ผู้ใหญ่)</label>
                                                                                        <input type="number" class="form-control" name="extra_adult" oninput="checke_rate_extar();" value="0" />
                                                                                    </div>
                                                                                </td>
                                                                                <td <?php echo $account; ?>><i data-feather='x' class="m-1 font-medium-4"></i></td>
                                                                                <td <?php echo $account; ?>>
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="extra_rate_adult">Rate Adult (ราคาผู้ใหญ่)</label>
                                                                                        <input type="text" name="extra_rate_adult" class="form-control numeral-mask" value="0" oninput="checke_rate_extar();">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-md-3 col-12" name="div_extar_perpax" hidden>
                                                                        <table>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="extra_child">Children (เด็ก)</label>
                                                                                        <input type="number" class="form-control" name="extra_child" oninput="checke_rate_extar();" value="0" />
                                                                                    </div>
                                                                                </td>
                                                                                <td <?php echo $account; ?>><i data-feather='x' class="m-1 font-medium-4"></i></td>
                                                                                <td <?php echo $account; ?>>
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="extra_rate_child">Rate Children (ราคาเด็ก)</label>
                                                                                        <input type="text" name="extra_rate_child" class="form-control numeral-mask" value="0" oninput="checke_rate_extar();">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-md-3 col-12" name="div_extar_perpax" hidden>
                                                                        <table>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="extra_infant">Infant (ทารก)</label>
                                                                                        <input type="number" class="form-control" name="extra_infant" oninput="checke_rate_extar();" value="0" />
                                                                                    </div>
                                                                                </td>
                                                                                <td <?php echo $account; ?>><i data-feather='x' class="m-1 font-medium-4"></i></td>
                                                                                <td <?php echo $account; ?>>
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="extra_rate_infant">Rate Infant (ราคาทารก)</label>
                                                                                        <input type="text" name="extra_rate_infant" class="form-control numeral-mask" value="0" oninput="checke_rate_extar();">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-md-3 col-12" name="div_extar_total" hidden>
                                                                        <table>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="extra_num_private">Private (จำนวน)</label>
                                                                                        <input type="number" class="form-control" name="extra_num_private" oninput="checke_rate_extar();" value="0" />
                                                                                    </div>
                                                                                </td>
                                                                                <td <?php echo $account; ?>><i data-feather='x' class="m-1 font-medium-4"></i></td>
                                                                                <td <?php echo $account; ?>>
                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="extra_rate_private">Rate Private (ราคา/จำนวน)</label>
                                                                                        <input type="text" name="extra_rate_private" class="form-control numeral-mask" value="0" oninput="checke_rate_extar();">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr />
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-12">
                                                        <button class="btn btn-outline-primary mr-50" type="button" data-repeater-create>
                                                            <i data-feather="plus" class="mr-25"></i>
                                                            <span>Add Extar Chang</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Form Booking Extra Charge Detail -->
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-danger btn-page-block-spinner" onclick="deleteBooking(<?php echo $bo_id; ?>);">
                                            <i data-feather="trash-2" class="mr-25"></i>
                                            <span>Deleted</span>
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-page-block-spinner" name="submit" value="Submit">
                                            <i data-feather='plus' class="mr-25"></i>
                                            <span>Submit</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- history Vertical -->
                            <div id="history-vertical" class="content">
                                <div class="row">
                                    <div class="col-12 border-bottom pb-1">
                                        <h3>ประวัติการใช้งาน Booking #<?php echo $book_full; ?></h3>
                                    </div>
                                    <div class="col-12">
                                        <div class="card-body">
                                            <ul class="timeline">
                                                <?php $logs = $bookObj->showlog($_GET['id']);
                                                if (!empty($logs)) {
                                                    foreach ($logs as $log) {
                                                        switch ($log['type_id']) {
                                                            case 1:
                                                                $color = 'success'; // insert
                                                                break;
                                                            case 2:
                                                                $color = 'info'; // update
                                                                break;
                                                            case 3:
                                                                $color = 'danger'; // delete
                                                                break;
                                                            case 4:
                                                                $color = 'warning';
                                                                break;
                                                            default:
                                                                $color = 'primary';
                                                                break;
                                                        }
                                                ?>
                                                        <li class="timeline-item">
                                                            <span class="timeline-point timeline-point-<?php echo $color; ?> timeline-point-indicator"></span>
                                                            <div class="timeline-event">
                                                                <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0">
                                                                    <span>
                                                                        <h6><?php echo $log['name']; ?></h6>
                                                                        <p class="mb-0">
                                                                            <?php echo !empty($log['detail']) ? $log['detail'] . '<br>' : ''; ?>
                                                                            <?php echo $log['firstname'] . ' ' . $log['lastname']; ?>
                                                                        </p>
                                                                    </span>
                                                                    <span class="timeline-event-time"><?php echo $log['created_at']; ?></span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                <?php }
                                                } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!------------------------------------------------------------------>
                            <!-- End Form Modal -->

                            <!-- Items Vertical -->
                        </div>
                </section>

            </div>
            <!-- /Vertical Wizard -->
        </div>
    </div>
</div>
<?php
$close_conn = $bookObj->close();
?>