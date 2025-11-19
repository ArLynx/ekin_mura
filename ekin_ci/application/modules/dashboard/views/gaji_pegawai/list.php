<!-- Main content -->
<section class="content" data-updated="<?php echo $_updated; ?>" data-deleted="<?php echo $_deleted; ?>">

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header with-border">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<select class="form-control select2" name="selected_sopd" onchange="getData()"
							style="width: 100%;">
							<?php if (get_session('id_groups') == '1' || get_session('id_groups') == '5' || get_session('id_groups') == '6' || $this->_user_login->PNS_PNSNIP == '197712242005012006'): ?>
							<option value="">- Pilih SOPD -</option>
							<?php endif;?>
							<?php if ($all_sopd): ?>
							<?php foreach ($all_sopd as $row): ?>
							<option value="<?php echo encode_crypt($row->KD_UNOR); ?>" <?php echo get_session('selected_sopd_gaji_pegawai') != null ? (get_session('selected_sopd_gaji_pegawai') == $row->KD_UNOR ? 'selected' : '') : ''; ?>>
								<?php echo $row->NM_UNOR; ?></option>
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
                        <?php alert_message_dashboard();?>
						<table id="datatableGP" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>NIP</th>
									<th>Nama</th>
									<th>DPI</th>
									<th>IW Sudah Dibayar</th>
									<th>Aksi</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

    <!-- Modal -->
	<div class="modal fade" id="addModalGajiPegawai" role="dialog" aria-labelledby="addModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="addModalLabel">Gaji Pegawai</h4>
				</div>
				<?php echo form_open('dashboard/gaji_pegawai/action'); ?>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-borderless margin-0">
									<tbody>
										<tr>
											<th width="120">Nama</th>
											<th width="10">:</th>
											<td><span id="modalNama"></span></td>
										</tr>
										<tr>
											<th>NIP</th>
											<th>:</th>
											<td><span id="modalNIP"></span></td>
										</tr>
										<tr>
											<th>DPI</th>
											<th>:</th>
											<td>
												<input type="hidden" name="modal_nip_encrypt" id="modalNipEncrypt">
												<input type="hidden" name="modal_unor_encrypt" id="modalUnorEncrypt">
												<input type="text" name="modal_gaji_kotor" id="modalGajiKotor" class="form-control input-currency" placeholder="Gaji Kotor">
											</td>
										</tr>
										<tr>
											<th>IW Sudah Dibayar</th>
											<th>:</th>
											<td>
												<input type="text" name="modal_iw_sudah_bayar" id="modalIwSudahBayar" class="form-control input-currency" placeholder="IW Sudah Dibayar">
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Simpan</button>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>

</section>
<!-- /.content -->

<script>
	var datatable = $('#datatableGP').DataTable({
		"columns": [
            {
				"width": "10"
			},
			null,
			null,
			null,
			null,
			{
				"width": "100"
			},
		],
		"aaSorting": [],
	});

	let _updated = $(".content").attr('data-updated');
	let _deleted = $(".content").attr('data-deleted');

    const NUMBER = (value) => currency(value, {
		symbol: "",
		precision: 0,
		separator: "."
	});

	var currencyCollection = document.getElementsByClassName("input-currency");
    var arrCurrency = Array.from(currencyCollection);

    arrCurrency.forEach(function(currency) {
        new Cleave(currency, {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
            numeralDecimalMark: ',',
            delimiter: '.',
        })
    });

    $('#addModalGajiPegawai').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var dataGajiPegawai = button.data('gaji_pegawai');

        let selected_sopd = $("select[name=selected_sopd]").val();

		$("#modalNama").html(dataGajiPegawai.PNS_NAMA);
		$("#modalNIP").html(dataGajiPegawai.PNS_PNSNIP);
		$("#modalNipEncrypt").val(dataGajiPegawai.nip_encrypt);
		$("#modalUnorEncrypt").val(selected_sopd);
		$("#modalGajiKotor").val(NUMBER(dataGajiPegawai.gaji_kotor).format(true));
		$("#modalIwSudahBayar").val(NUMBER(dataGajiPegawai.iw_sudah_bayar).format(true));
    });

    getData();

	function escape(key, val) {
		if (typeof (val) != "string") return val;
		return val.replace(/[\']/g, '');
	}

	function getData() {
		let selected_sopd = $("select[name=selected_sopd]").val();
		datatable.clear().draw();

		$("#datatableGP > tbody").html(`
			<tr>
				<td colspan="6" class="text-center">
					<img src="`+base_url +`assets/img/loading.svg">
				</td>
			</tr>
		`);

		$.get(base_url + '/dashboard/gaji_pegawai/get_data', {
				unor: selected_sopd
			})
			.then(function (response) {
				if(response != '') {
					let arrData = [];
					$.each(response, function (key, value) {
						let arrAksi = '';
						let data = [
							++key,
							value.PNS_PNSNIP,
							value.PNS_NAMA,
							NUMBER(value.gaji_kotor).format(true),
							NUMBER(value.iw_sudah_bayar).format(true),
						];

						if (_updated == 1) {
							arrAksi += '<div class="td-action">' +
							'<div class="btn-group btn-group-md" role="group" aria-label="...">' +
							"<button type='button' class='btn btn-warning' data-toggle='modal' data-target='#addModalGajiPegawai' data-gaji_pegawai='"+(JSON.stringify(value, escape))+"' title='Ubah Gaji Pegawai'>" +
							"<i class='fa fa-edit'></i>" +
							"</button>" +
							'</div>' +
							'</div>';
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
					$("#datatableGP > tbody").html(`
						<tr>
							<td colspan="6" class="text-center">
								No data available in table
							</td>
						</tr>
					`);
				}
			});
	}

</script>
