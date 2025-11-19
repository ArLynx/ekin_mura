<link rel="stylesheet" href="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.css'); ?>">
<style>
.swal2-popup {
  font-size: 1.6rem !important;
}

.spinner:before {
  content: '';
  box-sizing: border-box;
  position: absolute;
  top: 66%;
  left: 49%;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #00a6a6;
  width: 20px;
  height: 20px;
  -webkit-animation: spinner 2s linear infinite; /* Safari */
  animation: spinner 2s linear infinite;
  z-index:300;
}

/* Safari */
@-webkit-keyframes spinner {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spinner {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<script src="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.js'); ?>"></script>

<!-- Main content -->
<section class="content" data-id_groups="<?php echo get_session('id_groups'); ?>" data-updated="<?php echo $_updated; ?>" data-deleted="<?php echo $_deleted; ?>">

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
                            <option value="<?php echo $row->month; ?>" <?php echo isset($month) ? (substr($month, 1) == $row->month ? 'selected' : '') : ''; ?>><?php echo $row->month_text; ?></option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<select class="form-control select2" name="selected_sopd" onchange="getData()">
							<option value="">- Pilih SOPD -</option>
							<?php if ($all_sopd): ?>
							<?php foreach ($all_sopd as $row): ?>
							<option value="<?php echo encode_crypt($row->KD_UNOR); ?>"><?php echo $row->NM_UNOR; ?></option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<?php alert_message_dashboard();?>
						<table id="datatableBatalkanPenilaianSatu" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th rowspan="2" class="text-center th-top">No</th>
									<th rowspan="2" class="text-center th-top">Foto</th>
									<th rowspan="2" class="text-center th-top">Nama</th>
                                    <th colspan="4" class="text-center">Jumlah Pekerjaan</th>
                                    <th rowspan="2" class="text-center th-top">Waktu Efektif (Menit)</th>
                                    <th rowspan="2" class="text-center th-top">Grade Penilaian</th>
									<th rowspan="2" class="text-center th-top">Aksi</th>
								</tr>
                                <tr>
                                    <th colspan="0" class="text-center">Belum Diperiksa</th>
									<th colspan="0" class="text-center">Dikoreksi</th>
                                    <th colspan="0" class="text-center">Disetujui</th>
                                    <th colspan="0" class="text-center">Ditolak</th>
                                </tr>
							</thead>
							<div id="load" class="spinner"></div>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>
<!-- /.content -->

<script>
	var datatable = $('#datatableBatalkanPenilaianSatu').DataTable({
		"columns": [{
				"width": "1"
			},
			{
				"width": "20"
			},
			{
				"width": "220"
			},
			{
				"width": "10"
			},
			{
				"width": "10"
			},
            {
				"width": "10"
			},
            {
				"width": "10"
			},
            {
				"width": "10"
			},
            {
				"width": "10"
			},
            {
				"width": "25"
			},
		],
		"aaSorting": [],
		"language": {
			"emptyTable": " "
		},
	});

	getData();

    // let _updated = $(".content").attr('data-updated');
    let _updated = 1;
	let _deleted = $(".content").attr('data-deleted');
	let _id_groups = $(".content").attr('data-id_groups');

	function getData() {
		$("#load").show(datatable.clear().draw());
		let selected_year = $("select[name=selected_year]").val();
		let selected_month = $("select[name=selected_month]").val();
		let selected_sopd = $("select[name=selected_sopd]").val();
		$.get(base_url + '/dashboard/Batalkan_penilaian_satu/get_data', {
			selected_year: selected_year,
			selected_month: selected_month,
			selected_sopd: selected_sopd
		})
			.then(function (response) {
				datatable.clear().draw();
				let arrData = [];
				$("#load").hide();
				$.each(response, function (key, value) {
                    let arrAksi = '';
					let photoPath = base_url + '/assets/img/upload/user/';
					let no_image = base_url + '/assets/img/user.png';
					let image = `<img src="${value.PNS_PHOTO != null ? photoPath + value.PNS_PHOTO : no_image}" width="60" title="${value.PNS_PNSNAM}" style="padding: 0px 0px 0px 0px;">`;
					var wef = value.wkt_efektif / 60;

						if(wef >= 110){
							var huruf_wef = 'A';
						}else if(wef >= 94 && wef < 110){
							var huruf_wef = 'B';
						}else if(wef >= 68 && wef < 94){
							var huruf_wef = 'C';
						}else if(wef >= 42 && wef < 68){
							var huruf_wef = 'D';
						}else {
							var huruf_wef = 'E';
						}

						if(wef != 0) {
							if(wef > 6750){
								var wef = 6750;
							} else {
								var tmp = wef * 100 / 6750;
								var presentase = tmp.toFixed(2);
							}
						} else {
							var presentase = 0;
						}

					arrData = [
						"<div align='center'>" + ++key + "</div>",
						"<div align='center'>" + image + "</div>",
						((value.PNS_GLRDPN != null) ? value.PNS_GLRDPN + '. ' : '') + value.PNS_PNSNAM + ((value.PNS_GLRBLK != null) ? ', ' + value.PNS_GLRBLK : '')
						+ "<br>" + 'NIP. ' + value.PNS_PNSNIP
						+ "<br>" + value.NM_PKT + ' (' + value.NM_GOL + ')',
						"<div align='center'>" + value.jml_diajukan + "</div>",
						"<div align='center'>" + value.jml_dikoreksi + "</div>",
						"<div align='center'>" + value.jml_disetujui + "</div>",
						"<div align='center'>" + value.jml_ditolak + "</div>",
						"<div align='center'>" + value.wkt_efektif + "</div>",
						"<div align='center'><td><b style='font-size:25px'>" + huruf_wef + "</b><br /><i style='font-size:11px'><b>" + presentase + ' %' + "</b> Capaian Kinerja</i></td></div>",
					];

					arrAksi += '<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">' +
									'<div class="btn-group btn-group-sm" role="group" aria-label="">';

					if (_updated == 1) {
						//batalkan_kinerja_pns_bawahan
						arrAksi += '<a href="' + base_url + '/dashboard/batalkan_kinerja_penilaian_satu/index?id_encrypt=' + value
							.id_encrypt +
							'" class="btn btn-primary" title="Lihat Detail">' +
							'<i class="fa fa-mail-forward"></i>' +
							'</a> ';
					}

					arrAksi += 		'</div>' +
                               '</div>';

					if (arrAksi != '') {
						arrData.push(arrAksi);
					} else {
						arrData.push('<div class="td-action">' +
							'<div class="btn-group btn-group-sm" role="group" aria-label="...">' +
							'<span class="btn btn-danger" title="Locked"><i class="ion-ios-locked-outline"></i></span>' +
							'</div>' +
							'</div>');
					}
					
					datatable.row.add(arrData).draw(false);
				});
			});
	}

</script>
