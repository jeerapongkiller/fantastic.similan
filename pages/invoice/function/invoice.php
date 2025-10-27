<?php
include_once('../../../config/env.php');
include_once('../../../controllers/Invoice.php');

$invObj = new Invoice();
$response = true;
$today = date("Y-m-d");
$times = date("H:i:s");

if (isset($_POST['action']) && $_POST['action'] == "div") {
    // get value from ajax
    $cover_id = !empty($_POST['cover_id']) ? $_POST['cover_id'] : 0;
    $bo_id = !empty($_POST['bo_id']) ? json_decode($_POST['bo_id']) : [];
    $agent = $invObj->get_value('companies.name as name, companies.tat_license as license, companies.telephone as telephone, companies.address as address', 'bookings LEFT JOIN companies ON bookings.company_id = companies.id', 'bookings.id = ' . $bo_id[0], 0);

    echo 'cover_id : ' . $cover_id;
    echo '</br> bo_id : </br>';
    print_r($_POST['bo_id']);

    // if ($cover_id > 0) {
    //     $invoices = $invObj->showlistbooking(0, $cover_id);
    //     $agent = $invObj->get_value('companies.name as name, companies.tat_license as license, companies.telephone as telephone, companies.address as address', 'bookings LEFT JOIN companies ON bookings.company_id = companies.id', 'bookings.id = ' . $invoices[0]['bo_id'], 0);
    //     foreach ($invoices as $invoice) {
    //         $bo_id[] = $invoice['bo_id'];
    //     }
    // } else {

    // }
?>
    <input type="hidden" id="action" name="action" value="<?php echo ($cover_id > 0) ? 'edit' : 'create'; ?>">
    <input type="hidden" id="input_today" name="today" value="<?php echo $today; ?>">
    <input type="hidden" id="amount" name="amount" value="">
    <input type="hidden" id="cover_id" name="cover_id" value="<?php echo $cover_id; ?>">
    <div class="row">
        <div class="form-group col-md-3 col-12">
            <label class="form-label" for="is_approved"></label>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="is_approved" name="is_approved" value="1" checked />
                <label class="custom-control-label" for="is_approved">วางบิลแล้ว</label>
            </div>
        </div>
    </div>
    <div class="row" <?php echo ($cover_id == 0) ? 'hidden' : ''; ?>>
        <div class="form-group col-md-3 col-12">
            <label for="inv_no">ใบวางบิล</label>
            <input type="hidden" id="inv_no" name="inv_no" value="">
            <input type="hidden" id="inv_full" name="inv_full" value="">
            <div class="input-group">
                <span id="inv_no_text"><?php echo $invoices[0]['inv_full']; ?></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3 col-12">
            <div class="form-group">
                <label class="form-label" for="inv_date">วันที่วางบิล</label></br>
                <input type="text" class="form-control" id="inv_date" name="inv_date" value="<?php echo (!empty($invoices[0]['inv_date']) && $invoices[0]['inv_date'] != '0000-00-00') ? $invoices[0]['inv_date'] : $today; ?>" />
            </div>
        </div>
        <div class="form-group col-md-3 col-12">
            <div class="form-group">
                <label class="form-label" for="rec_date">กำหนดครบชำระภายในวันที่</label></br>
                <input type="text" class="form-control" id="rec_date" name="rec_date" value="<?php echo (!empty($invoices[0]['rec_date']) && $invoices[0]['rec_date'] != '0000-00-00') ? $invoices[0]['rec_date'] : $today; ?>" onchange="check_diff_date('rec_date')" />
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
                <span><?php echo $agent['name']; ?></span>
            </div>
        </div>
        <div class="form-group col-md-3 col-12">
            <label class="form-label" for="agent_tax">Tax ID.</label>
            <div class="input-group">
                <span><?php echo $agent['license']; ?></span>
            </div>
        </div>
        <div class="form-group col-md-3 col-12">
            <label class="form-label" for="agent_tel">Tel</label>
            <div class="input-group">
                <span><?php echo $agent['telephone']; ?></span>
            </div>
        </div>
        <div class="form-group col-md-3 col-12">
            <label class="form-label" for="agent_address">Address</label>
            <div class="input-group">
                <span><?php echo $agent['address']; ?></span>
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
                        // $select = $currency['id'] == $invoices[0]['rec_date'] ? 'selected' : '';
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
                        $select = $vat['id'] == $invoices[0]['vat_id'] ? 'selected' : '';
                    ?>
                        <option value="<?php echo $vat['id']; ?>" data-name="<?php echo $vat['name']; ?>" <?php echo $select; ?>><?php echo $vat['name']; ?></option>
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
                <input type="text" class="form-control numeral-mask" id="withholding" name="withholding" value="<?php echo !empty($invoices[0]['withholding']) ? $invoices[0]['withholding'] : 0; ?>" oninput="calculator_price();" />
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
                        $select = $branch['id'] == $invoices[0]['branche_id'] ? 'selected' : '';
                    ?>
                        <option value="<?php echo $branch['id']; ?>" data-name="<?php echo $branch['name']; ?>" <?php echo $select; ?>><?php echo $branch['name']; ?></option>
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
                        $select = $bank_acc['id'] == $invoices[0]['bank_account_id'] ? 'selected' : '';
                    ?>
                        <option value="<?php echo $bank_acc['id']; ?>" data-name="<?php echo $bank_acc['account_name']; ?>" <?php echo $select; ?>><?php echo $bank_acc['banName'] . ' ' . $bank_acc['account_no'] . ' (' . $bank_acc['account_name'] . ')'; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <table class="table table-bordered">
            <thead class="bg-darken-2 text-white">
                <tr class="table-black">
                    <td class="text-center" style="border-radius: 15px 0px 0px 0px; padding: 5px 0;" width="3%"><b>เลขที่</b></td>
                    <td class="text-center"><b>วันที่เดินทาง</b></td>
                    <td class="text-center"><b>ชื่อลูกค้า</b></td>
                    <td class="text-center"><b>โปรแกรม</b></td>
                    <td class="text-center"><b>หมายเลข</b></td>
                    <td class="text-center" colspan="2"><b>จํานวน</b></td>
                    <td class="text-center" colspan="2"><b>ราคาต่อหน่วย</b></td>
                    <td class="text-center"><b>ส่วนลด</b></td>
                    <td class="text-center"><b>จำนวนเงิน</b></td>
                    <td class="text-center" style="border-radius: 0px 15px 0px 0px;"><b>Cash on tour</b></td>
                </tr>
                <tr class="table-black-2">
                    <td class="text-center p-50"><small>Items</small></td>
                    <td class="text-center p-50"><small>Date</small></td>
                    <td class="text-center p-50"><small>Customer's name</small></td>
                    <td class="text-center p-50"><small>Programe</small></td>
                    <td class="text-center p-50"><small>Voucher No.</small></td>
                    <td class="text-center p-50"><small>Adult/Rider</small></td>
                    <td class="text-center p-50"><small>Child/Paasenger</small></td>
                    <td class="text-center p-50"><small>Adult/Rider</small></td>
                    <td class="text-center p-50"><small>Child/Paasenger</small></td>
                    <td class="text-center p-50"><small>Discount</small></td>
                    <td class="text-center p-50"><small>Amount</small></td>
                    <td class="text-center p-50"><small>COT</small></td>
                </tr>
            </thead>
            <?php if (!empty($bo_id)) { ?>
                <tbody>
                    <?php
                    for ($i = 0; $i < count($bo_id); $i++) {
                        $bookings = $invObj->fetch_assoc_booking($bo_id[$i]);

                        $adult = 0;
                        $child = 0;
                        $rates_adult = 0;
                        $rates_child = 0;
                        $rates_private = 0;
                        $amount = 0;
                        $all_rates = $invObj->get_value('*', ' booking_product_rates', 'booking_products_id = ' . $bookings['bp_id'], 1);
                        foreach ($all_rates as $rates) {
                            $adult += $rates['adult'];
                            $child += $rates['child'];
                            if ($bookings['booking_type_id'] == 1) {
                                $rates_adult += $rates['rates_adult'];
                                $rates_child += $rates['rates_child'];
                                $amount += $rates['adult'] * $rates['rates_adult'];
                                $amount += $rates['child'] * $rates['rates_child'];
                            } elseif ($bookings['booking_type_id'] == 2) {
                                $rates_private += $rates['rates_private'];
                                $amount += $rates['rates_private'];
                            }
                        }

                        // $e = 0;
                        // $extra = '';
                        // $extra_charges = $invObj->get_extra_charge($bookings['id']);
                        // if (!empty($extra_charges)) {
                        //     foreach ($extra_charges as $extra_charge) {
                        //         if ($extra_charge['type'] == 1) {
                        //             $amount += $extra_charge['adult'] * $extra_charge['rate_adult'];
                        //             $amount += $extra_charge['child'] * $extra_charge['rate_child'];
                        //             $amount += $extra_charge['infant'] * $extra_charge['rate_infant'];
                        //         } elseif ($extra_charge['type'] == 2) {
                        //             $amount += $extra_charge['privates'] * $extra_charge['rate_private'];
                        //         }
                        //         $extra = $e == 0 ? $extra_charge['extra_name'] : ' : ' . $extra_charge['extra_name'];
                        //         $e++;
                        //     }
                        // }
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $i + 1 . ' | ' . $bookings['id'] ?></td>
                            <td class="text-center"><?php echo date('j F Y', strtotime($bookings['travel_date'])); ?></td>
                            <td><?php echo $bookings['cus_name']; ?></td>
                            <td><?php echo $bookings['product_name']; ?></td>
                            <td class="text-center"><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['bo_full']; ?> </td>
                            <td class="text-center"><?php echo $adult; ?></td>
                            <td class="text-center"><?php echo $child; ?></td>
                            <td class="text-center"><?php echo number_format($rates_adult, 2); ?></td>
                            <td class="text-center"><?php echo number_format($rates_child, 2); ?></td>
                            <td class="text-center"><?php echo number_format($bookings['discount'], 2); ?></td>
                            <td class="text-center"><?php echo number_format($amount, 2); ?></td>
                            <td class="text-center"><?php echo number_format($bookings['cot'], 2); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tbody>
                    <?php
                    $no = 1;
                    $discount = 0;
                    $cot = 0;
                    $total = 0;
                    $in_booking = array();
                    $in_charge = array();
                    for ($i = 0; $i < count($bo_id); $i++) {
                        $bookings = $invObj->showlistbooking($bo_id[$i], 0);
                        if (!empty($bookings)) {
                            foreach ($bookings as $booking) {
                                if (in_array($booking['id'], $in_booking) == false) {
                                    $in_booking[] = $booking['id'];
                                    $discount = $discount + $booking['discount'];
                                    $cot = $cot + $booking['cot'];
                                    $total = $booking['bp_private_type'] == 1 ? $total + ($booking['adult'] * $booking['rate_adult']) + ($booking['child'] * $booking['rate_child']) : $total + $booking['rate_total'];
                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no++; ?></td>
                                        <td class="text-center"><?php echo date('j F Y', strtotime($booking['travel_date'])); ?></td>
                                        <td><?php echo $booking['cus_name']; ?></td>
                                        <td><?php echo $booking['product_name']; ?></td>
                                        <td class="text-center"><?php echo !empty($booking['voucher_no_agent']) ? $booking['voucher_no_agent'] : $booking['book_full']; ?> </td>
                                        <td class="text-center"><?php echo $booking['adult']; ?></td>
                                        <td class="text-center"><?php echo $booking['child']; ?></td>
                                        <td class="text-center"><?php echo number_format($booking['rate_adult'], 2); ?></td>
                                        <td class="text-center"><?php echo number_format($booking['rate_child'], 2); ?></td>
                                        <td class="text-center"><?php echo number_format($booking['discount'], 2); ?></td>
                                        <td class="text-center"><?php echo number_format($booking['bp_private_type'] == 1 ? ($booking['adult'] * $booking['rate_adult']) + ($booking['child'] * $booking['rate_child']) : $booking['rate_total'], 2); ?></td>
                                        <td class="text-center"><?php echo number_format($booking['cot'], 2); ?></td>
                                    </tr>
                                    <?php
                                }
                                if (in_array($booking['bec_id'], $in_charge) == false) {
                                    if (!empty($booking['extra_name']) || !empty($booking['bec_name'])) {
                                        $in_charge[] = $booking['bec_id'];
                                        $total = $booking['bec_type'] == 1 ? $total + ($booking['bec_adult'] * $booking['bec_rate_adult']) + ($booking['bec_child'] * $booking['bec_rate_child']) + ($booking['bec_infant'] * $booking['bec_rate_infant']) : $total + ($booking['bec_privates'] * $booking['bec_rate_private']);
                                    ?>
                                        <tr>
                                            <td class="text-left" colspan="5"><?php echo !empty($booking['extra_name']) ? $booking['extra_name'] : $booking['bec_name']; ?></td>
                                            <td class="text-center"><?php echo $booking['bec_type'] == 1 ? $booking['bec_adult'] : $booking['bec_privates']; ?></td>
                                            <td class="text-center"><?php echo $booking['bec_type'] == 1 ? $booking['bec_child'] : ''; ?></td>
                                            <td class="text-center"><?php echo $booking['bec_type'] == 1 ? number_format($booking['bec_rate_adult'], 2) : ''; ?></td>
                                            <td class="text-center"><?php echo $booking['bec_type'] == 1 ? number_format($booking['bec_rate_child'], 2) : ''; ?></td>
                                            <td class="text-center">-</td>
                                            <td class="text-center"><?php echo number_format($booking['bec_type'] == 1 ? ($booking['bec_adult'] * $booking['bec_rate_adult']) + ($booking['bec_child'] * $booking['bec_rate_child']) + ($booking['bec_infant'] * $booking['bec_rate_infant']) : ($booking['bec_privates'] * $booking['bec_rate_private'])); ?></td>
                                            <td class="text-center">-</td>
                                        </tr>
                    <?php   }
                                }
                            }
                        }
                    }
                    ?>

                    <tr>
                        <td colspan="10"></td>
                        <td class="text-center"><b>รวมเป็นเงิน</b><br><small>(Total)</small></td>
                        <td class="text-center" id="price-total"><?php echo number_format($total, 2); ?></td>
                    </tr>

                    <?php if ($discount > 0) { ?>
                        <tr>
                            <td colspan="10"></td>
                            <td class="text-center"><b>ส่วนลด</b><br><small>(Discount)</small></td>
                            <td class="text-center"><?php echo number_format($discount, 2); ?></td>
                        </tr>
                    <?php } ?>

                    <?php if ($cot > 0) { ?>
                        <tr>
                            <td colspan="10"></td>
                            <td class="text-center"><b>Cash on tour</b></td>
                            <td class="text-center"><?php echo number_format($cot, 2); ?></td>
                        </tr>
                    <?php } ?>

                    <tr id="tr-vat" hidden>
                        <td colspan="10"></td>
                        <td class="text-center"><b id="vat-text"></b><br><small>(Vat)</small></td>
                        <td class="text-center" id="price-vat"></td>
                    </tr>

                    <tr id="tr-withholding" hidden>
                        <td colspan="10"></td>
                        <td class="text-center"><b id="withholding-text"></b><br><small>(Withholding Tax)</small></td>
                        <td class="text-center" id="price-withholding"></td>
                    </tr>

                    <tr>
                        <td colspan="10"></td>
                        <td class="text-center"><b>ยอดชำระ</b><br><small>(Payment Amount)</small></td>
                        <td class="text-center" id="price-amount"><?php echo number_format($total, 2); ?></td>
                    </tr>
                </tbody>
            <?php } ?>
        </table>
    </div>
    <input type="hidden" id="discount" name="discount" value="<?php echo $discount; ?>">
    <input type="hidden" id="cot" name="cot" value="<?php echo $cot; ?>">
    <input type="hidden" id="price_total" name="price_total" value="<?php echo $total; ?>">
    <div class="row">
        <div class="form-group col-md-12">
            <div class="form-group">
                <label class="form-label" for="note">Note</label>
                <textarea class="form-control" name="note" id="note" rows="3"><?php echo !empty($invoices[0]['note']) ? $invoices[0]['note'] : ''; ?></textarea>
            </div>
        </div>
    </div>
<?php
} else {
    echo $response = false;
}
