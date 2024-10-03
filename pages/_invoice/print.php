<?php

use Mpdf\Tag\Em;

$type = !empty($_POST['action']) ? 'POST' : 'GET';
$action = empty($_POST['action']) ? !empty($_GET['action']) ? $_GET['action'] : 0 : $_POST['action'];
$id = empty($_POST['inv_id']) ? !empty($_GET['inv_id']) ? $_GET['inv_id'] : 0 : $_POST['inv_id'];
$cover_id = empty($_POST['cover_id']) ? !empty($_GET['cover_id']) ? $_GET['cover_id'] : 0 : $_POST['cover_id'];
$btn_edit = false;

$env_contro = $action == "preview" && $type == 'POST' ? '../../config/env.php' : 'config/env.php';
$inc_contro = $action == "preview" && $type == 'POST' ? '../../controllers/Invoice.php' : 'controllers/Invoice.php';
include_once($env_contro);
include_once($inc_contro);

$invObj = new Invoice();
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

if (isset($action) && $action == "preview" && (!empty($id) || !empty($cover_id))) {
    $no = 0;
    $invoices = $invObj->showlistinvoice('', '', '', '', 'preview', $id, $cover_id);
    $name_img = 'Invoice-' . $invoices[0]['inv_full'];
    foreach ($invoices as $invoice) {
        if ($invoice['bopay_id'] == 4) {
            $pay_id = !empty($invoice['bopay_id']) ? $invoice['bopay_id'] : 0;
            $cot_id = !empty($invoice['bopa_id']) ? $invoice['bopa_id'] : 0;
            $cot = !empty($invoice['total_paid']) ? $invoice['total_paid'] : 0;
        }
    }
?>
    <style>
        .default-td {
            border: 1px solid #333;
            font-size: 14px;
            color: #000;
            padding: 5px 5px;
        }

        #invoice-preview-vertical {
            background-color: #fff;
        }
    </style>
    <div class="card-body invoice-padding pb-0" id="invoice-preview-vertical">
        <!-- Header starts -->
        <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
            <div>
                <span class="brand-logo"><img src="app-assets/images/logo/logo-500.png" height="150"></span>
            </div>
            <div class="mt-md-0 mt-2">
                <span style="color: #000;">
                    <?php echo $main_document; ?>
                </span>
                <table width="100%" class="mt-50">
                    <tr>
                        <td rowspan="2" class="text-center" bgcolor="#333" style="color: #fff; border-radius: 15px 0px 0px 0px;">
                            <?php echo ($invoices[0]['vat_id'] > 0) ? 'ใบแจ้งหนี้ / ใบวางบิล <br> INVOICE / DELECERY ORDER' : 'ใบแจ้งหนี้ <br> INVOICE'; ?>
                        </td>
                        <td class="default-td text-center">
                            INVOICE NO.
                        </td>
                    </tr>
                    <tr>
                        <td class="default-td text-center">
                            <?php echo !empty($invoices[0]['no']) && $id > 0 ? $invoices[0]['inv_full'] . '/' . $invoices[0]['no'] : $invoices[0]['inv_full']; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- Header ends -->
        <?php if ($id > 0) {
            $btn_edit = $invoices[0]['no'] > 0 ? false : true;
        ?>
            <table width="100%" class="mt-1">
                <tr>
                    <td width="34%" align="left" class="default-td" colspan="4">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                Company
                            </dt>
                            <dd class="col-sm-8"><?php echo $invoices[0]['comp_name']; ?></dd>
                        </dl>
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                Voucher No.
                            </dt>
                            <dd class="col-sm-8"><?php echo $invoices[0]['voucher_no_agent']; ?></dd>
                        </dl>
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                Address
                            </dt>
                            <dd class="col-sm-8"><?php echo $invoices[0]['comp_address']; ?></dd>
                        </dl>
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                Tel
                            </dt>
                            <dd class="col-sm-8"><?php echo $invoices[0]['comp_telephone']; ?></dd>
                        </dl>
                    </td>
                    <td align="left" class="default-td" colspan="2">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-6 text-right">
                                Tax ID.
                            </dt>
                            <dd class="col-sm-6"><?php echo $invoices[0]['comp_tat']; ?></dd>
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
                            <dd class="col-sm-6"><?php echo date('j F Y', strtotime($invoices[0]['inv_date'])); ?></dd>
                        </dl>
                    </td>
                    <td width="34%" align="left" class="default-td" colspan="2">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                Programe
                            </dt>
                            <dd class="col-sm-8"><?php echo $invoices[0]['product_name']; ?></dd>
                        </dl>
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                Travel Date
                            </dt>
                            <dd class="col-sm-8"><?php echo date('j F Y', strtotime($invoices[0]['travel_date'])); ?></dd>
                        </dl>
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                Customer
                            </dt>
                            <dd class="col-sm-8"><?php echo $invoices[0]['cus_name']; ?></dd>
                        </dl>
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                Hotel
                            </dt>
                            <dd class="col-sm-8"><?php echo empty($invoices[0]['hotel_pickup_id']) ? !empty($invoices[0]['hotel_pickup']) ? $invoices[0]['hotel_pickup'] : '' : $invoices[0]['hotel_pickup_name']; ?></dd>
                        </dl>
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                Room
                            </dt>
                            <dd class="col-sm-8"><?php echo $invoices[0]['room_no']; ?></dd>
                        </dl>
                    </td>
                </tr>
            </table>
            <table width="100%" class="mt-1">
                <tr>
                    <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 15px 0px 0px 0px; padding: 5px 0;" width="10%"><b>เลขที่</b><br>
                        <small>No.</small>
                    </td>
                    <td class="text-center" bgcolor="#333" style="color: #fff;" width="30%"><b>รายการ</b><br>
                        <small>Description</small>
                    </td>
                    <td class="text-center" bgcolor="#333" style="color: #fff;" width="10%"><b>จํานวน</b><br>
                        <small>Quantity.</small>
                    </td>
                    <td class="text-center" bgcolor="#333" style="color: #fff;" width="10%"><b>ราคา/หน่วย</b><br>
                        <small>Unit Price.</small>
                    </td>
                    <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 0px 15px 0px 0px;" width="20%"><b>จํานวนเงิน</b><br>
                        <small>Amount</small>
                    </td>
                </tr>

                <tr>
                    <td class="default-td text-center" width="10%"><?php $no++;
                                                                    echo $no; ?></td> <!-- No. -->
                    <td class="default-td" width="30%">Adult</td> <!-- Description -->
                    <td class="default-td text-center" width="10%"><?php echo $invoices[0]['bp_adult']; ?></td> <!-- Quantity -->
                    <td class="default-td text-center" width="10%"><?php echo number_format($invoices[0]['rate_adult']); ?></td> <!-- Unit Price -->
                    <?php echo ($invoices[0]['bo_type'] == 1) ? '<td class="default-td text-center" width="20%">' . number_format($invoices[0]['bp_adult'] * $invoices[0]['rate_adult']) . '</td>' : '<td class="default-td text-center" width="20%" rowspan="3">' . number_format($invoices[0]['rate_total']) . '</td>'; ?>
                </tr>
                <tr>
                    <td class="default-td text-center" width="10%"><?php $no++;
                                                                    echo $no; ?></td> <!-- No. -->
                    <td class="default-td" width="30%">Children</td> <!-- Description -->
                    <td class="default-td text-center" width="10%"><?php echo $invoices[0]['bp_child']; ?></td> <!-- Quantity -->
                    <td class="default-td text-center" width="10%"><?php echo number_format($invoices[0]['rate_child']); ?></td> <!-- Unit Price -->
                    <?php echo ($invoices[0]['bo_type'] == 1) ? '<td class="default-td text-center" width="20%">' . number_format($invoices[0]['bp_child'] * $invoices[0]['rate_child']) . '</td>' : ''; ?>
                </tr>
                <tr>
                    <td class="default-td text-center" width="10%"><?php $no++;
                                                                    echo $no; ?></td> <!-- No. -->
                    <td class="default-td" width="30%">Infant</td> <!-- Description -->
                    <td class="default-td text-center" width="10%"><?php echo $invoices[0]['bp_infant']; ?></td> <!-- Quantity -->
                    <td class="default-td text-center" width="10%"><?php echo number_format($invoices[0]['rate_infant']); ?></td> <!-- Unit Price -->
                    <?php echo ($invoices[0]['bo_type'] == 1) ? '<td class="default-td text-center" width="20%">' . number_format($invoices[0]['bp_infant'] * $invoices[0]['rate_infant']) . '</td>' : ''; ?>
                </tr>

                <?php if ($invoices[0]['transfer_type'] == 1) { ?>
                    <tr>
                        <td class="default-td" width="100%" colspan="6">Transfer : <b>Join</b></td>
                    </tr>
                    <tr>
                        <td class="default-td text-center" width="10%"><?php $no++;
                                                                        echo $no; ?></td> <!-- No. -->
                        <td class="default-td" width="30%">Adult</td> <!-- Description -->
                        <td class="default-td text-center" width="10%"><?php echo $invoices[0]['bt_adult']; ?></td> <!-- Quantity -->
                        <td class="default-td text-center" width="10%"><?php echo number_format($invoices[0]['btr_rate_adult']); ?></td> <!-- Unit Price -->
                        <td class="default-td text-center" width="20%"><?php echo number_format($invoices[0]['bt_adult'] * $invoices[0]['btr_rate_adult']); ?></td> <!-- Amount -->
                    </tr>
                    <tr>
                        <td class="default-td text-center" width="10%"><?php $no++;
                                                                        echo $no; ?></td> <!-- No. -->
                        <td class="default-td" width="30%">Children</td> <!-- Description -->
                        <td class="default-td text-center" width="10%"><?php echo $invoices[0]['bt_child']; ?></td> <!-- Quantity -->
                        <td class="default-td text-center" width="10%"><?php echo number_format($invoices[0]['btr_rate_child']); ?></td> <!-- Unit Price -->
                        <td class="default-td text-center" width="20%"><?php echo number_format($invoices[0]['bt_child'] * $invoices[0]['btr_rate_child']); ?></td> <!-- Amount -->
                    </tr>
                    <tr>
                        <td class="default-td text-center" width="10%"><?php $no++;
                                                                        echo $no; ?></td> <!-- No. -->
                        <td class="default-td" width="30%">Infant</td> <!-- Description -->
                        <td class="default-td text-center" width="10%"><?php echo $invoices[0]['bt_infant']; ?></td> <!-- Quantity -->
                        <td class="default-td text-center" width="10%"><?php echo number_format($invoices[0]['btr_rate_infant']); ?></td> <!-- Unit Price -->
                        <td class="default-td text-center" width="20%"><?php echo number_format($invoices[0]['bt_infant'] * $invoices[0]['btr_rate_infant']); ?></td> <!-- Amount -->
                    </tr>
                <?php } else if ($invoices[0]['transfer_type'] == 2) { ?>
                    <tr>
                        <td class="default-td" width="100%" colspan="6">Transfer : <b>Private</b></td>
                    </tr>
                    <?php
                    $first_btr = array();
                    foreach ($invoices as $invoice) {
                        if (in_array($invoice['btr_id'], $first_btr) == false) {
                            $first_btr[] = $invoice['btr_id']; ?>
                            <tr>
                                <td class="default-td text-center" width="10%"><?php $no++;
                                                                                echo $no; ?></td>
                                <td class="default-td" width="30%"><?php echo $invoice['cars_category']; ?></td>
                                <td class="default-td text-center" width="10%">1</td>
                                <td class="default-td text-center" width="10%"><?php echo number_format($invoice['rate_private']); ?></td>
                                <td class="default-td text-center" width="20%"><?php echo number_format($invoice['rate_private']); ?></td>
                            </tr>
                    <?php }
                    }
                }
                if ($invoices[0]['bec_id'] != '') {
                    $total_bec = 0; ?>
                    <tr>
                        <td class="default-td" width="100%" colspan="5">Extra Charge</td>
                    </tr>
                    <?php
                    $first_bec = array();
                    foreach ($invoices as $invoice) {
                        if (in_array($invoice['bec_id'], $first_bec) == false) {
                            $first_bec[] = $invoice['bec_id'];
                            if ($invoice['bec_type'] == 1) {
                                $total_bec = $total_bec + (($invoice['bec_adult'] * $invoice['bec_rate_adult']) + ($invoice['bec_child'] * $invoice['bec_rate_child']) + ($invoice['bec_infant'] * $invoice['bec_rate_infant'])); ?>
                                <tr>
                                    <td class="default-td text-center" width="10%"><?php $no++;
                                                                                    echo $no; ?></td>
                                    <td class="default-td" width="30%"><?php echo !empty($invoice['extra_id']) ? $invoice['extra_name'] . ' (Adult)' : $invoice['bec_name'] . ' (Adult)'; ?></td>
                                    <td class="default-td text-center" width="10%"><?php echo $invoice['bec_adult']; ?></td>
                                    <td class="default-td text-center" width="10%"><?php echo number_format($invoice['bec_rate_adult']); ?></td>
                                    <td class="default-td text-center" width="20%"><?php echo number_format($invoice['bec_adult'] * $invoice['bec_rate_adult']); ?></td>
                                </tr>
                                <tr>
                                    <td class="default-td text-center" width="10%"><?php $no++;
                                                                                    echo $no; ?></td>
                                    <td class="default-td" width="30%"><?php echo !empty($invoice['extra_id']) ? $invoice['extra_name'] . ' (Children)' : $invoice['bec_name'] . ' (Children)'; ?></td>
                                    <td class="default-td text-center" width="10%"><?php echo $invoice['bec_child']; ?></td>
                                    <td class="default-td text-center" width="10%"><?php echo number_format($invoice['bec_rate_child']); ?></td>
                                    <td class="default-td text-center" width="20%"><?php echo number_format($invoice['bec_child'] * $invoice['bec_rate_child']); ?></td>
                                </tr>
                                <tr>
                                    <td class="default-td text-center" width="10%"><?php $no++;
                                                                                    echo $no; ?></td>
                                    <td class="default-td" width="30%"><?php echo !empty($invoice['extra_id']) ? $invoice['extra_name'] . ' (Infant)' : $invoice['bec_name'] . ' (Infant)'; ?></td>
                                    <td class="default-td text-center" width="10%"><?php echo $invoice['bec_infant']; ?></td>
                                    <td class="default-td text-center" width="10%"><?php echo number_format($invoice['bec_rate_infant']); ?></td>
                                    <td class="default-td text-center" width="20%"><?php echo number_format($invoice['bec_infant'] * $invoice['bec_rate_infant']); ?></td>
                                </tr>
                            <?php } elseif ($invoice['bec_type'] == 2) {
                                $total_bec = $total_bec + ($invoice['bec_privates'] * $invoice['bec_rate_private']); ?>
                                <tr>
                                    <td class="default-td text-center" width="10%"><?php $no++;
                                                                                    echo $no; ?></td>
                                    <td class="default-td" width="30%"><?php echo !empty($invoice['extra_id']) ? $invoice['extra_name'] : $invoice['bec_name']; ?></td>
                                    <td class="default-td text-center" width="10%"><?php echo $invoice['bec_privates']; ?></td>
                                    <td class="default-td text-center" width="10%"><?php echo number_format($invoice['bec_rate_private']); ?></td>
                                    <td class="default-td text-center" width="20%"><?php echo number_format($invoice['bec_privates'] * $invoice['bec_rate_private']); ?></td>
                                </tr>
                            <?php } ?>
                <?php }
                    }
                }
                $total = $invoices[0]['rate_total'];
                $total = ($invoices[0]['transfer_type'] == 1) ? $total + ($invoices[0]['bt_adult'] * $invoices[0]['btr_rate_adult']) + ($invoices[0]['bt_child'] * $invoices[0]['btr_rate_child']) + ($invoices[0]['bt_infant'] * $invoices[0]['btr_rate_infant']) : $total;
                $total = ($invoices[0]['transfer_type'] == 2) ? $total + $invObj->sumbtrprivate($invoices[0]['bt_id'])['sum_rate_private'] : $total;
                $total = !empty($total_bec) ? $total_bec + $total : $total;
                $amount = $total;
                if ($invoices[0]['vat_id'] == 1) {
                    $vat_total = $total * 100 / 107;
                    $vat_cut = $vat_total;
                    $vat_total = $total - $vat_total;
                    $withholding_total = $invoices[0]['withholding'] > 0 ? ($vat_cut * $invoices[0]['withholding']) / 100 : 0;
                    $amount = $total - $withholding_total;
                } elseif ($invoices[0]['vat_id'] == 2) {
                    $vat_total = ($total * 7) / 100;
                    $amount = $amount + $vat_total;
                    $withholding_total = $invoices[0]['withholding'] > 0 ? ($amount - $vat_total) * $invoices[0]['withholding'] / 100 : 0;
                    $amount = $amount - $withholding_total;
                }
                $amount = $invoices[0]['discount'] > 0 ? $amount - $invoices[0]['discount'] : $amount;
                $amount = $pay_id == 4 ? $amount - $cot : $amount;
                ?>
                <tr>
                    <td class="default-td text-center" colspan="4"><em><b><?php echo bahtText($amount) ?></b></em></td>
                    <td class="default-td text-center">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-8 text-right">
                                <b>ยอดรวม : </b>
                                <p style="font-size: 10px; margin-bottom: 2px;">(Total)</p>
                            </dt>
                            <dd class="col-sm-4 mt-50">฿ <?php echo number_format($total); ?></dd>
                        </dl>
                    </td>
                </tr>
                <tr>
                    <td class="default-td" colspan="4" rowspan="4">
                        <b>หมายเหตุและเงื่อนใข (Terms & Conditions)</b><br>
                        <p>
                            <?php echo $invoices[0]['banacc_id'] > 0 ? '</br><b>ชื่อบัญชี</b> ' . $invoices[0]['account_name'] . '</br><b>เลขที่บัญชี</b> ' . $invoices[0]['account_no'] . '</br><b>ธนาคาร</b> ' . $invoices[0]['bank_name'] : ''; ?>
                        </p>
                    </td>
                    <?php if ($invoices[0]['discount'] > 0) {
                        $total = $total - $invoice['discount']; ?>
                        <td class="default-td text-center">
                            <dl class="row" style="margin-bottom: 0;">
                                <dt class="col-sm-8 text-right">
                                    <b> ส่วนลด : </b>
                                    <p style="font-size: 10px; margin-bottom: 2px;">(Discount)</p>
                                </dt>
                                <dd class="col-sm-4 mt-50">฿ <?php echo number_format($invoices[0]['discount']); ?></dd>
                            </dl>
                        </td>
                    <?php } ?>
                </tr>
                <?php
                if ($invoices[0]['vat_id'] > 0) { ?>
                    <tr>
                        <td class="default-td text-center">
                            <dl class="row" style="margin-bottom: 0;">
                                <dt class="col-sm-8 text-right">
                                    <b> <?php echo $invoices[0]['vat_name']; ?> : </b>
                                    <p style="font-size: 10px; margin-bottom: 2px;">(Tax)</p>
                                </dt>
                                <dd class="col-sm-4 mt-50">฿ <?php echo number_format($vat_total); ?></dd>
                            </dl>
                        </td>
                    </tr>
                <?php }
                if ($invoices[0]['withholding'] > 0) { ?>
                    <tr>
                        <td class="default-td text-center">
                            <dl class="row" style="margin-bottom: 0;">
                                <dt class="col-sm-8 text-right">
                                    <b> หัก ณ ที่จ่าย (<?php echo $invoices[0]['withholding']; ?>%) : </b>
                                    <p style="font-size: 10px; margin-bottom: 2px;">(Withholding Tax)</p>
                                </dt>
                                <dd class="col-sm-4 mt-50">฿ <?php echo number_format($withholding_total); ?></dd>
                            </dl>
                        </td>
                    </tr>
                <?php }
                if ($pay_id == 4) { ?>
                    <tr>
                        <td class="default-td text-center">
                            <dl class="row" style="margin-bottom: 0;">
                                <dt class="col-sm-8 text-right mt-50">
                                    <b> Cash on tour : </b>
                                </dt>
                                <dd class="col-sm-4 mt-50">฿ <?php echo number_format($cot); ?></dd>
                            </dl>
                        </td>
                    </tr>
                <?php } elseif ($pay_id == 5) { ?>
                    <tr>
                        <td class="default-td text-center">
                            <dl class="row" style="margin-bottom: 0;">
                                <dt class="col-sm-8 text-right mt-50">
                                    <b> Deposit : </b>
                                </dt>
                                <dd class="col-sm-4 mt-50">฿ <?php echo number_format($cot); ?></dd>
                            </dl>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td class="default-td text-center" bgcolor="#333" style="color: #fff;">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-8 text-right">
                                <b>ยอดชำระ : </b>
                                <p style="font-size: 10px; margin-bottom: 2px;">(Payment Amount)</p>
                            </dt>
                            <dd class="col-sm-4 mt-50"><b>฿ <?php echo number_format($amount); ?></b></dd>
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
        <?php } elseif ($cover_id > 0) {
            $btn_edit = true;
            $no = 0;
            $sum_total = 0;
            $discount = 0;
            $payment_paid = 0;
            $first_inv = array();
            $first_bo = array();
            $first_bec = array();
            $pay_array = array();
            foreach ($invoices as $invoice) {
                if (in_array($invoice['bo_id'], $first_bo) == false) {
                    $first_bo[] = $invoice['bo_id'];
                    $bo_id[] = $invoice['bo_id'];
                    $no_total = $invoice['rate_total'];
                    $no_total = ($invoice['transfer_type'] == 1) ? $no_total + ($invoice['bt_adult'] * $invoice['btr_rate_adult']) + ($invoice['bt_child'] * $invoice['btr_rate_child']) + ($invoice['bt_infant'] * $invoice['btr_rate_infant']) : $no_total;
                    $no_total = ($invoice['transfer_type'] == 2) ? $invObj->sumbtrprivate($invoice['bt_id'])['sum_rate_private'] + $no_total : $no_total;

                    $discount = (!empty($invoice['discount'])) ? $discount + $invoice['discount'] : $discount;
                    $payment_paid = ($invoice['bopay_id'] == 4 || $invoice['bopay_id'] == 5) ? $payment_paid + $invoice['total_paid'] : $payment_paid;
                    $pay_array['pay_id'][] = $invoice['bopay_id'];
                    $pay_array['total'][] = $invoice['total_paid'];
                }

                if (in_array($invoice['bec_id'], $first_bec) == false) {
                    $first_bec[] = $invoice['bec_id'];
                    $total_bec = 0;
                    $total_bec = ($invoice['bec_type'] == 1) ? $total_bec + ($invoice['bec_adult'] * $invoice['bec_rate_adult']) + ($invoice['bec_child'] * $invoice['bec_rate_child']) + ($invoice['bec_infant'] * $invoice['bec_rate_infant']) : $total_bec;
                    $total_bec = ($invoice['bec_type'] == 2) ? $total_bec + ($invoice['bec_privates'] * $invoice['bec_rate_private']) : $total_bec;
                    $total_bec_arr[$invoice['bo_id']][] = $total_bec;
                }

                if (in_array($invoice['id'], $first_inv) == false) {
                    $first_inv[] = $invoice['id'];
                    # --- get value invoice --- #
                    $voucher_no_agent[] = !empty($invoice['voucher_no_agent']) ? $invoice['voucher_no_agent'] : '';
                    $book_full[] = !empty($invoice['book_full']) ? $invoice['book_full'] : '';
                    $cus_name[] = !empty($invoice['cus_name']) ? $invoice['cus_name'] : '';
                    $product_name[] = !empty($invoice['product_name']) ? $invoice['product_name'] : '';
                    $bopay_id[] = !empty($invoice['bopay_id']) ? $invoice['bopay_id'] : 0;
                    // $bopay_name[] = !empty($invoice['bopay_name']) ? $invoice['bopay_name'] : '';
                    $total_paid[] = !empty($invoice['total_paid']) ? $invoice['total_paid'] : 0;
                    $travel_date[] = !empty($invoice['travel_date']) ? $invoice['travel_date'] : '0000-00-00';
                    $inv_id[] = !empty($invoice['id']) ? $invoice['id'] : 0;
                    $inv_no[] = !empty($invoice['no']) ? $invoice['no'] : 0;
                    $inv_full[] = !empty($invoice['inv_full']) ? $invoice['inv_full'] : 0;
                    $rec_date[] = !empty($invoice['rec_date']) ? $invoice['rec_date'] : '0000-00-00';
                    $total[] = $no_total;
                }

                if ($invoice['bopay_id'] != 6) {
                    $bopay[$invoice['id']] = !empty($invoice['bopay_id']) ? $invoice['bopay_id'] : 0;
                    $bopa[$invoice['id']] = !empty($invoice['bopa_id']) ? $invoice['bopa_id'] : 0;
                    $bopay_name[$invoice['id']] = !empty($invoice['bopay_name']) ? $invoice['bopay_name'] : 0;
                    $cot_paid[$invoice['id']] = !empty($invoice['total_paid']) ? $invoice['total_paid'] : 0;
                }
            }
        ?>
            <table width="100%" class="mt-1">
                <tr>
                    <td width="34%" align="left" class="default-td" colspan="4">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                Company
                            </dt>
                            <dd class="col-sm-8"><?php echo $invoices[0]['comp_name']; ?></dd>
                        </dl>
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                Address
                            </dt>
                            <dd class="col-sm-8"><?php echo $invoices[0]['comp_address']; ?></dd>
                        </dl>
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                Tel
                            </dt>
                            <dd class="col-sm-8"><?php echo $invoices[0]['comp_telephone']; ?></dd>
                        </dl>
                    </td>
                    <td width="34%" align="left" class="default-td" colspan="2">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-6 text-right">
                                Tax ID.
                            </dt>
                            <dd class="col-sm-6"><?php echo $invoices[0]['comp_tat']; ?></dd>
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
                            <dd class="col-sm-6"><?php echo date('j F Y', strtotime($invoices[0]['inv_date'])); ?></dd>
                        </dl>
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-6 text-right">
                                Due Date
                            </dt>
                            <dd class="col-sm-6"><?php echo date('j F Y', strtotime($invoices[0]['rec_date'])); ?></dd>
                        </dl>
                    </td>
                </tr>
            </table>
            <table width="100%" class="mt-1">
                <!-- <tr>
                    <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 15px 0px 0px 0px; padding: 5px 0;" width="10%"><b>เลขที่</b><br>
                        <small>No.</small>
                    </td>
                    <td class="text-center" bgcolor="#333" style="color: #fff;" width="20%"><b>เลขใบแจ้งหนี้</b><br>
                        <small>Invoice No.</small>
                    </td>
                    <td class="text-center" bgcolor="#333" style="color: #fff;" width="20%"><b>Voucher No.</b>
                    </td>
                    <td class="text-center" bgcolor="#333" style="color: #fff;" width="10%"><b>ราคา/รวม</b><br>
                        <small>Amount.</small>
                    </td>
                    <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 0px 15px 0px 0px;" width="20%"><b>เงินคงค้าง</b><br>
                        <small>Accrual</small>
                    </td>
                </tr> -->
                <tr>
                    <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 15px 0px 0px 0px; padding: 5px 0;" width="5%"><b>เลขที่</b><br>
                        <small>No.</small>
                    </td>
                    <td class="text-center" bgcolor="#333" style="color: #fff;" width="8%"><b>Invoice No.</b>
                    </td>
                    <td class="text-center" bgcolor="#333" style="color: #fff;" width="8%"><b>Booking No.</b>
                    </td>
                    <td class="text-center" bgcolor="#333" style="color: #fff;" width="8%"><b>Voucher No.</b>
                    </td>
                    <td class="text-center" bgcolor="#333" style="color: #fff;" width="15%"><b>ชื่อลูค้า</b><br>
                        <small>Customer's Name</small>
                    </td>
                    <td class="text-center" bgcolor="#333" style="color: #fff;" width="15%"><b>โปรแกรม</b><br>
                        <small>Programe</small>
                    </td>
                    <td class="text-center" bgcolor="#333" style="color: #fff;" width="12%"><b>วันที่เที่ยว</b><br>
                        <small>Travel Date</small>
                    </td>
                    <td class="text-center" bgcolor="#333" style="color: #fff;" width="12%"><b>Payment</b>
                    <td class="text-center" bgcolor="#333" style="color: #fff; border-radius: 0px 15px 0px 0px;" width="20%"><b>จํานวนเงิน</b><br>
                        <small>Total</small>
                    </td>
                </tr>
                <?php
                for ($i = 0; $i < count(($inv_id)); $i++) {
                    $total_prod = $total[$i];
                    $total_prod = !empty($total_bec_arr) ? $total_prod + array_sum($total_bec_arr[$bo_id[$i]]) : $total_prod;
                    $total_prod = $bopay[$inv_id[$i]] == 4 || $bopay[$inv_id[$i]] == 5 ? $total_prod - $cot_paid[$inv_id[$i]] : $total_prod;
                ?>
                    <tr>
                        <td class="default-td text-center"><?php $no++;
                                                            echo $no; ?></td> <!-- No. -->
                        <td class="default-td text-center"><?php echo $inv_full[$i] . '/' . $inv_no[$i]; ?></td> <!-- Invoice No -->
                        <td class="default-td text-center"><?php echo $book_full[$i] ?></td> <!-- Booking No -->
                        <td class="default-td text-center"><?php echo $voucher_no_agent[$i]; ?></td> <!-- Voucher No Agent -->
                        <td class="default-td text-center"><?php echo $cus_name[$i]; ?></td> <!-- Customer Name -->
                        <td class="default-td text-center"><?php echo $product_name[$i]; ?></td> <!-- Programe -->
                        <td class="default-td text-center"><?php echo date('j F Y', strtotime($travel_date[$i])); ?></td> <!-- Travel Date -->
                        <td class="default-td text-center"><?php echo $bopay[$inv_id[$i]] == 4 || $bopay[$inv_id[$i]] == 5 ? $bopay_name[$inv_id[$i]] . '</br> (' . number_format($cot_paid[$inv_id[$i]]) . ')' : $bopay_name[$inv_id[$i]]; ?></td> <!-- Payments -->
                        <td class="default-td text-right"><?php echo number_format($total_prod); ?></td> <!-- Amount -->
                    </tr>
                <?php $sum_total = $sum_total + $total_prod;
                }
                $amount = $discount > 0 ? $sum_total - $discount : $sum_total;
                if ($invoices[0]['vat_id'] == 1) {
                    $vat_total = $sum_total * 100 / 107;
                    $vat_cut = $vat_total;
                    $vat_total = $sum_total - $vat_total;
                    $withholding_total = $invoices[0]['withholding'] > 0 ? ($vat_cut * $invoices[0]['withholding']) / 100 : 0;
                    $amount = $sum_total - $withholding_total;
                } elseif ($invoices[0]['vat_id'] == 2) {
                    $vat_total = ($sum_total * 7) / 100;
                    $sum_total = $sum_total + $vat_total;
                    $withholding_total = $invoices[0]['withholding'] > 0 ? ($sum_total - $vat_total) * $invoices[0]['withholding'] / 100 : 0;
                    $amount = $sum_total - $withholding_total;
                }
                // $amount = $payment_paid > 0 ? $amount - $payment_paid : $amount;
                ?>
                <tr>
                    <td class="default-td text-center" colspan="7"><em><b><?php echo bahtText($amount) ?></b></em></td>
                    <td class="default-td text-center" colspan="2">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-8 text-right">
                                <b>ยอดรวม : </b>
                                <p style="font-size: 10px; margin-bottom: 2px;">(Total)</p>
                            </dt>
                            <dd class="col-sm-4 mt-50 text-nowrap">฿ <?php echo number_format($sum_total); ?></dd>
                        </dl>
                    </td>
                </tr>
                <tr>
                    <td class="default-td" colspan="7" rowspan="4">
                        <b>หมายเหตุและเงื่อนใข (Terms & Conditions)</b><br>
                        <p>
                            <?php echo $invoices[0]['banacc_id'] > 0 ? '</br><b>ชื่อบัญชี</b> ' . $invoices[0]['account_name'] . '</br><b>เลขที่บัญชี</b> ' . $invoices[0]['account_no'] . '</br><b>ธนาคาร</b> ' . $invoices[0]['bank_name'] : ''; ?>
                        </p>
                    </td>
                    <?php if ($discount > 0) { ?>
                        <td class="default-td text-center" colspan="2">
                            <dl class="row" style="margin-bottom: 0;">
                                <dt class="col-sm-8 text-right">
                                    <b> ส่วนลด : </b>
                                    <p style="font-size: 10px; margin-bottom: 2px;">(Discount)</p>
                                </dt>
                                <dd class="col-sm-4 mt-50 text-nowrap">฿ <?php echo number_format($discount); ?></dd>
                            </dl>
                        </td>
                    <?php } ?>
                </tr>
                <?php
                if ($invoices[0]['vat_id'] > 0) { ?>
                    <tr>
                        <td class="default-td text-center" colspan="2">
                            <dl class="row" style="margin-bottom: 0;">
                                <dt class="col-sm-8 text-right">
                                    <b> <?php echo $invoices[0]['vat_name']; ?> : </b>
                                    <p style="font-size: 10px; margin-bottom: 2px;">(Tax)</p>
                                </dt>
                                <dd class="col-sm-4 mt-50 text-nowrap">฿ <?php echo number_format($vat_total, 2); ?></dd>
                            </dl>
                        </td>
                    </tr>
                <?php }
                if ($invoices[0]['withholding'] > 0) { ?>
                    <tr>
                        <td class="default-td text-center" colspan="2">
                            <dl class="row" style="margin-bottom: 0;">
                                <dt class="col-sm-8 text-right">
                                    <b> หัก ณ ที่จ่าย (<?php echo $invoices[0]['withholding']; ?>%) : </b>
                                    <p style="font-size: 10px; margin-bottom: 2px;">(Withholding Tax)</p>
                                </dt>
                                <dd class="col-sm-4 mt-50 text-nowrap">฿ <?php echo number_format($withholding_total); ?></dd>
                            </dl>
                        </td>
                    </tr>
                    <?php }
                for ($i = 0; $i < count($pay_array['pay_id']); $i++) {
                    if ($pay_array['pay_id'][$i] == 4) { ?>
                        <!-- <tr>
                            <td class="default-td text-center">
                                <dl class="row" style="margin-bottom: 0;">
                                    <dt class="col-sm-8 text-right mt-50">
                                        <b> Cash on tour : </b>
                                    </dt>
                                    <dd class="col-sm-4 mt-50">฿ <?php echo number_format($pay_array['total'][$i]); ?></dd>
                                </dl>
                            </td>
                        </tr> -->
                    <?php } elseif ($pay_array['pay_id'][$i] == 5) { ?>
                        <!-- <tr>
                            <td class="default-td text-center">
                                <dl class="row" style="margin-bottom: 0;">
                                    <dt class="col-sm-8 text-right mt-50">
                                        <b> Deposit : </b>
                                    </dt>
                                    <dd class="col-sm-4 mt-50">฿ <?php echo number_format($pay_array['total'][$i]); ?></dd>
                                </dl>
                            </td>
                        </tr> -->
                <?php }
                } ?>
                <tr>
                    <td class="default-td text-center" bgcolor="#333" style="color: #fff;" colspan="2">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-8 text-right">
                                <b>ยอดชำระ : </b>
                                <p style="font-size: 10px; margin-bottom: 2px;">(Payment Amount)</p>
                            </dt>
                            <dd class="col-sm-4 mt-50 text-nowrap"><b>฿ <?php echo number_format($amount); ?></b></dd>
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
        <?php } ?>
        <br><br>
    </div>
    <div class="modal-footer d-flex justify-content-between <?php echo $type == 'GET' ? 'hidden' : ''; ?>" id="btn-page">
        <div>
            <a href="javascript:void(0);" <?php echo $btn_edit == false ? 'hidden' : ''; ?> onclick="modal_edit_invoice(<?php echo $id; ?>, <?php echo $cover_id; ?>);">
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
            <a href="./?pages=invoice/print&action=<?php echo $action; ?>&inv_id=<?php echo $id; ?>&cover_id=<?php echo $cover_id; ?>" target="_blank">
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