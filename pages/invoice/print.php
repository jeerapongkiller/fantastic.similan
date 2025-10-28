<?php

$type = !empty($_POST['action']) ? 'POST' : 'GET';
$action = empty($_POST['action']) ? !empty($_GET['action']) ? $_GET['action'] : 0 : $_POST['action'];
$get_cover = empty($_POST['cover_id']) ? !empty($_GET['cover_id']) ? $_GET['cover_id'] : 0 : $_POST['cover_id'];
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

if (isset($action) && $action == "preview" && !empty($get_cover)) {
    $all_invocies = $invObj->fetch_all_invocie($inv_date = '', $inv_no = '', $get_cover);
    foreach ($all_invocies as $invocies) {
        $bo_id[] = $invocies['bo_id'];
    }
    $agent = $invObj->get_value(
        'companies.name as name, companies.tat_license as license, companies.telephone as telephone, companies.address as address',
        'bookings LEFT JOIN companies ON bookings.company_id = companies.id',
        'bookings.id = ' . $bo_id[0],
        0
    );
?>
    <style>
        .invoice-padding {
            background-color: #fff;
        }

        .invoice-padding .table-black td {
            color: #FFFFFF;
            background-color: <?php echo ($all_invocies[0]['vat_id'] > 0) ? '#004b0aff' : '#003285'; ?>;
            padding: 0.7rem;
        }

        .invoice-padding .table-black-2 td {
            color: #FFFFFF;
            background-color: <?php echo ($all_invocies[0]['vat_id'] > 0) ? '#00ce0aff' : '#0060ff'; ?>;
            padding: 0.5rem;
        }

        .invoice-padding .table-content td {
            border: 1px solid #333;
            font-size: 14px;
            color: #000;
            /* padding: 0.72rem 2rem */
            padding: 0.2rem 1rem
        }
    </style>
    <div class="card-body invoice-padding pb-0" id="invoice-preview-vertical">
        <!-- Header starts -->
        <div class="row">
            <div class="col-6">
                <span class="brand-logo"><img src="app-assets/images/logo/logo-500.png" height="120"></span>
            </div>
            <div class="col-6 text-right mt-md-0 mt-2">
                <span style="color: #000;">
                    <?php echo $main_document; ?>
                </span>
                <table width="100%" class="mt-50" hidden>
                    <tr class="table-content">
                        <th rowspan="2" class="text-center" bgcolor="#003285" style="color: #fff; border-radius: 15px 0px 0px 0px;">
                            ใบแจ้งหนี้ / INVOICE
                        </th>
                        <td class="text-center">
                            INVOICE NO.
                        </td>
                    </tr>
                    <tr class="table-content">
                        <td class="text-center">
                            <?php echo $inv_full[0]; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- Header ends -->
        <table width="100%" class="mt-1">
            <tr class="table-content">
                <td class="p-0" width="34%" align="left" colspan="4">
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
                <td class="p-0" width="34%" align="left" colspan="2">
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-6 text-right">
                            Tax ID.
                        </dt>
                        <dd class="col-sm-6"><?php echo $agent['license']; ?></dd>
                    </dl>
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-6 text-right">
                            Departure Date
                        </dt>
                        <dd class="col-sm-6"><?php echo date('j F Y', strtotime($all_invocies[0]['inv_date'])); ?></dd>
                    </dl>
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-6 text-right">
                            Due Date
                        </dt>
                        <dd class="col-sm-6"><?php echo date('j F Y', strtotime($all_invocies[0]['rec_date'])); ?></dd>
                    </dl>
                </td>
                <td class="p-0" width="34%" align="left" colspan="2">
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-6 text-right">
                            INVOICE NO.
                        </dt>
                        <dd class="col-sm-6"><?php echo $all_invocies[0]['inv_full']; ?></dd>
                    </dl>
                </td>
            </tr>
        </table>

        <table width="100%" class="mt-1">
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
                                    <tr class="table-content">
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
                                    <tr class="table-content">
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
                                <tr class="table-content">
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
                </tbody>
            <?php }

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

            <tr class="table-content">
                <td class="text-center" colspan="9"><em><b><?php echo bahtText($total_cal) ?></b></em></td>
                <td class="text-center" colspan="4">
                    <dl class="row" style="margin-bottom: 0;">
                        <dt class="col-sm-8 text-right">
                            <b>ยอดรวม : </b>
                            <p style="font-size: 10px; margin-bottom: 2px;">(Total)</p>
                        </dt>
                        <dd class="col-sm-4 mt-50 text-nowrap">฿ <?php echo number_format($amount); ?></dd>
                    </dl>
                </td>
            </tr>

            <tr class="table-content">
                <td colspan="9" rowspan="5">
                    <b>หมายเหตุและเงื่อนใข (Terms & Conditions)</b><br>
                    <p>
                        <?php echo !empty($all_invocies[0]['account_name']) ? '</br><b>ชื่อบัญชี</b> ' . $all_invocies[0]['account_name'] . '</br><b>เลขที่บัญชี</b> ' . $all_invocies[0]['account_no'] . '</br><b>ธนาคาร</b> ' . $all_invocies[0]['bank_name'] : ''; ?>
                    </p>
                    <p>
                        <?php echo $all_invocies[0]['note']; ?>
                    </p>
                </td>
                <?php if (!empty($discount)) { ?>
                    <td class="table-content text-center" colspan="4">
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

            <?php if (!empty($cot)) { ?>
                <tr class="table-content">
                    <td class="text-center" colspan="4">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-8 text-right">
                                <b>Cash on tour :</b>
                            </dt>
                            <dd class="col-sm-4 mt-50 text-nowrap">฿ <?php echo number_format($cot); ?></dd>
                        </dl>
                    </td>
                </tr>
            <?php } ?>

            <?php if ($all_invocies[0]['vat_id'] > 0) { ?>
                <tr class="table-content">
                    <td class="text-center" colspan="4">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-8 text-right">
                                <b> <?php echo $all_invocies[0]['vat_id'] != '-' ? $all_invocies[0]['vat_id'] == 1 ? 'รวมภาษี 7%' : 'แยกภาษี 7%' : '-' ?> : </b>
                                <p style="font-size: 10px; margin-bottom: 2px;">(Tax)</p>
                            </dt>
                            <dd class="col-sm-4 mt-50 text-nowrap">฿ <?php echo number_format($vat_total, 2); ?></dd>
                        </dl>
                    </td>
                </tr>
            <?php } ?>

            <?php if ($all_invocies[0]['withholding'] > 0) { ?>
                <tr class="table-content">
                    <td class="text-center" colspan="4">
                        <dl class="row" style="margin-bottom: 0;">
                            <dt class="col-sm-8 text-right">
                                <b> หัก ณ ที่จ่าย (<?php echo $all_invocies[0]['withholding']; ?>%) : </b>
                                <p style="font-size: 10px; margin-bottom: 2px;">(Withholding Tax)</p>
                            </dt>
                            <dd class="col-sm-4 mt-50 text-nowrap">฿ <?php echo number_format($withholding_total, 2); ?></dd>
                        </dl>
                    </td>
                </tr>
            <?php } ?>

            <tr class="table-content">
                <!-- <td colspan="9"></td> -->
                <td class="text-center" style="color: #fff; background-color: <?php echo ($all_invocies[0]['vat_id'] > 0) ? '#004b0aff' : '#003285'; ?>;" colspan="4">
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
                    <tr class="table-content">
                        <td class="table-content" align="center" valign="bottom" width="35%">
                            _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ <br>
                            ผู้รับวางบิล / Receiver Signature <br>
                            วันที่ / Date _ _ _ _ _ _ _ _ _ _ _ _ _ _
                        </td>
                        <td class="table-content" align="center" valign="center" width="30%" style="font-weight: bold; font-size: 24px; color: rgba(0, 0, 0, 0.5);">
                            <span class="brand-logo"><img src="app-assets/images/logo/logo-500.png" height="100"></span>
                        </td>
                        <td class="table-content" align="center" valign="bottom" width="35%">
                            <span class="text-center h3 text-black">KWAN</span><br>
                            _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ <br>
                            ผู้มีอำนาจลงนาม / Authorized Signature <br>
                            วันที่ / Date _ _ _ <?php echo date('j F Y', strtotime($all_invocies[0]['rec_date'])); ?> _ _ _
                        </td>
                    </tr>
                </table>
            </tr>
        </table>

        <div class="p-1"> </div>
    </div>
    <div class="modal-footer d-flex justify-content-between hidden <?php // echo $type == 'GET' ? 'hidden' : ''; 
                                                                    ?>">
        <div>
            <a href="javascript:void(0);" onclick="modal_invoice(<?php echo $get_cover; ?>);">
                <button type="button" class="btn btn-primary text-right">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                    </svg>
                    Edit
                </button>
            </a>

            <button type="button" class="btn btn-primary btn-page-block-spinner text-right" data-toggle="modal" data-target="#modal-add-receipt" onclick="modal_receipt(<?php echo $get_cover; ?>, <?php echo $comp_id[0]; ?>);">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                </svg>
                Receipt
            </button>
        </div>
        <div>
            <a href="./?pages=invoice/print&action=<?php echo $action; ?>&cover_id=<?php echo $get_cover; ?>" target="_blank">
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
    </div>
    <input type="hidden" name="name_img" id="name_img" value="<?php echo 'Invoice-' . $inv_full[0]; ?>">
<?php } ?>