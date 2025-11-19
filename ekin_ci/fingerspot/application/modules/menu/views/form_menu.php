<style>
.choose-icon{
    cursor:pointer;
}
</style>
<div class="content">
    <!-- Form inputs -->
    <div class="card">
        <div class="card-body">
            <?php echo form_open(current_url(),array('class'=>'form-validate-jquery')); ?>
                <fieldset class="mb-3">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Menu</legend>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Pilih Icon</label>
                        <div class="col-lg-10">
                            <div class="input-group">
                                <input type="text" class="form-control border-right-0" placeholder="Pilih Icon" name="icon_menu" value="<?php echo !empty($content)?$content->class_icon:""; ?>" required>
                                <span class="input-group-append">
                                    <button class="btn bg-teal" type="button" data-toggle="modal" data-target="#modal_full">Icon</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Parent Menu <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <select class="form-control select-search" name="parent_menu" required>
                                <option value="0">Root</option>
                                <?php
                                    echo $menu_option;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Nama Menu <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" value="<?php echo !empty($content)?$content->nama_menu:""; ?>" name="nama_menu" required placeholder="Nama Menu">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Nama Module <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" value="<?php echo !empty($content)?$content->nama_module:""; ?>" name="nama_module" required placeholder="Nama Module">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Nama Kelas <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" value="<?php echo !empty($content)?$content->nama_class:""; ?>" name="nama_class" required placeholder="Nama Kelas">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Urutan Menu <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" value="<?php echo !empty($content)?$content->order_menu:""; ?>" name="order_menu" required placeholder="Order Menu">
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

<script>
$(function(){
    $(".choose-icon").click(function(){
        let class_name_icon = $(this).children().children().attr("class");
        let repl_name = class_name_icon.replace(" mr-3 icon-2x","");
        $("[name=icon_menu]").val(repl_name);
        $('#modal_full').modal('toggle');
    });
});
</script>