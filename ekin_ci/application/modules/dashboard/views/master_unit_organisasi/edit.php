<!-- Main content -->
<section class="content">

	<!-- Your Page Content Here -->
	<div class="box box-primary">
		<?php echo form_open(); ?>
		<div class="box-body">
			<?php alert_message_dashboard();?>

			<div class="form-group">
				<label for="selected_unor">SOPD</label>
				<input type="text" id="selected_unor" class="form-control" value="<?php echo $selected_unor; ?>" readonly>
			</div>

			<div class="form-group <?php echo form_error('unit_organisasi') ? 'has-error' : ''; ?>">
				<label for="unit_organisasi">Unit Organisasi</label>
				<input type="text" name="unit_organisasi" id="unit_organisasi" class="form-control" placeholder="Unit Organisasi"
					value="<?php echo isset($master_unit_organisasi) ? $master_unit_organisasi->unit_organisasi : set_value('unit_organisasi'); ?>">
				<?php echo form_error('unit_organisasi', '<p class="help-block text-red">', '</p>'); ?>
				<br>
				<label for="unit_organisasi">Index Jabatan</label>
				<input type="text" name="index_jabatan" id="index_jabatan" class="form-control" placeholder="Index Jabatan"
					value="<?php echo isset($master_unit_organisasi) ? $master_unit_organisasi->index_jabatan : set_value('index_jabatan'); ?>">
				<?php echo form_error('index_jabatan', '<p class="help-block text-red">', '</p>'); ?>
			</div>

		</div><!-- /.box-body -->

		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
		<?php echo form_close(); ?>
	</div>

</section><!-- /.content -->
