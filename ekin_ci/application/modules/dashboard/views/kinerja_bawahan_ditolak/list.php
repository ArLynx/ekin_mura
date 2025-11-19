<link rel="stylesheet" href="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.css'); ?>">
<style>
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

.btn-group-lg>.btn, .btn-lg {
	padding: 10px 16px;
	font-size: 17px;
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
				<a href ="<?php echo base_url("/dashboard/kinerja_bawahan/index?id_encrypt=$idcry"); ?>" id="" class="btn btn-lg btn-primary topmenu topmenu-mobile">
					<span class="info-box-icon-ekin info-box-icon-ekin-mobile"><i class="fa fa-book"></i></span> Kegiatan Bawahan Belum Diperiksa</a>
				<a href ="<?php echo base_url("/dashboard/kinerja_bawahan_koreksi/index?id_encrypt=$idcry"); ?>" id="" class="btn btn-lg btn-warning topmenu topmenu-mobile">
					<span class="info-box-icon-ekin info-box-icon-ekin-mobile"><i class="fa fa-edit"></i></span> Kegiatan Bawahan Dikoreksi</a>
				<a href ="<?php echo base_url("/dashboard/kinerja_bawahan_setujui/index?id_encrypt=$idcry"); ?>" id="" class="btn btn-lg btn-success topmenu topmenu-mobile">
					<span class="info-box-icon-ekin info-box-icon-ekin-mobile"><i class="fa fa-check-square"></i></span> Kegiatan Bawahan Disetujui</a>
				<a href ="<?php echo base_url("/dashboard/kinerja_bawahan_ditolak/index?id_encrypt=$idcry"); ?>" id="" class="btn btn-lg btn-danger topmenu topmenu-mobile">
					<span class="info-box-icon-ekin info-box-icon-ekin-mobile"><i class="fa fa-shield"></i></span> Kegiatan Bawahan Ditolak</a>
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
                                    <img src="<?php echo (($pns->PNS_PHOTO != null) ? $photoPath . $pns->PNS_PHOTO : $no_image) ?>" width="90" title="<?php echo $pns->PNS_PNSNAM; ?>" />
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
					<div class="form-group">
                        <select class="form-control" name="selected_month" onchange="getData()">
							<option value="">- Pilih Bulan -</option>
							<?php if ($all_month): ?>
							<?php foreach ($all_month as $row): ?>
                            <option value="<?php echo $row->month; ?>" <?php echo isset($month) ? ($month == $row->month ? 'selected' : '') : ''; ?>><?php echo $row->month_text; ?></option>
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
						<?php alert_message_dashboard();?>
						<table id="datatableKegiatanBwhTolak" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>Waktu Kerja</th>
									<th>Norma Waktu</th>
                                    <th>Analisis Tugas</th>
                                    <th>Nama Pekerjaan</th>
                                    <th>Hasil Pekerjaan</th>
                                    <th>File</th>
									<th>Komentar Atasan/Penilai</th>
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
	var datatable = $('#datatableKegiatanBwhTolak').DataTable({
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
		
        $.get(base_url + '/dashboard/kinerja_bawahan_ditolak/get_data', {
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
					arrData = [
						++key,
						tglindo + ' / ' + jamindo_mulai + ' - ' + jamindo_akhir,
						value.norma_waktu + ' Menit',
                        value.nm_rincian,
                        value.nama_kegiatan,
                        value.output,
					];

					arrAksi += '<td align="center">' +
									'<div class="btn-group" align="left">';

					if((value.file_pendukung != '' && value.file_pendukung != null) || (value.dokumen_lampiran != '' && value.dokumen_lampiran != null)){
						arrAksi +=  '<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" style="">' +
											'<span class="caret" style=""></span>' +
										'</button>' +
											'<ul class="dropdown-menu" role="menu" style="min-width: 5px;">';
					}

						if(value.file_pendukung != '' && value.file_pendukung != null){
							arrAksi += '<li><a class="" href="' + base_url + '/assets/img/upload/image/' + value.file_pendukung +'" target="_blank"><i class="icon ion-image"></i> Image</a></li>';
						}

						if(value.dokumen_lampiran != '' && value.dokumen_lampiran != null){
							arrAksi += '<li><a class="" href="' + base_url + '/assets/upload/lampiran/' + value.dokumen_lampiran +'" target="_blank"><i class="icon ion-document"></i>  Dokumen</a></li>';
						}

					arrAksi += '</ul>' +
									'</div>' +
								   '</td>';

					if (arrAksi != '') {
						arrData.push(arrAksi);
						arrData.push(value.komentar_atasan);
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
