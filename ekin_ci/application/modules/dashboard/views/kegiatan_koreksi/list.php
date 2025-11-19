<link rel="stylesheet" href="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/kegiatan-style.css'); ?>">
<style>
.swal2-popup {
  font-size: 1.6rem !important;
}
</style>
<script src="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.js'); ?>"></script>

<!-- Main content -->
<section class="content" data-id_groups="<?php echo get_session('id_groups'); ?>" data-updated="<?php echo $_updated; ?>" data-deleted="<?php echo $_deleted; ?>">

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
	<div class="box">
		<div class="box-header with-border">
			<div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control" name="selected_year" onchange="getData()">
							<option value="">- Pilih Tahun -</option>
							<?php if ($all_year): ?>
							<?php foreach ($all_year as $row): ?>
							<option value="<?php echo $row->year; ?>"
								<?php echo $row->year == date('Y') ? 'selected' : ''; ?>><?php echo $row->year; ?>
							</option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>
                <div class="col-md-2">
					<div class="form-group">
                        <select class="form-control" name="selected_month" onchange="getData()">
							<option value="">- Pilih Bulan -</option>
							<?php if ($all_month): ?>
							<?php foreach ($all_month as $row): ?>
                            <option value="<?php echo $row->month; ?>" <?php echo isset($month) ? ($month == $row->month ? 'selected' : '') : ''; ?>><?php echo $row->month_text; ?></option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
                        <select class="form-control" name="selected_penilai" onchange="getData()">
							<option value="">- Pilih Penilai -</option>
							<?php if ($all_penilai): ?>
							<?php foreach ($all_penilai as $row): ?>
                            <option value="<?php echo $row; ?>" <?php echo $row == 'Atasan Langsung' ? 'selected' : ''; ?>><?php echo $row; ?></option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>
				<div class="pull-right box-tools" style="padding: 5px 15px 0px 0px;">
					<div class="badge bg-yellow" style="font-size : 14px;">
						<span>Total Kegiatan : <span id="countMenit" class=""></span><span> Menit</span></span>
					</div>
				</div>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<?php alert_message_dashboard();?>
						<table id="datatableKegiatan" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>Waktu Kerja</th>
									<th>Norma Waktu</th>
                                    <th>Analisis Tugas</th>
                                    <th>Nama Pekerjaan</th>
                                    <th>Hasil Pekerjaan</th>
									<th>Komentar Atasan/Penilai</th>
                                    <th>File</th>
									<th>Aksi</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>
<!-- /.content -->

<script>
	var datatable = $('#datatableKegiatan').DataTable({
		"columns": [{
				"width": "1"
			},
			{
				"width": "60"
			},
			{
				"width": "60"
			},
			{
				"width": "80"
			},
            null,
            null,
			null,
            {
				"width": "10"
			},
            {
				"width": "25"
			},
		],
		"aaSorting": [],
	});

    getData();
    $("a#addLinkKegiatan").hide();

	// let _updated = $(".content").attr('data-updated');
	let _updated = 1;
	// let _deleted = $(".content").attr('data-deleted');
	let _id_groups = $(".content").attr('data-id_groups');

	// $(function() {
	// 	if(_id_groups != '5') {
	// 		get_jabatan_by_sopd();
	// 	}
	// });

	function getData() {
		let selected_year = $("select[name=selected_year]").val();
		let selected_month = $("select[name=selected_month]").val();
		let selected_penilai = $("select[name=selected_penilai]").val();

		const Rib = value => currency(value, {
            symbol: "",
            precision: 0,
            separator: "."
        });

		$.get(base_url + '/dashboard/kegiatan_koreksi/get_data', {
			selected_year: selected_year,
			selected_month: selected_month,
			selected_penilai: selected_penilai
		})
			.then(function (response) {
				let countMenit = 0;
				datatable.clear().draw();
                $("a#addLinkKegiatan").attr('href', base_url + '/dashboard/kegiatan/add/');
                $("a#addLinkKegiatan").show();
				let arrData = [];
				$.each(response, function (key, value) {
                    var date = new Date(value.waktu_mulai);
                    var date_akhir = new Date(value.waktu_akhir);
                    var tglindo = ('0'+date.getDate()).slice(-2) + '-' + ('0'+(date.getMonth()+1)).slice(-2) + '-' + date.getFullYear();
					var jamindo_mulai = ('0'+date.getHours()).slice(-2) + ':' + ('0'+date.getMinutes()).slice(-2);
                    var jamindo_akhir = ('0'+date_akhir.getHours()).slice(-2) + ':' + ('0'+date_akhir.getMinutes()).slice(-2);
                    let arrAksi = '';
					let arrAksiFile = '';
					arrData = [
						++key,
						tglindo + ' / ' + jamindo_mulai + ' - ' + jamindo_akhir,
						value.norma_waktu + ' Menit',
                        value.nm_rincian,
                        value.nama_kegiatan,
                        value.output,
                        value.status == 2 || value.status == 3 ? value.komentar_atasan : value.komentar_penilai,
					];

						arrAksiFile += '<td align="center">' +
										'<div class="btn-group" align="left">';

					if((value.file_pendukung != '' && value.file_pendukung != null) || (value.dokumen_lampiran != '' && value.dokumen_lampiran != null)){
					arrAksiFile += '<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" style="">' +
										'<span class="caret" style=""></span>' +
									'</button>' +
										'<ul class="dropdown-menu" role="menu" style="min-width: 5px;">';
					}

					if(value.file_pendukung != '' && value.file_pendukung != null){
						arrAksiFile += '<li><a class="" href="' + base_url + '/assets/img/upload/image/' + value.file_pendukung +'" target="_blank"><i class="icon ion-image"></i> Image</a></li>';
					}

					if(value.dokumen_lampiran != '' && value.dokumen_lampiran != null){
						arrAksiFile += '<li><a class="" href="' + base_url + '/assets/upload/lampiran/' + value.dokumen_lampiran +'" target="_blank"><i class="icon ion-document"></i>  Dokumen</a></li>';
					}

						arrAksiFile += '</ul>' +
										'</div>' +
									   '</td>';

					arrAksi += '<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">' +
									'<div class="btn-group btn-group-sm" role="group" aria-label="">';

					if (_updated == 1) {
						if(value.status == '2' || value.status == '7'){
							arrAksi += '<a href="' + base_url + '/dashboard/kegiatan_koreksi/edit/' + value
								.id_encrypt +
								'.html" class="btn btn-warning" title="Perbaiki">' +
								'<i class="fa fa-edit"></i>' +
								'</a> ';
						}else{
							arrAksi += '<a href="' + base_url + '/dashboard/kegiatan_koreksi/edit/' + value
								.id_encrypt +
								'.html" class="btn btn-success" title="Sudah Diperbaiki">' +
								'<i class="fa fa-edit"></i>' +
								'</a> ';
							}
					}

					arrAksi += 		'</div>' +
							   '</div>';

					if (arrAksi != '') {
						arrData.push(arrAksiFile);
						arrData.push(arrAksi);
					} else {
						arrData.push('<div class="td-action">' +
							'<div class="btn-group btn-group-sm" role="group" aria-label="...">' +
							'<span class="btn btn-danger" title="Locked"><i class="ion-ios-locked-outline"></i></span>' +
							'</div>' +
							'</div>');
					}

					datatable.row.add(arrData).draw(false);
					countMenit = parseInt(countMenit) + parseInt(value.norma_waktu);
				});
				$("#countMenit").text(Rib(countMenit).format(true));
			});
	}

</script>
