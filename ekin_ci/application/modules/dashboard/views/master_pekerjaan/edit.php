<!-- Main content -->
<section class="content">

	<!-- Your Page Content Here -->
	<?php if (!is_null($selected_jabatan)): ?>
	<div class="box box-primary">

		<?php echo form_open(); ?>
		<div class="box-body">
			<?php alert_message_dashboard();?>

			<div class="form-group">
				<label for="selected_unor">SOPD</label>
				<input type="text" id="selected_unor" class="form-control" value="<?php echo $selected_unor; ?>"
					readonly>
			</div>

			<div class="form-group <?php echo form_error('kelas_jabatan') ? 'has-error' : ''; ?>">
				<label for="kelas_jabatan">Kelas Jabatan</label>
				<input type="text" name="kelas_jabatan" id="kelas_jabatan" class="form-control" value="<?php echo $selected_jabatan; ?>"
					readonly>
			</div>

			<div class="form-group <?php echo form_error('nama_pekerjaan') ? 'has-error' : ''; ?>">
				<label for="nama_pekerjaan">Nama Pekerjaan</label>
				<input type="text" name="nama_pekerjaan" id="nama_pekerjaan" class="form-control" placeholder="Nama Pekerjaan"
					value="<?php echo isset($pekerjaan) ? $pekerjaan->nama_pekerjaan : set_value('nama_pekerjaan'); ?>">
				<?php echo form_error('nama_pekerjaan', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('prioritas') ? 'has-error' : ''; ?>">
				<label for="prioritas">Prioritas</label>
				<input type="number" name="prioritas" id="prioritas" class="form-control"
					placeholder="Prioritas"
					value="<?php echo isset($pekerjaan) ? $pekerjaan->prioritas : set_value('prioritas'); ?>">
				<?php echo form_error('prioritas', '<p class="help-block text-red">', '</p>'); ?>
			</div>

		</div><!-- /.box-body -->

		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
		<?php echo form_close(); ?>

	</div>
	<?php else: ?>
		<div class="alert alert-danger" role="alert">
		Silakan mapping pekerjaan terlebih dahulu di <a href="#">sini..</a>
		</div>
	<?php endif;?>

</section><!-- /.content -->
