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
    $agent = $invObj->get_value(
        'companies.name as name, companies.tat_license as license, companies.telephone as telephone, companies.address as address',
        'bookings LEFT JOIN companies ON bookings.company_id = companies.id',
        'bookings.id = ' . $bo_id[0],
        0
    );
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
                    <td class="text-center"><b>ลูกค้า</b></td>
                    <td class="text-center" colspan="2"><b>จํานวน</b></td>
                    <td class="text-center" colspan="2"><b>ราคาต่อหน่วย</b></td>
                    <td class="text-center"><b>จำนวนเงิน</b></td>
                    <td class="text-center"><b>ส่วนลด</b></td>
                    <td class="text-center" style="border-radius: 0px 15px 0px 0px;"><b>Cash on tour</b></td>
                </tr>
                <tr class="table-black-2">
                    <td class="text-center p-50"><small>Items</small></td>
                    <td class="text-center p-50"><small>Date</small></td>
                    <td class="text-center p-50"><small>Customer's name</small></td>
                    <td class="text-center p-50"><small>Programe</small></td>
                    <td class="text-center p-50"><small>Voucher No.</small></td>
                    <td class="text-center p-50"><small>Category</small></td>
                    <td class="text-center p-50"><small>Adult</small></td>
                    <td class="text-center p-50"><small>Child</small></td>
                    <td class="text-center p-50"><small>Adult</small></td>
                    <td class="text-center p-50"><small>Child</small></td>
                    <td class="text-center p-50"><small>Amount</small></td>
                    <td class="text-center p-50"><small>Discount</small></td>
                    <td class="text-center p-50"><small>COT</small></td>
                </tr>
            </thead>
            <?php if (!empty($bo_id)) { ?>
                <tbody>
                    <?php
                    $discount = 0;
                    $cot = 0;
                    $amount = 0;
                    for ($i = 0; $i < count($bo_id); $i++) {
                        $all_bookings = $invObj->fetch_all_bookingdetail($bo_id[$i]);
                        $bo_arr = array();
                        $rates_arr = array();
                        foreach ($all_bookings as $bookings) {
                            if (in_array($bookings['bpr_id'], $rates_arr) == false) {
                                $rates_arr[] = $bookings['bpr_id'];
                                $rates_adult = 0;
                                if ($bookings['booking_type_id'] == 1) {
                                    $rates_adult += $bookings['adult'] * $bookings['rates_adult'];
                                    $rates_adult += $bookings['child'] * $bookings['rates_child'];
                                } elseif ($bookings['booking_type_id'] == 2) {
                                    $rates_adult += $bookings['rates_private'];
                                }
                                $amount += $rates_adult;
                                if (in_array($bookings['id'], $bo_arr) == false) {
                                    $bo_arr[] = $bookings['id'];
                                    $discount += $bookings['discount'];
                                    $cot += $bookings['cot'];
                                    $check_bpr = $invObj->get_value('id', 'booking_product_rates', 'booking_products_id = ' . $bookings['bp_id'], 1);
                    ?>
                                    <tr>
                                        <td class="text-center" rowspan="<?php echo count($check_bpr); ?>"><?php echo $i + 1; ?></td>
                                        <td class="text-center" rowspan="<?php echo count($check_bpr); ?>"><?php echo date('j F Y', strtotime($bookings['travel_date'])); ?></td>
                                        <td rowspan="<?php echo count($check_bpr); ?>"><?php echo $bookings['cus_name']; ?></td>
                                        <td rowspan="<?php echo count($check_bpr); ?>"><?php echo $bookings['product_name']; ?></td>
                                        <td class="text-center" rowspan="<?php echo count($check_bpr); ?>"><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['bo_full']; ?> </td>

                                        <td class="text-center cell-fit"><?php echo $bookings['category_name']; ?></td>
                                        <td class="text-center cell-fit"><?php echo $bookings['adult']; ?></td>
                                        <td class="text-center cell-fit"><?php echo $bookings['child']; ?></td>
                                        <td class="text-center cell-fit"><?php echo number_format($bookings['rates_adult'], 2); ?></td>
                                        <td class="text-center cell-fit"><?php echo number_format($bookings['rates_child'], 2); ?></td>
                                        <td class="text-center cell-fit"><?php echo number_format($rates_adult, 2); ?></td>

                                        <td class="text-center cell-fit" rowspan="<?php echo count($check_bpr); ?>"><?php echo !empty($bookings['discount']) ? number_format($bookings['discount'], 2) : '-'; ?></td>
                                        <td class="text-center cell-fit" rowspan="<?php echo count($check_bpr); ?>"><?php echo !empty($bookings['cot']) > 0 ?  number_format($bookings['cot'], 2) : '-'; ?></td>
                                    </tr>
                                <?php } else { ?>
                                    <tr>
                                        <td class="text-center cell-fit"><?php echo $bookings['category_name']; ?></td>
                                        <td class="text-center cell-fit"><?php echo $bookings['adult']; ?></td>
                                        <td class="text-center cell-fit"><?php echo $bookings['child']; ?></td>
                                        <td class="text-center cell-fit"><?php echo number_format($bookings['rates_adult'], 2); ?></td>
                                        <td class="text-center cell-fit"><?php echo number_format($bookings['rates_child'], 2); ?></td>
                                        <td class="text-center cell-fit"><?php echo number_format($rates_adult, 2); ?></td>
                                    </tr>
                        <?php }
                            }
                        } ?>

                        <?php
                        $e = 0;
                        $amount_extar = 0;
                        $extra_charges = $invObj->get_extra_charge($bo_id[$i]);
                        if (!empty($extra_charges)) {
                            foreach ($extra_charges as $extra_charge) {
                                if ($extra_charge['type'] == 1) {
                                    $amount_extar += $extra_charge['adult'] * $extra_charge['rate_adult'];
                                    $amount_extar += $extra_charge['child'] * $extra_charge['rate_child'];
                                    $amount_extar += $extra_charge['infant'] * $extra_charge['rate_infant'];
                                } elseif ($extra_charge['type'] == 2) {
                                    $amount_extar += $extra_charge['privates'] * $extra_charge['rate_private'];
                                }
                                $amount += $amount_extar;
                                $e++;
                        ?>
                                <tr>
                                    <td class="text-left" colspan="6"><?php echo !empty($extra_charge['extra_name']) ? $extra_charge['extra_name'] : $extra_charge['name']; ?></td>
                                    <td class="text-center"><?php echo $extra_charge['type'] == 1 ? $extra_charge['adult'] : $extra_charge['privates']; ?></td>
                                    <td class="text-center"><?php echo $extra_charge['type'] == 1 ? $extra_charge['child'] : ''; ?></td>
                                    <td class="text-center"><?php echo $extra_charge['type'] == 1 ? number_format($extra_charge['rate_adult'], 2) : ''; ?></td>
                                    <td class="text-center"><?php echo $extra_charge['type'] == 1 ? number_format($extra_charge['rate_child'], 2) : ''; ?></td>
                                    <td class="text-center">-</td>
                                    <td class="text-center"><?php echo number_format($amount_extar, 2); ?></td>
                                    <td class="text-center">-</td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    <?php } ?>

                    <tr>
                        <td colspan="9"></td>
                        <td class="text-center" colspan="2"><b>รวมเป็นเงิน</b><br><small>(Total)</small></td>
                        <td class="text-center" colspan="2" id="price-total"><?php echo number_format($amount, 2); ?></td>
                    </tr>

                    <?php if ($discount > 0) { ?>
                        <tr>
                            <td colspan="9"></td>
                            <td class="text-center" colspan="2"><b>ส่วนลด</b><br><small>(Discount)</small></td>
                            <td class="text-center" colspan="2"><?php echo number_format($discount, 2); ?></td>
                        </tr>
                    <?php } ?>

                    <?php if ($cot > 0) { ?>
                        <tr>
                            <td colspan="9"></td>
                            <td class="text-center" colspan="2"><b>Cash on tour</b></td>
                            <td class="text-center" colspan="2"><?php echo number_format($cot, 2); ?></td>
                        </tr>
                    <?php } ?>

                    <tr id="tr-vat" hidden>
                        <td colspan="9"></td>
                        <td class="text-center" colspan="2"><b id="vat-text"></b><br><small>(Vat)</small></td>
                        <td class="text-center" colspan="2" id="price-vat"></td>
                    </tr>

                    <tr id="tr-withholding" hidden>
                        <td colspan="9"></td>
                        <td class="text-center" colspan="2"><b id="withholding-text"></b><br><small>(Withholding Tax)</small></td>
                        <td class="text-center" colspan="2" id="price-withholding"></td>
                    </tr>

                    <tr>
                        <td colspan="9"></td>
                        <td class="text-center" colspan="2"><b>ยอดชำระ</b><br><small>(Payment Amount)</small></td>
                        <td class="text-center" colspan="2" id="price-amount"><?php echo number_format($amount, 2); ?></td>
                    </tr>
                </tbody>
            <?php } ?>
        </table>
    </div>
    <input type="text" id="discount" name="discount" value="<?php echo $discount; ?>">
    <input type="text" id="cot" name="cot" value="<?php echo $cot; ?>">
    <input type="text" id="price_total" name="price_total" value="<?php echo $amount; ?>">
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
