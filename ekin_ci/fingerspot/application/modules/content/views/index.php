<div class="content">
    <div class="card card-table">
        <div class="card-body">
            <div class="text-right">
                <a href="<?php echo base_url().'content/tambah_content'; ?>" class="btn btn-info">Tambah Content</a>
            </div>
        </div>
        <table id="datatableContent" class="table datatable-save-state table-bordered table-striped">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script>
let datatableContent = $("#datatableContent").DataTable();
get_data_content();
function get_data_content(){
    datatableContent.clear().draw();
    $.ajax({
        url: base_url+'content/request/get_data_content',
        type: 'GET',
        beforeSend: function(){
            loading_start();
        },
        success: function(response){
            $.each(response,function(index,value){
                datatableContent.row.add([
                    value.judul,
                    "<a href='"+base_url+"content/edit_content/"+value.id_encrypt+"' class='btn btn-primary btn-icon'><i class='icon-pencil7'></i></a> <a class='btn btn-danger btn-icon' onClick=\"confirm_delete('"+value.id_encrypt+"')\" href='#'><i class='icon-trash'></i></a>"
                ]).draw(false);
            });
        },
        complete:function(){
            loading_stop();
        }
    });
}

function confirm_delete(id_content){
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
                url: base_url+'content/delete_content',
                data : {id_content:id_content},
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
                                get_data_content();
                            }
                        });
                    }else{
                        swalInit(
                            'Gagal',
                            'Data tidak bisa dihapus',
                            'error'
                        ).then(function(results){
                            if(result.value){
                                get_data_content();
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
                    get_data_content();
                }
            });
        }
    });
}
</script>