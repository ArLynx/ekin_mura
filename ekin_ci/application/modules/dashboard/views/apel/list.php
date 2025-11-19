<section class="content" data-id_groups="<?php echo get_session('id_groups'); ?>" data-updated="<?php echo $_updated; ?>" data-deleted="<?php echo $_deleted; ?>">

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
										
									<?php foreach ($all_sopd as $row):  ?>
									
									<option value="<?php echo $row->KD_UNOR; ?>">
										<!-- <?php if($row->Status_UNOR == 'aktif'): ?> -->
										<?php echo $row->NM_UNOR; ?>
									<!-- <?php endif ?> -->
									</option>
										
									<?php endforeach;?>
									<?php endif;?>
								</select>
							</div>
				</div>
                <div class="col-md-2">
					<div class="form-group">
                  
                    <div class="input-group date" data-provide="datepicker">
                 
                    <input type="date" class="form-control">
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-th"></span>
                    </div>
                </div>

					</div>
				</div>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<form id="fromTanggapanVer" action="#" method="post">
							<?php alert_message_dashboard();?>
							<table id="datatableVerifikasi" class="table table-striped table-bordered" style="width: 100%;">
								<thead>
									<tr>
										<th rowspan="1" class="text-center th-top">No<z/th>
										<th rowspan="1" class="text-center th-top">Nama Pegawai</th>
								
										<th class="text-center">Apel Pagi?</th>
                                        	<th class="text-center">Apel Sore?</th>
									</tr>
								</thead>
                                <tbody>
                                    <tr class="text-center">
                                        <td>1</td>
                                         <td class="">Pegawai 1</td>
                                           <td><input type="checkbox"></input></td>
                                             <td><input type="checkbox"></input></td>
                                    </tr>

                                         <tr class="text-center">
                                        <td>2</td>
                                         <td class="">Pegawai 2</td>
                                           <td><input type="checkbox"></input></td>
                                             <td><input type="checkbox"></input></td>
                                    </tr>
                                </tbody>
							</table>

                            <button class="btn btn-warning">Simpan</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>

<script>
function getData() {
    let selected_sopd = $("select[name=selected_sopd]").val();
    console.log(selected_sopd);

    const apiUrl = "https://e-kinerja.murungrayakab.go.id//api/get_pegawai_tpp?unor=" + selected_sopd;

    // Define your headers
    const headers = new Headers();
    headers.append('Authorization', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJBd2FuIFRlbmdhaCBTdHVkaW8ifQ.QT3a2KI9o0OWy1pf1HEHwgvZSOH8kyhYmjTaaya9CC0'); // Replace 'YourAuthToken' with your actual token
    headers.append('Content-Type', 'application/json'); // Adjust content type if needed

    // Make a GET request with headers
    fetch(apiUrl, {
        method: 'GET',
        headers: headers,
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}



</script>