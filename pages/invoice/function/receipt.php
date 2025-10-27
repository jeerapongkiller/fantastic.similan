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
    $agent = $invObj->get_value('name, tat_license, telephone, address', 'companies ', 'id = ' . $agent_id, 0);
    $invoices = $invObj->showlistbooking(0, $cover_id);

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
                        <dd class="col-sm-6"><?php echo $invoices[0]['inv_full']; ?></dd>
                    </dl>
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-6 text-right">
                            สำนักงาน
                        </dt>
                        <dd class="col-sm-6"><?php echo $invoices[0]['brch_name']; ?></dd>
                    </dl>
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-6 text-right">
                            Departure Date
                        </dt>
                        <dd class="col-sm-6"><?php echo date("j F Y", strtotime($invoices[0]['inv_date'])); ?></dd>
                    </dl>
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-6 text-right">
                            Due Date
                        </dt>
                        <dd class="col-sm-6"><?php echo date("j F Y", strtotime($invoices[0]['rec_date'])); ?></dd>
                    </dl>
                    <dl class="row" style="margin-bottom: 0;">
                        <div class="col-12 text-center">
                            <?php echo (diff_date($today, $invoices[0]['rec_date'])['day'] > 0) ? '<span class="badge badge-pill badge-light-success text-capitalized">วันที่ครบกำหนดชำระ : ' . date("j F Y", strtotime($invoices[0]['rec_date'])) . ' (ครบกำหนดชำระในอีก ' . diff_date($today, $invoices[0]['rec_date'])['num'] . ' วัน)</span>' : '<span class="badge badge-pill badge-light-danger text-capitalized">วันที่ครบกำหนดชำระ : ' . date("j F Y", strtotime($invoices[0]['rec_date'])) . ' (เกินกำหนดชำระมาแล้ว ' . diff_date($today, $invoices[0]['rec_date'])['num'] . ' วัน)</span>'; ?>
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
                    <td class="text-center p-50"><small>Adult</small></td>
                    <td class="text-center p-50"><small>Child</small></td>
                    <td class="text-center p-50"><small>Adult</small></td>
                    <td class="text-center p-50"><small>Child</small></td>
                    <td class="text-center p-50"><small>Discount</small></td>
                    <td class="text-center p-50"><small>Amount</small></td>
                    <td class="text-center p-50"><small>COT</small></td>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $discount = 0;
                $cot = 0;
                $total = 0;
                $in_booking = array();
                $in_charge = array();
                if (!empty($invoices)) {
                    foreach ($invoices as $booking) {
                        if (in_array($booking['bo_id'], $in_booking) == false) {
                            $in_booking[] = $booking['bo_id'];
                            $discount = $discount + $booking['discount'];
                            $cot = $cot + $booking['cot'];
                            $total = $booking['private_type'] == 1 ? $total + ($booking['adult'] * $booking['rate_adult']) + ($booking['child'] * $booking['rate_child']) : $total + $booking['rate_total'];
                ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?><input type="hidden" name="bo_id[]" value="<?php echo $booking['bo_id']; ?>"></td>
                                <td class="text-center"><?php echo date('j F Y', strtotime($booking['travel_date'])); ?></td>
                                <td><?php echo $booking['cus_name']; ?></td>
                                <td><?php echo $booking['product_name']; ?></td>
                                <td class="text-center"><?php echo !empty($booking['voucher_no_agent']) ? $booking['voucher_no_agent'] : $booking['book_full']; ?> </td>
                                <td class="text-center"><?php echo $booking['adult']; ?></td>
                                <td class="text-center"><?php echo $booking['child']; ?></td>
                                <td class="text-center"><?php echo number_format($booking['rate_adult'], 2); ?></td>
                                <td class="text-center"><?php echo number_format($booking['rate_child'], 2); ?></td>
                                <td class="text-center"><?php echo number_format($booking['discount'], 2); ?></td>
                                <td class="text-center"><?php echo number_format($booking['private_type'] == 1 ? ($booking['adult'] * $booking['rate_adult']) + ($booking['child'] * $booking['rate_child']) : $booking['rate_total'], 2); ?></td>
                                <td class="text-center"><?php echo number_format($booking['cot'], 2); ?></td>
                            </tr>
                        <?php
                        }
                        if (in_array($booking['bec_id'], $in_charge) == false && !empty($booking['bec_id'])) {
                            $in_charge[] = $booking['bec_id'];
                            $total = $booking['bec_type'] == 1 ? $total + ($booking['bec_adult'] * $booking['bec_rate_adult']) + ($booking['bec_child'] * $booking['bec_rate_child']) + ($booking['bec_infant'] * $booking['bec_rate_infant']) : $total + ($booking['bec_privates'] * $booking['bec_rate_private']);
                        ?>
                            <tr>
                                <td class="text-left" colspan="5"><?php echo $booking['extra_name']; ?></td>
                                <td class="text-center"><?php echo $booking['bec_type'] == 1 ? $booking['bec_adult'] : $booking['bec_privates']; ?></td>
                                <td class="text-center"><?php echo $booking['bec_type'] == 1 ? $booking['bec_child'] : ''; ?></td>
                                <td class="text-center"><?php echo $booking['bec_type'] == 1 ? number_format($booking['bec_rate_adult'], 2) : ''; ?></td>
                                <td class="text-center"><?php echo $booking['bec_type'] == 1 ? number_format($booking['bec_rate_child'], 2) : ''; ?></td>
                                <td class="text-center"></td>
                                <td class="text-center"><?php echo number_format($booking['bec_type'] == 1 ? ($booking['bec_adult'] * $booking['bec_rate_adult']) + ($booking['bec_child'] * $booking['bec_rate_child']) + ($booking['bec_infant'] * $booking['bec_rate_infant']) : ($booking['bec_privates'] * $booking['bec_rate_private'])); ?></td>
                                <td class="text-center"></td>
                            </tr>
                    <?php
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

                    <?php
                    $total = ($discount > 0) ? $total - $discount : $total;
                    $total = ($cot > 0) ? $total - $cot : $total;
                    if ($invoices[0]['vat_id'] == 1) {
                        $vat_total = (($total * 100) / 107);
                        $vat_cut = $vat_total;
                        $vat_total = $total - $vat_total;
                        $withholding_total = $invoices[0]['withholding'] > 0 ? ($vat_cut * $invoices[0]['withholding']) / 100 : 0;
                        $total = $total - $withholding_total;
                    } else if ($invoices[0]['vat_id'] == 2) {
                        $vat_total = (($total * 7) / 100);
                        $total = $total + $vat_total;
                        $withholding_total = $invoices[0]['withholding'] > 0 ? (($total - $vat_total) * $invoices[0]['withholding']) / 100 : 0;
                        $total = $total - $withholding_total;
                    }
                    ?>

                    <tr id="tr-vat" <?php echo $invoices[0]['vat_id'] == 0 ? 'hidden' : ''; ?>>
                        <td colspan="10"></td>
                        <td class="text-center"><b><?php echo $invoices[0]['vat_id'] == 1 ? 'รวมภาษี 7%' : 'แยกภาษี 7%'; ?></b><br><small>(Vat)</small></td>
                        <td class="text-center"><?php echo number_format($vat_total, 2); ?></td>
                    </tr>

                    <tr id="tr-withholding" <?php echo $invoices[0]['withholding'] == 0 ? 'hidden' : ''; ?>>
                        <td colspan="10"></td>
                        <td class="text-center"><b><?php echo 'หัก ณ ที่จ่าย (' . $invoices[0]['withholding'] . '%)'; ?></b><br><small>(Withholding Tax)</small></td>
                        <td class="text-center"><?php echo number_format($withholding_total, 2); ?></td>
                    </tr>

                    <tr>
                        <td colspan="10"></td>
                        <td class="text-center"><b>ยอดชำระ</b><br><small>(Payment Amount)</small></td>
                        <td class="text-center" id="price-amount"><?php echo number_format($total, 2); ?></td>
                    </tr>

                <?php
                }
                ?>
            </tbody>
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
