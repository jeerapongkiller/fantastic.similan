<?php
$type = !empty($_POST['action']) ? 'POST' : 'GET';
$action = empty($_POST['action']) ? !empty($_GET['action']) ? $_GET['action'] : 0 : $_POST['action'];
$id = empty($_POST['id']) ? !empty($_GET['id']) ? $_GET['id'] : 0 : $_POST['id'];

$env_contro = $action == "preview" && $type == 'POST' ? '../../config/env.php' : 'config/env.php';
$inc_contro = $action == "preview" && $type == 'POST' ? '../../controllers/Quotation.php' : 'controllers/Quotation.php';
include_once($env_contro);
include_once($inc_contro);

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

$quotObj = new Quotation();
$today = date("Y-m-d");

if (!empty($id) && $id > 0) {
    $quotation = $quotObj->get_data($id);
    $details = $quotObj->get_datas('*', 'quotation_detail', 'quotation_id = ' . $quotation['id']);
    $bank = $quotObj->get_datas('bank_account.*, banks.name as bank_name', 'bank_account LEFT JOIN banks ON banks.id = bank_account.bank_id', 'bank_account.id = ' . $quotation['bank_id']);
} else {
    $quotation = [];
    $details = [];
}
?>
<section class="invoice-preview-wrapper" id="invoice-preview-wrapper">
    <div class="row invoice-preview">
        <!-- Invoice -->
        <div class="col-12" style="color: #000; background-color: #FFFFFF;">
            <div class="invoice-preview-card">
                <div class="card-body invoice-padding pb-0">
                    <!-- Header starts -->
                    <div class="row">
                        <div class="col-6">
                            <div class="logo-wrapper mb-50">
                                <img src="app-assets/images/logo/logo-500.png" height="80">
                            </div>
                            <p class="card-text mb-25">Fantastic Similan Travel</p>
                            <p class="card-text mb-25">26/74 หมู่ 7 ตำบลคึกคัก อำเภอตะกั่วป่า จ.พังงา 82220</p>
                            <p class="card-text mb-25">เบอร์มือถือ 0613851000</p>
                            <p class="card-text mb-25">https://www.facebook.com/Fantasticsimilan</p>
                            <p class="card-text mb-25 text-warning font-weight-bolder">ลูกค้า</p>
                            <p class="card-text mb-25 font-weight-bold"><?php echo $quotation['cus_name']; ?></p>
                        </div>
                        <div class="col-6">
                            <h2 class="text-right mb-50 text-warning font-weight-bolder"><?php echo $quotation['title'] == 1 ? 'ใบเสนอราคา' : 'ใบแจ้งหนี้'; ?></h2>
                            <hr>
                            <dl class="row mb-25">
                                <dt class="col-sm-8 text-right text-warning font-weight-bolder">เลขที่:</dt>
                                <dd class="col-sm-4"><?php echo $quotation['quo_full']; ?></dd>
                            </dl>
                            <dl class="row mb-25">
                                <dt class="col-sm-8 text-right text-warning font-weight-bolder">วันที่:</dt>
                                <dd class="col-sm-4"><?php echo date('j F Y', strtotime($quotation['date_quo'])); ?></dd>
                            </dl>
                            <dl class="row mb-25">
                                <dt class="col-sm-8 text-right text-warning font-weight-bolder">ผู้ขาย:</dt>
                                <dd class="col-sm-4"><?php echo $quotation['seller']; ?></dd>
                            </dl>
                            <hr>
                            <dl class="row mb-25">
                                <dt class="col-sm-8 text-right text-warning font-weight-bolder">ชื่องาน:</dt>
                                <dd class="col-sm-4"><?php echo $quotation['name']; ?></dd>
                            </dl>
                        </div>
                    </div>
                    <!-- Header ends -->
                </div>

                <!-- Invoice Description starts -->
                <div class="table-responsive">
                    <table class="tableprint w-100">
                        <thead style="background-color: #4B4B4B !important; color: #FFF !important;">
                            <tr>
                                <th class="py-1 text-center">#</th>
                                <th class="py-1">รายละเอียด</th>
                                <th class="py-1">จำนวน</th>
                                <th class="py-1">ราคาต่อหน่วย</th>
                                <th class="py-1">ส่วนลด</th>
                                <th class="py-1">ยอดรวม</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($details)) {
                                $amount = 0;
                                $discount = 0;
                                $no = 1;
                                foreach ($details as $detail) {
                                    $discount += $detail['discount'];
                                    $amount += $detail['qty'] * $detail['cost'];
                            ?>
                                    <tr class="border-bottom">
                                        <td class="text-center py-1" style="vertical-align: top;"><?php echo $no; ?></td>
                                        <td class="py-1">
                                            <p class="card-text">
                                                <?php echo $detail['name']; ?> <br>
                                                <?php echo nl2br($detail['detail']); ?>
                                            </p>
                                        </td>
                                        <td class="py-1" style="vertical-align: top;">
                                            <span class="font-weight-bold"><?php echo number_format($detail['qty']); ?></span>
                                        </td>
                                        <td class="py-1" style="vertical-align: top;">
                                            <span class="font-weight-bold"><?php echo number_format($detail['cost']); ?></span>
                                        </td>
                                        <td class="py-1" style="vertical-align: top;">
                                            <span class="font-weight-bold"><?php echo number_format($detail['discount']); ?></span>
                                        </td>
                                        <td class="py-1" style="vertical-align: top;">
                                            <span class="font-weight-bold"><?php echo number_format(($detail['qty'] * $detail['cost']) - $detail['discount']); ?></span>
                                        </td>
                                    </tr>
                            <?php $no++;
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>

                <div class="row p-2">
                    <div class="col-6">
                        <h5 class="font-weight-bold mb-0"><em><b>(<?php echo bahtText($amount - $discount) ?>)</b></em></h5>
                    </div>
                    <div class="col-6">
                        <dl class="row mb-25 text-right">
                            <dt class="col-sm-10 text-warning font-weight-bolder">รวมเป็นเงิน:</dt>
                            <dd class="col-sm-2 font-weight-bold"><?php echo number_format($amount); ?></dd>
                        </dl>
                        <dl class="row mb-25 text-right">
                            <dt class="col-sm-10 text-warning font-weight-bolder">ส่วนลด:</dt>
                            <dd class="col-sm-2 font-weight-bold"><?php echo number_format($discount); ?></dd>
                        </dl>
                        <hr>
                        <dl class="row mb-25 text-right">
                            <dt class="col-sm-10 text-warning font-weight-bolder">จำนวนเงินรวมทั้งสิ้น:</dt>
                            <dd class="col-sm-2 font-weight-bold"><?php echo number_format($amount - $discount); ?></dd>
                        </dl>
                    </div>
                </div>
                <!-- Invoice Description ends -->

                <div class="card-body invoice-padding pb-0">
                    <div class="row invoice-sales-total-wrapper">
                        <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">
                            <span class="text-warning font-weight-bolder">หมายเหตุ</span>
                            <br>
                            <?php 
                            echo 'กรณีทำการจอง กรุณาชำระเงินผ่านธนาคาร ' . $bank[0]['bank_name'] . ' <br>
                            หมยเลขบัญชี ' . $bank[0]['account_no'] . ' <br>
                            ชื่อบัญชี ' . $bank[0]['account_name'] . ' เท่านั้น'; ?>
                        </div>
                        <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3 text-right">
                            <span>ในนาม Fantastic Similan Travel</span>
                        </div>
                    </div>
                </div>

                <hr class="invoice-spacing" />

                <!-- Invoice Note starts -->
                <div class="card-body invoice-padding pt-0">
                    <div class="row">
                        <div class="col-6">
                            <table width="100%" height="100px">
                                <tr class="table-content">
                                    <td class="table-content" align="center" valign="bottom" width="50%">
                                        _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ <br>
                                        ผู้สั่งซื้อสินค้า <br>
                                    </td>
                                    <td class="table-content" align="center" valign="bottom" width="50%">
                                        _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ <br>
                                        วันที่ <br>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-6">
                            <table width="100%" height="100px">
                                <tr class="table-content">
                                    <td class="table-content" align="center" valign="bottom" width="50%">
                                        <p class="mb-0">นายอภิชา เกตุสอาด</p>
                                        _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ <br>
                                        ผู้อนุมัติ <br>
                                    </td>
                                    <td class="table-content" align="center" valign="bottom" width="50%">
                                        _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ <br>
                                        วันที่ <br>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- quotation Note ends -->
            </div>
        </div>
        <!-- /quotation -->
    </div>
</section>

<div class="modal-footer d-flex justify-content-between <?php echo $type == 'GET' ? 'hidden' : ''; ?>">
    <a href="javascript:void(0);">
        <button type="button" class="btn btn-info text-left" value="image" onclick="download_image();">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-image" viewBox="0 0 16 16">
                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54A.505.505 0 0 1 1 12.5v-9a.5.5 0 0 1 .5-.5h13z" />
            </svg>
            Image
        </button>
    </a>
    <a href="./?pages=quotation/print&action=<?php echo $action; ?>&id=<?php echo $id; ?>" target="_blank">
        <button type="button" class="btn btn-info text-left" name="print" value="print">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
            </svg>
            Print
        </button>
    </a>
</div>

<input type="hidden" name="name_img" id="name_img" value="<?php echo $quotation['quo_full']; ?>">