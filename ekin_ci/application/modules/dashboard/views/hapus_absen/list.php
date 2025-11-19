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
				<div class="col-md-4">
					<div class="form-group">
						<select class="form-control select2" name="selected_sopd" onchange="getData()"
							style="width: 100%;">
							<?php if (get_session('id_groups') == '1' || get_session('id_groups') == '5'): ?>
							<option value="">- Pilih SOPD -</option>
							<?php endif;?>
							<?php if ($all_sopd): ?>
							<?php foreach ($all_sopd as $row): ?>
							<option value="<?php echo encode_crypt($row->KD_UNOR); ?>"><?php echo $row->NM_UNOR; ?>
							</option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>

                <div class="col-md-2">
					<div class="form-group">
						<select class="form-control" name="selected_date" onchange="getData()">
							<option value="">- Pilih Tanggal -</option>
								<?php for ($i = 1; $i <= 31; $i++): ?>
									<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
								<?php endfor;?>
						</select>
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<select class="form-control" name="selected_month" onchange="getData()">
							<option value="">- Pilih Bulan -</option>
							<?php if ($all_month): ?>
							<?php foreach ($all_month as $row): ?>
							<option value="<?php echo encode_crypt($row->month); ?>" data-month="<?php echo $row->month; ?>"><?php echo $row->month_text; ?></option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>

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

			</div>
		</div>

		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<div class="alert alert-info" role="alert">
							Hanya untuk menghapus absen DL, DD, atau CT..
						</div>
						<?php alert_message_dashboard();?>
						<table id="datatableHA" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>NIP</th>
									<th>Nama</th>
									<th>Tanggal</th>
									<th>Jenis</th>
									<th>Jam</th>
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
	var datatable = $('#datatableHA').DataTable({
		"columns": [{
				"width": "10"
			},
			{
				"width": "100"
			},
			{
				"width": "300"
			},
			null,
			null,
			{
				"width": "100"
			},
			{
				"width": "100"
			},
		],
		"aaSorting": [],
	});

	let _id_groups = $(".content").attr('data-id_groups');
	let _updated = $(".content").attr('data-updated');
	let _deleted = $(".content").attr('data-deleted');

	$(function () {
		if(_id_groups != '1' && _id_groups != '5') {
			getData();
		}
	});

	function getData() {
		let selected_sopd = $("select[name=selected_sopd]").val();
		let selected_date = $("select[name=selected_date]").val();
		let selected_month = $("select[name=selected_month]").val();
		let selected_year = $("select[name=selected_year]").val();
		datatable.clear().draw();

		$("#datatableHA > tbody").html(`
			<tr>
				<td colspan="7" class="text-center">
					<img src="`+base_url +`assets/img/loading.svg">
				</td>
			</tr>
		`);

		$.get(base_url + '/dashboard/hapus-absen/get_data', {
				selected_sopd: selected_sopd,
                selected_date: selected_date,
                selected_month: selected_month,
                selected_year: selected_year
			})
			.then(function (response) {
				if(response != '') {
					let arrData = [];
					$.each(response, function (key, value) {
						let arrAksi = '';
						let data = [
							++key,
							value.PNS_PNSNIP,
							value.PNS_PNSNAM,
							value.tanggal,
							value.jenis,
							value.waktu,
						];

						
						if (_deleted == 1) {
							arrAksi += '<button type="button" class="btn btn-danger" onclick="deleteData(\'' +
								value.id_encrypt + '\')" title="Hapus">' +
								'<i class="ion-trash-a"></i>' +
								'</button>';
						}

						if (arrAksi != '') {
							data.push(arrAksi);
						} else {
							data.push('<div class="td-action">' +
								'<div class="btn-group btn-group-md" role="group" aria-label="...">' +
								'<span class="btn btn-danger" title="Locked"><i class="ion-ios-locked-outline"></i></span>' +
								'</div>' +
								'</div>');
						}
						arrData.push(data);
					});
					datatable.rows.add(arrData).draw(false);
				} else {
					$("#datatableHA > tbody").html(`
						<tr>
							<td colspan="7" class="text-center">
								No data available in table
							</td>
						</tr>
					`);
				}
			});
	}

	function deleteData(id_encrypt) {
		const swalWithBootstrapButtons = Swal.mixin({
			customClass: {
				confirmButton: 'btn btn-success',
				cancelButton: 'btn btn-danger'
			},
			buttonsStyling: false
		})

		swalWithBootstrapButtons.fire({
			title: 'Apakah Anda yakin ingin menghapus data ini?',
			text: "",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Hapus',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.value) {
				$.get(base_url + "/dashboard/hapus-absen/delete", {
					id_encrypt: id_encrypt
				}).then(function () {
					getData();
					swalWithBootstrapButtons.fire(
						'Hapus berhasil',
						'Data berhasil dihapus',
						'success'
					)
				});
			} else if (
				/* Read more about handling dismissals below */
				result.dismiss === Swal.DismissReason.cancel
			) {
				swalWithBootstrapButtons.fire(
					'Hapus dibatalkan',
					'Data aman',
					'error'
				)
			}
		})
	}

</script>