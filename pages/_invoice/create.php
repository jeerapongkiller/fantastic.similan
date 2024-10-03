<?php

use Mpdf\Tag\Em;

require_once 'controllers/Invoice.php';

$invObj = new Invoice();
$today = date("Y-m-d");
$times = date("H:i:s");

$search_travel = !empty($_GET['search_travel']) ? $_GET['search_travel'] : '';
$search_agent = !empty($_GET['search_agent']) ? $_GET['search_agent'] : 'all';
?>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- cars list start -->
            <section class="app-user-list">
                <div class="card">
                    <!-- Search Filter Start -->
                    <div class="content-header">
                        <h5 class="pt-1 pl-2 pb-0">Search Filter</h5>
                        <form id="invoice-search-form" name="invoice-search-form" method="post" enctype="multipart/form-data">
                            <div class="d-flex align-items-center mx-50 row pt-0 pb-0">
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <label class="form-label" for="search_travel">Travel Date</label>
                                        <input type="text" class="form-control flatpickr-range" id="search_travel" name="search_travel" value="<?php echo $search_travel; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label for="search_agent">Agents</label>
                                        <select class="form-control select2" id="search_agent" name="search_agent">
                                            <option value="all" <?php echo $search_agent == 'all' ? 'selected' : ''; ?>>All</option>
                                            <?php
                                            $agents = $invObj->showlistagent();
                                            foreach ($agents as $agent) {
                                            ?>
                                                <option value="<?php echo $agent['id']; ?>" data-name="<?php echo $agent['name']; ?>" data-address="<?php echo $agent['address']; ?>" data-telephone="<?php echo $agent['telephone']; ?>" data-tat="<?php echo $agent['tat_license']; ?>" <?php echo $search_agent == $agent['id'] ? 'selected' : ''; ?>><?php echo $agent['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <!-- <button type="submit" class="btn btn-primary" name="submit" value="Submit">Search</button> -->
                                    <button type="button" class="btn btn-primary" onclick="search_invoice();">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <hr class="pb-0 pt-0">
                    <div class="card-datatable pt-0" id="booking-search-table">
                        <?php
                        $total = 0;
                        $first_book = array();
                        $first_btr = array();
                        $first_pay = array();
                        $first_ext = array();
                        $bookings = $invObj->showlistbookings('all', '0000-00-00', '0000-00-00', 'all');
                        # --- Check products --- #
                        if (!empty($bookings)) {
                            foreach ($bookings as $booking) {
                                if (empty($booking['inv_id']) || (!empty($booking['inv_id']) && $booking['inv_deleted'] == 1)) {
                                    # --- get value booking transfer rate !transfer_type == 2! --- #
                                    if ((in_array($booking['btr_id'], $first_btr) == false) && ($booking['transfer_type'] == 2) && !empty($booking['btr_id'])) {
                                        $first_btr[] = $booking['btr_id'];
                                        $array_transfer[$booking['id']]['cars_category'][] = !empty($booking['cars_category']) ? $booking['cars_category'] : 0;
                                        $array_transfer[$booking['id']]['rate_private'][] = !empty($booking['rate_private']) ? $booking['rate_private'] : 0;
                                    }

                                    if (in_array($booking['id'], $first_book) == false) {
                                        $first_book[] = $booking['id'];
                                        # --- get value booking --- #
                                        $bo_id[] = !empty($booking['id']) ? $booking['id'] : 0;
                                        $book_full[] = !empty($booking['book_full']) ? $booking['book_full'] : '';
                                        $discount[] = !empty($booking['discount']) ? $booking['discount'] : 0;
                                        $voucher_no_agent[] = !empty($booking['voucher_no_agent']) ? $booking['voucher_no_agent'] : '';
                                        $book_type_name[] = !empty(!empty($booking['booking_type_id'])) ? $booking['booktye_name'] : '';
                                        $book_status_name[] = !empty(!empty($booking['booksta_name'])) ? $booking['booksta_name'] : 0;
                                        $payment_name[] = !empty(!empty($booking['bookpay_name'])) ? $booking['bookpay_name'] : 0;
                                        $bo_type[] = !empty(!empty($booking['booking_type_id'])) ? $booking['booking_type_id'] : 0;
                                        $status[] = '<span class="badge badge-pill ' . $booking['booksta_class'] . ' text-capitalized"> ' . $booking['booksta_name'] . ' </span>';
                                        # --- get value booking products --- #
                                        $bp_id[] = !empty($booking['bp_id']) ? $booking['bp_id'] : 0;
                                        $product_name[] = !empty($booking['product_name']) ? $booking['product_name'] : 0;
                                        $travel_date[] = !empty($booking['travel_date']) ? $booking['travel_date'] : '0000-00-00';
                                        $adult[] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                                        $child[] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                                        $infant[] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                                        $private_type[] = !empty($booking['bp_private_type']) ? $booking['bp_private_type'] : 0;
                                        # --- get value booking product rate --- #
                                        $bpr_id[] = !empty($booking['bpr_id']) ? $booking['bpr_id'] : 0;
                                        $rate_adult[] = !empty($booking['rate_adult']) ? $booking['rate_adult'] : 0;
                                        $rate_child[] = !empty($booking['rate_child']) ? $booking['rate_child'] : 0;
                                        $rate_infant[] = !empty($booking['rate_infant']) ? $booking['rate_infant'] : 0;
                                        $rate_total[] = !empty($booking['rate_total']) ? $booking['rate_total'] : 0;
                                        # --- get value booking transfer --- #
                                        $bt_id[] = !empty($booking['bt_id']) ? $booking['bt_id'] : 0;
                                        $bt_adult[] = !empty($booking['bt_adult']) ? $booking['bt_adult'] : 0;
                                        $bt_child[] = !empty($booking['bt_child']) ? $booking['bt_child'] : 0;
                                        $bt_infant[] = !empty($booking['bt_infant']) ? $booking['bt_infant'] : 0;
                                        $start_pickup[] = !empty($booking['start_pickup']) ? $booking['start_pickup'] : '00:00:00';
                                        $end_pickup[] = !empty($booking['end_pickup']) ? $booking['end_pickup'] : '00:00:00';
                                        $hotel_pickup[] = !empty($booking['hotel_pickup']) ? $booking['hotel_pickup'] : '';
                                        $hotel_dropoff[] = !empty($booking['hotel_dropoff']) ? $booking['hotel_dropoff'] : '';
                                        $room_no[] = !empty($booking['room_no']) ? $booking['room_no'] : '';
                                        $transfer_type[] = !empty($booking['transfer_type']) ? $booking['transfer_type'] : 0;
                                        $pickup_type[] = !empty($booking['pickup_type']) ? $booking['pickup_type'] : 0;
                                        $pickup_id[] = !empty($booking['pickup_id']) ? $booking['pickup_id'] : 0;
                                        $pickup_name[] = !empty($booking['pickup_name']) ? $booking['pickup_name'] : '';
                                        # --- get value booking transfer rate --- #
                                        $btr_id[] = !empty($booking['btr_id']) ? $booking['btr_id'] : 0;
                                        $btr_rate_adult[] = !empty($booking['btr_rate_adult']) ? $booking['btr_rate_adult'] : 0;
                                        $btr_rate_child[] = !empty($booking['btr_rate_child']) ? $booking['btr_rate_child'] : 0;
                                        $btr_rate_infant[] = !empty($booking['btr_rate_infant']) ? $booking['btr_rate_infant'] : 0;
                                        # --- get value customers --- #
                                        $cus_id[] = !empty($booking['cus_id']) && $booking['cus_head'] == 1 ? $booking['cus_id'] : 0;
                                        $cus_name[] = !empty($booking['cus_name']) && $booking['cus_head'] == 1 ? $booking['cus_name'] : '';
                                        $cus_whatsapp['whatsapp'][] = !empty($booking['whatsapp']) && $booking['cus_head'] == 1 ? $booking['whatsapp'] : '';
                                    }
                                    # --- get value booking extra chang --- #
                                    if ((in_array($booking['bec_id'], $first_ext) == false) && !empty($booking['bec_id'])) {
                                        $first_ext[] = $booking['bec_id'];
                                        $bec_id[$booking['id']][] = !empty($booking['bec_id']) ? $booking['bec_id'] : 0;
                                        $extra_name[$booking['id']][] = !empty($booking['extra_name']) ? $booking['extra_name'] : '';
                                        $bec_name[$booking['id']][] = !empty($booking['bec_name']) ? $booking['bec_name'] : '';
                                        $bec_type[$booking['id']][] = !empty($booking['bec_type']) ? $booking['bec_type'] : 0;
                                        $bec_adult[$booking['id']][] = !empty($booking['bec_adult']) ? $booking['bec_adult'] : 0;
                                        $bec_child[$booking['id']][] = !empty($booking['bec_child']) ? $booking['bec_child'] : 0;
                                        $bec_infant[$booking['id']][] = !empty($booking['bec_infant']) ? $booking['bec_infant'] : 0;
                                        $bec_privates[$booking['id']][] = !empty($booking['bec_privates']) ? $booking['bec_privates'] : 0;
                                        $bec_rate_adult[$booking['id']][] = !empty($booking['bec_rate_adult']) ? $booking['bec_rate_adult'] : 0;
                                        $bec_rate_child[$booking['id']][] = !empty($booking['bec_rate_child']) ? $booking['bec_rate_child'] : 0;
                                        $bec_rate_infant[$booking['id']][] = !empty($booking['bec_rate_infant']) ? $booking['bec_rate_infant'] : 0;
                                        $bec_rate_private[$booking['id']][] = !empty($booking['bec_rate_private']) ? $booking['bec_rate_private'] : 0;
                                    }
                                    # --- get value booking payment --- #
                                    if ((in_array($booking['bopa_id'], $first_pay) == false) && !empty($booking['bopa_id'])) {
                                        $first_pay[] = $booking['bopa_id'];
                                        $bopa_id[$booking['id']][] = !empty($booking['bopa_id']) ? $booking['bopa_id'] : 0;
                                        $bopae_id[$booking['id']][] = !empty($booking['bopae_id']) ? $booking['bopae_id'] : 0;
                                        $bopay_id[$booking['id']] = !empty($booking['bopay_id']) ? $booking['bopay_id'] : 0;
                                        $bopay_name[$booking['id']] = !empty($booking['bopay_name']) ? $booking['bopay_name'] : '';
                                        $bopay_text[$booking['id']] = $booking['bopay_id'] == 4 || $booking['bopay_id'] == 5 ? $booking['bopay_name'] . ' </br>(' . number_format($booking['total_paid']) . ')' : $booking['bopay_name'];
                                        $total_paid[$booking['id']] = !empty($booking['total_paid']) ? $booking['total_paid'] : 0;
                                        $bopay_name_class[$booking['id']] = !empty($booking['bopay_name_class']) ? $booking['bopay_name_class'] : '';
                                    }
                                }
                            }
                        }
                        ?>
                        <table class="booking-list-table table table-responsive">
                            <thead class="thead-light">
                                <tr>
                                    <th class="cell-fit">STATUS</th>
                                    <th class="cell-fit">PAYMENT</th>
                                    <th>BOKING NO.</th>
                                    <th>AGENT VOUCHER NO.</th>
                                    <th>TRAVEL DATE</th>
                                    <th>PROGRAM</th>
                                    <th>Booking Type</th>
                                    <th class="cell-fit">AMOUNT</th>
                                </tr>
                            </thead>
                            <?php if (!empty($bookings)) { ?>
                                <tbody>
                                    <?php for ($i = 0; $i < count($bo_id); $i++) {
                                        if (!empty($bopay_id[$bo_id[$i]]) && ($bopay_id[$bo_id[$i]] == 2 || $bopay_id[$bo_id[$i]] == 4 || $bopay_id[$bo_id[$i]] == 5)) {
                                            # --- get value total --- #
                                            $total = $rate_total[$i];
                                            $total = $transfer_type[$i] == 1 ? $total + ($bt_adult[$i] * $btr_rate_adult[$i]) : $total;
                                            $total = $transfer_type[$i] == 1 ? $total + ($bt_child[$i] * $btr_rate_child[$i]) : $total;
                                            $total = $transfer_type[$i] == 1 ? $total + ($bt_infant[$i] * $btr_rate_infant[$i]) : $total;
                                            $total = $transfer_type[$i] == 2 ? $invObj->sumbtrprivate($bt_id[$i])['sum_rate_private'] + $total : $total;
                                            if (!empty($bec_id[$bo_id[$i]])) {
                                                for ($a = 0; $a < count($bec_id[$bo_id[$i]]); $a++) {
                                                    $total = $bec_type[$bo_id[$i]][$a] == 1 ? $total + ($bec_adult[$bo_id[$i]][$a] * $bec_rate_adult[$bo_id[$i]][$a]) + ($bec_child[$bo_id[$i]][$a] * $bec_rate_child[$bo_id[$i]][$a]) + ($bec_infant[$bo_id[$i]][$a] * $bec_rate_infant[$bo_id[$i]][$a]) : $total;
                                                    $total = $bec_type[$bo_id[$i]][$a] == 2 ? $total + ($bec_privates[$bo_id[$i]][$a] * $bec_rate_private[$bo_id[$i]][$a]) : $total;
                                                }
                                            }
                                            $total = $total - $discount[$i];
                                            $total = ($bopay_id[$bo_id[$i]] == 4 || $bopay_id[$bo_id[$i]] == 5) && (!empty($bopae_id[$bo_id[$i]])) ? $total - $total_paid[$bo_id[$i]] : $total;
                                            $href = 'href="./?pages=booking/edit&id=' . $bo_id[$i] . '&search_travel=' . $today . '&search_agent=all" style="color:#6E6B7B"';
                                    ?>
                                            <tr>
                                                <td><a <?php echo $href; ?>><?php echo $status[$i]; ?></a></td>
                                                <td><a <?php echo $href; ?>><?php echo '<span class="badge badge-pill ' . $bopay_name_class[$bo_id[$i]] . ' text-capitalized"> ' . $bopay_text[$bo_id[$i]] . ' </span>'; ?></a></td>
                                                <td><a <?php echo $href; ?>><?php echo $book_full[$i]; ?></a></td>
                                                <td><a <?php echo $href; ?>><?php echo $voucher_no_agent[$i]; ?></a></td>
                                                <td><a <?php echo $href; ?>><?php echo date("j F Y", strtotime($travel_date[$i])); ?></a></td>
                                                <td><a <?php echo $href; ?>><?php echo $product_name[$i]; ?></a></td>
                                                <td><a <?php echo $href; ?>><?php echo $bo_type[$i] == 1 ? 'Join' : 'Private'; ?></a></td>
                                                <td class="text-right"><a <?php echo $href; ?>><?php echo number_format($total); ?></a></td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                            <?php } ?>
                        </table>
                    </div>
                </div>
                <!-- list section end -->

                <!-- Start Form Modal -->
                <!------------------------------------------------------------------>
                <div class="modal-size-xl d-inline-block">
                    <div class="modal fade text-left" id="modal-add-invoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel16">สร้าง Invoice</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="invoice-form" name="invoice-form" method="post" enctype="multipart/form-data">
                                        <input type="hidden" id="today" name="today" value="<?php echo $today; ?>">
                                        <input type="hidden" id="amount" name="amount" value="">
                                        <div id="div-show"></div>
                                        <div class="row">
                                            <div class="form-group col-md-3 col-12">
                                                <label class="form-label" for="is_approved"></label>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="is_approved" name="is_approved" value="1" checked />
                                                    <label class="custom-control-label" for="is_approved">วางบิลแล้ว</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" hidden>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="inv_no">ใบวางบิล</label>
                                                <input type="hidden" id="inv_no" name="inv_no" value="">
                                                <input type="hidden" id="inv_full" name="inv_full" value="">
                                                <div class="input-group">
                                                    <span id="inv_no_text"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-3 col-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="inv_date">วันที่วางบิล</label></br>
                                                    <input type="text" class="form-control" id="inv_date" name="inv_date" value="" />
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="rec_date">กำหนดครบชำระภายในวันที่</label></br>
                                                    <input type="text" class="form-control" id="rec_date" name="rec_date" value="" onchange="check_diff_date('rec_date')" />
                                                    <p class="text-danger font-weight-bold" id="diff_rec_date"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="content-header">
                                                    <h5>Agent</h5>
                                                </div>
                                                <hr class="mt-0">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label class="form-label" for="agent_name">Company</label>
                                                <div class="input-group">
                                                    <span id="agent_name_text"></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label class="form-label" for="agent_tax">Tax ID.</label>
                                                <div class="input-group">
                                                    <span id="agent_tax_text"></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label class="form-label" for="agent_tel">Tel</label>
                                                <div class="input-group">
                                                    <span id="agent_tel_text"></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label class="form-label" for="agent_address">Address</label>
                                                <div class="input-group">
                                                    <span id="agent_address_text"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="content-header">
                                                    <h5 class="mt-1">เงื่อนไขราคา</h5>
                                                </div>
                                                <hr class="mt-0">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="currency">สกุลเงิน</label>
                                                <div id="div-currency">
                                                    <select class="form-control select2" id="currency" name="currency">
                                                        <option value="">Please choose currency ... </option>
                                                        <?php
                                                        $currencys = $invObj->showcurrency();
                                                        foreach ($currencys as $currency) {
                                                        ?>
                                                            <option value="<?php echo $currency['id']; ?>" data-name="<?php echo $currency['name']; ?>" <?php echo $currency['id'] == 4 ? 'selected' : ''; ?>><?php echo $currency['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="input-group">
                                                    <span id="currency_text"></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="vat">ภาษีมูลค่าเพิ่ม</label>
                                                <div id="div-vat">
                                                    <select class="form-control select2" id="vat" name="vat" onchange="calculator_price();">
                                                        <option value="0">No Vat ... </option>
                                                        <?php
                                                        $vats = $invObj->showvat();
                                                        foreach ($vats as $vat) {
                                                        ?>
                                                            <option value="<?php echo $vat['id']; ?>" data-name="<?php echo $vat['name']; ?>"><?php echo $vat['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="input-group">
                                                    <span id="vat_text"></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <label for="withholding">หัก ณ ที่จ่าย (%)</label>
                                                <div id="div-withholding">
                                                    <input type="text" class="form-control numeral-mask" id="withholding" name="withholding" value="" oninput="calculator_price();" />
                                                </div>
                                                <div class="input-group">
                                                    <span id="withholding"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Start Data Table payment -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="content-header">
                                                    <h5 class="mt-1">การชำระเงิน</h5>
                                                </div>
                                                <hr class="mt-0">
                                            </div>
                                            <div class="form-group col-md-3 col-12">
                                                <div class="form-group">
                                                    <?php $branches = $invObj->showbranch(); ?>
                                                    <label for="branch">สาขา</label>
                                                    <select class="form-control select2" id="branch" name="branch">
                                                        <option value="">Please choose Branch ... </option>
                                                        <?php
                                                        foreach ($branches as $branch) {
                                                        ?>
                                                            <option value="<?php echo $branch['id']; ?>" data-name="<?php echo $branch['name']; ?>" <?php echo $branch['id'] == 1 ? 'selected' : ''; ?>><?php echo $branch['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-12" id="div-bank-account">
                                                <div class="form-group">
                                                    <?php $banks_acc = $invObj->showbankaccount(); ?>
                                                    <label for="bank_account">เข้าบัญชี</label>
                                                    <select class="form-control select2" id="bank_account" name="bank_account">
                                                        <option value="">Please choose bank account ... </option>
                                                        <?php
                                                        foreach ($banks_acc as $bank_acc) {
                                                        ?>
                                                            <option value="<?php echo $bank_acc['id']; ?>" data-name="<?php echo $bank_acc['account_name']; ?>"><?php echo $bank_acc['banName'] . ' ' . $bank_acc['account_no'] . ' (' . $bank_acc['account_name'] . ')'; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="div-only-booking">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td width="34%" align="left" class="default-td" colspan="4">
                                                        <dl class="row" style="margin-bottom: 0;">
                                                            <dt class="col-sm-4 text-right">
                                                                Voucher No.
                                                            </dt>
                                                            <dd class="col-sm-8" id="voucher_no_text"></dd>
                                                        </dl>
                                                        <dl class="row" style="margin-bottom: 0;">
                                                            <dt class="col-sm-4 text-right">
                                                                Booking No.
                                                            </dt>
                                                            <dd class="col-sm-8" id="booking_no_text"></dd>
                                                        </dl>
                                                        <dl class="row" style="margin-bottom: 0;">
                                                            <dt class="col-sm-4 text-right">
                                                                โปรแกรม <br>
                                                                <small>(Programe)</small>
                                                            </dt>
                                                            <dd class="col-sm-8" id="programe_text"></dd>
                                                        </dl>
                                                    </td>
                                                    <td align="left" class="default-td" colspan="2">
                                                        <dl class="row" style="margin-bottom: 0;">
                                                            <dt class="col-sm-4 text-right">
                                                                วันที่เที่ยว <br>
                                                                <small>(Travel Date)</small>
                                                            </dt>
                                                            <dd class="col-sm-8" id="travel_date_text"></dd>
                                                        </dl>
                                                        <dl class="row" style="margin-bottom: 0;">
                                                            <dt class="col-sm-4 text-right">
                                                                ประเภทลูกค้า <br>
                                                                <small>(Guest Type)</small>
                                                            </dt>
                                                            <dd class="col-sm-8" id="guest_type_text"></dd>
                                                        </dl>
                                                        <dl class="row" style="margin-bottom: 0;">
                                                            <dt class="col-sm-4 text-right">
                                                                ชื่อลูค้า <br>
                                                                <small>(Customer Name)</small>
                                                            </dt>
                                                            <dd class="col-sm-8" id="cus_name_text"></dd>
                                                        </dl>
                                                    </td>
                                                    <td width="34%" align="left" class="default-td" colspan="2">
                                                        <dl class="row" style="margin-bottom: 0;">
                                                            <dt class="col-sm-6 text-right">
                                                                สถานที่รับ <br>
                                                                <small>(Pick-Up Hotel)</small>
                                                            </dt>
                                                            <dd class="col-sm-6" id="hotel_text"></dd>
                                                        </dl>
                                                        <dl class="row" style="margin-bottom: 0;">
                                                            <dt class="col-sm-6 text-right">
                                                                หมายเลขห้อง <br>
                                                                <small>(Room No.)</small>
                                                            </dt>
                                                            <dd class="col-sm-6" id="room_text"></dd>
                                                        </dl>
                                                        <dl class="row" style="margin-bottom: 0;">
                                                            <dt class="col-sm-6 text-right">
                                                                เวลารับ <br>
                                                                <p style="font-size: 10px; margin-bottom: 0;">(Pick-Up Time)</small>
                                                            </dt>
                                                            <dd class="col-sm-6" id="pickup_time_text"></dd>
                                                        </dl>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table class="table table-bordered">
                                                <thead class="bg-warning bg-darken-2 text-white">
                                                    <tr>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 15px 0px 0px 0px; padding: 5px 0;" width="10%"><b>เลขที่</b><br>
                                                            <small>No.</small>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="40%"><b>รายการ</b><br>
                                                            <small>Description</small>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="15%"><b>จํานวน</b><br>
                                                            <small>Quantity.</small>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="15%"><b>ราคา/หน่วย</b><br>
                                                            <small>Unit Price.</small>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 0px 15px 0px 0px;" width="20%"><b>จํานวนเงิน</b><br>
                                                            <small>Amount</small>
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody-only-booking">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row" id="div-multi-booking">
                                            <table class="table table-bordered">
                                                <thead class="bg-warning bg-darken-2 text-white">
                                                    <tr>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 15px 0px 0px 0px; padding: 5px 0;" width="3%"><b>เลขที่</b><br>
                                                            <small>No.</small>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="10%"><b>Voucher No.</b>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="10%"><b>Booking No.</b>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="17%"><b>ชื่อลูค้า</b><br>
                                                            <small>Customer's Name</small>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="17%"><b>โปรแกรม</b><br>
                                                            <small>Programe</small>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="15%"><b>วันที่เที่ยว</b><br>
                                                            <small>Travel Date</small>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff;" width="10%"><b>Payment</b>
                                                        </td>
                                                        <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 0px 15px 0px 0px;" width="18%"><b>จํานวนเงิน</b><br>
                                                            <small>Total</small>
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody-multi-booking">

                                                </tbody>
                                            </table>
                                        </div>
                                        <input type="hidden" id="price_total" name="price_total" value="">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="note">Note</label>
                                                    <textarea class="form-control" name="note" id="note" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span></span>
                                            <button type="submit" class="btn btn-primary" id="btn-submit-inv" name="submit" value="Submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------------------------------------------------------------------>
                    <!-- End Form Modal -->

                </div>
            </section>
            <!-- cars type list ends -->
        </div>
    </div>
</div>