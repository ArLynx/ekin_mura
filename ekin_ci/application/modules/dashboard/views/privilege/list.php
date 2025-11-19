<?php
# @Author: Awan Tengah
# @Date:   2017-05-04T21:17:34+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-09-01T22:40:22+07:00
?>

<div class='box-header with-border'>
    <h3 class='box-title'>Menu</h3>
</div><!-- /.box-header -->
<table class='table table-hover'>
    <tbody>
    <tr>
        <th>Menu</th>
        <th>View</th>
        <th>Create</th>
        <th>Update</th>
        <th>Delete</th>
    </tr>
    <?php foreach($menu as $row): ?>
        <?php $ci = & get_instance(); ?>
        <?php $privilege = $ci->get_privilege($id_groups, $row->id);?>
        <tr>
            <td>
                <?php echo $row->title; ?>
            </td>
            <td>
                <input type='checkbox' class='flat-red' name='menu[<?php echo $row->id; ?>][view]' <?php echo !empty($privilege) && ($privilege->view == 1) ? 'checked' : '';?>>
            </td>
            <td>
                <input type='checkbox' class='flat-red' name='menu[<?php echo $row->id; ?>][create]' <?php echo !empty($privilege) && ($privilege->create == 1) ? 'checked' : '';?>>
            </td>
            <td>
                <input type='checkbox' class='flat-red' name='menu[<?php echo $row->id; ?>][update]' <?php echo !empty($privilege) && ($privilege->update == 1) ? 'checked' : '';?>>
            </td>
            <td>
                <input type='checkbox' class='flat-red' name='menu[<?php echo $row->id; ?>][delete]' <?php echo !empty($privilege) && ($privilege->delete == 1) ? 'checked' : '';?>>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<script>
    $('input[type="checkbox"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green'
    });
</script>
