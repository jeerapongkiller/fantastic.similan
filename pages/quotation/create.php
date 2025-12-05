<?php
require_once 'controllers/Quotation.php';

$quotObj = new Quotation();
?>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Quotations</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./?pages=quotation/list">Quotations List</a></li>
                                <li class="breadcrumb-item active">Create</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Validation -->
            <section class="invoice-add-wrapper">
                <div class="row">
                    <!-- jQuery Validation -->
                    <div class="col-md-12 col-12">
                        <div class="card">
                            <!-- <div class="card-header">
                                <h4 class="card-title">User</h4>
                            </div> -->
                            <div class="card-body">
                                <form id="quotation-create-form" name="quotation-create-form" method="post" enctype="multipart/form-data">
                                    <div class="row invoice-add">
                                        <!-- Quotation Add starts -->
                                        <div class="col-12">
                                            <div class="card invoice-preview-card">
                                                <!-- Header starts -->
                                                <div class="card-body invoice-padding pb-0">
                                                    <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                                                        <div>
                                                            <div class="logo-wrapper mb-50">
                                                                <img src="app-assets/images/logo/logo-500.png" height="100">
                                                            </div>
                                                            <p class="card-text mb-25">Fantastic Similan Travel</p>
                                                            <p class="card-text mb-25">26/74 หมู่ 7 ตำบลคึกคัก อำเภอตะกั่วป่า จ.พังงา 82220</p>
                                                            <p class="card-text mb-25">เบอร์มือถือ 0613851000</p>
                                                            <p class="card-text mb-1">https://www.facebook.com/Fantasticsimilan</p>
                                                            <div class="form-group mb-0">
                                                                <h6 class="form-label" for="cus_name">ลูกค้า</h6>
                                                                <input type="text" class="form-control" id="cus_name" name="cus_name" value="" />
                                                            </div>
                                                        </div>
                                                        <div class="invoice-number-date mt-md-0 mt-2">
                                                            <div class="d-flex align-items-center mb-1">
                                                                <select class="form-control select2-size-lg" id="title" name="title">
                                                                    <option value="1">ใบเสนอราคา</option>
                                                                    <option value="2">ใบแจ้งหนี้</option>
                                                                    <option value="3">ใบเสร็จรับเงิน</option>
                                                                </select>
                                                            </div>
                                                            <div class="d-flex align-items-center mb-1">
                                                                <span class="title">วันที่:</span>
                                                                <input type="text" class="form-control date-picker" id="date_quo" name="date_quo" />
                                                            </div>
                                                            <div class="d-flex align-items-center mb-1">
                                                                <span class="title">ผู้ขาย:</span>
                                                                <input type="text" class="form-control" id="seller" name="seller" />
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <span class="title">ชื่องาน:</span>
                                                                <input type="text" class="form-control" id="name" name="name" />
                                                            </div>
                                                            <div class="d-flex align-items-center mt-1">
                                                                <select class="form-control select2" id="bank_id" name="bank_id">
                                                                    <?php
                                                                    $banks = $quotObj->showbankaccount();
                                                                    foreach ($banks as $bank) {
                                                                    ?>
                                                                        <option value="<?php echo $bank['id']; ?>"><?php echo $bank['banName'] . ' ' . $bank['account_no'] . ' (' . $bank['account_name'] . ')'; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Header ends -->

                                                <hr class="invoice-spacing" />

                                                <!-- Product Details starts -->
                                                <div class="card-body invoice-padding invoice-product-details">
                                                    <div data-repeater-list="group-a">
                                                        <div class="repeater-wrapper" data-repeater-item>
                                                            <div class="row">
                                                                <div class="col-12 d-flex product-details-border position-relative pr-0">
                                                                    <div class="row w-100 pr-lg-0 pr-1 py-2">
                                                                        <div class="col-lg-1 col-12 my-lg-0 my-2">
                                                                            <b class="card-text col-title mb-md-50 mb-0">#</b>
                                                                            <b class="card-text number mb-0">1</b>
                                                                        </div>
                                                                        <div class="col-lg-5 col-12 mb-lg-0 mb-2 mt-lg-0 mt-2">
                                                                            <b class="card-text col-title mb-md-50 mb-0">รายละเอียด</b>
                                                                            <input type="text" class="form-control mb-25" name="name" value="" placeholder="ชื่อสินค้า" />
                                                                            <textarea class="form-control" name="detail" rows="1"></textarea>
                                                                        </div>
                                                                        <div class="col-lg-1 col-12 my-lg-0 my-2">
                                                                            <b class="card-text col-title mb-md-2 mb-0">จำนวน</b>
                                                                            <input type="text" class="form-control numeral-mask qty-input" name="qty" value="0" placeholder="0" />
                                                                        </div>
                                                                        <div class="col-lg-2 col-12 my-lg-0 my-2">
                                                                            <b class="card-text col-title mb-md-2 mb-0">ราคาต่อหน่วย</b>
                                                                            <input type="text" class="form-control numeral-mask cost-input" name="cost" value="0" placeholder="0" />
                                                                        </div>
                                                                        <div class="col-lg-2 col-12 my-lg-0 my-2">
                                                                            <b class="card-text col-title mb-md-2 mb-0">ส่วนลด</b>
                                                                            <input type="text" class="form-control numeral-mask discount-input" name="discount" value="0" placeholder="0" />
                                                                        </div>
                                                                        <div class="col-lg-1 col-12 mt-lg-0 mt-2">
                                                                            <b class="card-text col-title mb-md-50 mb-0">ยอดรวม</b>
                                                                            <b class="card-text sum-price mb-0">$0.00</b>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex flex-column align-items-center justify-content-between border-left py-50 px-25">
                                                                        <i data-feather="x" class="cursor-pointer font-medium-3" data-repeater-delete></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col-12 px-0">
                                                            <button type="button" class="btn btn-primary btn-sm btn-add-new" data-repeater-create>
                                                                <i data-feather="plus" class="mr-25"></i>
                                                                <span class="align-middle">รายละเอียด</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Product Details ends -->

                                                <hr class="invoice-spacing mt-0" />

                                                <div class="card-body invoice-padding">
                                                    <div class="row invoice-sales-total-wrapper">
                                                        <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">
                                                            <h5><em><b id="bahtText">(สามแสนบาทถ้วน)</b></em></h5>
                                                        </div>
                                                        <div class="col-md-6 d-flex justify-content-end order-md-2 order-1">
                                                            <div class="invoice-total-wrapper">
                                                                <div class="invoice-total-item">
                                                                    <p class="invoice-total-title">Subtotal:</p>
                                                                    <p class="invoice-total-amount">$1800</p>
                                                                </div>
                                                                <hr class="my-50">
                                                                <div class="invoice-total-item">
                                                                    <p class="invoice-total-title">Total:</p>
                                                                    <p class="invoice-total-amount">$1690</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr class="invoice-spacing mt-0" />

                                                <div class="d-flex justify-content-between">
                                                    <span></span>
                                                    <button class="btn btn-primary btn-submit">
                                                        <span class="align-middle d-sm-inline-block d-none btn-page-block-spinner">
                                                            <i data-feather="plus" class="mr-25"></i>
                                                            Submit
                                                        </span>
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                        <!-- Quotation Add ends -->
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /jQuery Validation -->
                </div>
            </section>
            <!-- /Validation -->

        </div>
    </div>
</div>

<?php
$close_conn = $quotObj->close();
?>