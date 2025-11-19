<!-- Main content -->
<section class="content">

	<!-- Your Page Content Here -->
	<div class="box box-primary">
		<?php echo form_open(); ?>
		<div class="box-body">
			<!-- <div class="row">
				<div class="col-md-12"> -->
					<?php alert_message_dashboard();?>

					<div class="form-group <?php echo form_error('unor') ? 'has-error' : ''; ?>">
						<label for="unor">SOPD</label>
						<select name="unor" id="unor" class="form-control select2" style="width: 100%;" onchange="getDataMap()">
							<?php if (get_session('id_groups') == '5'): ?>
							<option value="">- Pilih SOPD -</option>
							<?php endif;?>
							<?php if ($all_sopd): ?>
							<?php foreach ($all_sopd as $row): ?>
							<option value="<?php echo $row->KD_UNOR; ?>" <?php echo $selected_unor == $row->KD_UNOR ? 'selected' : ''; ?>><?php echo $row->NM_UNOR; ?></option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
						<!--  -->
						
						<?php echo form_error('unor', '<p class="help-block text-red">', '</p>'); ?>
					</div>

					<div class="form-group <?php echo form_error('KD_GENPOS') ? 'has-error' : ''; ?>">
						<label for="kd_genpos">Jabatan Lama</label>
						<select name="kd_genpos" id="kd_genpos" class="form-control select2" style="width: 100%;" onchange="getJpJft()">
							<option value="">- Pilih Jabatan Lama -</option>
							<?php if (isset($all_genpos)): ?>
								<?php foreach ($all_genpos as $row): ?>
									<option value="<?php echo $row->KD_GENPOS; ?>" <?php echo isset($maping_pekerjaan) ? ($maping_pekerjaan->KD_GENPOS == $row->KD_GENPOS ? 'selected' : '') : ''; ?>><?php echo $row->NM_GENPOS; ?></option>
								<?php endforeach;?>
							<?php endif;?>
						</select>
						<?php echo form_error('KD_GENPOS', '<p class="help-block text-red">', '</p>'); ?>
					</div>

					<div class="form-group option_jp <?php echo form_error('jab_pelaksana') ? 'has-error' : ''; ?>">
						<label for="jab_pelaksana">Jabatan Pelaksana</label>
						<select name="jab_pelaksana" id="jab_pelaksana" class="form-control select2" style="width: 100%;" onchange="">
							<option value="">- Pilih Jabatan Pelaksana -</option>
							<?php if (isset($all_jabatan_fungsional)): ?>
								<?php foreach ($all_jabatan_fungsional as $row): ?>
									<option value="<?php echo encode_crypt($row->no); ?>" <?php echo isset($maping_pekerjaan) ? ($maping_pekerjaan->no_master_jabfus == $row->no ? 'selected' : '') : ''; ?>><?php echo $row->nama_jabfus; ?></option>
								<?php endforeach;?>
							<?php endif;?>
						</select>
						<?php echo form_error('jab_pelaksana', '<p class="help-block text-red">', '</p>'); ?>
					</div>

					<div class="form-group option_jft <?php echo form_error('jab_fungsional_tertentu') ? 'has-error' : ''; ?>">
						<label for="jab_fungsional_tertentu">Jabatan Fungsional Tertentu</label>
						<select name="jab_fungsional_tertentu" id="jab_fungsional_tertentu" class="form-control select2" style="width: 100%;" onchange="">
							<option value="">- Pilih Jabatan Fungsional Tertentu -</option>
							<?php if (isset($all_jabatan_fungsional_tertentu)): ?>
								<?php foreach ($all_jabatan_fungsional_tertentu as $row): ?>
									<option value="<?php echo encode_crypt($row->KD_FPOS); ?>" <?php echo isset($maping_pekerjaan) ? ($maping_pekerjaan->KD_FPOS == $row->KD_FPOS ? 'selected' : '') : ''; ?>><?php echo $row->NM_FPOS; ?></option>
								<?php endforeach;?>
							<?php endif;?>
						</select>
						<?php echo form_error('jab_fungsional_tertentu', '<p class="help-block text-red">', '</p>'); ?>
					</div>

					<div class="form-group <?php echo form_error('id_master_kelas_jabatan') ? 'has-error' : ''; ?>">
						<label for="id_master_kelas_jabatan">Kelas Jabatan Baru</label>
						<select name="id_master_kelas_jabatan" id="id_master_kelas_jabatan" class="form-control select2" style="width: 100%;">
							<option value="">- Pilih Kelas Jabatan Baru -</option>
							<?php if (isset($all_master_kelas_jabatan)): ?>
								<?php foreach ($all_master_kelas_jabatan as $row): ?>
									<option value="<?php echo $row->id; ?>" <?php echo isset($maping_pekerjaan) ? ($maping_pekerjaan->id_master_kelas_jabatan == $row->id ? 'selected' : '') : ''; ?>><?php echo $row->nama_jabatan. ' | ' .$row->unit_organisasi; ?></option>
								<?php endforeach;?>
							<?php endif;?>
						</select>
						<?php echo form_error('id_master_kelas_jabatan', '<p class="help-block text-red">', '</p>'); ?>
					</div>
				<!-- </div>
			</div> -->
		</div><!-- /.box-body -->

		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
		<?php echo form_close(); ?>
	</div>

</section><!-- /.content -->

<script>
    $(function() {
        let maping_pekerjaan_kd_genpos = $("select[name=kd_genpos]").val();
        if(maping_pekerjaan_kd_genpos == '9999')
        {
            $(".option_jp").show();
            $(".option_jft").hide();
        } else if(maping_pekerjaan_kd_genpos == 'FT'){
            $(".option_jp").hide();
            $(".option_jft").show();
        } else {
            $(".option_jp").hide();
            $(".option_jft").hide();
        }
	});

	function getDataMap() {
		let selected_unor = $("select[name=unor]").val();
		if (selected_unor) {
			$("select[name=kd_genpos]").html('<option value="">- Pilih Jabatan Lama -</option>');

			$.get(base_url + '/api/get_all_jabatan_lama_sopd', {
					unor: selected_unor,
				})
				.then(function (response) {
					$.each(response, function (key, value) {
						$("select[name=kd_genpos]").append(
							"<option value='" + value.KD_GENPOS + "'>" + value.NM_GENPOS + "</option>"
						);
					});
				});
		}
	}

    function getJpJft() {
		$(".option_jp").hide();
        $(".option_jft").hide();

        let selected_kd_genpos = $("select[name=kd_genpos]").val();
        let selected_unor = $("select[name=unor]").val();
        if(selected_kd_genpos == '9999'){
            $.get(base_url + '/api/get_all_jp_by_genpos', {
			    selected_kd_genpos: selected_kd_genpos,
                selected_unor: selected_unor
		    })
            .then(function (response) {
                $(".option_jp").show();
                $(".option_jft").hide();
                $.each(response, function (key, value) {
					$("select[name=jab_pelaksana]").append(
							"<option value='" + value.no_encrypt + "'>" + value.nama_jabfus + "</option>"
					);
				});
            });
        } else if(selected_kd_genpos == 'FT') {
            $.get(base_url + '/api/get_all_jft_by_genpos', {
			    selected_kd_genpos: selected_kd_genpos,
                selected_unor: selected_unor
		    })
            .then(function (response) {
                $(".option_jft").show();
                $(".option_jp").hide();
                $.each(response, function (key, value) {
					$("select[name=jab_fungsional_tertentu]").append(
							"<option value='" + value.KD_FPOS_encrypt + "'>" + value.NM_FPOS + "</option>"
					);
				});
            });
        }
    }

</script>