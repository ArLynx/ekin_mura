<div class="content">
    <!-- Basic datatable -->
    <div class="card">
        <div class="card-body">
            <div class="text-right">
                <a href="<?php echo base_url(); ?>level_user/tambah_level_user" class="btn btn-info">Tambah Level User</a>
            </div>
        </div>
        <table id="datatableLevelUser" class="table datatable-save-state">
            <thead>
                <tr>
                    <th>Nama Hak Akses</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- /basic datatable -->
</div>

<script>
let datatableLevelUser = $("#datatableLevelUser").DataTable();
get_data_level_user();
function get_data_level_user(){
    datatableLevelUser.clear().draw();
    $.ajax({
        url: base_url+'level_user/request/get_data_level_user',
        type: 'GET',
        beforeSend: function(){
            loading_start();
        },
        success: function(response){
            $.each(response,function(index,value){
                datatableLevelUser.row.add([
                    value.nama_level_user,
                    "<a href='"+base_url+"level_user/edit_level_user/"+value.id_encrypt+"' class='btn btn-primary btn-icon'><i class='icon-pencil7'></i></a> <a class='btn btn-danger btn-icon' onClick=\"confirm_delete('"+value.id_encrypt+"')\" href='#'><i class='icon-trash'></i></a>"
                ]).draw(false);
            });
        },
        complete:function(response){
            loading_stop();
        }
    });
}

function confirm_delete(id_level_user){
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
                url: base_url+'level_user/delete_level_user',
                data : {id_level_user:id_level_user},
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
                                get_data_level_user();
                            }
                        });
                    }else{
                        swalInit(
                            'Gagal',
                            'Data tidak bisa dihapus',
                            'error'
                        ).then(function(results){
                            if(result.value){
                                get_data_level_user();
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
                    get_data_level_user();
                }
            });
        }
    });
}
</script>