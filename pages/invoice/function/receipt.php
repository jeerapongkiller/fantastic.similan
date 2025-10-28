<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Invoice.php';

$invObj = new Invoice();
$today = date("Y-m-d");
$times = date("H:i:s");

function diff_date($today, $diff_date)
{
    $diff_inv = array();
    $date1 = date_create($today);
    $date2 = date_create($diff_date);
    $diff = date_diff($date1, $date2);
    $diff_inv['day'] =  $diff->format("%R%a");
    $diff_inv['num'] =  $diff->format("%a");

    return $diff_inv;
}

if (isset($_POST['action']) && $_POST['action'] == "modal" && !empty($_POST['cover_id'])) {
    $cover_id = $_POST['cover_id'];
    $agent_id = !empty($_POST['agent_id']) ? $_POST['agent_id'] : 0;

    $all_invocies = $invObj->fetch_all_invocie($inv_date = '', $inv_no = '', $cover_id);
    foreach ($all_invocies as $invocies) {
        $bo_id[] = $invocies['bo_id'];
    }
    $agent = $invObj->get_value('name, tat_license, telephone, address', 'companies ', 'id = ' . $agent_id, 0);

    if ($cover_id > 0) {
        $receipts = $invObj->get_value('*', 'receipts', ' cover_id = ' . $cover_id, 0);
    }
?>
    <input type="hidden" id="rec_id" name="rec_id" value="<?php echo !empty($receipts['id']) ? $receipts['id'] : 0; ?>">
    <input type="hidden" id="cover_id" name="cover_id" value="<?php echo $cover_id; ?>">
    <input type="hidden" id="today" name="today" value="<?php echo $today; ?>">
    <div class="row">
        <div class="form-group col-md-3 col-12">
            <label class="form-label" for="is_approved"></label>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="is_approved" name="is_approved" value="1" checked />
                <label class="custom-control-label" for="is_approved">ชำระเงินแล้ว</label>
            </div>
        </div>
    </div>
    <div class="row" <?php echo empty($receipts['id']) ? 'hidden' : ''; ?>>
        <div class="form-group col-md-3 col-12">
            <label for="rec_no">ใบเสร็จรับเงิน</label>
            <div class="input-group">
                <span><?php echo $receipts['rec_full']; ?></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3 col-12">
            <div class="form-group">
                <label class="form-label" for="rec_date_2">วันที่ชำระ</label></br>
                <input type="text" class="form-control" id="rec_date_2" name="rec_date" value="<?php echo !empty($receipts['rec_date']) ? $receipts['rec_date'] : $today; ?>" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="content-header">
                <h5 class="mt-1">การชำระเงิน</h5>
            </div>
            <hr class="mt-0">
        </div>
        <div class="form-group col-md-3 col-12">
            <div class="form-group">
                <?php $payments = $invObj->showpayments(2); ?>
                <label for="payments_type">รูปแบบการชำระเงิน</label>
                <select class="form-control select2" id="payments_type" name="payments_type" onchange="check_payment();">
                    <option value="">Please choose payments type ... </option>
                    <?php
                    foreach ($payments as $payment) {
                        $select = $payment['id'] == $receipts['payment_id'] ? 'selected' : '';
                    ?>
                        <option value="<?php echo $payment['id']; ?>" data-name="<?php echo $payment['name']; ?>" <?php echo $select; ?>><?php echo $payment['name']; ?></option>
                    <?php
                    } ?>
                </select>
            </div>
        </div>
        <div class="form-group col-md-3 col-12" id="div-bank-account-2" hidden>
            <div class="form-group">
                <?php $banks_acc = $invObj->showbankaccount(); ?>
                <label for="bank_account">เข้าบัญชี</label>
                <select class="form-control select2" id="bank_account" name="bank_account">
                    <option value="">Please choose bank account ... </option>
                    <?php
                    foreach ($banks_acc as $bank_acc) {
                        $select = $bank_acc['id'] == $receipts['bank_account_id'] ? 'selected' : '';
                    ?>
                        <option value="<?php echo $bank_acc['id']; ?>" data-name="<?php echo $bank_acc['account_name']; ?>" <?php echo $select; ?>><?php echo $bank_acc['banName'] . ' ' . $bank_acc['account_no'] . ' (' . $bank_acc['account_name'] . ')'; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group col-md-3 col-12" id="div-bank" hidden>
            <div class="form-group">
                <?php $banks = $invObj->showbank(); ?>
                <label for="rec_bank">ธนาคาร</label>
                <select class="form-control select2" id="rec_bank" name="rec_bank">
                    <option value="">Please choose bank ... </option>
                    <?php
                    foreach ($banks as $bank) {
                        $select = $bank['id'] == $receipts['bank_cheque_id'] ? 'selected' : '';
                    ?>
                        <option value="<?php echo $bank['id']; ?>" data-name="<?php echo $bank['name']; ?>" <?php echo $select; ?>><?php echo $bank['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-group col-md-3 col-12" id="div-check-no" hidden>
            <div class="form-group">
                <label class="form-label" for="check_no">เลขที่เช็ค</label>
                <input type="text" id="check_no" name="check_no" class="form-control" value="<?php echo !empty($receipts['cheque_no']) ? $receipts['cheque_no'] : ''; ?>" />
            </div>
        </div>
        <div class="form-group col-md-3 col-12" id="div-check-date" hidden>
            <div class="form-group">
                <label class="form-label" for="date_check">วันที่เช็ค</label></br>
                <input type="text" class="form-control" id="date_check" name="date_check" value="<?php echo !empty($receipts['cheque_date']) && $receipts['cheque_date'] != '0000-00-00' ? $receipts['cheque_date'] : $today; ?>" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3 col-12">
            <span <?php echo empty($receipts['file']) ? 'hidden' : ''; ?>>
                <div class="form-group mt-1">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="delete_file" name="delete_file" value="1" />
                        <label class="custom-control-label" for="delete_file">Delete</label>
                    </div>
                </div>
                <input type="hidden" id="before_file" name="before_file" class="form-control" value="<?php echo !empty($receipts['file']) ? $receipts['file'] : ''; ?>" />
                <div class="d-flex align-items-center justify-content-center mt-2 mb-2">
                    <img id="img-file" src="storage/uploads/receipt/<?php echo $receipts['file']; ?>" class="img-fluid product-img" alt="image" width="250" />
                </div>
            </span>
            <label for="file">แนบรูปภาพ</label>
            <input type="file" class="form-control" id="file" name="file[]" />
        </div>
    </div>
    <div class="row">
        <table class="table table-bordered">
            <tr class="table-content">
                <td width="50%" align="left" colspan="4">
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-4 text-right">
                            Company
                        </dt>
                        <dd class="col-sm-8"><?php echo $agent['name']; ?></dd>
                    </dl>
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-4 text-right">
                            Address
                        </dt>
                        <dd class="col-sm-8"><?php echo $agent['address']; ?></dd>
                    </dl>
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-4 text-right">
                            Tel
                        </dt>
                        <dd class="col-sm-8"><?php echo $agent['telephone']; ?></dd>
                    </dl>
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-4 text-right">
                            Tax ID.
                        </dt>
                        <dd class="col-sm-8"><?php echo $agent['tat_license']; ?></dd>
                    </dl>
                </td>
                <td width="50%" align="left" colspan="2">
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-6 text-right">
                            Invoice No.
                        </dt>
                        <dd class="col-sm-6"><?php echo $all_invocies[0]['inv_full']; ?></dd>
                    </dl>
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-6 text-right">
                            สำนักงาน
                        </dt>
                        <dd class="col-sm-6"><?php echo $all_invocies[0]['brch_name']; ?></dd>
                    </dl>
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-6 text-right">
                            Departure Date
                        </dt>
                        <dd class="col-sm-6"><?php echo date("j F Y", strtotime($all_invocies[0]['inv_date'])); ?></dd>
                    </dl>
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-6 text-right">
                            Due Date
                        </dt>
                        <dd class="col-sm-6"><?php echo date("j F Y", strtotime($all_invocies[0]['rec_date'])); ?></dd>
                    </dl>
                    <dl class="row" style="margin-bottom: 0;">
                        <div class="col-12 text-center">
                            <?php echo (empty($receipts)) ? (diff_date($today, $all_invocies[0]['rec_date'])['day'] > 0) ? '<span class="badge badge-pill badge-light-success text-capitalized">วันที่ครบกำหนดชำระ : ' . date("j F Y", strtotime($all_invocies[0]['rec_date'])) . ' (ครบกำหนดชำระในอีก ' . diff_date($today, $all_invocies[0]['rec_date'])['num'] . ' วัน)</span>' : '<span class="badge badge-pill badge-light-danger text-capitalized">วันที่ครบกำหนดชำระ : ' . date("j F Y", strtotime($all_invocies[0]['rec_date'])) . ' (เกินกำหนดชำระมาแล้ว ' . diff_date($today, $all_invocies[0]['rec_date'])['num'] . ' วัน)</span>' : '<span class="badge badge-pill badge-light-success text-capitalized">ชำระเรียบร้อยแล้ว</span>'; ?>
                        </div>
                    </dl>
                </td>
            </tr>
        </table>

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
                    <td class="text-center" rowspan="2" style="border-radius: 0px 15px 0px 0px;"><b>COT</b></td>
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
                    <!-- <td class="text-center p-50"><small>COT</small></td> -->
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

                    <?php
                    $total_cal = $amount;
                    $total_cal = !empty($discount) ? $total_cal - $discount : $total_cal;
                    $total_cal = !empty($cot) ? $total_cal - $cot : $total_cal;
                    if ($all_invocies[0]['vat_id'] == 1) {
                        $vat_total = $total_cal * 100 / 107;
                        $vat_cut = $vat_total;
                        $vat_total = $total_cal - $vat_total;
                        $withholding_total = $all_invocies[0]['withholding'] > 0 ? ($vat_cut * $all_invocies[0]['withholding']) / 100 : 0;
                        $total_cal = $total_cal - $withholding_total;
                    } elseif ($all_invocies[0]['vat_id'] == 2) {
                        $vat_total = ($total_cal * 7) / 100;
                        $total_cal = $total_cal + $vat_total;
                        $withholding_total = $all_invocies[0]['withholding'] > 0 ? ($total_cal - $vat_total) * $all_invocies[0]['withholding'] / 100 : 0;
                        $total_cal = $total_cal - $withholding_total;
                    }
                    ?>

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

                    <?php if ($all_invocies[0]['vat_id']) { ?>
                        <tr>
                            <td colspan="9"></td>
                            <td class="text-center" colspan="2"><b><?php echo $all_invocies[0]['vat_id'] != '-' ? $all_invocies[0]['vat_id'] == 1 ? 'รวมภาษี 7%' : 'แยกภาษี 7%' : '-' ?> : </b><br><small>(Vat)</small></td>
                            <td class="text-center" colspan="2"><?php echo number_format($vat_total, 2); ?></td>
                        </tr>
                    <?php } ?>

                    <?php if ($all_invocies[0]['withholding'] > 0) { ?>
                        <tr>
                            <td colspan="9"></td>
                            <td class="text-center" colspan="2"><b>หัก ณ ที่จ่าย (<?php echo $all_invocies[0]['withholding']; ?>%) : </b><br><small>(Withholding Tax)</small></td>
                            <td class="text-center" colspan="2"><?php echo number_format($withholding_total, 2); ?></td>
                        </tr>
                    <?php } ?>

                    <tr>
                        <td colspan="9"></td>
                        <td class="text-center" colspan="2"><b>ยอดชำระ</b><br><small>(Payment Amount)</small></td>
                        <td class="text-center" colspan="2"><?php echo number_format($total_cal, 2); ?></td>
                    </tr>
                </tbody>
            <?php } ?>
        </table>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <div class="form-group">
                <label class="form-label" for="note">Note</label>
                <textarea class="form-control" name="note" id="note" rows="3"><?php echo !empty($receipts['note']) ? $receipts['note'] : ''; ?></textarea>
            </div>
        </div>
    </div>
    <input type="hidden" id="amount" name="amount" value="<?php echo number_format((float)$total, 2, '.', ''); ?>">
<?php
} else {
    echo false;
}
