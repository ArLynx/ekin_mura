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

			<div class="form-group <?php echo form_error('id_master_jabatan_pns') ? 'has-error' : ''; ?>">
				<label for="id_master_jabatan_pns">Kategori PNS</label>
				<select name="id_master_jabatan_pns" id="id_master_jabatan_pns" class="form-control">
					<option value="">Pilih Kategori PNS</option>
					<?php if ($master_jabatan_pns): ?>
					<?php foreach ($master_jabatan_pns as $row): ?>
					<option value="<?php echo $row->id; ?>" <?php echo isset($master_kelas_jabatan) ? ($master_kelas_jabatan->id_master_jabatan_pns == $row->id ? 'selected' : '') : set_select('id_master_jabatan_pns', $row->id); ?>><?php echo $row->jabatan_pns; ?></option>
					<?php endforeach;?>
					<?php endif;?>
				</select>
				<?php echo form_error('id_master_jabatan_pns', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('kelas_jabatan') ? 'has-error' : ''; ?>">
				<label for="kelas_jabatan">Kelas Jabatan</label>
				<input type="text" name="kelas_jabatan" id="kelas_jabatan" class="form-control"
					placeholder="Kelas Jabatan"
					value="<?php echo isset($master_kelas_jabatan) ? $master_kelas_jabatan->kelas_jabatan : set_value('kelas_jabatan'); ?>">
				<?php echo form_error('kelas_jabatan', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('nama_jabatan') ? 'has-error' : ''; ?>">
				<label for="nama_jabatan">Nama Jabatan</label>
				<input type="text" name="nama_jabatan" id="nama_jabatan" class="form-control" placeholder="Nama Jabatan"
					value="<?php echo isset($master_kelas_jabatan) ? $master_kelas_jabatan->nama_jabatan : set_value('nama_jabatan'); ?>">
				<?php echo form_error('nama_jabatan', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('id_master_unit_organisasi') ? 'has-error' : ''; ?>">
				<label for="id_master_unit_organisasi">Unit Organisasi</label>
				<select name="id_master_unit_organisasi" id="id_master_unit_organisasi" class="form-control">
					<option value="">Pilih Unit Organisasi</option>
					<?php if ($master_unit_organisasi): ?>
					<?php foreach ($master_unit_organisasi as $row): ?>
					<option value="<?php echo $row->id; ?>" <?php echo isset($master_kelas_jabatan) ? ($master_kelas_jabatan->id_master_unit_organisasi == $row->id ? 'selected' : '') : set_select('id_master_unit_organisasi', $row->id); ?>><?php echo $row->unit_organisasi; ?></option>
					<?php endforeach;?>
					<?php endif;?>
				</select>
				<?php echo form_error('id_master_unit_organisasi', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group">
				<span class="text-danger">*Pastikan Anda sudah menambahkan <strong>Unit Organisasi</strong> terlebih
					dahulu.</span>
			</div>

		</div><!-- /.box-body -->

		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
		<?php echo form_close(); ?>
	</div>

</section><!-- /.content -->
