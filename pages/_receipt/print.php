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
    $all_recripts = $recObj->fetch_all_recripts($id);
    $all_bookings = $recObj->fetch_all_bookings($id);

    $agent = $recObj->get_value(
        'companies.name as name, companies.tat_license as license, companies.telephone as telephone, companies.address as address',
        'bookings LEFT JOIN companies ON bookings.company_id = companies.id',
        'bookings.id = ' . $all_bookings[0]['id'],
        0
    );
?>
    <style>
        .default-td td {
            border: 1px solid #333;
            font-size: 14px;
            color: #000;
            padding: 5px 5px;
        }

        .default {
            border: 1px solid #333;
            font-size: 14px;
            color: #000;
            padding: 5px 5px;
        }

        #receipt-preview-vertical {
            background-color: #fff;
        }

        #receipt-preview-vertical .table-black td {
            background-color: <?php echo ($all_bookings[0]['vat'] > 0) ? '#960007ff' : '#003285'; ?>;
            color: #fff;
            padding: 10px 0;
        }

        #receipt-preview-vertical .table-black-2 td {
            background-color: <?php echo ($all_bookings[0]['vat'] > 0) ? '#ff3f49ff' : '#0060ff'; ?>;
            color: #fff;
            padding: 5px 0;
        }
    </style>
    <div class="card-body receipt-padding pb-0" id="receipt-preview-vertical">
        <!-- Header starts -->
        <div class="row">
            <div class="col-6">
                <span class="brand-logo"><img src="app-assets/images/logo/logo-500.png" height="120"></span>
            </div>
            <div class="col-6 text-right mt-md-0 mt-2">
                <span style="color: #000;">
                    <?php echo $main_document; ?>
                </span>
                <table width="100%" class="mt-50">
                    <tr>
                        <td rowspan="2" class="text-center" bgcolor="<?php echo ($all_bookings[0]['vat'] > 0) ? '#960007' : '#003285'; ?>" style="color: #fff; border-radius: 15px 0px 0px 0px;">
                            <?php echo ($all_bookings[0]['vat'] > 0) ? 'ใบเสร็จรับเงิน / ใบกำกับภาษี <br> RECEIPT / TAX INVOICE' : 'ใบเสร็จรับเงิน <br> RECEIPT'; ?>
                        </td>
                        <td class="default text-center">
                            เลขใบเสร็จรับเงิน
                        </td>
                    </tr>
                    <tr class="default-td">
                        <td class="text-center">
                            <?php echo $all_recripts[0]['rec_full']; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <table width="100%" class="mt-1">
            <tr class="default-td">
                <td width="34%" align="left" colspan="4">
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
                </td>
                <td align="left" colspan="2">
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-6 text-right">
                            Tax ID.
                        </dt>
                        <dd class="col-sm-6"><?php echo $agent['license']; ?></dd>
                    </dl>
                    <!-- <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-6 text-right">
                            สำนักงาน
                        </dt>
                        <dd class="col-sm-6"><?php // echo $brch_name[0]; ?></dd>
                    </dl> -->
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-6 text-right">
                            PAID Date
                        </dt>
                        <dd class="col-sm-6"><?php echo date('j F Y', strtotime($all_recripts[0]['rec_date'])); ?></dd>
                    </dl>
                </td>
                <td width="34%" align="left" colspan="2">
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-4 text-right">
                            การชำระเงิน
                        </dt>
                        <dd class="col-sm-8"><?php echo $all_recripts[0]['payt_name']; ?></dd>
                    </dl>
                    <?php if ($all_recripts[0]['payt_id'] == 4) { ?>
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                เลขบัญชี
                            </dt>
                            <dd class="col-sm-8"><?php echo $all_recripts[0]['account_name'] . ' (' . $all_recripts[0]['account_no'] . ')'; ?></dd>
                        </dl>
                    <?php } elseif ($all_recripts[0]['payt_id'] == 5) { ?>
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                เลขที่เช็ค/ธนาคาร
                            </dt>
                            <dd class="col-sm-8"><?php echo $all_recripts[0]['cheque_no'] . ' / ' . $all_recripts[0]['bank_name']; ?></dd>
                        </dl>
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-4 text-right">
                                วันที่เช็ค
                            </dt>
                            <dd class="col-sm-8"><?php echo date('j F Y', strtotime($all_recripts[0]['cheque_date'])); ?></dd>
                        </dl>
                    <?php } ?>
                </td>
            </tr>
        </table>

        <table width="100%" class="mt-1">
            <tr class="table-black">
                <td class="text-center" style="border-radius: 15px 0px 0px 0px; padding: 5px 0;" width="3%"><b>เลขที่</b></td>
                <td class="text-center"><b>เลขใบแจ้งหนี้</b></td>
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
                <td class="text-center p-50"><small>Invoice No.</small></td>
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
            </tr>
            <?php if (!empty($all_bookings)) { ?>
                <tbody>
                    <?php
                    $discount = 0;
                    $cot = 0;
                    $amount = 0;
                    $i = 1;
                    $bo_arr = array();
                    foreach ($all_bookings as $bookings) {
                        $all_rates = $recObj->fetch_all_rates($bookings['id']);
                        $rates_adult = 0;
                        foreach ($all_rates as $rates) {
                            if ($bookings['booking_type_id'] == 1) {
                                $rates_adult += $rates['adult'] * $rates['rates_adult'];
                                $rates_adult += $rates['child'] * $rates['rates_child'];
                            } elseif ($bookings['booking_type_id'] == 2) {
                                $rates_adult += $rates['rates_private'];
                            }
                            $amount += $rates_adult;
                            if (in_array($bookings['id'], $bo_arr) == false) {
                                $bo_arr[] = $bookings['id'];
                                $discount += $bookings['discount'];
                                $cot += $bookings['cot'];
                                $check_bpr = $recObj->get_value('id', 'booking_product_rates', 'booking_products_id = ' . $bookings['bp_id'], 1);
                    ?>
                                <tr class="default-td">
                                    <td class="text-center" rowspan="<?php echo count($check_bpr); ?>"><?php echo $i++; ?></td>
                                    <td class="text-center" rowspan="<?php echo count($check_bpr); ?>"><?php echo $bookings['inv_full']; ?></td>
                                    <td class="text-center" rowspan="<?php echo count($check_bpr); ?>"><?php echo date('j F Y', strtotime($bookings['travel_date'])); ?></td>
                                    <td rowspan="<?php echo count($check_bpr); ?>"><?php echo $bookings['cus_name']; ?></td>
                                    <td rowspan="<?php echo count($check_bpr); ?>"><?php echo $bookings['product_name']; ?></td>
                                    <td class="text-center" rowspan="<?php echo count($check_bpr); ?>"><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['bo_full']; ?> </td>

                                    <td class="text-center cell-fit"><?php echo $rates['category_name']; ?></td>
                                    <td class="text-center cell-fit"><?php echo $rates['adult']; ?></td>
                                    <td class="text-center cell-fit"><?php echo $rates['child']; ?></td>
                                    <td class="text-center cell-fit"><?php echo number_format($rates['rates_adult'], 2); ?></td>
                                    <td class="text-center cell-fit"><?php echo number_format($rates['rates_child'], 2); ?></td>
                                    <td class="text-center cell-fit"><?php echo number_format($rates_adult, 2); ?></td>

                                    <td class="text-center cell-fit" rowspan="<?php echo count($check_bpr); ?>"><?php echo !empty($bookings['discount']) ? number_format($bookings['discount'], 2) : '-'; ?></td>
                                    <td class="text-center cell-fit" rowspan="<?php echo count($check_bpr); ?>"><?php echo !empty($bookings['cot']) > 0 ?  number_format($bookings['cot'], 2) : '-'; ?></td>
                                </tr>
                            <?php } else { ?>
                                <tr class="default-td">
                                    <td class="text-center cell-fit"><?php echo $rates['category_name']; ?></td>
                                    <td class="text-center cell-fit"><?php echo $rates['adult']; ?></td>
                                    <td class="text-center cell-fit"><?php echo $rates['child']; ?></td>
                                    <td class="text-center cell-fit"><?php echo number_format($rates['rates_adult'], 2); ?></td>
                                    <td class="text-center cell-fit"><?php echo number_format($rates['rates_child'], 2); ?></td>
                                    <td class="text-center cell-fit"><?php echo number_format($rates_adult, 2); ?></td>
                                </tr>
                        <?php }
                        }
                        ?>

                        <?php
                        $e = 0;
                        $amount_extar = 0;
                        $extra_charges = $recObj->get_extra_charge($bookings['id']);
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
                                <tr class="default-td">
                                    <td class="text-left" colspan="6"><?php echo !empty($extra_charge['extra_name']) ? $extra_charge['extra_name'] : $extra_charge['name']; ?></td>
                                    <td></td>
                                    <td class="text-center"><?php echo $extra_charge['type'] == 1 ? $extra_charge['adult'] : $extra_charge['privates']; ?></td>
                                    <td class="text-center"><?php echo $extra_charge['type'] == 1 ? $extra_charge['child'] : ''; ?></td>
                                    <td class="text-center"><?php echo $extra_charge['type'] == 1 ? number_format($extra_charge['rate_adult'], 2) : ''; ?></td>
                                    <td class="text-center"><?php echo $extra_charge['type'] == 1 ? number_format($extra_charge['rate_child'], 2) : ''; ?></td>
                                    <td class="text-center"><?php echo number_format($amount_extar, 2); ?></td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                </tr>
                        <?php
                            }
                        }
                        ?>

                    <?php } ?>
                </tbody>
            <?php } ?>

            <?php
            $total_cal = $amount;
            if ($all_bookings[0]['vat'] == 1) {
                $vat_total = $total_cal * 100 / 107;
                $vat_cut = $vat_total;
                $vat_total = $total_cal - $vat_total;
                $withholding_total = $all_bookings[0]['withholding'] > 0 ? ($vat_cut * $all_bookings[0]['withholding']) / 100 : 0;
                $total_cal = $total_cal - $withholding_total;
            } elseif ($all_bookings[0]['vat'] == 2) {
                $vat_total = ($total_cal * 7) / 100;
                $total_cal = $total_cal + $vat_total;
                $withholding_total = $all_bookings[0]['withholding'] > 0 ? ($total_cal - $vat_total) * $all_bookings[0]['withholding'] / 100 : 0;
                $total_cal = $total_cal - $withholding_total;
            }
            $total_cal = !empty($discount) ? $total_cal - $discount : $total_cal;
            $total_cal = !empty($cot) ? $total_cal - $cot : $total_cal;
            ?>

            <tr class="default-td">
                <td class="text-center" colspan="9"><em><b><?php echo bahtText($total_cal) ?></b></em></td>
                <td class="text-center" colspan="5">
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-8 text-right">
                            <b>ยอดรวม : </b>
                            <p style="font-size: 10px; margin-bottom: 2px;">(Total)</p>
                        </dt>
                        <dd class="col-sm-4 mt-50 text-nowrap">฿ <?php echo number_format($amount, 2); ?></dd>
                    </dl>
                </td>
            </tr>

            <?php
            // $amount = !empty($discount) ? $amount - array_sum($discount) : $amount;
            // $amount = !empty($cot) ? $amount - array_sum($cot) : $amount;
            ?>

            <tr class="default-td">
                <td colspan="9" rowspan="5">
                    <b>หมายเหตุและเงื่อนใข (Terms & Conditions)</b><br>
                    <p>
                        <?php echo !empty($all_bookings[0]['account_name']) ? '</br><b>ชื่อบัญชี</b> ' . $all_bookings[0]['account_name'] . '</br><b>เลขที่บัญชี</b> ' . $all_bookings[0]['account_no'] . '</br><b>ธนาคาร</b> ' . $all_bookings[0]['bank_name'] : ''; ?>
                    </p>
                    <p>
                        <?php echo $all_recripts[0]['note']; ?>
                    </p>
                </td>
                <?php if (!empty($discount)) { ?>
                    <td class="table-content text-center" colspan="5">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-8 text-right">
                                <b> ส่วนลด : </b>
                                <p style="font-size: 10px; margin-bottom: 2px;">(Discount)</p>
                            </dt>
                            <dd class="col-sm-4 mt-50 text-nowrap">฿ <?php echo number_format($discount, 2); ?></dd>
                        </dl>
                    </td>
                <?php } ?>
            </tr>

            <?php if (!empty($cot)) { ?>
                <tr class="default-td">
                    <td class="text-center" colspan="5">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-8 text-right">
                                <b>Cash on tour :</b>
                            </dt>
                            <dd class="col-sm-4 mt-50 text-nowrap">฿ <?php echo number_format($cot, 2); ?></dd>
                        </dl>
                    </td>
                </tr>
            <?php } ?>

            <?php if ($all_bookings[0]['vat'] > 0) { ?>
                <tr class="default-td">
                    <td class="text-center" colspan="5">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-8 text-right">
                                <b> <?php echo $all_bookings[0]['vat'] != '-' ? $all_bookings[0]['vat'] == 1 ? 'รวมภาษี 7%' : 'แยกภาษี 7%' : '-' ?> : </b>
                                <p style="font-size: 10px; margin-bottom: 2px;">(Tax)</p>
                            </dt>
                            <dd class="col-sm-4 mt-50 text-nowrap">฿ <?php echo number_format($vat_total, 2); ?></dd>
                        </dl>
                    </td>
                </tr>
            <?php } ?>

            <?php if ($all_bookings[0]['withholding'] > 0) { ?>
                <tr class="default-td">
                    <td class="text-center" colspan="5">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-8 text-right">
                                <b> หัก ณ ที่จ่าย (<?php echo $all_bookings[0]['withholding']; ?>%) : </b>
                                <p style="font-size: 10px; margin-bottom: 2px;">(Withholding Tax)</p>
                            </dt>
                            <dd class="col-sm-4 mt-50 text-nowrap">฿ <?php echo number_format($withholding_total, 2); ?></dd>
                        </dl>
                    </td>
                </tr>
            <?php } ?>

            <tr class="default-td">
                <td class="text-center" bgcolor="<?php echo ($all_bookings[0]['vat'] > 0) ? '#960007' : '#003285'; ?>" style="color: #fff;" colspan="5">
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-8 text-right">
                            <b>ยอดชำระ : </b>
                            <p style="font-size: 10px; margin-bottom: 2px;">(Payment Amount)</p>
                        </dt>
                        <dd class="col-sm-4 mt-50 text-nowrap"><b>฿ <?php echo number_format($total_cal, 2); ?></b></dd>
                    </dl>
                </td>
            </tr>
        </table>

        <table width="100%" class="mt-1">
            <tr>
                <table width="100%" height="150px">
                    <tr class="default-td">
                        <td align="center" valign="bottom" width="35%">
                            _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ <br>
                            ผู้รับวางบิล / Receiver Signature <br>
                            วันที่ / Date _ _ _ _ _ _ _ _ _ _ _ _ _ _
                        </td>
                        <td align="center" valign="center" width="30%" style="font-weight: bold; font-size: 24px; color: rgba(0, 0, 0, 0.5);">
                            ตราประทับบริษัท
                        </td>
                        <td align="center" valign="bottom" width="35%">
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
    <!-- <div class="modal-footer d-flex justify-content-between <?php echo $type == 'GET' ? 'hidden' : '';  ?>" id="btn-page" hidden>
        <div>
            <a href="javascript:void(0);" onclick="modal_receipt(<?php echo $id; ?>);">
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
                <button type="button" class="btn btn-info text-left" name="print" value="print"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                        <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                        <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                    </svg>
                    Print
                </button>
            </a>
            <a href="javascript:void(0);">
                <button type="button" class="btn btn-info text-left" value="image" onclick="download_image();"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-image" viewBox="0 0 16 16">
                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                        <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54A.505.505 0 0 1 1 12.5v-9a.5.5 0 0 1 .5-.5h13z" />
                    </svg>
                    Image
                </button>
            </a>
        </div>
    </div> -->
    <input type="hidden" name="name_img" id="name_img" value="<?php echo $rec_full[0]; ?>">
<?php } ?>