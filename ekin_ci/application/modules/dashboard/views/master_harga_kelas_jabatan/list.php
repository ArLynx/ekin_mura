<!-- Main content -->
<section class="content">

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header with-border" style="padding-bottom: 0;">
			<div class="row">

				<div class="col-md-2">
					<div class="form-group">
                        <label>Pilih Tahun</label>
						<select class="form-control" name="selected_year" onchange="getData()">
							<option value="">- Pilih Tahun -</option>
							<?php if ($all_year): ?>
							<?php foreach ($all_year as $row): ?>
							<option value="<?php echo $row->year; ?>"><?php echo $row->year; ?>
							</option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>

				<div class="col-md-2">
					<!-- <button type="button" class="btn btn-default">Cetak Laporan</<button> -->
				</div>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table id="datatableMHKJ" class="table table-striped table-bordered">
							<thead>
								<tr>
									<th width="50" style="vertical-align: middle;">Kelas Jabatan</th>
									<th width="200" style="vertical-align: middle;">Jabatan</th>
									<th width="100" style="vertical-align: middle;">Tukin BPK Perkelas Jabatan</th>
									<th width="50" style="vertical-align: middle;">IKFD</th>
									<th width="50" style="vertical-align: middle;">IKK</th>
									<th width="50" style="vertical-align: middle;">IPPD</th>
									<th width="50" style="vertical-align: middle;">KOEF</th>
									<th width="100" style="vertical-align: middle;">Basic TPP Kabupaten</th>
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
	var datatable = $('#datatableMHKJ').DataTable({
		"aaSorting": [],
		"bPaginate": false,
		"bSort": false,
		"createdRow": function (row, data, dataIndex) {
			row.children[0].classList.add("text-center");
			row.children[1].classList.add("text-left");
			row.children[2].classList.add("text-right");
			row.children[3].classList.add("text-center");
			row.children[4].classList.add("text-center");
			row.children[5].classList.add("text-center");
			row.children[6].classList.add("text-left");
			row.children[7].classList.add("text-right");
		}
	});

	const NUMBER = value => currency(value, {
		symbol: "",
		precision: 0,
		separator: "."
	});

	getData();

	function getData() {
		datatable.clear().draw();
		
		$("#datatableMHKJ > tbody").html(`
			<tr>
				<td colspan="8" class="text-center">
					<img src="`+base_url +`assets/img/loading.svg">
				</td>
			</tr>
		`);

		$.get(base_url + '/dashboard/master_harga_kelas_jabatan/get_data', {
				year: $("select[name=selected_year]").val()
			})
			.then(function (response) {
				if(response != '') {
					$.each(response, function (key, value) {
						datatable.row.add([
							value.kelas_jabatan,
							value.jabatan,
							NUMBER(value.tukin_bpk).format(true),
							value.ikfd,
							value.ikk,
							value.ippd,
							value.koef,
							value.tpp_basic,
						]).draw(false);
					});
				} else {
					$("#datatableMHKJ > tbody").html(`
						<tr>
							<td colspan="8" class="text-center">
								No data available in table
							</td>
						</tr>
					`);
				}
			});
	}

</script>
