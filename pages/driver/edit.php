<?php
require_once 'controllers/Driver.php';

$drvObj = new Driver();

if (!empty($_GET['id']) && $_GET['id'] > 0) {
    $id = $_GET['id'];
    $drv = $drvObj->get_data($id);
    if ($drv == false) {
        $close_conn = $drvObj->close();
        header('location:./?pages=driver/list');
    }
} else {
    header('location:./?pages=driver/list');
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
                        <h2 class="content-header-title float-left mb-0">Driver</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./?pages=driver/list">Driver List</a></li>
                                <li class="breadcrumb-item active"><?php echo $drv['first_name'] . ' ' . $drv['last_name']; ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Validation -->
            <section class="bs-validation">
                <div class="row">
                    <!-- jQuery Validation -->
                    <div class="col-md-12 col-12">
                        <div class="card">
                            <!-- <div class="card-header">
                                <h4 class="card-title">driver</h4>
                            </div> -->
                            <div class="card-body">
                                <form id="driver-edit-form" name="driver-edit-form" method="post" enctype="multipart/form-data">
                                    <input type="hidden" class="form-control" id="driver_id" name="driver_id" value="<?php echo $drv['id']; ?>" />
                                    <div class="row">
                                        <div class="col-md-2 col-12">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <?php $is_approved_checked = $drv['is_approved'] == 1 ? 'checked' : ''; ?>
                                                    <input type="checkbox" class="custom-control-input" id="is_approved" name="is_approved" value="1" <?php echo $is_approved_checked; ?> />
                                                    <label class="custom-control-label" for="is_approved">Active</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2 col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="id_card">ID Card</label>
                                                <input type="text" class="form-control" id="id_card" name="id_card" value="<?php echo $drv['id_card']; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="name">Name</label>
                                                <input type="text" class="form-control" id="name" name="name" value="<?php echo $drv['name']; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="birth_date">Birth Date</label>
                                                <input type="text" id="birth_date" name="birth_date" class="form-control flatpickr-basic" value="<?php echo $drv['birth_date']; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-12">
                                            <div class="form-group">
                                                <label class="d-block" for="gender">Gender</label>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="gender-male" name="gender" class="custom-control-input" value="1" <?php echo $drv['sex'] == 1 ? 'checked' : ''; ?> />
                                                    <label class="custom-control-label" for="gender-male">Male</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="gender-female" name="gender" class="custom-control-input" value="2" <?php echo $drv['sex'] == 2 ? 'checked' : ''; ?> />
                                                    <label class="custom-control-label" for="gender-female">Female</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="telephone">Telephone</label>
                                                <input type="text" id="telephone" name="telephone" class="form-control" value="<?php echo $drv['telephone']; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="issue_date">Issue Date (Driver License)</label>
                                                <input type="text" id="issue_date" name="issue_date" class="form-control" value="<?php echo $drv['issue_date']; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="expire_date">Expire Date (Driver License)</label>
                                                <input type="text" id="expire_date" name="expire_date" class="form-control" value="<?php echo $drv['expire_date']; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="d-block" for="address">Address</label>
                                                <textarea class="form-control" id="address" name="address" rows="2"><?php echo $drv['address']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label>Image</label>
                                                <?php if (!empty($drv['pic'])) { ?>
                                                    <div class="form-group mt-1">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="delete_pic" name="delete_pic[]" value="1" />
                                                            <label class="custom-control-label" for="delete_pic">Delete</label>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="before_pic" name="before_pic[]" class="form-control" value="<?php echo $drv['pic']; ?>" />
                                                    <div class="d-flex align-items-center justify-content-center mt-2 mb-2">
                                                        <img src="<?php echo $hostPageUrl; ?>/storage/uploads/drivers/pic/<?php echo $drv['pic']; ?>" class="img-fluid product-img" alt="Pic" />
                                                    </div>
                                                <?php } ?>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="pic" name="pic[]" />
                                                    <label class="custom-file-label" for="pic">Choose image file</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label>Image (Driver License)</label>
                                                <?php if (!empty($drv['pic_dl'])) { ?>
                                                    <div class="form-group mt-1">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="delete_pic_dl" name="delete_pic_dl[]" value="1" />
                                                            <label class="custom-control-label" for="delete_pic_dl">Delete</label>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="before_pic_dl" name="before_pic_dl[]" class="form-control" value="<?php echo $drv['pic_dl']; ?>" />
                                                    <div class="d-flex align-items-center justify-content-center mt-2 mb-2">
                                                        <img src="<?php echo $hostPageUrl; ?>/storage/uploads/drivers/pic-dl/<?php echo $drv['pic_dl']; ?>" class="img-fluid product-img" alt="Pic DL" />
                                                    </div>
                                                <?php } ?>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="pic_dl" name="pic_dl[]" />
                                                    <label class="custom-file-label" for="pic_dl">Choose image file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <?php if ($_SESSION["supplier"]["role_id"] == 1 || $_SESSION["supplier"]["role_id"] == 2) { ?>
                                            <button type="button" class="btn btn-danger" onclick="deleteDriver(<?php echo $id; ?>);"><i data-feather='trash-2'></i> Delete</button>
                                        <?php } ?>
                                        <button type="submit" class="btn btn-primary" name="submit" value="Submit">Submit</button>
                                    </div>
                                    <div id="div-driver"></div>
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
$close_conn = $drvObj->close();
?>