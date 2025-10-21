<?php
require_once 'controllers/Quotation.php';

$quotObj = new Quotation();
$quotations = $quotObj->showlist();
?>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- quotations list start -->
            <section class="app-user-list">
                <!-- quotations filter start -->
                <div class="card">
                    <h5 class="card-header">Search Filter</h5>
                    <form id="quotation-search-form" name="quotation-search-form" method="post" enctype="multipart/form-data">
                        <div class="d-flex align-items-center mx-50 row pt-0 pb-2">
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <select class="form-control select2" id="title" name="title">
                                        <option value="all">All</option>
                                        <option value="1">ใบเสนอราคา</option>
                                        <option value="2">ใบแจ้งหนี้</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="name">ชื่องาน</label>
                                    <input type="text" class="form-control" id="name" name="name" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="date_quo">วันที่</label>
                                    <input type="text" class="form-control date-picker" id="date_quo" name="date_quo" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="cus_name">ลูกค้า</label>
                                    <input type="text" class="form-control" id="cus_name" name="cus_name" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <button type="submit" class="btn btn-primary" name="submit" value="Submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- quotations type filter end -->
                <!-- list section start -->

                <div class="card">
                    <div class="card-datatable pt-0" id="quotation-search-table">
                        <table class="quotation-list-table table table-responsive">
                            <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th>เลขที่</th>
                                    <th>วันที่</th>
                                    <th>ผู้ขาย</th>
                                    <th>ชื่องาน</th>
                                    <th>ลูกค้า</th>
                                </tr>
                            </thead>
                            <?php if ($quotations) { ?>
                                <tbody>
                                    <?php
                                    foreach ($quotations as $quotation) {
                                        $href = 'href="./?pages=quotation/edit&id=' . $quotation['id'] . '" style="color:#6E6B7B"';
                                    ?>
                                        <tr>
                                            <td><a <?php echo $href; ?>><?php echo $quotation['title'] == 1 ? 'ใบเสนอราคา' : 'ใบแจ้งหนี้'; ?></a></td>
                                            <td class="cell-fit"><a <?php echo $href; ?>><?php echo $quotation['quo_full'] ?></a></td>
                                            <td><a <?php echo $href; ?>><?php echo date('j F Y', strtotime($quotation['date_quo'])); ?></a></td>
                                            <td><a <?php echo $href; ?>><?php echo $quotation['seller']; ?></a></td>
                                            <td><a <?php echo $href; ?>><?php echo $quotation['name']; ?></a></td>
                                            <td><a <?php echo $href; ?>><?php echo $quotation['cus_name']; ?></a></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            <?php } ?>
                        </table>
                    </div>
                </div>
                <!-- list section end -->
            </section>
            <!-- cars type list ends -->

        </div>
    </div>
</div>