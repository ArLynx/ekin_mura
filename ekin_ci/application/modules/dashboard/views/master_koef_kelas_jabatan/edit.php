<!-- Main content -->
<section class="content">

	<!-- Your Page Content Here -->
	<div class="box box-primary">
		<?php echo form_open(); ?>
		<div class="box-body">
			<?php alert_message_dashboard();?>

            <div class="form-group <?php echo form_error('id_master_jabatan_pns') ? 'has-error' : ''; ?>">
				<label for="id_master_jabatan_pns">Jabatan PNS</label>
				<select name="id_master_jabatan_pns" id="id_master_jabatan_pns" class="form-control">
					<option value="">Pilih Jabatan PNS</option>
					<?php if ($master_jabatan_pns): ?>
					<?php foreach ($master_jabatan_pns as $row): ?>
					<option value="<?php echo $row->id; ?>" <?php echo isset($master_koef_kelas_jabatan) ? ($master_koef_kelas_jabatan->id_master_jabatan_pns == $row->id ? 'selected' : '') : set_select('id_master_jabatan_pns', $row->id); ?>><?php echo $row->jabatan_pns; ?></option>
					<?php endforeach;?>
					<?php endif;?>
				</select>
				<?php echo form_error('id_master_jabatan_pns', '<p class="help-block text-red">', '</p>'); ?>
			</div>

            <div class="form-group <?php echo form_error('kelas_jabatan') ? 'has-error' : ''; ?>">
				<label for="kelas_jabatan">Kelas Jabatan</label>
				<select name="kelas_jabatan" id="kelas_jabatan" class="form-control">
					<option value="">Pilih Kelas Jabatan</option>
					<?php for ($i = 1; $i <= 15; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo isset($master_koef_kelas_jabatan) ? ($master_koef_kelas_jabatan->kelas_jabatan == $i ? 'selected' : '') : set_select('kelas_jabatan', $i); ?>><?php echo $i; ?></option>
                    <?php endfor;?>
				</select>
				<?php echo form_error('kelas_jabatan', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('koef') ? 'has-error' : ''; ?>">
				<label for="koef">Koef <small style="color: red;">Koma ditandai dengan tanda titik (.)</small></label>
				<input type="text" name="koef" id="koef" class="form-control" placeholder="Koef"
					value="<?php echo isset($master_koef_kelas_jabatan) ? $master_koef_kelas_jabatan->koef : set_value('koef'); ?>">
				<?php echo form_error('koef', '<p class="help-block text-red">', '</p>'); ?>
			</div>

            <div class="form-group <?php echo form_error('tahun') ? 'has-error' : ''; ?>">
				<label for="tahun">Tahun</label>
				<input type="text" name="tahun" id="tahun" class="form-control" placeholder="Tahun"
					value="<?php echo isset($master_koef_kelas_jabatan) ? $master_koef_kelas_jabatan->tahun : set_value('tahun'); ?>">
				<?php echo form_error('tahun', '<p class="help-block text-red">', '</p>'); ?>
			</div>

		</div><!-- /.box-body -->

		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
		<?php echo form_close(); ?>
	</div>

</section><!-- /.content -->
