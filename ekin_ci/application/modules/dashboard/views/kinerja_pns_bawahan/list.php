<link rel="stylesheet" href="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.css'); ?>">
<style>
/* > 1350px */
.swal2-popup {
  font-size: 1.6rem !important;
}

.topmenu {
	height: 60px;
	margin-bottom: -18px;
	margin-left: 10px;
	position: relative;
	border: 1px solid #c8ced3;
    border-radius: 0.5rem;
	line-height: 40px;
	box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 2px 4px rgba(0,0,0,0.12);
}

.info-box-icon-ekin {
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
    border-bottom-left-radius: 5px;
    display: block;
    float: left;
    height: 40px;
    width: 40px;
    text-align: center;
    font-size: 20px;
    line-height: 40px;
    background: rgba(0,0,0,0.1);
	margin-right: 4px;
}

/* 1260px - 1435px */
@media (max-width: 1435px) {
	.btn-group-lg>.btn, .btn-lg {
		padding: 10px 16px;
		font-size: 13.5px;
		/* font-weight: bold; */
	}
}

/* 1055px - 1259px */
@media (max-width: 1259px) {
	.btn-group-lg>.btn, .btn-lg {
		padding: 10px 16px;
		font-size: 11px;
	}

	.info-box-icon-ekin-mobile {
        border-top-left-radius: 3px;
		border-top-right-radius: 3px;
		border-bottom-right-radius: 3px;
		border-bottom-left-radius: 3px;
		float: left;
		height: 20px;
		width: 20px;
		margin-top: 10px;
		text-align: center;
		font-size: 10px;
		line-height: 20px;
		background: rgba(0,0,0,0.1);
		margin-right: 2px;
    }
}

/* 905px - 1054px */
@media (max-width: 1054px) {
	.btn-group-lg>.btn, .btn-lg {
		padding: 10px 16px;
		font-size: 10px;
	}

	.info-box-icon-ekin-mobile {
		display: none;
    }
}

@media (max-width: 768px) {
    .topmenu {
        display: none;
    }

	.info-box-icon-ekin {
        display: none;
    }

	.topmenu-mobile {
		display: -webkit-inline-box;
		height: 30px;
		margin-bottom: 2px;
		border: 1px solid #c8ced3;
    	border-radius: 0.5rem;
		padding-top: 0px;
		padding-right: 10px;
		padding-bottom: 0px;
		padding-left: 10px;
		font-size: 14px;
		line-height: 30px;
		box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 2px 4px rgba(0,0,0,0.12);
	}

	.info-box-icon-ekin-mobile {
        border-top-left-radius: 3px;
		border-top-right-radius: 3px;
		border-bottom-right-radius: 3px;
		border-bottom-left-radius: 3px;
		/* display: block; */
		float: left;
		height: 20px;
		width: 20px;
		text-align: center;
		font-size: 10px;
		line-height: 20px;
		background: rgba(0,0,0,0.1);
		margin-right: 2px;
    }
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
				<?php $idcry = encode_crypt($pns->id); ?>
				<a href ="<?php echo base_url("/dashboard/kinerja_pns_bawahan/index?id_encrypt=$idcry"); ?>" id="" class="btn btn-lg btn-primary topmenu topmenu-mobile">
					<span class="info-box-icon-ekin info-box-icon-ekin-mobile"><i class="fa fa-book"></i></span> Kinerja Belum Diperiksa</a>
				<a href ="<?php echo base_url("/dashboard/kinerja_pns_bawahan_koreksi/index?id_encrypt=$idcry"); ?>" id="" class="btn btn-lg btn-warning topmenu topmenu-mobile">
					<span class="info-box-icon-ekin info-box-icon-ekin-mobile"><i class="fa fa-edit"></i></span> Kinerja Dikoreksi</a>
				<a href ="<?php echo base_url("/dashboard/kinerja_pns_bawahan_setujui/index?id_encrypt=$idcry"); ?>" id="" class="btn btn-lg btn-success topmenu topmenu-mobile">
					<span class="info-box-icon-ekin info-box-icon-ekin-mobile"><i class="fa fa-check-square"></i></span> Kinerja Disetujui</a>
				<a href ="<?php echo base_url("/dashboard/kinerja_pns_bawahan_ditolak/index?id_encrypt=$idcry"); ?>" id="" class="btn btn-lg btn-danger topmenu topmenu-mobile">
					<span class="info-box-icon-ekin info-box-icon-ekin-mobile"><i class="fa fa-shield"></i></span> Kinerja Ditolak</a>
			</form>
			</nav>
		</div>
	</div>

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header with-border">
		<div class="row">
                <div class="col-md-1">
                    <div class="form-group">
                        <table class="table table-borderless">
                            <?php
                                $photoPath = base_url('/assets/img/upload/user/');
                                $no_image = base_url('/assets/img/user.png');
                            ?>
                            <tr style="padding: 2px;">
                                <span>
                                    <img src="<?php echo (($pns->PNS_PHOTO != null) ? $photoPath . $pns->PNS_PHOTO : $no_image) ?>" width="90" title="<?php //echo $pns->PNS_PNSNAM; ?>" />
                                </span>
                            </tr>
                        </table>
                    </div>
				</div>
                <div class="col-md-4">
                    <div class="form-group">
                        <table class="table table-borderless">
                            <tr style="padding: 2px;">
                                <th style="padding: 2px;">Nama</th>
                                <td style="padding: 2px;">:</td>
                                <td style="padding: 2px;"><b><?php echo $pns->PNS_PNSNAM; ?></b></td>
                            </tr>
                            <tr style="padding: 2px;">
                                <th style="padding: 2px;">NIP</th>
                                <td style="padding: 2px;">:</td>
                                <td style="padding: 2px;"><?php echo $pns->PNS_PNSNIP; ?></td>
                            </tr>
                            <tr style="padding: 2px;">
                                <th style="padding: 2px;">Pangkat/Gol</th>
                                <td style="padding: 2px;">:</td>
                                <td style="padding: 2px;"><?php echo $pns->nm_pangkat.'('.$pns->nm_golongan.')'; ?></td>
                            </tr>
                            <tr style="padding: 2px;">
                                <th style="padding: 2px;">Jabatan</th>
                                <td style="padding: 2px;">:</td>
                                <td style="padding: 2px;"><?php echo $pns->nm_jab; ?></td>
                            </tr>
                        </table>
                    </div>
				</div>
				<div class="col-md-3">
                    <div class="form-group">
					</div>
				</div>
                <div class="col-md-2" style="margin-top: 65px;">
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
                <div class="col-md-2" style="margin-top: 65px;">
					<div class="form-group"><!-- pull-right -->
                        <select class="form-control" name="selected_month" onchange="getData()">
							<option value="">- Pilih Bulan -</option>
							<?php if ($all_month): ?>
							<?php foreach ($all_month as $row): ?>
                            <option value="<?php echo $row->month; ?>" <?php echo isset($month) ? (substr($month, 1) == $row->month ? 'selected' : '') : ''; ?>><?php echo $row->month_text; ?></option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
                        <input type="hidden" name="id_temp" id="id_temp" value="<?php echo encode_crypt($pns->id); ?>" />
					</div>
				</div>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
					<form id="fromTanggapanKeg" action="<?php echo base_url('/dashboard/kinerja_pns_bawahan/tanggapan-all-kegiatan-penilai'); ?>" method="post">
						<?php alert_message_dashboard();?>
						<table id="datatableKegiatanKinerjaBwh" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>Waktu Kerja</th>
									<th>Norma Waktu</th>
                                    <th>Analisis Tugas</th>
                                    <th>Nama Pekerjaan</th>
                                    <th>Hasil Pekerjaan</th>
                                    <th>File</th>
									<th>Aksi</th>
									<th>
										<button class="btn btn-sm btn-success btncheckboxsetuju" type="submit" style="height:22px" title="Setuju Semua" value="1">
                            			<i class="fa fa-check-circle" style="position: absolute; margin:-6px 0px 0px -5px;"></i></button><br />
										<button class="btn btn-sm btn-danger btncheckboxtolak" type="submit" style="height:22px" title="Tolak Semua" value="4">
                            			<i class="fa fa-times-circle" style="position: absolute; margin:-6px 0px 0px -5px;"></i></button><br />
										<input style="margin-left: 5px;" type="checkbox" id="select_all" />
										<input type="hidden" name="id_temps" id="id_temps" value="<?php echo encode_crypt($pns->id); ?>" />
										<input type="hidden" name="stt" id="stt" value="" />
										<input type="hidden" name="countmax" id="countmax" value="" />
										<input type="hidden" name="summax" id="summax" value="" />
									</th>
								</tr>
							</thead>
						</table>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>
<!-- /.content -->

<!-- Modal setujui -->
<div class="modal fade" id="setujuiModal" tabindex="-1" role="dialog" aria-labelledby="setujuiModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Konfirmasi LKH Penilai</h4>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <span class="origin-form">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Uraian Tugas</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textUT"></td>
										</tr>
									</table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Analisis Tugas</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textAT"></td>
										</tr>
									</table>
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-md-3 col-form-label">Waktu Kerja</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textWktKerja"></td>
										</tr>
									</table>
									<input type="hidden" nama="norma_wkt" id="norma_wkt" class="form-control norma_wkt">
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-md-3 col-form-label">Durasi Yang Diajukan</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textDurasi"></td>
											<td>&nbsp;Menit</td>
										</tr>
									</table>
									<input type="hidden" nama="idkegiatanedit" id="idkegiatanedit" class="form-control idkegiatanedit">
									<!--<textarea name="nama_rincian_pekerjaan_edit" id="nama_rincian_pekerjaan_edit" cols="30" rows="2" class="form-control namarincianpekerjaanedit" placeholder="Nama Rincian Pekerjaan" required></textarea> -->
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-md-3 col-form-label">Durasi Yang Disetujui</label>
                                <div class="col-md-2">
									<input type="text" name="textdurasiedit" id="textdurasiedit" class="form-control textdurasiedit" placeholder="Durasi Yang Disetujui" value="" required>
                                </div>
								<div class="col-md-1" style="margin-left: -15px;">Menit</div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Nama Pekerjaan</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textNP"></td>
										</tr>
									</table>
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-md-3 col-form-label">Hasil Pekerjaan</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textHP"></td>
										</tr>
									</table>
                                </div>
                            </div>
                            <hr>
                        </span>
                        <div class="place-clone"></div>
                    </div>
                    <div class="card-footer">
						<button class="btn btn-sm btn-primary save-edit-setujui-penilai">
                            <i class="fa fa-dot-circle-o"></i> Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal setujui end -->

<!-- Modal koreksi -->
<div class="modal fade" id="koreksiModal" tabindex="-1" role="dialog" aria-labelledby="koreksiModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Konfirmasi LKH Penilai (Koreksi)</h4>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <span class="origin-form">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Uraian Tugas</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textUTKoreksi"></td>
										</tr>
									</table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Analisis Tugas</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textATKoreksi"></td>
										</tr>
									</table>
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-md-3 col-form-label">Waktu Kerja</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textWktKerjaKoreksi"></td>
										</tr>
									</table>
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-md-3 col-form-label">Durasi Yang Diajukan</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textDurasiKoreksi"></td>
											<td>&nbsp;Menit</td>
										</tr>
									</table>
									<input type="hidden" nama="idkegiataneditkoreksi" id="idkegiataneditkoreksi" class="form-control idkegiataneditkoreksi">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Nama Pekerjaan</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textNPKoreksi"></td>
										</tr>
									</table>
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-md-3 col-form-label">Hasil Pekerjaan</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textHPKoreksi"></td>
										</tr>
									</table>
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-md-3 col-form-label">Komentar/Arahan</label>
                                <div class="col-md-9">
									<textarea name="textkomentarkoreksi" id="textkomentarkoreksi" cols="30" rows="2" class="form-control textkomentarkoreksi" placeholder="Komentar/Arahan Koreksi" required></textarea>
                                </div>
                            </div>
                            <hr>
                        </span>
                        <div class="place-clone"></div>
                    </div>
                    <div class="card-footer">
						<button class="btn btn-sm btn-primary save-edit-koreksi-penilai">
                            <i class="fa fa-dot-circle-o"></i> Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal koreksi end -->

<!-- Modal tolak -->
<div class="modal fade" id="tolakModal" tabindex="-1" role="dialog" aria-labelledby="tolakModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Konfirmasi LKH Penilai (Tolak)</h4>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <span class="origin-form">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Uraian Tugas</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textUTTolak"></td>
										</tr>
									</table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Analisis Tugas</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textATTolak"></td>
										</tr>
									</table>
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-md-3 col-form-label">Waktu Kerja</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textWktKerjaTolak"></td>
										</tr>
									</table>
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-md-3 col-form-label">Durasi Yang Diajukan</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textDurasiTolak"></td>
											<td>&nbsp;Menit</td>
										</tr>
									</table>
									<input type="hidden" nama="idkegiatanedittolak" id="idkegiatanedittolak" class="form-control idkegiatanedittolak">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Nama Pekerjaan</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textNPTolak"></td>
										</tr>
									</table>
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-md-3 col-form-label">Hasil Pekerjaan</label>
                                <div class="col-md-9">
									<table>
										<tr>
											<td width="5px">:&nbsp;</td>
											<td id="textHPTolak"></td>
										</tr>
									</table>
                                </div>
                            </div>
                            <hr>
                        </span>
                        <div class="place-clone"></div>
                    </div>
                    <div class="card-footer">
						<button class="btn btn-sm btn-primary save-edit-tolak-penilai">
                            <i class="fa fa-dot-circle-o"></i> Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal tolak end -->

<script>
	var datatable = $('#datatableKegiatanKinerjaBwh').DataTable({
		"columns": [{
				"width": "1"
			},
			{
				"width": "60"
			},
			{
				"width": "50"
			},
			{
				"width": "80"
			},
            null,
            null,
            {
				"width": "10"
			},
            {
				"width": "85"
			},
			{
				"width": "3"
			},
		],
		"aaSorting": [],
	});

    getData();

	// let _updated = $(".content").attr('data-updated');
	// let _deleted = $(".content").attr('data-deleted');
	let _updated = 1;
	let _deleted = 1;
	let _id_groups = $(".content").attr('data-id_groups');

	// $(function() {
	// 	if(_id_groups != '5') {
	// 		get_jabatan_by_sopd();
	// 	}
	// });

	function getData() {
		let selected_year = $("select[name=selected_year]").val();
		let selected_month = $("select[name=selected_month]").val();
		let id_temp = $("input[name=id_temp]").val();

		$.get(base_url + '/dashboard/kinerja_pns_bawahan/get_data', {
			selected_year: selected_year,
			selected_month: selected_month,
			id_temp: id_temp
		})
			.then(function (response) {
				datatable.clear().draw();
				let arrData = [];
				$.each(response, function (key, value) {
                    var date = new Date(value.waktu_mulai);
                    var date_akhir = new Date(value.waktu_akhir);
                    var tglindo = ('0'+date.getDate()).slice(-2) + '-' + ('0'+(date.getMonth()+1)).slice(-2) + '-' + date.getFullYear();
					var jamindo_mulai = ('0'+date.getHours()).slice(-2) + ':' + ('0'+date.getMinutes()).slice(-2);
                    var jamindo_akhir = ('0'+date_akhir.getHours()).slice(-2) + ':' + ('0'+date_akhir.getMinutes()).slice(-2);
                    let arrAksi = '';
					let arrAksiFile = '';
					let arrAksiCek = '';
					arrData = [
						++key,
						tglindo + ' / ' + jamindo_mulai + ' - ' + jamindo_akhir,
						value.norma_waktu + ' Menit',
                        value.nm_rincian,
                        value.nama_kegiatan,
                        value.output,
					];

					arrAksiFile += '<td align="center">' +
									'<div class="btn-group" align="left">';

					if((value.file_pendukung != '' && value.file_pendukung != null) || (value.dokumen_lampiran != '' && value.dokumen_lampiran != null)){
						arrAksiFile +=  '<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" style="">' +
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

						arrAksi += '<button type="button" class="btn btn-success" onclick="setujuiData(\'' +
							value.id_encrypt + '\')" data-toggle="modal" data-target="#setujuiModal" title="Setujui">' +
							'<i class="fa fa-check-circle"></i>' +
							'</button>';

					if (_updated == 1) {
						arrAksi += '<button type="button" class="btn btn-warning" onclick="koreksiData(\'' +
							value.id_encrypt + '\')" data-toggle="modal" data-target="#koreksiModal" title="Koreksi">' +
							'<i class="fa fa-question-circle"></i>' +
							'</button>';
					}

					if (_deleted == 1) {
						arrAksi += '<button type="button" class="btn btn-danger" onclick="tolakData(\'' +
							value.id_encrypt + '\')" data-toggle="modal" data-target="#tolakModal" title="Tolak">' +
							'<i class="fa fa-times-circle"></i>' +
							'</button>';
					}

					arrAksi += 		'</div>' +
							   '</div>';

					arrAksiCek += '<div class="form-check checkbox">'+
									'<input class="form-check-input kegiatancektop" type="checkbox" value="'+ value.id +'" name="idkegiatancektop[]">'+
								  '</div>';

					if (arrAksi != '') {
						arrData.push(arrAksiFile);
						arrData.push(arrAksi);
						arrData.push(arrAksiCek);
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

	function setujuiData(id_encrypt) {
		$.get(base_url + '/api/get_kinerja_bawahan_setujui_byid', {
			id_encrypt: id_encrypt
		})
            .then(function(response) {
                $.each(response, function(key, value) {
					$("#textUT").text(value.nm_pekerjaan);
					$("#textAT").text(value.nm_rincian);
					var date = new Date(value.waktu_mulai);
					var year = date.getFullYear();
					var month = ("00" + (date.getMonth() + 1)).slice(-2);
					var day = ("00" + date.getDate()).slice(-2);
					var hours = ("00" + date.getHours()).slice(-2);
					var minutes = ("00" + date.getMinutes()).slice(-2);
					var d = new Date(value.waktu_akhir);
					var jam = ("00" + d.getHours()).slice(-2);
					var menit = ("00" + d.getMinutes()).slice(-2);
					$("#textWktKerja").text(day + '-' + month + '-' + year + ' / ' + hours + ':' + minutes + ' - ' + jam + ':' + menit);
					$("#textDurasi").text(value.norma_waktu);
					$(".textdurasiedit").val(value.norma_waktu);
					$(".norma_wkt").val(value.norma_wkt);
					$(".idkegiatanedit").val(value.id_kegiatan_encrypt);
					$("#textNP").text(value.nama_kegiatan);
					$("#textHP").text(value.output);
                });
            })

		$('#setujuiModal').on('hidden.bs.modal', function () {
			$(this).find("input,textarea,select").val('').end();
			$("#textUT").html("");
			$("#textAT").html("");
			$("#textWktKerja").html("");
			$("#textDurasi").html("");
			$("#textNP").html("");
			$("#textHP").html("");
		});
	}

	$(".save-edit-setujui-penilai").click(function() {
		let norma_waktu = $("#textdurasiedit").val();
		let id_kegiatan = $("#idkegiatanedit").val();
		let norma_waktu_max = $("#norma_wkt").val();

		var norma_waktu_int = norma_waktu / 1;
		var norma_waktu_max_int = norma_waktu_max / 1;
		
        $.get(base_url + '/api/save_edit_setujui_penilai', {
			id_kegiatan: id_kegiatan,
			norma_waktu: norma_waktu
        })

			.then(function(response) {
				if(norma_waktu_int > norma_waktu_max_int){
					Swal.fire({
						type: 'error',
						title: 'Oops...',
						text: 'Norma waktu melebihi batas maximal menit pekerjaan'
					});
				}else if(norma_waktu_int <= 0){
					Swal.fire({
						type: 'error',
						title: 'Oops...',
						text: 'Norma waktu tidak boleh lebih kecil dari 1 menit'
					});
				}else{
					Swal.fire({
						type: 'success',
						title: 'Sukses',
						text: 'Edit sukses'
					});
					getData();
					datatable.clear().draw();
					$(".modal .close").click();
				}
            })
    });

	function koreksiData(id_encrypt) {
		$.get(base_url + '/api/get_kinerja_bawahan_koreksi_byid', {
			id_encrypt: id_encrypt
		})
            .then(function(response) {
                $.each(response, function(key, value) {
					$("#textUTKoreksi").text(value.nm_pekerjaan);
					$("#textATKoreksi").text(value.nm_rincian);
					var date = new Date(value.waktu_mulai);
					var year = date.getFullYear();
					var month = ("00" + (date.getMonth() + 1)).slice(-2);
					var day = ("00" + date.getDate()).slice(-2);
					var hours = ("00" + date.getHours()).slice(-2);
					var minutes = ("00" + date.getMinutes()).slice(-2);
					var d = new Date(value.waktu_akhir);
					var jam = ("00" + d.getHours()).slice(-2);
					var menit = ("00" + d.getMinutes()).slice(-2);
					$("#textWktKerjaKoreksi").text(day + '-' + month + '-' + year + ' / ' + hours + ':' + minutes + ' - ' + jam + ':' + menit);
					$("#textDurasiKoreksi").text(value.norma_waktu);
					$(".textkomentarkoreksi").val(value.komentar_penilai);
					$(".idkegiataneditkoreksi").val(value.id_kegiatan_encrypt);
					$("#textNPKoreksi").text(value.nama_kegiatan);
					$("#textHPKoreksi").text(value.output);
                });
            })

		$('#koreksiModal').on('hidden.bs.modal', function () {
			$(this).find("input,textarea,select").val('').end();
			$("#textUTKoreksi").html("");
			$("#textATKoreksi").html("");
			$("#textWktKerjaKoreksi").html("");
			$("#textDurasiKoreksi").html("");
			$("#textNPKoreksi").html("");
			$("#textHPKoreksi").html("");
		});
	}

	$(".save-edit-koreksi-penilai").click(function() {
		let komentar_penilai = $("#textkomentarkoreksi").val();
		let id_kegiatan = $("#idkegiataneditkoreksi").val();
		
        $.get(base_url + '/api/save_edit_koreksi_penilai', {
			id_kegiatan: id_kegiatan,
			komentar_penilai: komentar_penilai
        })

			.then(function(response) {
				if(komentar_penilai == ""){
					Swal.fire({
						type: 'error',
						title: 'Oops...',
						text: 'Komentar penilai tidak boleh kosong'
					});
				}else{
					Swal.fire({
						type: 'success',
						title: 'Sukses',
						text: 'Edit sukses'
					});
					getData();
					datatable.clear().draw();
					$(".modal .close").click();
				}
            })
    });

	function tolakData(id_encrypt) {
		$.get(base_url + '/api/get_kinerja_bawahan_tolak_byid', {
			id_encrypt: id_encrypt
		})
            .then(function(response) {
                $.each(response, function(key, value) {
					$("#textUTTolak").text(value.nm_pekerjaan);
					$("#textATTolak").text(value.nm_rincian);
					var date = new Date(value.waktu_mulai);
					var year = date.getFullYear();
					var month = ("00" + (date.getMonth() + 1)).slice(-2);
					var day = ("00" + date.getDate()).slice(-2);
					var hours = ("00" + date.getHours()).slice(-2);
					var minutes = ("00" + date.getMinutes()).slice(-2);
					var d = new Date(value.waktu_akhir);
					var jam = ("00" + d.getHours()).slice(-2);
					var menit = ("00" + d.getMinutes()).slice(-2);
					$("#textWktKerjaTolak").text(day + '-' + month + '-' + year + ' / ' + hours + ':' + minutes + ' - ' + jam + ':' + menit);
					$("#textDurasiTolak").text(value.norma_waktu);
					$(".idkegiatanedittolak").val(value.id_kegiatan_encrypt);
					$("#textNPTolak").text(value.nama_kegiatan);
					$("#textHPTolak").text(value.output);
                });
            })

		$('#tolakModal').on('hidden.bs.modal', function () {
			$(this).find("input,textarea,select").val('').end();
			$("#textUTTolak").html("");
			$("#textATTolak").html("");
			$("#textWktKerjaTolak").html("");
			$("#textDurasiTolak").html("");
			$("#textNPTolak").html("");
			$("#textHPTolak").html("");
		});
	}

	$(".save-edit-tolak-penilai").click(function() {
		let id_kegiatan = $("#idkegiatanedittolak").val();
		
        $.get(base_url + '/api/save_edit_tolak_penilai', {
			id_kegiatan: id_kegiatan
        })

			.then(function(response) {
				if(id_kegiatan != null){
					Swal.fire({
						type: 'success',
						title: 'Sukses',
						text: 'Tolak sukses'
					});
					getData();
					datatable.clear().draw();
					$(".modal .close").click();
				}
            })
    });

//
	var table = $('#datatableKegiatanKinerjaBwh').DataTable();
    $('#select_all').click(function () {
        var checkbox = $('.kegiatancektop:checkbox', table.rows().nodes()).prop('checked', this.checked);
		var id = "";
        for(var i = 0; i < checkbox.length; i++){
            if(checkbox[i].checked){
                id = id + checkbox[i].value +", ";
            }
		}
		$('#countmax').val(id.replace(/,\s*$/, ""));
		$('#summax').val(checkbox.length);
    });

	$(document).on('click','.kegiatancektop',function (){
		if($('.kegiatancektop:checked').length == $('.kegiatancektop').length){
			$('#select_all').prop('checked',true);
		}else{
			$('#select_all').prop('checked',false);
			document.querySelector('#countmax').value = '';
			document.querySelector('#summax').value = '';
		}
	});

	$(document).on('click', '.btncheckboxsetuju', function(e) {
		e.preventDefault();
		var status = $('.btncheckboxsetuju').val();
		$('#stt').val(status);
		Swal.fire({
			title: 'Setujui Pekerjaan',
            text: "Apakah Anda yakin akan menyetujui pekerjaan yang terpilih ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ok',
            cancelButtonText: 'Batal'
		}).then(function (result) {
			if (result.value) {
				$('#fromTanggapanKeg').submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                Swal.fire(
                    'Penyetujuan dibatalkan',
                    'Data aman',
                    'error'
                )
            }
		});
	});

	$(document).on('click', '.btncheckboxtolak', function(e) {
		e.preventDefault();
		var status = $('.btncheckboxtolak').val();
		$('#stt').val(status);
		Swal.fire({
			title: 'Tolak Pekerjaan',
            text: "Apakah Anda yakin akan menolak pekerjaan yang terpilih ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ok',
            cancelButtonText: 'Batal'
		}).then(function (result) {
			if (result.value) {
				$('#fromTanggapanKeg').submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                Swal.fire(
                    'Penolakan dibatalkan',
                    'Data aman',
                    'error'
                )
            }
		});
	});

</script>
