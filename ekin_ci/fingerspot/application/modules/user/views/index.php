<div class="content">
    <div class="card border-top-success">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Level: </label>
                        <select class="form-control select-search" name="level_search" onChange="get_data_user()">
                            <option value="">-- PILIH --</option>
                            <?php
                            foreach($list_level_user as $key=>$row){
                                $selected = "";
                                if($selected_level == $row->id_level_user){
                                    $selected = 'selected="selected"';
                                }
                                ?>
                                <option <?php echo $selected; ?> value="<?php echo encrypt_data($row->id_level_user); ?>"><?php echo $row->nama_level_user; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-table">
        <div class="card-body">
            <div class="text-right">
                <a href="<?php echo base_url().'user/tambah_user'; ?>" class="btn btn-info">Tambah User</a>
            </div>
        </div>
        <table id="datatableUser" class="table datatable-save-state table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Level</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script>
let datatableUser = $("#datatableUser").DataTable();
function get_data_user(){
    let level = $("select[name=level_search]").val();
    datatableUser.clear().draw();
    if(level){
        $.ajax({
            url: base_url+'user/request/get_data_user',
            data:{level:level},
            type: 'GET',
            beforeSend: function(){
                loading_start();
            },
            success: function(response){
                $.each(response,function(index,value){
                    datatableUser.row.add([
                        value.nama_lengkap,
                        value.username,
                        value.nama_level_user,
                        "<a href='"+base_url+"user/edit_user/"+value.id_encrypt+"' class='btn btn-primary btn-icon'><i class='icon-pencil7'></i></a> <a class='btn btn-danger btn-icon' onClick=\"confirm_delete('"+value.id_encrypt+"')\" href='#'><i class='icon-trash'></i></a>"
                    ]).draw(false);
                });
            },
            complete:function(){
                loading_stop();
            }
        });
    }
}

function confirm_delete(id_user){
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
                url: base_url+'user/delete_user',
                data : {id_user:id_user},
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
                                get_data_user();
                            }
                        });
                    }else{
                        swalInit(
                            'Gagal',
                            'Data tidak bisa dihapus',
                            'error'
                        ).then(function(results){
                            if(result.value){
                                get_data_user();
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
                    get_data_user();
                }
            });
        }
    });
}
</script>