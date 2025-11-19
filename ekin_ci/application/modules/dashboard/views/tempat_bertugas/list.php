<section class="content" data-id_groups="<?php echo get_session('id_groups'); ?>" data-updated="<?php echo $_updated; ?>" data-deleted="<?php echo $_deleted; ?>">

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header with-border">
			<div class="row">
              <div class="col-md-4">
                 		<div class="form-group">
								<select class="form-control select2" id="selected_year" name="selected_year" onchange="getDataYear(this.val)"
									style="width: 100%;">
									<option value="">- Pilih Tahun -</option>
                                    <?php for($i=2023; $i<= date("Y"); $i++): ?>
									  <option value="<?= $i ?>"><?php echo $i ?></option>
                                    <?php endfor;?>					
								</select>
							</div>
							   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"> Tambah Data </button>
				</div>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<form id="fromTanggapanVer" action="#" method="post">
							<?php alert_message_dashboard();?>
							<table id="datatabletempatbertugas" class="table table-striped table-bordered" style="width: 100%;">
								<thead>
									<tr>
										<th rowspan="1" class="text-center th-top">No<z/th>
										<th rowspan="1" class="text-center th-top">Kelas Jabatan</th>
										<th class="text-center">Basic TPP ASN (Rp.)</th>
										<th class="text-center">Alokasi TPP</th>
										<th class="text-center">TPP Berdasarkan Tempat Bertugas yang Dibayarkan (Rp.)</th>
										<th class="text-center">Aksi</th>
									</tr>
								</thead>
                                <tbody>
                                    <!-- <tr class="text-center">
                                        <td>1</td>
                                         <td class="">12</td>
                                           <td>8.853.600.-</td>
										   <td>10%</td>
										   <td>885.360,-</td>
                                            
                                    </tr>

                                          <tr class="text-center">
                                        <td>2</td>
                                         <td class="">11</td>
                                           <td>7.822.788.-</td>
										   <td>10%</td>
										   <td>782.279,-</td>
                                            
                                    </tr> -->
                                </tbody>
							</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<div class="modal fade" id="myModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
				  <h3 class="modal-title" id="exampleModalLabel" class='text-bold'>Input Tempat Bertugas</h3>
            </div>
            <div class="modal-body">
        <?php echo form_open('dashboard/tempat_bertugas/add'); ?>
      

    <div class="form-group">
        <label for="inputNama">Pilih Kelas Jabatan:</label>
        <select  class="form-control select2" id="selected_kelas_jabatan" name="selected_kelas_jabatan" style="width: 100%;">
            <option value="">- Pilih Kelas Jabatan -</option>
			<?php for($i=15;$i>=1;$i--): ?>
			<option value="<?= $i?>"><?= $i?></option>
			<?php endfor;?>
        </select>
    </div>

	      <div class="form-group">
                <label for="inputNama">Alokasi TPP (%):</label>
                 <input type="text" class="form-control" id="alokasi_tpp" name="alokasi_tpp" oninput="dataTPP()" placeholder="Masukkan Nilai">
                <!-- <select class="form-control select2" id="alokasi_tpp" name="alokasi_tpp"  style="width: 100%;">
                    <option value="">- Pilih Alokasi TPP -</option>
					  <option value="0.1">10%</option>			
                </select> -->
            </div>

    <div class="form-group">
        <label for="inputNama">Pilih Tahun:</label>
        <select class="form-control select2" id="selected_tahun" name="selected_tahun" style="width: 100%;">
            <option value="">- Pilih Tahun -</option>
            <?php for($i = 2023; $i <= date("Y"); $i++): ?>
            <option value="<?= $i ?>"><?= $i; ?></option>
            <?php endfor; ?>							
        </select>
    </div>

    <div class="form-group">
        <label for="basic_tpp">Input Basic TPP ASN:</label>
         <input type="hidden" class="form-control" id="id" name="id"  placeholder="Masukkan Nilai">
        <input type="hidden" class="form-control" id="edit" name="edit" placeholder="Masukkan Nilai">
         <input type="hidden" class="form-control" id="dibayarkan" name="dibayarkan" >
        <input type="text" class="form-control" id="basic_tpp" name="basic_tpp" oninput="dataTPP()" placeholder="Masukkan Nilai">
    </div>

	 <div class="form-group">
        <p id='tpp_diterima'class='text-bold text-success'>TPP diterima : Rp.0</p>
			</div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary"  >Simpan</button>
    </div>
 <?php echo form_close(); ?>
            </div> 
        </div>
    </div>
</div>


<div class="modal fade" id="deleteModal" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
				  <h3 class="modal-title" id="deleteModal" class='text-bold'>Hapus Data</h3>
            </div>
            <div class="modal-body">
        <?php echo form_open('dashboard/tempat_bertugas/delete'); ?>
        <input type="hidden" id="id_delete" name="id_delete">
              <h4 class="text-success text-bold">Hapus Data ?</h4> 
              <br> 
                 <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary"  >Ya</button>
    </div>
 <?php echo form_close(); ?>
            </div>
            
        </div>
    </div>
</div>
</section>

<script>
		const NUMBER = (value) => currency(value, {
		symbol: "",
		precision: 0,
		separator: "."
	});


	function formatBesaranTPP(value) {
    // Your custom formatting logic here
    return NUMBER(value).format(true);
}
    //  $(document).ready(function() {
    //         // Code inside this block will run after the DOM is fully loaded
    //         console.log("Document is ready!");

    //         // Your other JavaScript code here
    //     });

function dataTPP(){

    let alokasi = $('input[name=alokasi_tpp]').val() / 100;
    let alokasiInt = parseInt(alokasi, 10);

     var value = document.getElementById('basic_tpp').value;
    // console.log(alokasi * value)
        // $('#tpp_diterima').text('Rp.'+ formatBesaranTPP(value)).trigger('change');

         $('#dibayarkan').val(alokasi * value);
         $('#tpp_diterima').text('Rp.'+ formatBesaranTPP(alokasi * value ));
}

 function getDataYear() {
    let selected_year = $('select[name=selected_year]').val();
	// console.log(selected_year)

    $.get(base_url + '/api/get_tempat_bertugas', {
        selected_year: selected_year
    })
    .then(function (response) {
        let datatable = $("#datatabletempatbertugas").DataTable({
            destroy: true,
            aaSorting: [],
            bSort: false,
            
            columns: [
                { data: null, render: function (data, type, row, meta) { return meta.row + 1; } },
                { data: "kelas_jabatan",render: function(data, type, row) { return '<h6 class="text-bold">'+data+'</h6>' } },
				{ data: "basic_tpp", render: function(data, type, row) { return '<h6 class="text-center text-bold text-success">'+'Rp. ' + formatBesaranTPP(data) + '</h6>'; } },
                { data: "alokasi_tpp" ,render: function(data, type, row) { return '<h6 class="text-center text-bold">'+ data + '%' + '</h6>'} },
                { data: "dibayarkan",render: function(data, type, row) { return '<h6 class="text-center text-bold text-success">'+ 'Rp. ' + formatBesaranTPP(data) +'</h6>'} },        
                 { data: 'id', render: function (data, type, row, meta) { return '  <div class="text-center"><button type="button"  onclick="editModal('+ data + ','+ row.kelas_jabatan + ','+ row.tahun + ',' + row.basic_tpp  + ',' + row.dibayarkan + ',' + row.alokasi_tpp + ')" class="btn btn-default" style="padding: 8px 12px 6px;" ><i class="fa fa-edit"></i></button> <button type="button"  class="btn btn-danger" onclick="deleteModal('+ data +')" style="padding: 8px 12px 6px;" ><i class="fa fa-trash"></i></button> </div>' } },
            ],
            createdRow: function (row, data, index) {
                // Add custom class to the cell in the fifth column (besaran_tpp)
                $('td', row).eq(5).addClass('custom-class');
            }
        });

        // Clear existing rows in the table
        datatable.clear().draw();

        // Populate the DataTable with the received data
        if (response && response.length > 0) {
            for (let i = 0; i < response.length; i++) {
				if(response[i].tahun == selected_year){

					datatable.row.add(response[i]).draw(false);
				}
            }
        }
    });
}


function editModal(id, kelas_jabatan, tahun, basic_tpp, dibayarkan, alokasi_tpp){

    $('#edit').val(1);
    $('#id').val(id);
    $('#basic_tpp').val(basic_tpp);
    $('#dibayarkan').val(dibayarkan);

    console.log(kelas_jabatan)

    $('#alokasi_tpp').val(alokasi_tpp);
    $('#selected_kelas_jabatan').val(kelas_jabatan).trigger('change');
    $('#selected_tahun').val(tahun).trigger('change');
    $('#tpp_diterima').text('Rp.'+ formatBesaranTPP(dibayarkan ));
    $('#myModal').modal('show');
}

// Menangkap peristiwa penutupan modal
    $('#myModal').on('hidden.bs.modal', function (e) {
    // Mengatur nilai-nilai input ke nilai awal atau kosong
    $('#selected_kelas_jabatan').val('').trigger('change');
     $('#alokasi_tpp').val('');
    $('#selected_tahun').val('').trigger('change');

    $('#basic_tpp').val('');
    $('#edit').val('');

    $('#id').val('');
    $('#basic_tpp').val('');
    $('#dibayarkan').val('');

    $('#id_delete').val('');
});

function deleteModal(id){
    console.log(id)
     $('#id_delete').val(id);
   $('#deleteModal').modal('show');
}

</script>