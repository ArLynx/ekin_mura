<!-- Main content -->
<section class="content" data-is_add="<?php echo $is_add; ?>">

	<!-- Your Page Content Here -->
	<div class="box box-primary">
		<?php echo form_open_multipart(); ?>
		<div class="box-body">
			<?php alert_message_dashboard();?>

			<div class="form-group <?php echo form_error('unor') ? 'has-error' : ''; ?>">
				<label for="unor">SOPD</label>
				<select name="unor" id="unor" class="form-control select2" onchange="getAllPns()">
					<?php if (get_session('id_groups') == '1' || get_session('id_groups') == '5'): ?>
					<option value="">- Pilih SOPD -</option>
					<?php endif;?>
					<?php if ($all_sopd): ?>
					<?php foreach ($all_sopd as $row): ?>
					<option value="<?php echo $row->KD_UNOR; ?>" <?php echo $selected_unor == $row->KD_UNOR ? 'selected' : ''; ?>><?php echo $row->NM_UNOR; ?></option>
					<?php endforeach;?>
					<?php endif;?>
				</select>
				<?php echo form_error('unor', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('pns_pnsnip') ? 'has-error' : ''; ?>">
				<label for="pns_pnsnip">PNS</label>
				<select name="pns_pnsnip" id="pns_pnsnip" class="form-control select2">
                    <option value="">- Pilih PNS -</option>
                    <?php if (isset($detail_pns)): ?>
                        <option value="<?php echo $detail_pns->PNS_PNSNIP ?>" selected><?php echo "{$detail_pns->PNS_NAMA} | {$detail_pns->nama_jabatan}"; ?></option>
                    <?php endif;?>
				</select>
				<?php echo form_error('pns_pnsnip', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('pns_unor_plt') ? 'has-error' : ''; ?>">
				<label for="pns_unor_plt">Plt di SOPD</label>
				<select name="pns_unor_plt" id="pns_unor_plt" class="form-control select2"
					onchange="getKelasJabatanPlt()">
					<option value="">- Pilih SOPD Plt -</option>
					<?php if ($all_sopd_to): ?>
					<?php foreach ($all_sopd_to as $row): ?>
					<option value="<?php echo $row->KD_UNOR; ?>" <?php echo isset($pns_plt) ? ($pns_plt->pns_unor_plt == $row->KD_UNOR ? 'selected' : '') : ''; ?>><?php echo $row->NM_UNOR; ?></option>
					<?php endforeach;?>
					<?php endif;?>
				</select>
				<?php echo form_error('pns_unor_plt', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('id_master_kelas_jabatan_plt') ? 'has-error' : ''; ?>">
				<label for="id_master_kelas_jabatan_plt">Kelas Jabatan Plt</label>
				<select name="id_master_kelas_jabatan_plt" id="id_master_kelas_jabatan_plt" class="form-control select2">
					<option value="">- Pilih Kelas Jabatan Plt -</option>
					<?php if (isset($all_master_kelas_jabatan)): ?>
						<?php foreach ($all_master_kelas_jabatan as $row): ?>
							<option value="<?php echo $row->id; ?>" <?php echo isset($pns_plt) ? ($pns_plt->id_master_kelas_jabatan_plt == $row->id ? 'selected' : '') : ''; ?>><?php echo "{$row->nama_jabatan} | {$row->unit_organisasi}"; ?></option>
						<?php endforeach;?>
					<?php endif;?>
				</select>
				<?php echo form_error('id_master_kelas_jabatan_plt', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('awal_plt') ? 'has-error' : ''; ?>">
				<label for="awal_plt">Mulai Bulan</label>
				<input type="month" name="awal_plt" class="form-control" placeholder="Mulai Bulan"
					value="<?php echo isset($pns_plt) ? $pns_plt->awal_plt : set_value('awal_plt'); ?>">
				<?php echo form_error('awal_plt', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('sk_plt') ? 'has-error' : ''; ?>">
				<label for="sk_plt">SK Plt <?php echo isset($pns_plt) ? (!is_null($pns_plt->sk_plt) ? '(<a href="' . base_url() . path_image('sk_plt_path') . $pns_plt->sk_plt . '" target="_blank">Lihat SK</a>)' : '') : ''; ?></label>
				<input type="file" name="sk_plt" id="sk_plt" class="form-control" placeholder="SK Plt">
				<?php echo form_error('sk_plt', '<p class="help-block text-red">', '</p>'); ?>
			</div>

		</div><!-- /.box-body -->

		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
		<?php echo form_close(); ?>
	</div>

</section><!-- /.content -->

<script>
	let _is_add = $(".content").attr('data-is_add');
	if(_is_add == 1) {
		getAllPns();
	}
	function getAllPns() {
		let selected_unor = $("select[name=unor]").val();
		if (selected_unor) {
			$("select[name=pns_pnsnip]").html('<option value="">- Pilih PNS -</option>');

			$.get(base_url + '/api/get_all_pegawai_sopd', {
					unor: selected_unor,
					is_tkd: 'yes'
				})
				.then(function (response) {
					$.each(response, function (key, value) {
						$("select[name=pns_pnsnip]").append(
							"<option value='" + value.PNS_PNSNIP + "'>" + value.PNS_NAMA + " | " + value
							.nama_jabatan + "</option>"
						);
					});
				});
		}
	}

	function getKelasJabatanPlt() {
		let pns_unor_plt = $("select[name=pns_unor_plt]").val();

        $("select[name=id_master_kelas_jabatan_plt]").html('<option value="">- Pilih Kelas Jabatan Plt -</option>');

		if (pns_unor_plt) {
            $.get(base_url + '/api/get_all_master_kelas_jabatan', {
                unor: pns_unor_plt
            })
            .then(function(response) {
                $.each(response, function (key, value) {
						$("select[name=id_master_kelas_jabatan_plt]").append(
							"<option value='" + value.id + "'>" + value.nama_jabatan + '|' + value.unit_organisasi + "</option>"
						);
					});
            });
		}
	}

</script>
