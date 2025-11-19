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
					<form id="fromTanggapanKeg" action="<?php echo base_url('/dashboard/batalkan_kinerja_penilaian_satu/batalkan-tanggapan-all-kegiatan-penilai'); ?>" method="post">
						<?php alert_message_dashboard();?>
						<table id="datatableBatalKegiatanKinerjaPenilaianSatu" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>Waktu Kerja</th>
									<th>Norma Waktu</th>
                                    <th>Analisis Tugas</th>
                                    <th>Nama Pekerjaan</th>
                                    <th>Hasil Pekerjaan</th>
                                    <th>File</th>
									<th>
										<button class="btn btn-sm btn-success btncheckboxbatalsetuju" type="submit" style="height:22px" title="Batal Setujui" value="1">
                            			<i class="fa fa-check-circle" style="position: absolute; margin:-6px 0px 0px -5px;"></i></button><br />
										<input style="margin-left: 5px;" type="checkbox" id="select_all" />
										<input type="hidden" name="id_temps" id="id_temps" value="<?php echo encode_crypt($pns->id); ?>" />
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

<script>
	var datatable = $('#datatableBatalKegiatanKinerjaPenilaianSatu').DataTable({
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

		$.get(base_url + '/dashboard/batalkan_kinerja_penilaian_satu/get_data', {
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

					arrAksiCek += '<div class="form-check checkbox">'+
									'<input class="form-check-input kegiatancektop" type="checkbox" value="'+ value.id +'" name="idkegiatancektop[]">'+
								  '</div>';

					if (arrAksiCek != '') {
						arrData.push(arrAksiFile);
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

	var table = $('#datatableBatalKegiatanKinerjaPenilaianSatu').DataTable();
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

	$(document).on('click', '.btncheckboxbatalsetuju', function(e) {
		e.preventDefault();
		var status = $('.btncheckboxbatalsetuju').val();
		Swal.fire({
			title: 'Batalkan Pekerjaan',
            text: "Apakah Anda yakin akan membatalkan persetujuan pekerjaan yang terpilih ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ok',
            cancelButtonText: 'Cancel'
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

</script>
