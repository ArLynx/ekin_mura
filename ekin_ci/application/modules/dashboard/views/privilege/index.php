<?php
# @Author: Awan Tengah
# @Date:   2019-09-01T21:53:13+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-09-02T08:49:49+07:00
?>

    <!-- Main content -->
    <section class="content">

        <!-- Your Page Content Here -->
        <div class="box">
            <?php echo form_open(); ?>
            <div class="box-header">
            <div class="alert alert-danger alert-dismissible" role="alert">
                <i class="fa fa-warning"></i> DO NOT TOUCH, I WILL NOT BE RESPONSIBLE !!!
            </div>
            <?php alert_message_dashboard();?>
                <div class="form-group <?php echo form_error('id_groups') ? 'has-error' : ''; ?>" style="margin-bottom: 0;">
                    <label for="id_groups">Level</label>
                    <select name="id_groups" id="id_groups" class="form-control" onchange="show_list_menu(this.value);" required>
                        <option value="">Choose</option>
                        <?php foreach ($groups as $row): ?>
                            <option value="<?php echo $row->id; ?>"><?php echo $row->description; ?></option>
                        <?php endforeach;?>
                    </select>
                    <?php echo form_error('id_groups', '<p class="help-block text-red">', '</p>'); ?>
                </div>
            </div>
            <div class="box-body">
                <div id="list-menu"></div>
            </div>
            <div class="box-footer">
                <input type="submit" value="Simpan" class="btn btn-primary">
            </div>
            <?php echo form_close(); ?>
        </div>

    </section><!-- /.content -->

<script>
    function show_list_menu(val) {
        $.get("<?php echo base_url('dashboard/privilege/lists'); ?>" + "/" + val, function (data) {
            $("#list-menu").html(data);
        });
    }
</script>
