<link rel="stylesheet" href="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.css'); ?>">
<style>
	.swal2-popup {
		font-size: 1.6rem !important;
	}

</style>
<script src="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.js'); ?>"></script>

<!-- Main content -->
<section class="content" data-deleted="<?php echo $_deleted; ?>">

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header" style="padding-bottom: 0;">
			<div class="row">
				<div class="col-md-12">
					<?php alert_message_dashboard();?>
					<?php if( $_created == '1'): ?>
						<a href="<?php echo site_url('dashboard/absen-libur/add'); ?>" class="btn btn-primary">Tambah</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table id="datatableAL" class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>No</th>
									<th>Tanggal</th>
									<th>Keterangan</th>
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
	var datatable = $('#datatableAL').DataTable({
        "columns": [{
				"width": "10"
			},
			{
				"width": "200"
			},
			null,
            {
				"width": "50"
			},
		],
		"aaSorting": [],
		"createdRow": function (row, data, dataIndex) {
			row.children[3].classList.add("text-center");
		}
	});

	getData();

    let _deleted = $(".content").attr('data-deleted');

	function getData() {
		datatable.clear().draw();

		$("#datatableAL > tbody").html(`
			<tr>
				<td colspan="4" class="text-center">
					<img src="`+base_url +`assets/img/loading.svg">
				</td>
			</tr>
		`);

		$.get(base_url + '/dashboard/absen-libur/get_data')
			.then(function (response) {
                if(response != '') {
					let arrData = [];
					$.each(response, function (key, value) {
						let arrAksi = '';
						let data = [
							++key,
							value.tanggal,
							value.nama_libur
						];

						if (_deleted == 1) {
							arrAksi += '<button type="button" class="btn btn-danger" onclick="deleteData(\'' +
								value.id + '\')" title="Hapus">' +
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
					$("#datatableAL > tbody").html(`
						<tr>
							<td colspan="4" class="text-center">
								No data available in table
							</td>
						</tr>
					`);
				}
			});
	}

    function deleteData(id) {
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
				$.get(base_url + "/dashboard/absen-libur/delete", {
					id: id
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
