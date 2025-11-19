<div class="content">
    <!-- Basic datatable -->
    <div class="card">
        <table id="datatablePrivilageLevel" class="table datatable-save-state">
            <thead>
                <tr>
                    <th>Level</th>
                    <th>Menu</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- /basic datatable -->
</div>

<script>
let datatablePrivilageLevel = $("#datatablePrivilageLevel").DataTable();
get_data_privilage_level();
function get_data_privilage_level(){
    datatablePrivilageLevel.clear().draw();
    $.ajax({
        url: base_url+'privilage_level/request/get_data_privilage_level',
        type: 'GET',
        beforeSend: function(){
            loading_start();
        },
        success: function(response){
            $.each(response,function(index,value){
                datatablePrivilageLevel.row.add([
                    value.nama_level_user,
                    value.nama_menu,
                    "<a href='"+base_url+"privilage_level/set_privilage_menu/"+value.id_encrypt+"' class='btn btn-primary btn-icon'><i class='icon-pencil7'></i></a>"
                ]).draw(false);
            });
        },
        complete:function(response){
            loading_stop();
        }
    });
}
</script>