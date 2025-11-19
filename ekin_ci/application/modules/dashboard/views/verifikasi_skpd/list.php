<link rel="stylesheet" href="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.css'); ?>">
<style>
.swal2-popup {
  font-size: 1.6rem !important;
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
							<option value="<?php echo $row->month; ?>" <?php echo isset($month) ? ($month == $row->month ? 'selected' : '') : ''; ?>><?php echo $row->month_text; ?></option>
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
						<form id="fromTanggapanVer" action="<?php echo base_url('/dashboard/verifikasi_skpd/verifikasi-all-skpd'); ?>" method="post">
							<?php alert_message_dashboard();?>
							<table id="datatableVerifikasi" class="table table-striped table-bordered" style="width: 100%;">
								<thead>
									<tr>
										<th rowspan="1" class="text-center th-top">No</th>
										<th rowspan="1" class="text-center th-top">Dinas</th>
										<th rowspan="1" class="text-center th-top">Status Verifikasi</th>
										<th colspan="1" class="text-center th-top">Per Tanggal</th>
										<th class="text-center">Verifikasi ?
											<?php if(get_session('id_groups') != 1): ?>
											<button class="btn-group btn-sm btn-success btncheckboxver" type="submit" style="height:22px" title="Verifikasi" value="1">
											<i class="fa fa-check-circle" style="position: absolute; margin:-6px 0px 0px -5px;"></i></button><br />
											<input type="checkbox" id="select_all" />
											<input type="hidden" name="tahun" id="tahun" value="" />
											<input type="hidden" name="bulan" id="bulan" value="" />
											<input type="hidden" name="countmax" id="countmax" value="" />
											<input type="hidden" name="summax" id="summax" value="" />
											<?php endif; ?>
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
	var datatable = $('#datatableVerifikasi').DataTable({
		"columns": [{
				"width": "1"
			},
			{
				"width": "600"
			},
			null,
			null,
            {
				"width": "68"
			},
		],
		"aaSorting": [],
	});

    getData();

    let _updated = $(".content").attr('data-updated');
	let _deleted = $(".content").attr('data-deleted');
    let _id_groups = $(".content").attr('data-id_groups');
    
    function getData() {
		datatable.clear().draw();
		let selected_year = $("select[name=selected_year]").val();
		let selected_month = $("select[name=selected_month]").val();
		
		$("#datatableVerifikasi > tbody").html(`
			<tr>
				<td colspan="5" class="text-center">
					<img src="`+base_url +`assets/img/loading.svg">
				</td>
			</tr>
		`);
		
		$.get(base_url + '/dashboard/Verifikasi_skpd/get_data', {
			selected_year: selected_year,
			selected_month: selected_month
		})
			.then(function (response) {
			
				if(response != '') {
					let arrData = [];
					$.each(response, function (key, value) {
						let arrAksi = '';

						let data = [
							"<div align='center'>" + ++key + "</div>",
							"<div align=''>" + value.NM_UNOR + "</div>",
							(value.status == 'sudah' ? "<div align='center'><span class='label label-success'>" + value.status + "</div>" : "<div align='center'><span class='label label-warning'>" + value.status + "</div>"),
							"<div align='center'>" + (value.tanggal == null ? '-' : value.tanggal) + "</div>",
						];

						arrAksi += '<div class="text-center" role="toolbar" aria-label="Toolbar with button groups" style="margin-left: -20px;">' +
										'<div class="btn-group btn-group-sm" role="group" aria-label="">';

							if(value.status != 'sudah'){
								arrAksi += '<div class="form-check checkbox">'+
												'<input class="form-check-input vercektop" type="checkbox" value="'+ value.KD_UNOR +'" name="unorvercektop[]">'+
											'</div>';
							} else {
								arrAksi += '<div class="form-check checkbox">'+
											'</div>';
							}

						arrAksi += 		'</div>' +
								'</div>';

						if (arrAksi != '') {
							data.push(arrAksi);
						} else {
							data.push('<div class="td-action">' +
								'<div class="btn-group btn-group-sm" role="group" aria-label="...">' +
								'<span class="btn btn-danger" title="Locked"><i class="ion-ios-locked-outline"></i></span>' +
								'</div>' +
								'</div>');
						}
						arrData.push(data);
					});
					datatable.rows.add(arrData).draw(false);
				} else {
					$("#datatableVerifikasi > tbody").html(`
						<tr>
							<td colspan="5" class="text-center">
								No data available in table
							</td>
						</tr>
					`);
				}
			});
				
	}

	var table = $('#datatableVerifikasi').DataTable();
    $('#select_all').click(function () {
        var checkbox = $('.vercektop:checkbox', table.rows().nodes()).prop('checked', this.checked);
		var id = "";
        for(var i = 0; i < checkbox.length; i++){
            if(checkbox[i].checked){
                id = id + checkbox[i].value +", ";
            }
		}
		$('#countmax').val(id.replace(/,\s*$/, ""));
		$('#summax').val(checkbox.length);
    });

	$(document).on('click','.vercektop',function (){
		if($('.vercektop:checked').length == $('.vercektop').length){
			$('#select_all').prop('checked',true);
		}else{
			$('#select_all').prop('checked',false);
			document.querySelector('#countmax').value = '';
			document.querySelector('#summax').value = '';
		}
	});

	$(document).on('click', '.btncheckboxver', function(e) {
		e.preventDefault();
		let selected_year = $("select[name=selected_year]").val();
		let selected_month = $("select[name=selected_month]").val();
		$('#tahun').val(selected_year);
		$('#bulan').val(selected_month);
		Swal.fire({
			title: 'Verifikasi SKPD',
            text: "Apakah Anda yakin akan memverifikasi SKPD yang terpilih ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Iya',
            cancelButtonText: 'Tidak'
		}).then(function (result) {
			if (result.value) {
				$('#fromTanggapanVer').submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                Swal.fire(
                    'Verifikasi dibatalkan',
                    'Data aman',
                    'error'
                )
            }
		});
	});

</script>