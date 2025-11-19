<link rel="stylesheet" href="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.css'); ?>">
<style>
.swal2-popup {
  font-size: 1.6rem !important;
}
</style>
<script src="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.js'); ?>"></script>

<!-- Main content -->
<section class="content" data-id_groups="<?php echo get_session('id_groups'); ?>" data-updated="<?php echo $_updated; ?>">

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header with-border">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<select class="form-control select2" name="selected_sopd" onchange="getData()">
							<?php if (get_session('id_groups') == '1' || get_session('id_groups') == '5'): ?>
							<option value="">- Pilih SOPD -</option>
							<?php endif;?>
							<?php if ($all_sopd): ?>
							<?php foreach ($all_sopd as $row): ?>
							<option value="<?php echo encode_crypt($row->KD_UNOR); ?>"><?php echo $row->NM_UNOR; ?></option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>
				<?php if (isset($_created) == 1): ?>
					<div class="col-md-4">
						<a href="#" id="addPLT" class="btn btn-primary">Tambah</a>
					</div>
				<?php endif;?>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<?php alert_message_dashboard();?>
						<table id="datatablePPlt" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>NIP</th>
									<th>Nama</th>
									<th>Jabatan</th>
									<th>Jabatan PLT</th>
									<th>Periode</th>
									<th>SK Plt</th>
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
	var datatable = $('#datatablePPlt').DataTable({
		"columns": [
			{
				"width": "10"
			},
			{
				"width": "150"
			},
			null,
			null,
			null,
			{
				"width": "100"
			},
			null,
			{
				"width": "100"
			},
		],
		"aaSorting": [],
	});

	let _id_groups = $(".content").attr('data-id_groups');
	let _updated = $(".content").attr('data-updated');

    getData();
    $("a#addPLT").hide();

	function getData() {
		datatable.clear().draw();
		let selected_sopd = $("select[name=selected_sopd]").val();

		$("#datatablePPlt > tbody").html(`
			<tr>
				<td colspan="8" class="text-center">
					<img src="`+base_url +`assets/img/loading.svg">
				</td>
			</tr>
		`);

		$.get(base_url + '/dashboard/pegawai-plt/get_data', {
			selected_sopd: selected_sopd
		})
		.then(function (response) {
			$("a#addPLT").attr('href', base_url + '/dashboard/pegawai-plt/add/' + selected_sopd);
			$("a#addPLT").show();
            if(response != '') {
				let arrData = [];
				$.each(response, function (key, value) {
					let arrAksi = '';
                    let data = [
						++key,
						value.PNS_PNSNIP,
						value.PNS_NAMA,
						value.nama_jabatan + '<br><strong>' + value.NM_UNOR + '</strong>',
						value.nama_jabatan_plt + '<br><strong>' + value.NM_UNOR_PLT + '</strong>',
						value.awal_plt + ' sampai ' + (value.akhir_plt != null ? value.akhir_plt : '-'),
						value.sk_plt != null ? '<a href="'+value.sk_plt_path + value.sk_plt+'" target="_blank">Lihat SK</a>' : '-'
					];

					if((_id_groups == '5') && value.akhir_plt == null) {
						arrAksi += '<div class="td-action">' +
						'<div class="btn-group btn-group-md" role="group" aria-label="...">' +
						'<a href="' + base_url + '/dashboard/pegawai-plt/edit/' + value.id_encrypt +
						'.html" class="btn btn-warning" title="Ubah">' +
						'<i class="fa fa-edit"></i>' +
						'</a>' +
						'<button type="button" class="btn btn-danger" onclick="deleteData(\''+value.id_encrypt+'\')" title="Hapus">' +
						'<i class="ion-trash-a"></i>' +
						'</button>' +
						'<button type="button" class="btn btn-success" onclick="finishPLT(\''+value.id_encrypt+'\')" title="PLT Selesai">' +
						'<i class="fa fa-check"></i>' +
						'</div>' +
						'</div>';
					}

					if (_id_groups == '2' && _updated == 1 && value.akhir_plt == null) {
						arrAksi +='<button type="button" class="btn btn-success" onclick="finishPLT(\''+value.id_encrypt+'\')" title="PLT Selesai">' +
						'<i class="fa fa-check"></i>';
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
				$("#datatablePPlt > tbody").html(`
					<tr>
						<td colspan="8" class="text-center">
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
                $.get(base_url + "/dashboard/pegawai-plt/delete",{
                    id_encrypt:id_encrypt
                }).then(function() {
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

	function finishPLT(id_encrypt) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Apakah Anda yakin PLT beliau sudah selesai?',
			html: '<div class="form-group"><label style="float: left; font-size: .8em;">Bulan Selesai</label><input type="month" id="akhir_plt" name="akhir_plt" class="form-control"></div>',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
			preConfirm: function() {
				return new Promise((resolve, reject) => {
					resolve({
						akhirPLT: $('input[id="akhir_plt"]').val()
					});
				});
			}
        }).then((result) => {
            if (result.value) {
                $.get(base_url + "/dashboard/pegawai-plt/action",{
                    id_encrypt:id_encrypt,
					akhir_plt: result.value.akhirPLT
                }).then(function(response) {
                    getData();
                    swalWithBootstrapButtons.fire(
                        response.title,
                        response.text,
                        response.status
                    )
                });
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Aksi dibatalkan',
                    'Data aman',
                    'error'
                )
            }
        })
    }

</script>
