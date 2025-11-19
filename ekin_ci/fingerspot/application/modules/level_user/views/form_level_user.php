<div class="content">
    <!-- Form inputs -->
    <div class="card">
        <div class="card-body">
            <?php echo form_open(current_url(),array('class'=>'form-validate-jquery')); ?>
                <fieldset class="mb-3">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Level User</legend>
                    
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Nama Level User <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" value="<?php echo !empty($content)?$content->nama_level_user:""; ?>" name="level_user" required placeholder="Nama Level User">
                        </div>
                    </div>
                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit <i class="icon-paperplane ml-2"></i></button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
    <!-- /form inputs -->
</div>