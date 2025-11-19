<div class="content">
    <!-- Basic datatable -->
    <div class="card">
        <div class="card-body">
            <div class="text-right">
                <a href="<?php echo base_url(); ?>menu/tambah_menu" class="btn btn-info">Tambah Menu</a>
            </div>
        </div>
        <table id="datatableLevelMenu" class="table datatable-save-state">
            <thead>
                <tr>
                    <th>Nama Menu</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- /basic datatable -->
</div>

<script>
let datatableLevelMenu = $("#datatableLevelMenu").DataTable();
get_data_menu();
function get_data_menu(){
    datatableLevelMenu.clear().draw();
    $.ajax({
        url: base_url+'menu/request/get_data_menu',
        type: 'GET',
        beforeSend: function(){
            loading_start();
        },
        success: function(response){
            $.each(response,function(index,value){
                datatableLevelMenu.row.add([
                    value.nama_menu,
                    "<a href='"+base_url+"menu/edit_menu/"+value.id_encrypt+"' class='btn btn-primary btn-icon'><i class='icon-pencil7'></i></a> <a class='btn btn-danger btn-icon' onClick=\"confirm_delete('"+value.id_encrypt+"')\" href='#'><i class='icon-trash'></i></a>"
                ]).draw(false);
            });
        },
        complete:function(response){
            loading_stop();
        }
    });
}

function confirm_delete(id_menu){
    var swalInit = swal.mixin({
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        cancelButtonClass: 'btn btn-light'
    });

    swalInit({
        title: 'Apakah anda yakin menghapus data ini?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya!',
        cancelButtonText: 'Batal!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false
    }).then(function(result) {
        if(result.value) {
            $.ajax({
                url: base_url+'menu/delete_menu',
                data : {id_menu:id_menu},
                type: 'GET',
                beforeSend: function(){
                    loading_start();
                },
                success: function(response){
                    if(response){
                        swalInit(
                            'Berhasil',
                            'Data sudah dihapus',
                            'success'
                        ).then(function(results){
                            if(result.value){
                                get_data_menu();
                            }
                        });
                    }else{
                        swalInit(
                            'Gagal',
                            'Data tidak bisa dihapus',
                            'error'
                        ).then(function(results){
                            if(result.value){
                                get_data_menu();
                            }
                        });
                    }
                },
                complete:function(response){
                    loading_stop();
                }
            });
        }
        else if(result.dismiss === swal.DismissReason.cancel) {
            swalInit(
                'Batal',
                'Data masih tersimpan!',
                'error'
            ).then(function(results){
                loading_stop();
                if(result.results){
                    get_data_menu();
                }
            });
        }
    });
}
</script>