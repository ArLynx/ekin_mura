<!-- Main content -->
<!--
# @Author: Awan Tengah
# @Date:   2019-08-22T23:00:41+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-08-22T23:01:33+07:00
-->

<section class="content" ng-app="mjkModule" ng-controller="mjkController as mc">

    <!-- Your Page Content Here -->
    <div class="box">
        <div class="box-header">
            <div class="row">
                <div class="col-md-6">
                    <div class="box-tools pull-right" ng-cloak>
                        Showing {{mc.numbering + $index}} to {{mc.lengthFilter}} of {{mc.itemsLength}} entries
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <div uib-alert ng-repeat="alert in alerts" ng-class="'alert-' + (alert.type || 'warning')" close="closeAlert($index)" ng-cloak>{{alert.msg}}</div>

            </div>
        </div>
    </div>

    <script type="text/ng-template" id="viewModal.html">
        <div class="modal-header">
            <h3 class="modal-title">Jam Kerja</h3>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-hover margin-0">
                    <tbody>
                        <tr>
                            <th width="100">Jam Masuk</th>
                            <td width="10">:</td>
                            <td>{{selected.item.jam_masuk}}</td>
                        </tr>
                        <tr>
                            <th width="100">Jam Pulang</th>
                            <td width="10">:</td>
                            <td>{{selected.item.jam_masuk}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-warning" type="button" ng-click="cancel()">Close</button>
        </div>
    </script>

    <script type="text/ng-template" id="addEditModal.html">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Jam Kerja</h3>
        </div>
        <div class="modal-body">
            <div class="">
                <table class="table table-hover margin-0">
                    <tbody>
                        <tr>
                            <th>Jam Masuk<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td>
                                <div class="input-group"
                                    moment-picker="jam_masuk"
                                    locale="id"
                                    format="HH:mm">
                                    <span class="input-group-addon">
                                        <i class="ion-clock"></i>
                                    </span>
                                    <input class="form-control"
                                        placeholder="Pilih jam masuk"
                                        ng-model="jam_masuk"
                                        ng-model-options="{ updateOn: 'blur' }">
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <th>Jam Pulang<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td>
                                <div class="input-group"
                                    moment-picker="jam_pulang"
                                    locale="id"
                                    format="HH:mm">
                                    <span class="input-group-addon">
                                        <i class="ion-clock"></i>
                                    </span>
                                    <input class="form-control"
                                        placeholder="Pilih jam pulang"
                                        ng-model="jam_pulang"
                                        ng-model-options="{ updateOn: 'blur' }">
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <th colspan="3"><span style="color: red;">*</span> inputan wajib diisi</th>
                        </tr>

                        <tr>
                            <td colspan="3">
                                <button type="button" ng-disabled="isDisabled" class="btn btn-primary" ng-click="submit()">Submit</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-warning" type="button" ng-click="cancel()">Close</button>
        </div>
    </script>

    <script type="text/ng-template" id="alert.html">
        <div ng-transclude></div>
    </script>

</section>
<!-- /.content -->
