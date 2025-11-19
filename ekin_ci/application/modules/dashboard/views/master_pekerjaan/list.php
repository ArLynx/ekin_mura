<link rel="stylesheet" href="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.css'); ?>">
<style>
	.swal2-popup {
		font-size: 1.6rem !important;
	}

</style>
<script src="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.js'); ?>"></script>

<!-- Main content -->

<section class="content" data-selected_jabatan_tupoksi="<?php echo get_session('selected_jabatan_tupoksi'); ?>"
	data-selected_jabatan_tupoksix="<?php echo get_session('selected_jabatan_tupoksi_encrypt'); ?>"
	data-id_groups="<?php echo get_session('id_groups'); ?>" data-created="<?php echo $_created ?? 0; ?>" data-updated="<?php echo $_updated; ?>"
	data-deleted="<?php echo $_deleted; ?>">

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header with-border">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<select class="form-control select2" name="selected_sopd" onchange="get_jabatan_by_sopd()">
							<?php if (get_session('id_groups') == '1' || get_session('id_groups') == '5'): ?>
							<option value="">- Pilih SOPD -</option>
							<?php endif;?>
							<?php if ($all_sopd): ?>
							<?php foreach ($all_sopd as $row): ?>
							<option value="<?php echo encode_crypt($row->KD_UNOR); ?>"
								<?php echo get_session('selected_sopd_tupoksi') == $row->KD_UNOR ? 'selected' : ''; ?>>
								<?php echo $row->NM_UNOR; ?>
							</option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
					<div class="form-group">
						<select class="form-control select2" name="selected_jabatan" id="selected_jabatan"
							onchange="getData()">
							<?php if (get_session('id_groups') == '1' || get_session('id_groups') == '5'): ?>
							<option value="">- Pilih Jabatan -</option>
							<?php endif;?>
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<?php if($_created): ?>
							<a href="#" id="addLinkPekerjaan" class="btn btn-primary">Tambah</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<?php alert_message_dashboard();?>
						<table id="datatableMPekerjaan" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama Pekerjaan</th>
									<th>Prioritas</th>
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
	$("a#addLinkPekerjaan").hide();

	let _created = $(".content").attr('data-created');
	let _updated = $(".content").attr('data-updated');
	let _deleted = $(".content").attr('data-deleted');
	let _rincian = _created;
	let _id_groups = $(".content").attr('data-id_groups');
	let _selected_jabatan_tupoksi = $(".content").attr('data-selected_jabatan_tupoksi');
	let _selected_jabatan_tupoksix = $(".content").attr('data-selected_jabatan_tupoksix');

	$(function () {
		get_jabatan_by_sopd();
		getData();
	});

	function getData() {
		var _selected_jabatan_text = $("select[name=selected_jabatan] option:selected").text();
		var datatable = $('#datatableMPekerjaan').DataTable({
			"columns": [{
					"width": "1"
				},
				{
					"width": "850"
				},
				null,
				{
					"width": "150"
				},
			],
			"aaSorting": [],
			"dom": 'Bfrtip',
			"buttons": [{
					extend: 'excelHtml5',
					title: 'Tupoksi ' + _selected_jabatan_text,
					exportOptions: {
						columns: [0, 1, 2]
					},
					text: '<i class="fa fa-file-excel-o"></i> Excel',
				},
				{
					extend: 'pdfHtml5',
					title: 'Tupoksi ' + _selected_jabatan_text,
					exportOptions: {
						columns: [0, 1, 2]
					},
					text: '<i class="fa fa-file-pdf-o"></i> PDF',
				}
			],
			"bDestroy": true
		});

		datatable.clear().draw();
		let selected_sopd = $("select[name=selected_sopd]").val();
		let selected_jabatan = $("select[name=selected_jabatan]").val() ? $("select[name=selected_jabatan]").val() :
			_selected_jabatan_tupoksix;
		
		$("#datatableMPekerjaan > tbody").html(`
			<tr>
				<td colspan="4" class="text-center">
					<img src="`+base_url +`assets/img/loading.svg">
				</td>
			</tr>
		`);
		
		$.get(base_url + '/dashboard/master_pekerjaan/get_data', {
				selected_sopd: selected_sopd,
				selected_jabatan: selected_jabatan
			})
			.then(function (response) {
				$("a#addLinkPekerjaan").attr('href', base_url + '/dashboard/master-pekerjaan/add/' +
					selected_sopd +
					'/' + selected_jabatan);
				$("a#addLinkPekerjaan").show();
				if (response != '') {
					let arrData = [];
					$.each(response, function (key, value) {
						let arrAksi = '';
						let data = [
							++key,
							value.nama_pekerjaan,
							value.prioritas,
						];

						arrAksi +=
							'<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">' +
							'<div class="btn-group btn-group-sm" role="group" aria-label="...">';

						if (_updated == 1) {
							if (value.id_jabatan_encrypt != null && value.id_master_kelas_jabatan_encrypt !=
								null) {
								arrAksi += '<a href="' + base_url + '/dashboard/master-pekerjaan/edit/' + value
									.id_encrypt + '/' + value.unor_encrypt + '/' + value.id_jabatan_encrypt +
									'.html" class="btn btn-warning" title="Ubah">' +
									'<i class="fa fa-edit"></i>' +
									'</a> ';
							} else {
								arrAksi += '<a href="' + base_url + '/dashboard/master-pekerjaan/edit/' + value
									.id_encrypt + '/' + value.unor_encrypt + '/' + value
									.id_master_kelas_jabatan_encrypt +
									'.html" class="btn btn-warning" title="Ubah">' +
									'<i class="fa fa-edit"></i>' +
									'</a> ';
							}
						}

						if (_deleted == 1) {
							arrAksi += '<button type="button" class="btn btn-danger" onclick="deleteData(\'' +
								value.id_encrypt + '\')" title="Hapus">' +
								'<i class="ion-trash-a"></i>' +
								'</button>';
						}

						arrAksi += '</div>' +
							'<div class="btn-group btn-group-sm" role="group" aria-label="...">';

						if (_rincian == 1) {
							arrAksi += '<a href="' + base_url +
								'/dashboard/master-rincian-pekerjaan/index?id_encrypt=' + value
								.id_encrypt + '&id_master_kelas_jabatan_encrypt=' + value
								.id_master_kelas_jabatan_encrypt +
								'" class="btn btn-success" title="Rincian">' +
								'<i class="fa fa-link"> Rincian</i>' +
								'</a>';
						}

						arrAksi += '</div>' +
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
					$("#datatableMPekerjaan > tbody").html(`
						<tr>
							<td colspan="4" class="text-center">
								No data available in table
							</td>
						</tr>
					`);
				}
			});
	}

	function get_jabatan_by_sopd() {
		getData();
		let selected_sopd = $("select[name=selected_sopd]").val();
		$.get(base_url + '/api/get_jabatan_bysopd', {
				selected_sopd: selected_sopd
			})
			.then(function (response) {
				$("select[name=selected_jabatan]").html("<option value=''>- Pilih Jabatan -</option>");
				$.each(response, function (key, value) {
					$("select[name=selected_jabatan]").append(
						"<option value='" + value.id_master_kelas_jabatan_encrypt + "' " + (value.id ==
							_selected_jabatan_tupoksi ? 'selected' : '') + ">" + value.nama_jabatan +
						" | " + value.unit_organisasi + "</option>"
					);
				});
			})
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
				$.get(base_url + "/dashboard/master_pekerjaan/delete", {
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
