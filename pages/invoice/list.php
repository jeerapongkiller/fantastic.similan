<?php
require_once 'controllers/Invoice.php';

$invObj = new Invoice();
$times = date("H:i:s");
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime(" +1 day"));
$yesterday = date("Y-m-d", strtotime("-1 day"));
$day7 = date("Y-m-d", strtotime("-7 day"));
$day15 = date("Y-m-d", strtotime("-15 day"));
$day30 = date("Y-m-d", strtotime("-30 day"));
// $today = '2025-03-01';
// $tomorrow = '2025-04-30';

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
?>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Invoice</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">รอวางบิล</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                <div class="form-group breadcrumb-right">
                    <button class="btn btn-success" data-toggle="modal" data-target="#modal-invoice" onclick="modal_invoice(0);"><i data-feather='plus'></i> New Invoice</button>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- Basic tabs start -->
            <section id="basic-tabs-components">
                <!-- invoice filter start -->
                <div class="card">
                    <h5 class="card-header">Search Filter</h5>
                    <form id="invoice-search-form" name="invoice-search-form" method="post" enctype="multipart/form-data">
                        <div class="d-flex align-items-center mx-50 row pt-0 pb-2">
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="search_type">ประเภท (Type)</label>
                                    <select class="form-control select2" id="search_type" name="search_type">
                                        <option value="all">All</option>
                                        <option value="overdue">Overdue</option>
                                        <option value="paid">Paid</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="search_period">วันที่เที่ยว (Travel Date)</label>
                                    <select class="form-control select2" id="search_period" name="search_period" onchange="custom_day();">
                                        <option value="all">All</option>
                                        <option value="today">Today</option>
                                        <option value="yesterday">Yesterday</option>
                                        <option value="last7days">Last 7 Days</option>
                                        <option value="last15days">Last 15 Days</option>
                                        <option value="last30days">Last 30 Days</option>
                                        <option value="custom">Custom</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="search_agent">Agents</label>
                                    <select class="form-control select2" id="search_agent" name="search_agent">
                                        <option value="all">All</option>
                                        <?php
                                        $agents = $invObj->showlistagent();
                                        foreach ($agents as $agent) {
                                        ?>
                                            <option value="<?php echo $agent['id']; ?>"
                                                data-name="<?php echo $agent['name']; ?>"
                                                data-address="<?php echo $agent['address']; ?>"
                                                data-telephone="<?php echo $agent['telephone']; ?>"
                                                data-tat="<?php echo $agent['tat_license']; ?>">
                                                <?php echo $agent['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="search_invoice">Invoice No #</label>
                                    <input type="text" class="form-control" id="search_invoice" name="search_invoice" />
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="search_booking">Booking No #</label>
                                    <input type="text" class="form-control" id="search_booking" name="search_booking" />
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="search_voucher">Voucher No #</label>
                                    <input type="text" class="form-control" id="search_voucher" name="search_voucher" />
                                </div>
                            </div>
                            <div class="col-md-3 col-12" id="div_search_travel" hidden>
                                <div class="form-group">
                                    <label class="form-label" for="search_travel">Travel Date</label>
                                    <input type="text" class="form-control" id="search_travel" name="search_travel" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <button type="submit" class="btn btn-primary" name="submit" value="submit"><i data-feather='search'></i> Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- invoice filter end -->

                <!-- invoice table start -->
                <div id="invoice-search-table">
                    <?php

                    $first_cover = array();
                    $first_company = array();
                    $first_booking = array();
                    $first_bpr = array();
                    $first_extar = array();
                    // $invoices = $invObj->showlist('all', 'all', '', '', '', '');
                    if (!empty($invoices)) {
                        foreach ($invoices as $invoice) {
                            # --- get value agent --- #
                            if (in_array($invoice['comp_id'], $first_company) == false && !empty($invoice['comp_id'])) {
                                $first_company[] = $invoice['comp_id'];
                                $agent_id[] = !empty($invoice['comp_id']) ? $invoice['comp_id'] : 0;
                                $agent_name[] = !empty($invoice['comp_name']) ? $invoice['comp_name'] : '';
                            }
                            # --- get value booking --- #
                            if (in_array($invoice['cover_id'], $first_cover) == false) {
                                $first_cover[] = $invoice['cover_id'];
                                $cover_id[$invoice['comp_id']][] = !empty($invoice['cover_id']) ? $invoice['cover_id'] : 0;
                                $rec_id[$invoice['comp_id']][] = !empty($invoice['rec_id']) ? $invoice['rec_id'] : 0;
                                $inv_full[$invoice['comp_id']][] = !empty($invoice['inv_full']) ? $invoice['inv_full'] : '';
                                $inv_date[$invoice['comp_id']][] = !empty($invoice['inv_date']) ? $invoice['inv_date'] : '0000-00-00';
                                $rec_date[$invoice['comp_id']][] = !empty($invoice['rec_date']) ? $invoice['rec_date'] : '0000-00-00';
                                $vat[$invoice['comp_id']][] = !empty($invoice['vat']) ? $invoice['vat'] : '-';
                                $withholding[$invoice['comp_id']][] = !empty($invoice['withholding']) ? $invoice['withholding'] : '-';
                                $due_date[$invoice['comp_id']][] = (diff_date($today, $invoice['rec_date'])['day'] > 0) ? '<span class="badge badge-pill badge-light-success text-capitalized">' . date("j F Y", strtotime($invoice['rec_date'])) . ' (ครบกำหนดชำระในอีก ' . diff_date($today, $invoice['rec_date'])['num'] . ' วัน)</span>' : '<span class="badge badge-pill badge-light-danger text-capitalized">' . date("j F Y", strtotime($invoice['rec_date'])) . ' (เกินกำหนดชำระมาแล้ว ' . diff_date($today, $invoice['rec_date'])['num'] . ' วัน)</span>';
                            }
                            # --- get value booking --- #
                            if (in_array($invoice['id'], $first_booking) == false) {
                                $first_booking[] = $invoice['id'];
                                $bo_id[$invoice['comp_id']][] = !empty($invoice['id']) ? $invoice['id'] : 0;
                                $bo_inv[$invoice['cover_id']][] = !empty($invoice['id']) ? $invoice['id'] : 0;
                                $cot[$invoice['comp_id']][] = !empty($invoice['total_paid']) ? $invoice['total_paid'] : 0;
                                $cot_comp[$invoice['comp_id']][] = !empty($invoice['total_paid']) ? $invoice['total_paid'] : 0;
                                $cot_inv[$invoice['cover_id']][] = !empty($invoice['total_paid']) ? $invoice['total_paid'] : 0;
                            }
                            # --- get value rates --- #
                            if ((in_array($invoice['bpr_id'], $first_bpr) == false) && !empty($invoice['bpr_id'])) {
                                $first_bpr[] = $invoice['bpr_id'];
                                $bpr_id[$invoice['comp_id']][] = !empty($invoice['bpr_id']) ? $invoice['bpr_id'] : 0;
                                $category_id[$invoice['comp_id']][] = !empty($invoice['category_id']) ? $invoice['category_id'] : 0;
                                $category_name[$invoice['comp_id']][] = !empty($invoice['category_name']) ? $invoice['category_name'] : 0;
                                $category_cus[$invoice['comp_id']][] = !empty($invoice['category_cus']) ? $invoice['category_cus'] : 0;
                                $adult[$invoice['comp_id']][] = !empty($invoice['bpr_adult']) ? $invoice['bpr_adult'] : 0;
                                $child[$invoice['comp_id']][] = !empty($invoice['bpr_child']) ? $invoice['bpr_child'] : 0;
                                $infant[$invoice['comp_id']][] = !empty($invoice['bpr_infant']) ? $invoice['bpr_infant'] : 0;
                                $foc[$invoice['comp_id']][] = !empty($invoice['bpr_foc']) ? $invoice['bpr_foc'] : 0;
                                $tourrist[$invoice['comp_id']][] = $invoice['bpr_adult'] + $invoice['bpr_child'] + $invoice['bpr_infant'] + $invoice['bpr_foc'];
                                $total_comp[$invoice['comp_id']][] = $invoice['bp_private_type'] == 1 ? ($invoice['booksta_id'] != 2 && $invoice['booksta_id'] != 4) ? ($invoice['bpr_adult'] * $invoice['rate_adult']) + ($invoice['bpr_child'] * $invoice['rate_child']) : $invoice['rate_total'] : $invoice['rate_total'];
                                $total_inv[$invoice['cover_id']][] = $invoice['bp_private_type'] == 1 ? ($invoice['booksta_id'] != 2 && $invoice['booksta_id'] != 4) ? ($invoice['bpr_adult'] * $invoice['rate_adult']) + ($invoice['bpr_child'] * $invoice['rate_child']) : $invoice['rate_total'] : $invoice['rate_total'];
                            }
                            # --- get value booking --- #
                            if (in_array($invoice['bec_id'], $first_extar) == false && (!empty($invoice['extra_id']) || !empty($invoice['bec_name']))) {
                                $first_extar[] = $invoice['bec_id'];
                                $extar['agent'][$invoice['comp_id']][] = $invoice['bec_type'] == 1 ? ($invoice['bec_adult'] * $invoice['bec_rate_adult']) + ($invoice['bec_child'] * $invoice['bec_rate_child']) : ($invoice['bec_privates'] * $invoice['bec_rate_private']);
                                $extar['inv'][$invoice['cover_id']][] = $invoice['bec_type'] == 1 ? ($invoice['bec_adult'] * $invoice['bec_rate_adult']) + ($invoice['bec_child'] * $invoice['bec_rate_child']) : ($invoice['bec_privates'] * $invoice['bec_rate_private']);
                            }
                        }
                    }
                    if (!empty($agent_id)) {
                        for ($i = 0; $i < count($agent_id); $i++) {
                    ?>
                            <div class="card" id="basic-table">
                                <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                                    <div class="col-lg-12 col-xl-12 text-center text-bold h4"><?php echo $agent_name[$i]; ?></div>
                                </div>
                                <table class="table table-bordered table-striped table-vouchure-t2">
                                    <thead class="thead-light">
                                        <tr class="table-warning">
                                            <td colspan="9">
                                                <dl class="row text-center m-0">
                                                    <dt class="col-sm-3 m-0">Invoice <b><?php echo !empty($cover_id[$agent_id[$i]]) ? count($cover_id[$agent_id[$i]]) : 0; ?></b></dt>
                                                    <dt class="col-sm-3 m-0">Booking <b><?php echo !empty($bo_id[$agent_id[$i]]) ? count($bo_id[$agent_id[$i]]) : 0; ?></b></dt>
                                                    <dt class="col-sm-2 m-0">ยอดรวม <b><?php echo !empty($total_comp[$agent_id[$i]]) ? !empty($extar['agent'][$agent_id[$i]]) ? number_format((array_sum($total_comp[$agent_id[$i]]) + array_sum($extar['agent'][$agent_id[$i]])), 2) : number_format(array_sum($total_comp[$agent_id[$i]]), 2) : '-'; ?></b></dt>
                                                    <dt class="col-sm-2 m-0">COT <b><?php echo !empty($cot_comp[$agent_id[$i]]) ? number_format(array_sum($cot_comp[$agent_id[$i]]), 2) : '-'; ?></b></dt>
                                                    <dt class="col-sm-2 m-0">ยอดชำระ <b><?php echo !empty($total_comp[$agent_id[$i]]) ? !empty($extar['agent'][$agent_id[$i]]) ? number_format((array_sum($total_comp[$agent_id[$i]]) + array_sum($extar['agent'][$agent_id[$i]]) - array_sum($cot[$agent_id[$i]])), 2) : number_format(array_sum($total_comp[$agent_id[$i]]) - array_sum($cot[$agent_id[$i]]), 2) : '-'; ?></b></dt>
                                                </dl>
                                            </td>
                                        </tr>
                                        <tr>
                                            <!-- <th></th> -->
                                            <th>วันที่ออก INVOICE</th>
                                            <th>Invoice No.</th>
                                            <th>กำหนดรับชำระ</th>
                                            <th class="text-center cell-fit">Booking</th>
                                            <th class="text-center">ยอดรวม</th>
                                            <th class="text-center">COT</th>
                                            <th class="text-center">ยอดชำระ</th>
                                            <th class="cell-fit">รับชำระ</th>
                                            <th class="cell-fit">ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($cover_id[$agent_id[$i]])) {
                                            for ($a = 0; $a < count($cover_id[$agent_id[$i]]); $a++) {
                                                $modal = 'data-dismiss="modal" data-toggle="modal" data-target="#modal-invoice" onclick="modal_invoice(' . $cover_id[$agent_id[$i]][$a] . ');"';
                                        ?>
                                                <tr>
                                                    <td <?php echo $modal; ?>><?php echo date('j F Y', strtotime($inv_date[$agent_id[$i]][$a])); ?></td>
                                                    <td <?php echo $modal; ?>><?php echo $inv_full[$agent_id[$i]][$a]; ?></td>
                                                    <td <?php echo $modal; ?>><?php echo $rec_id[$agent_id[$i]][$a] == 0 ? $due_date[$agent_id[$i]][$a] : '<span class="badge badge-pill badge-light-success text-capitalized">ชำระเรียบร้อยแล้ว</span>'; ?></td>
                                                    <td <?php echo $modal; ?> class="text-center"><?php echo !empty($bo_inv[$cover_id[$agent_id[$i]][$a]]) ? count($bo_inv[$cover_id[$agent_id[$i]][$a]]) : '-'; ?></td>
                                                    <td <?php echo $modal; ?> class="text-center"><?php echo !empty($total_inv[$cover_id[$agent_id[$i]][$a]]) ? !empty($extar['inv'][$cover_id[$agent_id[$i]][$a]]) ? number_format((array_sum($total_inv[$cover_id[$agent_id[$i]][$a]]) + array_sum($extar['inv'][$cover_id[$agent_id[$i]][$a]])), 2) : number_format(array_sum($total_inv[$cover_id[$agent_id[$i]][$a]]), 2) : '-'; ?></td>
                                                    <td <?php echo $modal; ?> class="text-center"><?php echo !empty($cot_inv[$cover_id[$agent_id[$i]][$a]]) ? number_format(array_sum($cot_inv[$cover_id[$agent_id[$i]][$a]]), 2) : '-'; ?></td>
                                                    <td <?php echo $modal; ?> class="text-center" width="8%"><?php echo !empty($total_inv[$cover_id[$agent_id[$i]][$a]]) ? !empty($extar['inv'][$cover_id[$agent_id[$i]][$a]]) ? number_format((array_sum($total_inv[$cover_id[$agent_id[$i]][$a]]) + array_sum($extar['inv'][$cover_id[$agent_id[$i]][$a]]) - array_sum($cot_inv[$cover_id[$agent_id[$i]][$a]])), 2) : number_format(array_sum($total_inv[$cover_id[$agent_id[$i]][$a]]) - array_sum($cot_inv[$cover_id[$agent_id[$i]][$a]]), 2) : '-'; ?></td>
                                                    <td class="text-center"><button type="button" class="btn btn-sm btn-gradient-info" data-toggle="modal" data-target="#modal-receipt" onclick="modal_receipt(<?php echo $cover_id[$agent_id[$i]][$a]; ?>, <?php echo $agent_id[$i]; ?>)">ชำระ</button></td>
                                                    <td class="text-center"><a href="javascript:void(0)" onclick="deleteInvoice(<?php echo $cover_id[$agent_id[$i]][$a]; ?>);"><i data-feather='trash-2'></i></a></td>
                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
                <!-- invoice table end -->
            </section>
            <!-- Basic Tabs end -->
        </div>

        <!--------- Modal start ----------->
        <div class="modal fade text-left" id="modal-invoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="h4-title">เลือก Bookings</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="invoice-form" name="invoice-form" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div id="div-select-invoice">
                                <div class="row">
                                    <div class="form-group col-md-3 col-12">
                                        <label class="form-label" for="travel_date">วันที่เดินทาง (Travel Date)</label></br>
                                        <input type="text" class="form-control flatpickr-range" id="travel_date" name="travel_date" value="" onchange="modal_invoice();" />
                                    </div>
                                </div>
                                <div class="row" id="div-agent"></div>
                                <div id="div-booking"></div>
                            </div>
                            <div id="div-form-invoice"></div>
                            <div id="div-form-invoice-edit" hidden></div>
                            <div id="div-preview-invoice" hidden></div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <span>
                                <button type="button" class="btn btn-outline-secondary" id="btn-modal-previous" onclick="modal_page_invoice('previous');" hidden><i data-feather='arrow-left'></i> ก่อนหน้า</button>
                                <button type="button" class="btn btn-outline-secondary" id="btn-modal-preview-previous" onclick="modal_page_invoice('edit');" hidden><i data-feather='arrow-left'></i> ก่อนหน้า</button>
                                <button type="button" class="btn btn-gradient-warning" id="btn-modal-preview" onclick="modal_page_invoice('preview');" hidden><i data-feather='image'></i> Preview</button>
                            </span>
                            <span>
                                <a href="./?pages=invoice/print" id="btn-modal-print" hidden target="_blank">
                                    <button type="button" class="btn btn-gradient-warning"><i data-feather='printer'></i> Print</button>
                                </a>
                                <button type="button" class="btn btn-gradient-warning" id="btn-modal-image" onclick="download_image('invoice');" hidden><i data-feather='image'></i> Image</button>
                                <button type="button" class="btn btn-primary" id="btn-modal-next" onclick="modal_page_invoice('next');">ต่อไป <i data-feather='arrow-right'></i></button>
                                <button type="submit" class="btn btn-primary" id="btn-modal-submit" hidden><i data-feather='plus'></i> Submit</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal-size-xl d-inline-block">
            <div class="modal fade text-left" id="modal-receipt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Receipt</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="receipt-form" name="receipt-form" method="post" enctype="multipart/form-data">
                                <div id="div-receipt-form"></div>
                                <div id="div-receipt-preview" hidden></div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span>
                                        <button type="button" class="btn btn-outline-secondary" id="btn-modal-receipt-previous" onclick="modal_page_invoice('receipt-form');" hidden><i data-feather='arrow-left'></i> ก่อนหน้า</button>
                                        <button type="button" class="btn btn-gradient-warning" id="btn-modal-receipt-preview" onclick="modal_page_invoice('receipt-preview');" hidden><i data-feather='image'></i> Preview</button>
                                    </span>
                                    <span>
                                        <a href="./?pages=receipt/print" id="btn-modal-receipt-print" hidden target="_blank">
                                            <button type="button" class="btn btn-gradient-warning"><i data-feather='printer'></i> Print</button>
                                        </a>
                                        <button type="button" class="btn btn-gradient-warning" id="btn-modal-receipt-image" onclick="download_image('receipt');" hidden><i data-feather='image'></i> Image</button>
                                        <button type="submit" class="btn btn-primary" name="submit" id="btn-modal-receipt-submit"><i data-feather='plus'></i> Submit</button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------- Modal end ----------->

    </div>
</div>