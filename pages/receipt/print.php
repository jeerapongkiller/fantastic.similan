<?php
$type = !empty($_POST['action']) ? 'POST' : 'GET';
$action = empty($_POST['action']) ? !empty($_GET['action']) ? "print" : 0 : "preview";
$id = empty($_POST['rec_id']) ? !empty($_GET['rec_id']) ? $_GET['rec_id'] : 0 : $_POST['rec_id'];
$btn_edit = false;

$env_contro = $action == "preview" && $type == 'POST' ? '../../config/env.php' : 'config/env.php';
$inc_contro = $action == "preview" && $type == 'POST' ? '../../controllers/Receipt.php' : 'controllers/Receipt.php';
include_once($env_contro);
include_once($inc_contro);

$recObj = new Receipt();
$today = date("Y-m-d");
$times = date("H:i:s");

function bahtText(float $amount): string
{
    [$integer, $fraction] = explode('.', number_format(abs($amount), 2, '.', ''));

    $baht = convert($integer);
    $satang = convert($fraction);

    $output = $amount < 0 ? 'ติดลบ' : '';
    $output .= $baht ? $baht . 'บาท' : '';
    $output .= $satang ? $satang . 'สตางค์' : 'ถ้วน';

    return $baht . $satang === '' ? 'ศูนย์บาทถ้วน' : $output;
}

function convert(string $number): string
{
    $values = ['', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'];
    $places = ['', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน'];
    $exceptions = ['หนึ่งสิบ' => 'สิบ', 'สองสิบ' => 'ยี่สิบ', 'สิบหนึ่ง' => 'สิบเอ็ด'];

    $output = '';

    foreach (str_split(strrev($number)) as $place => $value) {
        if ($place % 6 === 0 && $place > 0) {
            $output = $places[6] . $output;
        }

        if ($value !== '0') {
            $output = $values[$value] . $places[$place % 6] . $output;
        }
    }

    foreach ($exceptions as $search => $replace) {
        $output = str_replace($search, $replace, $output);
    }

    return $output;
}

if (isset($action) && !empty($id)) {
    $no = 0;
    $first_rec = array();
    $first_inv = array();
    $first_ext = array();
    $receipts = $recObj->showlistreceipt('', '', '', '', 'preview', $id);
    foreach ($receipts as $receipt) {
        if ((in_array($receipt['id'], $first_rec) == false)) {
            $first_rec[] = $receipt['id'];
            $rec_id[] = !empty($receipt['rec_id']) ? $receipt['rec_id'] : 0;
            $rec_full[] = !empty($receipt['rec_full']) ? $receipt['rec_full'] : 0;
        }

        if ((in_array($receipt['inv_id'], $first_inv) == false)) {
            $first_inv[] = $receipt['inv_id'];
            $inv_id[] = !empty($receipt['inv_id']) ? $receipt['inv_id'] : 0;
            $inv_full[] = !empty($receipt['inv_full']) ? $receipt['inv_full'] : 0;
            $inv_no[] = !empty($receipt['inv_no']) ? $receipt['inv_no'] : 0;
            $voucher_no_agent[] = !empty($receipt['voucher_no_agent']) ? $receipt['voucher_no_agent'] : '';
            $bo_full[] = !empty($receipt['book_full']) ? $receipt['book_full'] : '';
            $product_name[] = !empty($receipt['product_name']) ? $receipt['product_name'] : '';
            $travel_date[] = !empty($receipt['travel_date']) ? $receipt['travel_date'] : '';
            $bopay_id[] = !empty($receipt['bopay_id']) ? $receipt['bopay_id'] : 0;
            $discount[] = !empty($receipt['discount']) ? $receipt['discount'] : 0;
            $total_paid[] = !empty($receipt['bopay_id']) && ($receipt['bopay_id'] == 4 || $receipt['bopay_id'] == 5) ? $receipt['total_paid'] : 0;

            $bo_id[] = !empty($receipt['bo_id']) ? $receipt['bo_id'] : 0;
            $transfer_type[] = !empty($receipt['transfer_type']) ? $receipt['transfer_type'] : 0;
            $rate_total[] = !empty($receipt['rate_total']) ? $receipt['rate_total'] : 0;
            $bt_id[] = !empty($receipt['bt_id']) ? $receipt['bt_id'] : 0;
            $bt_adult[] = !empty($receipt['bt_adult']) ? $receipt['bt_adult'] : 0;
            $bt_child[] = !empty($receipt['bt_child']) ? $receipt['bt_child'] : 0;
            $bt_infant[] = !empty($receipt['bt_infant']) ? $receipt['bt_infant'] : 0;
            $btr_rate_adult[] = !empty($receipt['btr_rate_adult']) && $receipt['transfer_type'] == 1 ? $receipt['btr_rate_adult'] : 0;
            $btr_rate_child[] = !empty($receipt['btr_rate_child']) && $receipt['transfer_type'] == 1 ? $receipt['btr_rate_child'] : 0;
            $btr_rate_infant[] = !empty($receipt['btr_rate_infant']) && $receipt['transfer_type'] == 1 ? $receipt['btr_rate_infant'] : 0;
            // $total = $receipt['rate_total'];
            // $total = ($receipt['transfer_type'] == 1) ? $total + ($receipt['bt_adult'] * $receipt['btr_rate_adult']) + ($receipt['bt_child'] * $receipt['btr_rate_child']) + ($receipt['bt_infant'] * $receipt['btr_rate_infant']) : $total;
            // $total = ($receipt['transfer_type'] == 2) ? $recObj->sumbtrprivate($receipt['bt_id'])['sum_rate_private'] + $total : $total;
            // $total = $recObj->sumbectotal($receipt['bo_id'])['sum_rate_total'] + $total;

            // $array_total[] = $total;
        }
        # --- get value booking extra chang --- #
        if ((in_array($receipt['bec_id'], $first_ext) == false) && !empty($receipt['bec_id'])) {
            $first_ext[] = $receipt['bec_id'];
            $bec_id[$receipt['bo_id']][] = !empty($receipt['bec_id']) ? $receipt['bec_id'] : 0;
            $extra_name[$receipt['bo_id']][] = !empty($receipt['extra_name']) ? $receipt['extra_name'] : '';
            $bec_name[$receipt['bo_id']][] = !empty($receipt['bec_name']) ? $receipt['bec_name'] : '';
            $bec_type[$receipt['bo_id']][] = !empty($receipt['bec_type']) ? $receipt['bec_type'] : 0;
            $bec_adult[$receipt['bo_id']][] = !empty($receipt['bec_adult']) ? $receipt['bec_adult'] : 0;
            $bec_child[$receipt['bo_id']][] = !empty($receipt['bec_child']) ? $receipt['bec_child'] : 0;
            $bec_infant[$receipt['bo_id']][] = !empty($receipt['bec_infant']) ? $receipt['bec_infant'] : 0;
            $bec_privates[$receipt['bo_id']][] = !empty($receipt['bec_privates']) ? $receipt['bec_privates'] : 0;
            $bec_rate_adult[$receipt['bo_id']][] = !empty($receipt['bec_rate_adult']) ? $receipt['bec_rate_adult'] : 0;
            $bec_rate_child[$receipt['bo_id']][] = !empty($receipt['bec_rate_child']) ? $receipt['bec_rate_child'] : 0;
            $bec_rate_infant[$receipt['bo_id']][] = !empty($receipt['bec_rate_infant']) ? $receipt['bec_rate_infant'] : 0;
            $bec_rate_private[$receipt['bo_id']][] = !empty($receipt['bec_rate_private']) ? $receipt['bec_rate_private'] : 0;
        }
    }

    $name_img = 'Receipt-' . $receipts[0]['rec_full'];
?>
    <style>
        .default-td {
            border: 1px solid #333;
            font-size: 14px;
            color: #000;
            padding: 5px 5px;
        }

        #receipt-preview-vertical {
            background-color: #fff;
        }
    </style>
    <div class="card-body receipt-padding pb-0" id="receipt-preview-vertical">
        <!-- Header starts -->
        <div class="d-flex justify-content-between flex-md-row flex-column receipt-spacing mt-0">
            <div>
                <?php if ($receipts[0]['vat_id'] > 0) { ?>
                    <span class="brand-logo"><img src="app-assets/images/logo/logo-500.png" height="150"></span>
                <?php } ?>
            </div>
            <div class="mt-md-0 mt-2">
                <span style="color: #000;">
                    <?php echo $receipts[0]['vat_id'] > 0 ? $main_document : $main_document_th; ?>
                </span>
                <table width="100%" class="mt-50">
                    <tr>
                        <td rowspan="2" class="text-center" bgcolor="#333" style="color: #fff; border-radius: 15px 0px 0px 0px;">
                            <?php echo ($receipts[0]['vat_id'] > 0) ? 'ใบเสร็จรับเงิน / ใบกำกับภาษี <br> RECEIPT / TAX INVOICE' : 'ใบเสร็จรับเงิน <br> RECEIPT'; ?>
                        </td>
                        <td class="default-td text-center">
                            <?php echo 'เลขใบเสร็จรับเงิน'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="default-td text-center">
                            <?php echo $receipts[0]['rec_full']; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <table width="100%" class="mt-1">
            <tr>
                <td width="34%" align="left" class="default-td" colspan="4">
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-4 text-right">
                            Company
                        </dt>
                        <dd class="col-sm-8"><?php echo $receipts[0]['comp_name']; ?></dd>
                    </dl>
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-4 text-right">
                            Address
                        </dt>
                        <dd class="col-sm-8"><?php echo $receipts[0]['comp_address']; ?></dd>
                    </dl>
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-4 text-right">
                            Tel
                        </dt>
                        <dd class="col-sm-8"><?php echo $receipts[0]['comp_telephone']; ?></dd>
                    </dl>
                </td>
                <td align="left" class="default-td" colspan="2">
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-6 text-right">
                            Tax ID.
                        </dt>
                        <dd class="col-sm-6"><?php echo $receipts[0]['comp_tat']; ?></dd>
                    </dl>
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-6 text-right">
                            สำนักงาน
                        </dt>
                        <dd class="col-sm-6"><?php echo $receipts[0]['brch_name']; ?></dd>
                    </dl>
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-6 text-right">
                            PAID Date
                        </dt>
                        <dd class="col-sm-6"><?php echo date('j F Y', strtotime($receipts[0]['rec_date'])); ?></dd>
                    </dl>
                </td>
                <td width="34%" align="left" class="default-td" colspan="2">
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-4 text-right">
                            การชำระเงิน
                        </dt>
                        <dd class="col-sm-8"><?php echo $receipts[0]['payt_name']; ?></dd>
                    </dl>
                    <?php if ($receipts[0]['payt_id'] == 4) { ?>
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                เลขบัญชี
                            </dt>
                            <dd class="col-sm-8"><?php echo $receipts[0]['account_name'] . ' (' . $receipts[0]['account_no'] . ')'; ?></dd>
                        </dl>
                    <?php } elseif ($receipts[0]['payt_id'] == 5) { ?>
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                เลขที่เช็ค/ธนาคาร
                            </dt>
                            <dd class="col-sm-8"><?php echo $receipts[0]['cheque_no'] . ' / ' . $receipts[0]['bank_name']; ?></dd>
                        </dl>
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                วันที่เช็ค
                            </dt>
                            <dd class="col-sm-8"><?php echo date('j F Y', strtotime($receipts[0]['cheque_date'])); ?></dd>
                        </dl>
                    <?php } ?>
                </td>
            </tr>
        </table>
        <table width="100%" class="mt-1">
            <tr>
                <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 15px 0px 0px 0px; padding: 5px 0;" width="7%"><b>เลขที่</b><br>
                    <small>No.</small>
                </td>
                <td class="text-center" bgcolor="#333" style="color: #fff;" width="12%"><b>INVOICE NO.</b>
                </td>
                <td class="text-center" bgcolor="#333" style="color: #fff;" width="12%"><b>VOUCHER NO.</b>
                </td>
                <td class="text-center" bgcolor="#333" style="color: #fff;" width="12%"><b>BOOKING NO.</b>
                </td>
                <td class="text-center" bgcolor="#333" style="color: #fff;" width="25%"><b>โปรแกรม</b><br>
                    <small>Programe</small>
                </td>
                <td class="text-center" bgcolor="#333" style="color: #fff;" width="12%"><b>วันที่เที่ยว</b><br>
                    <small>Travel Date.</small>
                </td>
                <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 0px 15px 0px 0px;" width="20%"><b>จํานวนเงิน</b><br>
                    <small>Amount</small>
                </td>
            </tr>
            <?php
            for ($i = 0; $i < count($inv_id); $i++) {
                $total = $rate_total[$i];
                $total = $transfer_type[$i] == 1 ? $total + ($bt_adult[$i] * $btr_rate_adult[$i]) : $total;
                $total = $transfer_type[$i] == 1 ? $total + ($bt_child[$i] * $btr_rate_child[$i]) : $total;
                $total = $transfer_type[$i] == 1 ? $total + ($bt_infant[$i] * $btr_rate_infant[$i]) : $total;
                $total = $transfer_type[$i] == 2 ? $recObj->sumbtrprivate($bt_id[$i])['sum_rate_private'] + $total : $total;
                if (!empty($bec_id[$bo_id[$i]])) {
                    for ($a = 0; $a < count($bec_id[$bo_id[$i]]); $a++) {
                        $total = $bec_type[$bo_id[$i]][$a] == 1 ? $total + ($bec_adult[$bo_id[$i]][$a] * $bec_rate_adult[$bo_id[$i]][$a]) + ($bec_child[$bo_id[$i]][$a] * $bec_rate_child[$bo_id[$i]][$a]) + ($bec_infant[$bo_id[$i]][$a] * $bec_rate_infant[$bo_id[$i]][$a]) : $total;
                        $total = $bec_type[$bo_id[$i]][$a] == 2 ? $total + ($bec_privates[$bo_id[$i]][$a] * $bec_rate_private[$bo_id[$i]][$a]) : $total;
                    }
                }
                $total = $total - $discount[$i];
                // $total = ($bo_bopay_id[$i] == 4 || $bo_bopay_id[$i] == 5) ? $total - $bo_total_paid[$i] : $total;
                $array_total[] = $total;
            ?>
                <tr>
                    <td class="default-td text-center"><?php $no++;
                                                                    echo $no; ?></td> <!-- No. -->
                    <td class="default-td text-center"><?php echo $inv_no > 0 ? $inv_full[$i] . '/' . $inv_no[$i] : $inv_full[$i]; ?></td> <!-- receipt -->
                    <td class="default-td text-center"><?php echo $voucher_no_agent[$i]; ?></td> <!-- Voucher -->
                    <td class="default-td text-center"><?php echo $bo_full[$i]; ?></td> <!-- Voucher -->
                    <td class="default-td text-center"><?php echo $product_name[$i]; ?></td> <!-- Programe -->
                    <td class="default-td text-center"><?php echo date('j F Y', strtotime($travel_date[$i])); ?></td> <!-- Travel Date -->
                    <td class="default-td text-center"><?php echo number_format($total); ?></td> <!-- Amount -->
                </tr>
            <?php }
            $sum_total = array_sum($array_total);
            $sum_total = (!empty($discount)) ? $sum_total - array_sum($discount) : $sum_total;
            $sum_total = (!empty($total_paid)) ? $sum_total - array_sum($total_paid) : $sum_total;
            $amount = $sum_total;
            if ($receipts[0]['vat_id'] == 1) {
                $vat_total = $sum_total * 100 / 107;
                $vat_cut = $vat_total;
                $vat_total = $sum_total - $vat_total;
                $withholding_total = $receipts[0]['withholding'] > 0 ? ($vat_cut * $receipts[0]['withholding']) / 100 : 0;
                $amount = $sum_total - $withholding_total;
            } elseif ($receipts[0]['vat_id'] == 2) {
                $vat_total = ($sum_total * 7) / 100;
                $sum_total = $sum_total + $vat_total;
                $withholding_total = $receipts[0]['withholding'] > 0 ? ($sum_total - $vat_total) * $receipts[0]['withholding'] / 100 : 0;
                $amount = $sum_total - $withholding_total;
            }
            ?>
            <tr>
                <td class="default-td text-center" colspan="5"><em><b><?php echo bahtText($amount); ?></b></em></td>
                <td class="default-td text-center" colspan="2">
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-8 text-right">
                            <b>ยอดรวม : </b>
                            <p style="font-size: 10px; margin-bottom: 2px;">(Total)</p>
                        </dt>
                        <dd class="col-sm-4 mt-50">฿ <?php echo !empty($array_total) ? number_format(array_sum($array_total)) : 0; ?></dd>
                    </dl>
                </td>
            </tr>
            <tr>
                <td class="default-td" colspan="5" rowspan="4">
                    <b>หมายเหตุและเงื่อนใข (Terms & Conditions)</b><br>
                    <p></p>
                </td>
                <?php if (!empty($discount) && array_sum($discount) > 0) { ?>
                    <td class="default-td text-center" colspan="2">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-8 text-right">
                                <b> ส่วนลด : </b>
                                <p style="font-size: 10px; margin-bottom: 2px;">(Discount)</p>
                            </dt>
                            <dd class="col-sm-4 mt-50">฿ <?php echo number_format(array_sum($discount)); ?></dd>
                        </dl>
                    </td>
                <?php } ?>
            </tr>
            <?php for ($i = 0; $i < count($bopay_id); $i++) {
                if ($bopay_id[$i] == 4) { ?>
                    <tr>
                        <td class="default-td text-center" colspan="2">
                            <dl class="row" style="margin-bottom: 0;">
                                <dt class="col-sm-8 text-right">
                                    <b> Cash on tour </b>
                                </dt>
                                <dd class="col-sm-4 mt-50">฿ <?php echo number_format($total_paid[$i]); ?></dd>
                            </dl>
                        </td>
                    </tr>
                <?php }
                if ($bopay_id[$i] == 5) { ?>
                    <tr>
                        <td class="default-td text-center" colspan="2">
                            <dl class="row" style="margin-bottom: 0;">
                                <dt class="col-sm-8 text-right">
                                    <b> Deposit </b>
                                </dt>
                                <dd class="col-sm-4 mt-50">฿ <?php echo number_format($total_paid[$i]); ?></dd>
                            </dl>
                        </td>
                    </tr>
                <?php }
            }
            if ($receipts[0]['vat_id'] > 0) { ?>
                <tr>
                    <td class="default-td text-center" colspan="2">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-8 text-right">
                                <b> <?php echo $receipts[0]['vat_name']; ?> : </b>
                                <p style="font-size: 10px; margin-bottom: 2px;">(Tax)</p>
                            </dt>
                            <dd class="col-sm-4 mt-50">฿ <?php echo number_format($vat_total, 2); ?></dd>
                        </dl>
                    </td>
                </tr>
            <?php }
            if ($receipts[0]['withholding'] > 0) { ?>
                <tr>
                    <td class="default-td text-center" colspan="2" rowspan="4">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-8 text-right">
                                <b> หัก ณ ที่จ่าย (<?php echo $receipts[0]['withholding']; ?>%) : </b>
                                <p style="font-size: 10px; margin-bottom: 2px;">(Withholding Tax)</p>
                            </dt>
                            <dd class="col-sm-4 mt-50">฿ <?php echo number_format($withholding_total, 2); ?></dd>
                        </dl>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td class="default-td text-center" colspan="2" bgcolor="#333" style="color: #fff;">
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-8 text-right">
                            <b>ยอดชำระ : </b>
                            <p style="font-size: 10px; margin-bottom: 2px;">(Payment Amount)</p>
                        </dt>
                        <dd class="col-sm-4 mt-50"><b>฿ <?php echo number_format($amount, 2); ?></b></dd>
                    </dl>
                </td>
            </tr>
        </table>
        <table width="100%" class="mt-1">
            <tr>
                <table width="100%" height="150px">
                    <tr>
                        <td class="default-td" align="center" valign="bottom" width="35%">
                            _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ <br>
                            ผู้รับวางบิล / Receiver Signature <br>
                            วันที่ / Date _ _ _ _ _ _ _ _ _ _ _ _ _ _
                        </td>
                        <td class="default-td" align="center" valign="center" width="30%" style="font-weight: bold; font-size: 24px; color: rgba(0, 0, 0, 0.5);">
                            ตราประทับบริษัท
                        </td>
                        <td class="default-td" align="center" valign="bottom" width="35%">
                            _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ <br>
                            ผู้มีอำนาจลงนาม / Authorized Signature <br>
                            วันที่ / Date _ _ _ _ _ _ _ _ _ _ _ _ _ _
                        </td>
                    </tr>
                </table>
            </tr>
        </table>
        <br><br>
    </div>
    <div class="modal-footer d-flex justify-content-between <?php echo $type == 'GET' ? 'hidden' : '';  ?>" id="btn-page">
        <div>
            <a href="javascript:void(0);" onclick="modal_edit_receipt(<?php echo $id; ?>);">
                <button type="button" class="btn btn-primary text-right">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                    </svg>
                    Edit
                </button>
            </a>
        </div>
        <div>
            <a href="./?pages=receipt/print&action=<?php echo $action; ?>&rec_id=<?php echo $id; ?>" target="_blank">
                <button type="button" class="btn btn-primary text-left" name="print" value="print"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                        <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                        <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                    </svg>
                    Print
                </button>
            </a>
            <a href="javascript:void(0);">
                <button type="button" class="btn btn-primary text-left" value="image" onclick="download_image();"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-image" viewBox="0 0 16 16">
                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                        <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54A.505.505 0 0 1 1 12.5v-9a.5.5 0 0 1 .5-.5h13z" />
                    </svg>
                    Image
                </button>
            </a>
        </div>
    </div>
    <input type="hidden" name="name_img" id="name_img" value="<?php echo $name_img; ?>">
<?php } ?>