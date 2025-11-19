<section class="content" data-id_groups="<?php echo get_session('id_groups'); ?>" data-updated="<?php echo $_updated; ?>" data-deleted="<?php echo $_deleted; ?>">

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header with-border">
			<div class="row">
                <div class="col-md-4">
                       <input type='hidden' name="default_tahun" id='default_tahun' value="<?=$selected_year?>">
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
					
							<?php alert_message_dashboard();?>
							<table id="datatablekondisikerja" class="table table-striped table-bordered" style="width: 100%;">
								<thead>
									<tr>
										<th rowspan="1" class="text-center th-top">No<z/th>
										<th rowspan="1" class="text-center th-top">Jabatan & Unit Organisasi (Bidang)</th>
                                        <th rowspan="1" class="text-center th-top">SKPD</th>
										<th rowspan="1" class="text-center th-top">Nama PNS</th>
										<th class="text-center">Besaran TPP</th>    	
                                        <th class="text-center">Aksi</th>   
									</tr>
								</thead>
                                <tbody>
                                    
                                    <!-- <?php if($kondisi_kerja):?>
									<?php $no = 0; foreach ($kondisi_kerja as $row): $no++?>
                              
                                         <tr class="text-center">
                                         <td><?= $no?></td>
                                         <td class=""><h6 class="text-bold"><?= $row->nama_jabatan ?></h6></td>
										 <td class=""><h6 class="font-font-weight-lighter"><?= $row->unit_organisasi?></h6></td>
                                         <td class=""><h6 class="text-bold"><?= $row->NM_UNOR ?></h6></td>
										 <td class=""><h6><?= ($row->gelar_depan? $row->gelar_depan : '') . ' '. $row->nama_pns. ' '. ($row->gelar_belakang? $row->gelar_belakang : '') ?></h6></td>
                                         <td><p><?= $row->besaran_tpp?></p></td>                                            
                                    </tr>

									<?php endforeach;?>
                                    <?php endif; ?> -->
                                </tbody>
							</table>                         
						
					</div>
				</div>
			</div>
		</div>
	</div>

<!-- Modal -->
<!-- <div id="myModal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> -->
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
				  <h3 class="modal-title" id="exampleModalLabel" class='text-bold'>Input Kondisi Kerja</h3>
            </div>
            <div class="modal-body">
        <?php echo form_open('dashboard/kondisi_kerja/add'); ?>
            <div class="form-group">
                <label for="inputNama">SOPD:</label>
                <select class="form-control select2" id="selected_sopd" name="selected_sopd" onchange="getData(this.value)" style="width: 100%;">
                    <option value="">- Pilih SOPD -</option>
                    <?php foreach ($sopd as $key): ?>
                    <option value="<?= $key->KD_UNOR ?>"><?php echo $key->NM_UNOR ?></option>
                    <?php endforeach; ?>						
                </select>
            </div>

    <div class="form-group">
        <label for="inputNama">Pilih Kelas Jabatan:</label>
        <select id="selectedKelasJabatan" class="form-control select2" name="selected_kelas_jabatan" style="width: 100%;">
            <option value="">- Pilih Kelas Jabatan -</option>
        </select>
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

        <label for="besaran_tpp">Nilai:</label>
         <input type="hidden" class="form-control" id="kondisi_id" name="kondisi_id" placeholder="Masukkan Nilai">
        <input type="hidden" class="form-control" id="edit" name="edit" placeholder="Masukkan Nilai">
        <input type="text" class="form-control" id="besaran_tpp" name="besaran_tpp" placeholder="Masukkan Nilai">
        <input type="hidden" class="form-control" id="headers" name="headers" value="<?= $headers ?>" placeholder="Masukkan Nilai">
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
        <?php echo form_open('dashboard/kondisi_kerja/delete'); ?>
        <input type="hidden" id="kondisi_id_delete" name="kondisi_id_delete">
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

	
function getData(val) {
 	 let selected_sopd = val;

  $.get(base_url + '/dashboard/kondisi_kerja/get_data', {
    selected_sopd: selected_sopd
  })
  .then(function (response) {
    let selectElement = $('#selectedKelasJabatan'); // Updated ID
    // Clear existing options
    selectElement.empty();

    // Append new options
    for (let i = 0; i < response.length; i++) {
      selectElement.append('<option value="' + response[i].id + '">' + response[i].nama_jabatan+'( ' + response[i].unit_organisasi + ' )' + '( ' + (response[i].gelar_depan != null ?  response[i].gelar_depan : ' ')+ ' ' + (response[i].nama_pns != null ? response[i].nama_pns : '')   + ' ' +(response[i].gelar_belakang != null ?  response[i].gelar_belakang : ' ')+ ' )' +  '</option>');
    }
  });
}

  function getDataYear() {
    let selected_year = $('select[name=selected_year]').val();

    $.get(base_url + '/api/get_kondisi_kerja', {
        selected_year: selected_year
    })
    .then(function (response) {
        let datatable = $("#datatablekondisikerja").DataTable({
            destroy: true,
            aaSorting: [],
            bSort: false,
            
            columns: [
                { data: null, render: function (data, type, row, meta) { return meta.row + 1; } },
                { data: "nama_jabatan",render: function(data, type, row) { return '<h6 class="text-bold">'+data+'</h6>' + '<h6>' + row.unit_organisasi + '</h6>'} },
                { data: "NM_UNOR" ,render: function(data, type, row) { return '<h6 class="text-bold">'+data+'</h6>'} },
                { data: "nama_pns",render: function(data, type, row) { return '<h6 class="text-center text-bold text-success">'+ (row.gelar_depan != null ?  rrow.gelar_depan : ' ')+ ' ' + (row.nama_pns != null ? row.nama_pns : '')   + ' ' +(row.gelar_belakang != null ?  row.gelar_belakang : ' ') +'</h6>'} },
                { data: "besaran_tpp", render: function(data, type, row) { return '<h6 class="text-center text-bold text-success">'+'Rp. ' + formatBesaranTPP(data) + '</h6>'; } },
                { data: 'id', render: function (data, type, row, meta) { return '  <div class="text-center"><button type="button"  onclick="editModal('+data + ','+ row.unor + ','+ row.tahun + ',' + row.besaran_tpp + ',' + row.kondisi_id + ')" class="btn btn-default" style="padding: 8px 12px 6px;" ><i class="fa fa-edit"></i></button> <button type="button"  class="btn btn-danger" onclick="deleteModal('+row.kondisi_id +')" style="padding: 8px 12px 6px;" ><i class="fa fa-trash"></i></button> </div>' } },
             
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
                datatable.row.add(response[i]).draw(false);
            }
        }
    });
}

// Custom function to format besaran_tpp
function formatBesaranTPP(value) {
    // Your custom formatting logic here
    return NUMBER(value).format(true);
}

// Menangkap peristiwa penutupan modal
$('#myModal').on('hidden.bs.modal', function (e) {
    // Mengatur nilai-nilai input ke nilai awal atau kosong
     $('#selectedKelasJabatan').val('').trigger('change');
    $('#selected_sopd').val('').trigger('change');
    $('#selected_tahun').val('').trigger('change');
    $('#besaran_tpp').val('');
    $('#kondisi_id').val('');
    $('#edit').val('');
});

function deleteModal(kondisi_id){
    console.log(kondisi_id)
     $('#kondisi_id_delete').val(kondisi_id);
   $('#deleteModal').modal('show');
}

function editModal(id, unor, tahun, besaran_tpp,kondisi_id){

    $('#edit').val(1);
    $('#kondisi_id').val(kondisi_id);
    $('#besaran_tpp').val(besaran_tpp);
    $('#selected_sopd').val(unor).trigger('change');

    function waitForValue() {
    let selected_kelas_jabatan = $('select[name=selected_kelas_jabatan]').val();
   
    
    if (selected_kelas_jabatan) {
      $('#selectedKelasJabatan').val(id).trigger('change');
    } else {
        setTimeout(waitForValue, 1000); // Ganti 1000 dengan waktu penundaan dalam milidetik
    }
}

// Panggil fungsi pertama kali
waitForValue();
    $('#selected_tahun').val(tahun).trigger('change');
    $('#myModal').modal('show');
}

$( document ).ready(function() {
        let selected_year = $('input[name=default_tahun]').val();
        $('#selected_year').val(selected_year);
        getDataYear()
   
});

</script>