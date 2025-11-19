<link rel="stylesheet" href="<?php echo base_url('assets/css/kegiatan-style.css'); ?>">
<style>
/*time picker*/
.main_table__j8SHe td>a {
    padding: 6px 0;
    font-size: 1.5rem;
}
.main_table__j8SHe .main_header__3DmbH {
    font-size: 14px;
}
</style>

<script src="<?php echo base_url('assets/plugin/timepicker.js/timepicker.min.js'); ?>"></script>

<!-- Main content -->
<section class="content" data-selected_uraian_tugas="<?php echo get_session('selected_uraian_tugas'); ?>">

	<!-- Your Page Content Menu Top Here -->
	<div class="box">
		<div class="box-header with-border">
			<nav class="navbar navbar-light bg-light">
			<form class="form-inline">
				<a href ="<?php echo base_url('/dashboard/kegiatan/add/'); ?>" id="" class="btn btn-lg btn-info topmenu topmenu-mobile">
					<span class="info-box-icon-ekin info-box-icon-ekin-mobile"><i class="fa fa-pencil"></i></span> Input Kegiatan</a>
				<a href ="<?php echo base_url('/dashboard/kegiatan'); ?>" id="" class="btn btn-lg btn-primary topmenu topmenu-mobile">
					<span class="info-box-icon-ekin info-box-icon-ekin-mobile"><i class="fa fa-book"></i></span> Kegiatan Belum Diperiksa</a>
				<a href ="<?php echo base_url('/dashboard/kegiatan_koreksi'); ?>" id="" class="btn btn-lg btn-warning topmenu topmenu-mobile">
					<span class="info-box-icon-ekin info-box-icon-ekin-mobile"><i class="fa fa-edit"></i></span> Kegiatan Dikoreksi</a>
				<a href ="<?php echo base_url('/dashboard/kegiatan_disetujui'); ?>" id="" class="btn btn-lg btn-success topmenu topmenu-mobile">
					<span class="info-box-icon-ekin info-box-icon-ekin-mobile"><i class="fa fa-check-square"></i></span> Kegiatan Disetujui</a>
				<a href ="<?php echo base_url('/dashboard/kegiatan_ditolak'); ?>" id="" class="btn btn-lg btn-danger topmenu topmenu-mobile">
					<span class="info-box-icon-ekin info-box-icon-ekin-mobile"><i class="fa fa-shield"></i></span> Kegiatan Ditolak</a>
			</form>
			</nav>
		</div>
	</div>

	<!-- Your Page Content Here -->
	<div class="box box-primary">
		<?php echo form_open_multipart(); ?>
		<div class="box-body">
			<?php alert_message_dashboard();?>

			<div class="alert alert-info alert-dismissible fade in text-center" role="alert">
				<p class="text-danger">Aplikasi ini mencatat apa yang kita tuliskan, tetapi <b><?php if ($pns->PNS_AGAMA == 1) {
					echo "Allah SWT";
				} else {
					echo "Tuhan YME";
				}
				?></b> tahu apa yang kita lakukan.</p>
				<p><strong>Cek pekerjaan Anda</strong> sudah dikoreksi atau belum setiap akhir bulannya.</p>
			</div>

			<div class="row">
				<div class="col-md-2">
					<div class="form-group <?php echo form_error('tgl_input') ? 'has-error' : ''; ?>">
						<label for="tgl_input">Tanggal Mulai</label>
						<input type="date" name="tgl_input" id="tgl_input" class="form-control" placeholder="Tanggal" style="width:190px;"
							value="<?php echo isset($kegiatan) ? date('Y-m-d', strtotime($kegiatan->waktu_mulai)) : (set_value('tgl_input') == null ? date('Y-m-d') : set_value('tgl_input')); ?>" onchange="get_cek_uraian_by_tgl_mutasi()">
					</div>
				</div>
			</div>

			<div class="form-group <?php echo form_error('uraian_tugas') ? 'has-error' : ''; ?>">
				<label for="uraian_tugas">Uraian Tugas</label>
				<select name="uraian_tugas" id="uraian_tugas" class="form-control select2" onchange="get_analisis_tugas_by_uraian()">
					<option value="">- Pilih Uraian Tugas -</option>
					<?php if (isset($uraian_tugas)): ?>
						<?php foreach ($uraian_tugas as $row): ?>
							<option value="<?php echo $row->id; ?>" <?php echo isset($kegiatan) ? ($kegiatan->pekerjaan_id == $row->id ? 'selected' : '') : set_select('uraian_tugas', $row->id); ?>><?php echo $row->nama_pekerjaan; ?></option>
						<?php endforeach;?>
					<?php endif;?>
				</select>
				<input type="hidden" name="uraian_tugas_temp" id="uraian_tugas_temp" value="<?php echo isset($kegiatan) ? $kegiatan->pekerjaan_id : (set_value('uraian_tugas_temp') == null ? '' : set_value('uraian_tugas_temp')); ?>" />
				<?php echo form_error('uraian_tugas', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('analisis_tugas') ? 'has-error' : ''; ?>">
				<label for="analisis_tugas">Analisis Tugas</label>
				<select name="analisis_tugas" id="analisis_tugas" class="form-control select2" onchange="get_norma_waktu()">
					<option value="">- Pilih Analisis Tugas -</option>
					<?php if (isset($analisis_tugas_kegiatan)): ?>
						<?php foreach ($analisis_tugas_kegiatan as $row): ?>
							<option value="<?php echo encode_crypt($row->id); ?>" <?php echo isset($kegiatan) ? ($kegiatan->rincian_pekerjaan_id == $row->id ? 'selected' : '') : (isset($analisis_tugas_kegiatan_tempp) ? ($analisis_tugas_kegiatan_tempp->id == $row->id ? 'selected' : '') : ''); ?>><?php echo $row->nama_rincian . " | " . "Maksimal " . $row->norma_waktu . " Menit" . " | " . $row->nm_satuan; ?></option>
						<?php endforeach;?>
					<?php endif;?>
				</select>
				<input type="hidden" name="analisis_tugas_temp" id="analisis_tugas_temp" value="<?php echo isset($kegiatan) ? $kegiatan->rincian_pekerjaan_id : (set_value('analisis_tugas_temp') == null ? '' : set_value('analisis_tugas_temp')); //echo set_value('analisis_tugas_temp');               ?>" />
				<?php echo form_error('analisis_tugas', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="row">
				<div class="col-md-2">
					<div class="form-group <?php echo form_error('jam_input') ? 'has-error' : ''; ?>">
						<label for="jam_input">Jam Mulai</label>
						<input type="text" name="jam_input" id="jam_input" class="form-control" placeholder="Jam" style="width:100%;"
							value="<?php echo isset($kegiatan) ? date('H:i', strtotime($kegiatan->waktu_mulai)) : (set_value('jam_input') == null ? date('H:i') : set_value('jam_input')); ?>">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group <?php echo form_error('durasi_input') ? 'has-error' : ''; ?>">
						<label for="durasi_input">Durasi (Menit)</label>
						<input type="text" name="durasi_input" id="durasi_input" class="form-control" placeholder="Durasi" style="width:65px;"
							value="<?php echo isset($kegiatan) ? $kegiatan->norma_waktu : (set_value('durasi_input') == null ? '0' : set_value('durasi_input')); ?>">
					</div>
				</div>
				<div class="col-md-12">
					<?php echo form_error('jam_input', '<p class="help-block text-red">', '</p>'); ?>
					<?php echo form_error('durasi_input', '<p class="help-block text-red">', '</p>'); ?>
				</div>
			</div>

			<div class="form-group <?php echo form_error('nama_kegiatan') ? 'has-error' : ''; ?>">
				<label for="nama_kegiatan">Nama Pekerjaan</label>
				<textarea name="nama_kegiatan" id="nama_kegiatan" class="form-control" placeholder="Nama Pekerjaan"
					cols="30"
					rows="4"><?php echo isset($kegiatan) ? $kegiatan->nama_kegiatan : set_value('nama_kegiatan'); ?></textarea>
					<th colspan="0"><span style="color: #c0c0c0;">* <strong>Contoh :</strong> Mengetik Surat Pengantar.</span></th>
				<?php echo form_error('nama_kegiatan', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('hasil_pekerjaan') ? 'has-error' : ''; ?>">
				<label for="hasil_pekerjaan">Hasil Pekerjaan</label>
				<textarea name="hasil_pekerjaan" id="hasil_pekerjaan" class="form-control" placeholder="Hasil Pekerjaan"
					cols="30"
					rows="4"><?php echo isset($kegiatan) ? $kegiatan->output : set_value('hasil_pekerjaan'); ?></textarea>
					<th colspan="0"><span style="color: #c0c0c0;">* <strong>Contoh :</strong> 1 Lembar Surat Pengantar telah diketik.</span></th>
				<?php echo form_error('hasil_pekerjaan', '<p class="help-block text-red">', '</p>'); ?>
			</div>

            <div class="form-group">
                <label for="file_pendukung">File Pendukung (max: 5mb)</label>
                <div class="">
                    <?php if (isset($kegiatan)): ?>
						<a href="<?php echo !is_null($kegiatan->file_pendukung) && !empty($kegiatan->file_pendukung) ? base_url(get_config_item('image_path') . $kegiatan->file_pendukung) : ''; ?>" target="_blank">
							<img src="<?php echo !empty($kegiatan->file_pendukung) ? base_url(get_config_item('image_path') . $kegiatan->file_pendukung) : base_url(get_config_item('image_path') . 'no_image.png'); ?>" style="border: 1px solid #ddd;border-radius: 4px;padding: 5px;width: 350px;" class="rounded float-left">
						</a>
                	<?php endif;?>
                    <input type="file" name="file_pendukung" id="file_pendukung" <?php echo isset($kegiatan) ? "" : "class='checkval'"; ?>>
                </div>
            </div>

			<div class="form-group">
                <label for="dokumen_lampiran">Dokumen Lampiran PDF (max: 5mb)</label>
                <div class="">
                    <?php if (isset($kegiatan) && !empty($kegiatan->dokumen_lampiran)): ?>
                        <a href="<?php echo !is_null($kegiatan->dokumen_lampiran) && !empty($kegiatan->dokumen_lampiran) ? base_url(get_config_item('lampiran_path') . $kegiatan->dokumen_lampiran) : ''; ?>" target="_blank">
                            Dokumen Lampiran PDF
                        </a><br>
                    <?php endif;?>
					<input type="file" name="dokumen_lampiran" id="dokumen_lampiran" <?php echo isset($kegiatan) ? "" : "class='checkval'"; ?>>
                </div>
            </div>

		</div><!-- /.box-body -->

		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
		<?php echo form_close(); ?>
	</div>

</section><!-- /.content -->

<script>

	var timepicker = new TimePicker('#jam_input', {
	lang: 'idn',
	theme: 'dark'
	});
	timepicker.on('change', function(evt) {

	var value = (evt.hour || '00') + ':' + (evt.minute || '00');
	evt.element.value = value;

	});

// let selected_jabatan = $("select[name=selected_jabatan]").val() != null ? $("select[name=selected_jabatan]").val() : _selected_jabatan_tupoksix;
	let _selected_uraian_tugas = $(".content").attr('data-selected_uraian_tugas');

	$(function() {
		let selected_uraian_tugas_temp = $("input[name=uraian_tugas_temp]").val();

        get_cek_uraian_by_tgl_mutasi();
		if(selected_uraian_tugas_temp != null){
			get_analisis_tugas_by_uraian();
		}
	});

	//temporary jabatan mutasi
	function get_cek_uraian_by_tgl_mutasi() {
		let selected_tgl_input = $("input[name=tgl_input]").val();
		let selected_uraian_tugas_temp = $("input[name=uraian_tugas_temp]").val();

		$("select[name=uraian_tugas]").html('<option value="">- Pilih Uraian Tugas -</option>');

			$.get(base_url + '/api/get_cek_uraian_bytglmutasi', {
				selected_tgl_input: selected_tgl_input
		    })
            .then(function (response) {
                $.each(response, function (key, value) {
					$("select[name=uraian_tugas]").append(
							"<option value='" + value.id_pekerjaan_encrypt + "' "+(value.id == _selected_uraian_tugas ? 'selected' : (value.id == selected_uraian_tugas_temp ? 'selected' : ''))+">" + value.nama_pekerjaan + "</option>"
					);
				});
            });
	}

	function get_analisis_tugas_by_uraian() {
		let selected_uraian_tugas_add = $("select[name=uraian_tugas]").val();
		let selected_uraian_tugas_edit = $("input[name=uraian_tugas_temp]").val() != null ? $("input[name=uraian_tugas_temp]").val() : $("select[name=uraian_tugas]").val();
		let selected_analisis_tugas_tmp = $("input[name=analisis_tugas_temp]").val();

		$("select[name=analisis_tugas]").html('<option value="">- Pilih Analisis Tugas -</option>');

            $.get(base_url + '/api/get_analisis_tugas_byuraian', {
				selected_uraian_tugas_add: selected_uraian_tugas_add,
				selected_uraian_tugas_edit: selected_uraian_tugas_edit
		    })
            .then(function (response) {
                $.each(response, function (key, value) {
					$('#uraian_tugas_temp').val(value.id_pekerjaan);
					$("select[name=analisis_tugas]").append(
						"<option value='" + value.id_rincian_pekerjaan_encrypt + "' "+(value.id == selected_analisis_tugas_tmp ? 'selected' : '')+">" + value.nama_rincian + " | " + "Maksimal " + value.norma_waktu + " Menit" + " | " + value.nm_satuan + "</option>"
					);
				});
            });
	}

	function get_norma_waktu(){
		let selected_analisis_tugas = $("select[name=analisis_tugas]").val();

		$.get(base_url + '/api/get_jam_byanalisis_tugas', {
			selected_analisis_tugas: selected_analisis_tugas
		})
        .then(function (response) {
            $.each(response, function (key, value) {
				$('#analisis_tugas_temp').val(value.id);
				$('#durasi_input').val(value.norma_waktu);
			});
        });
	}

</script>
