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
						<select class="form-control select2" name="selected_sopd" onchange="getData()">
							<?php if (get_session('id_groups') == '1' || get_session('id_groups') == '5'): ?>
								<?php if($unor_selected):  ?>
								<option value=<?php echo encode_crypt($unor_selected) ?>><?= $nama_unor ?></option>
									<?php else: ?>
									<option value="">- Pilih SOPD -</option>
									<?php endif ?>
							
							<?php endif;?>
							<?php if ($all_sopd): ?>
							
							<?php foreach ($all_sopd as $row): ?>
								<?php if($row->KD_UNOR != $unor_selected):   ?>
							<option value="<?php echo encode_crypt($row->KD_UNOR); ?>"><?php echo $row->NM_UNOR; ?>
							<?php endif ?>

							</option>
							<?php endforeach;?>
							
							<?php endif;?>
						</select>
					</div>
				</div>
				<div class="col-md-4">
					
					<?php if ($_created == 1): ?>
						<a href="" id="addLinkUO" class="btn btn-primary">Tambah</a>
					<?php endif;?>
				</div>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table id="datatableMKJ" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>Unit Organisasi</th>
									<th>Index Jabatan</th>
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
	
	var datatable = $('#datatableMKJ').DataTable({
		"columns": [{
				"width": "10"
			},
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

	$("a#addLinkUO").hide();

	let _updated = $(".content").attr('data-updated');
	let _deleted = $(".content").attr('data-deleted');
	let _id_groups = $(".content").attr('data-id_groups');

	$(function () {
		if(_id_groups != '1' && _id_groups != '5' && _id_groups != '2' ) {
			getData();
		}
	});

	function getData() {
		let selected_sopd = $("select[name=selected_sopd]").val();
		
		datatable.clear().draw();

		$("#datatableMKJ > tbody").html(`
			<tr>
				<td colspan="3" class="text-center">
					<img src="`+base_url +`assets/img/loading.svg">
				</td>
			</tr>
		`);

		$.get(base_url + '/dashboard/master_unit_organisasi/get_data', {
				selected_sopd: selected_sopd
			})
			.then(function (response) {
			
			
				$("a#addLinkUO").attr('href', base_url + '/dashboard/master-unit-organisasi/add/' + selected_sopd);
				$("a#addLinkUO").show();
				if(response != '') {
					let arrData = [];
					let count = 0;
						
					$.each(response, function (key, value) {
						count = count + 1;
			
						let arrAksi = '';
						let data = [
							++key,
							value.unit_organisasi,
							value.index_jabatan
						];

						if (_updated == 1) {
							arrAksi += '<a href="' + base_url + '/dashboard/master-unit-organisasi/edit/' + value
								.id_encrypt + '/' + value.unor_encrypt +
								'.html" class="btn btn-warning" title="Ubah">' +
								'<i class="fa fa-edit"></i>' +
								'</a> ';
						}

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
					$("#datatableMKJ > tbody").html(`
						<tr>
							<td colspan="3" class="text-center">
								No data available in table
							</td>
						</tr>
					`);
				}
			});
	}

	$(document).ready(function() {
  	 getData();
	});

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
				$.get(base_url + "/dashboard/master_unit_organisasi/delete", {
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
