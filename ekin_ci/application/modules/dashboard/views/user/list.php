<link rel="stylesheet" href="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.css'); ?>">
<style>
	.swal2-popup {
		font-size: 1.6rem !important;
	}

</style>
<script src="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.js'); ?>"></script>

<!-- Main content -->
<section class="content" data-updated="<?php echo $_updated; ?>" data-deleted="<?php echo $_deleted; ?>">

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header with-border" style="padding-bottom: 0;">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<select class="form-control select2" name="selected_groups" onchange="getData()" style="width: 100%;">
							<option value="">- Pilih Groups -</option>
							<?php if ($groups): ?>

							<?php foreach ($groups as $row): ?>
								<?php if(get_session('id_groups') == '2'): ?>
									<?php if($row->id == 3){ ?>

										<option selected value="<?php echo encode_crypt($row->id); ?>" data-id="<?php echo $row->id; ?>"><?php echo ucfirst($row->description); ?>
								<?php	} ?>

									<?php else:?>
							<option value="<?php echo encode_crypt($row->id); ?>" data-id="<?php echo $row->id; ?>"><?php echo ucfirst($row->description); ?>
									<?php  endif?>
							</option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">


				<div class="col-md-4">
					<div class="form-group">
						<select class="form-control select2" name="selected_sopd" onchange="getData()">
							<option value="">- Pilih SOPD -</option>
							<?php if ($all_sopd): ?>
							<?php foreach ($all_sopd as $row): ?>
							<option value="<?php echo encode_crypt($row->KD_UNOR); ?>"><?php echo $row->NM_UNOR; ?>
							</option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>


			</div>
			<div class="row">
				<div class="col-md-4" style="margin-bottom: 1em;">
				<?php if($_created == '1'): ?>
					<a href="#" id="addLinkUser" class="btn btn-primary">Tambah</a>
				<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<?php alert_message_dashboard();?>
						<table id="datatableUser" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama</th>
									<th>Username</th>
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
	var datatable = $('#datatableUser').DataTable({
        "columns": [{
				"width": "10"
			},
			{
				"width": "400"
			},
			null,
            {
				"width": "65"
			},
		],
		"aaSorting": [],
		"createdRow": function (row, data, dataIndex) {
			row.children[3].classList.add("text-center");
		}
	});

	$("a#addLinkUser").hide();

    let _updated = $(".content").attr('data-updated');
    let _deleted = $(".content").attr('data-deleted');

	function getData() {
		let selected_sopd = $("select[name=selected_sopd]").val();
		let selected_groups = $("select[name=selected_groups]").val();

		// let getGroupsId = $("select[name=selected_groups]").find(":selected").attr('data-id');

		datatable.clear().draw();

		$("#datatableUser > tbody").html(`
			<tr>
				<td colspan="4" class="text-center">
					<img src="`+base_url +`assets/img/loading.svg">
				</td>
			</tr>
		`);

		$.get(base_url + '/dashboard/user/get_data', {
			selected_sopd: selected_sopd,
			selected_groups: selected_groups
		})
			.then(function (response) {
				if(selected_sopd && selected_groups) {
					$("a#addLinkUser").attr('href', base_url + '/dashboard/user/add/' + selected_sopd + '/' + selected_groups);
					$("a#addLinkUser").show();
				}

                if(response != '') {
					let arrData = [];
					$.each(response, function (key, value) {
						let arrAksi = '';
						let data = [
							++key,
							value.PNS_NAMA,
							value.username
						];

						if (_updated == 1) {
							arrAksi += '<a href="' + base_url + '/dashboard/user/edit/' + value
								.id_encrypt + '/' + value.unor_encrypt + '/' + selected_groups + '.html" class="btn btn-warning" title="Ubah">' +
								'<i class="fa fa-edit"></i>' +
								'</a> ';
						}

						if (_deleted == 1) {
							arrAksi += '<button type="button" class="btn btn-danger" onclick="deleteData(\'' +
								value.id_encrypt + '\', \'' +
								selected_groups + '\')" title="Hapus">' +
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
					$("#datatableUser > tbody").html(`
						<tr>
							<td colspan="4" class="text-center">
								No data available in table
							</td>
						</tr>
					`);
				}
			});
	}

    function deleteData(id_encrypt, selected_groups) {
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
				$.get(base_url + "/dashboard/user/delete", {
					id_encrypt: id_encrypt,
					selected_groups: selected_groups,
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
