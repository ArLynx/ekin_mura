<!-- Main content -->
<section class="content">

	<!-- Your Page Content Here -->
	<div class="box box-primary">
		<?php echo form_open(); ?>
		<div class="box-body">
			<?php alert_message_dashboard();?>

			<div class="form-group <?php echo form_error('sn') ? 'has-error' : ''; ?>">
				<label for="sn">Serial Number</label>
				<input type="text" name="sn" id="sn" class="form-control"
					placeholder="Serial Number"
					value="<?php echo isset($tarik_absen) ? $tarik_absen->sn : set_value('sn'); ?>">
				<?php echo form_error('sn', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('kd_unor') ? 'has-error' : ''; ?>">
				<label for="kd_unor">Kode Unit Organisasi (Unor)</label>
				<input type="text" name="kd_unor" id="kd_unor" class="form-control" placeholder="Unit Organisasi"
					value="<?php echo isset($tarik_absen) ? $tarik_absen->kd_unor : set_value('kd_unor'); ?>">
				<?php echo form_error('kd_unor', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('nm_unor') ? 'has-error' : ''; ?>">
				<label for="nm_unor">Nama Unit Organisasi (Unor)</label>
				<input type="text" name="nm_unor" id="nm_unor" class="form-control" placeholder="Nama Unit Organisasi"
					value="<?php echo isset($tarik_absen) ? $tarik_absen->nm_unor : set_value('nm_unor'); ?>">
				<?php echo form_error('nm_unor', '<p class="help-block text-red">', '</p>'); ?>
			</div>

		</div><!-- /.box-body -->

		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
		<?php echo form_close(); ?>
	</div>

</section><!-- /.content -->
