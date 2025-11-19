<!-- Main content -->
<section class="content">

	<!-- Your Page Content Here -->
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
				<input type="text" name="kelas_jabatan" id="kelas_jabatan" class="form-control" value="<?php echo $selected_jabatan->nm_jabatan; ?>" 
					readonly>
			</div>

			<div class="form-group <?php echo form_error('nama_pekerjaan') ? 'has-error' : ''; ?>">
				<label for="nama_pekerjaan">Nama Pekerjaan</label>
				<input type="text" name="nama_pekerjaan" id="nama_pekerjaan" class="form-control" value="<?php echo $selected_pekerjaan; ?>" 
					readonly>
			</div>

			<div class="form-group <?php echo form_error('nama_rincian_pekerjaan') ? 'has-error' : ''; ?>">
				<label for="nama_rincian_pekerjaan">Nama Rincian Pekerjaan</label>
				<input type="text" name="nama_rincian_pekerjaan" id="nama_rincian_pekerjaan" class="form-control" placeholder="Nama Rincian Pekerjaan"
					value="<?php echo isset($master_rincian_pekerjaan) ? $master_rincian_pekerjaan->nama_rincian_pekerjaan : set_value('nama_rincian_pekerjaan'); ?>">
				<?php echo form_error('nama_rincian_pekerjaan', '<p class="help-block text-red">', '</p>'); ?>
			</div>
			
			<div class="form-group <?php echo form_error('norma_waktu') ? 'has-error' : ''; ?>">
				<label for="norma_waktu">Norma Waktu</label>
				<input type="text" name="norma_waktu" id="norma_waktu" class="form-control"
					placeholder="Norma Waktu"
					value="<?php echo isset($master_rincian_pekerjaan) ? $master_rincian_pekerjaan->norma_waktu : set_value('norma_waktu'); ?>">
				<?php echo form_error('norma_waktu', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('id_satuan') ? 'has-error' : ''; ?>">
				<label for="id_satuan">Satuan</label>
				<select name="id_satuan" id="id_satuan" class="form-control select2">
					<option value="">Pilih Satuan</option>
					<?php if ($satuan): ?>
					<?php foreach ($satuan as $row): ?>
					<option value="<?php echo $row->id; ?>" <?php echo set_select('satuan', (isset($master_rincian_pekerjaan) ? $master_rincian_pekerjaan->id_satuan : $row->id), (isset($master_rincian_pekerjaan) ? ($master_rincian_pekerjaan->id_satuan == $row->id ? true : false) : false)); ?>><?php echo $row->nama; ?></option>
					<?php endforeach;?>
					<?php endif;?>
				</select>
				<?php echo form_error('id_satuan', '<p class="help-block text-red">', '</p>'); ?>
			</div>

		</div><!-- /.box-body -->

		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
		<?php echo form_close(); ?>
	</div>

</section><!-- /.content -->
