<!-- Main content -->
<section class="content">

	<div class="row">
		<div class="col-md-6">
			<!-- Your Page Content Here -->
			<div class="box box-primary">
				<?php echo form_open(); ?>
				<div class="box-body">
					<?php alert_message_dashboard();?>

					<?php if($setting): ?>
                        <?php foreach($setting as $row): ?>
                            <div class="form-group">
                                <label for="<?php echo $row->nama; ?>"><?php echo $setting_placeholder[$row->id]; ?></label>
                                <input type="text" name="<?php echo $row->nama; ?>" id="<?php echo $row->nama; ?>"
                                    class="form-control" placeholder="<?php echo $setting_placeholder[$row->id]; ?>"
                                    value="<?php echo $row->bulan; ?>">
                            </div>
                        <?php endforeach; ?>
					<?php endif; ?>

				</div><!-- /.box-body -->

				<div class="box-footer">
					<?php if($_created): ?>
						<button type="submit" class="btn btn-primary">Simpan</button>
					<?php endif; ?>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>

</section><!-- /.content -->
