<!-- Main content -->
<section class="content">

	<!-- Your Page Content Here -->
	<div class="box box-primary">
		<?php echo form_open(); ?>
		<div class="box-body">
			<?php alert_message_dashboard();?>

			<div class="form-group <?php echo form_error('tanggal') ? 'has-error' : ''; ?>">
				<label for="tanggal">Tanggal Libur</label>
				<input type="date" name="tanggal" id="tanggal" class="form-control" placeholder="Tanggal Libur"
					value="<?php echo isset($absen_libur) ? $absen_libur->tanggal : set_value('tanggal'); ?>">
				<?php echo form_error('tanggal', '<p class="help-block text-red">', '</p>'); ?>
			</div>

            <div class="form-group <?php echo form_error('nama_libur') ? 'has-error' : ''; ?>">
				<label for="nama_libur">Keterangan</label>
				<input type="text" name="nama_libur" id="nama_libur" class="form-control" placeholder="Keterangan"
					value="<?php echo isset($absen_libur) ? $absen_libur->nama_libur : set_value('nama_libur'); ?>">
				<?php echo form_error('nama_libur', '<p class="help-block text-red">', '</p>'); ?>
			</div>

		</div><!-- /.box-body -->

		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
		<?php echo form_close(); ?>
	</div>

</section><!-- /.content -->
