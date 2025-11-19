<?php
# @Author: Awan Tengah
# @Date:   2019-09-01T21:53:13+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-09-02T08:49:39+07:00
?>


    <!-- Main content -->
    <section class="content" ng-app="menuModule" ng-controller="menuController as mc">

        <!-- Your Page Content Here -->
        <div class="box">
            <div class="box-header">
                <?php if (isset($_created) == 1): ?>
                    <a href="<?php echo site_url('dashboard/menu/add'); ?>" class="btn btn-primary">Tambah</a>
                <?php endif;?>
                <div class="box-tools" ng-cloak>
                    Showing {{mc.numbering + $index}} to {{mc.lengthFilter}} of {{mc.itemsLength}} entries
                </div>
                <br>
            </div>
            <div class="box-body table-responsive">
                <?php alert_message_dashboard();?>
                <table class="table table-hover table-bordered" st-pipe="mc.callServer" st-table="mc.displayed"
                st-safe-src="mc.callServer" refresh-table>
                <thead>
                    <tr>
                        <th width="10" rowspan="2" class="th-top">No</th>
                        <th st-sort="title">Menu</th>
                        <th st-sort="parent">Menu Induk</th>
                        <th st-sort="url">Url</th>
                        <th st-sort="order">Urutan</th>
                        <th width="160" st-sort="created_at">Ditambahkan pada</th>
                        <th width="110" rowspan="2" class="th-top">Aksi</th>
                    </tr>
                    <tr>
                        <th><input st-search="title" placeholder="Pencarian.." class="input-sm form-control"></th>
                        <th><input st-search="parent" placeholder="Pencarian.." class="input-sm form-control"></th>
                        <th><input st-search="url" placeholder="Pencarian.." class="input-sm form-control"></th>
                        <th><input st-search="order" placeholder="Pencarian.." class="input-sm form-control"></th>
                        <th><input st-search="created_at" placeholder="Pencarian.."
                            class="input-sm form-control"></th>
                        </tr>
                    </thead>
                    <tbody ng-show="!mc.isLoading" ng-cloak>
                        <tr ng-repeat="row in mc.displayed">
                            <td>{{mc.numbering + $index}}</td>
                            <td>{{row.title}}</td>
                            <td>{{row.parent}}</td>
                            <td>{{row.url}}</td>
                            <td>{{row.order}}</td>
                            <td>{{row.created_at}}</td>
                            <td class="td-action">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" ng-click="openDetailModal(row)">
                                        Detail
                                    </button>
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                    aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu ul-action" role="menu">
                                    <?php if (isset($_updated) == 1): ?>
                                        <li>
                                            <a href="<?php echo site_url('dashboard/menu/edit/{{row.id}}'); ?>">
                                                <i class="icon ion-compose"></i> Ubah
                                            </a>
                                        </li>
                                    <?php endif;?>
                                    <?php if (isset($_deleted) == 1): ?>
                                        <li><a href="#" ng-click="removeItem(row)"><i
                                            class="icon ion-android-close"></i> Hapus</a></li>
                                        <?php endif;?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr ng-show="mc.emptyData">
                            <td colspan="7" class="text-center">Data not found.</td>
                        </tr>
                    </tbody>
                    <tbody ng-show="mc.isLoading" ng-cloak>
                        <tr>
                            <td colspan="7" class="text-center">
                                <img src="<?php echo base_url('assets/img/loading.svg'); ?>" alt="Loading..">
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-center" st-pagination=""
                            st-items-by-page="<?php echo isset($limit) ? $limit : 10; ?>" colspan="7">
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <script type="text/ng-template" id="myModalContent.html">
        <div class="modal-header">
            <h3 class="modal-title">Details</h3>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <tbody>

                        <tr>
                            <th>Menu</th>
                            <td width="10">:</td>
                            <td>{{ selected.item.title }}</td>
                        </tr>

                        <tr>
                            <th>Menu Induk</th>
                            <td width="10">:</td>
                            <td>{{ selected.item.parent }}</td>
                        </tr>

                        <tr>
                            <th>Controller</th>
                            <td width="10">:</td>
                            <td>{{ selected.item.controller }}</td>
                        </tr>

                        <tr>
                            <th>Url</th>
                            <td width="10">:</td>
                            <td>{{ selected.item.url }}</td>
                        </tr>

                        <tr>
                            <th>Urutan</th>
                            <td width="10">:</td>
                            <td>{{ selected.item.order }}</td>
                        </tr>

                        <tr>
                            <th>Icon</th>
                            <td width="10">:</td>
                            <td>{{ selected.item.icon }}</td>
                        </tr>

                        <tr>
                            <th>Ditambahkan pada</th>
                            <td width="10">:</td>
                            <td>{{ selected.item.created_at }}</td>
                        </tr>

                        <tr>
                            <th>Diubah pada</th>
                            <td width="10">:</td>
                            <td>{{ selected.item.updated_at }}</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-warning" type="button" ng-click="cancel()">Tutup</button>
        </div>
    </script>

</section>

<script>
var app = angular.module('menuModule', ['smart-table', 'oitozero.ngSweetAlert', 'ui.bootstrap']);

app.factory('Resource', ['$q', '$filter', '$timeout', '$http', function ($q, $filter, $timeout, $http) {

    var recordItems = [];

    function getPage(start, number, params) {

        var deferred = $q.defer();

        var getData = $http.get("<?php echo base_url('dashboard/menu/get-data'); ?>")
        .then(function (response) {

            recordItems = response.data;

            var filtered = params.search.predicateObject ? $filter('filter')(recordItems, params.search.predicateObject) : recordItems;

            if (params.sort.predicate) {
                filtered = $filter('orderBy')(filtered, params.sort.predicate, params.sort.reverse);
            }

            var result = filtered.slice(start, start + number);

            $timeout(function () {
                //note, the server passes the information about the data set size
                deferred.resolve({
                    data: result,
                    itemsLength: recordItems.length,
                    numberOfPages: Math.ceil(filtered.length / number)
                });
            }, 300);
        });

        return deferred.promise;
    }

    return {
        getPage: getPage
    };

}]);

app.directive("refreshTable", function(){
    return {
        require:'stTable',
        restrict: "A",
        link:function(scope,elem,attr,table){
            scope.$on("refreshData", function() {
                table.pipe(table.tableState());
            });
        }
    }}
);

app.controller('menuController', ['Resource', '$scope', 'SweetAlert', '$http', '$uibModal',
function (service, $scope, SweetAlert, $http, $uibModal) {

    var ctrl = this;

    this.displayed = [];

    this.callServer = function callServer(tableState) {

        ctrl.isLoading = true;
        ctrl.emptyData = true;

        var pagination = tableState.pagination;

        var start = pagination.start || 0;     // This is NOT the page number, but the index of item in the list that you want to use to display the table.
        var number = pagination.number || <?php echo isset($limit) ? $limit : 10; ?>;  // Number of entries showed per page.

        service.getPage(start, number, tableState).then(function (result) {
            ctrl.displayed = result.data;
            ctrl.itemsLength = result.itemsLength;
            ctrl.emptyData = ctrl.displayed.length > 0 ? false : true;
            ctrl.numbering = ctrl.emptyData == true ? start : start + 1;
            ctrl.lengthFilter = ctrl.emptyData == true ? 0 : (ctrl.numbering - 1 + ctrl.displayed.length);
            tableState.pagination.numberOfPages = result.numberOfPages;//set the number of pages so the pagination can update
            ctrl.isLoading = false;
        });
    };

    $scope.openDetailModal = function (row) {
        var modal = $uibModal.open({
            templateUrl: 'myModalContent.html',
            scope: $scope,
            resolve: {
                item: function () {
                    return row;
                }
            }
        });

        $scope.items = row;
        $scope.selected = {
            item: $scope.items
        };

        $scope.modalInstance = modal;

        return modal.result
    };

    $scope.cancel = function () {
        $scope.modalInstance.dismiss('cancel');
    };

    $scope.removeItem = function removeItem(row) {
        SweetAlert.swal({
            title: "Are you sure?",
            text: "Your will not be able to recover this record!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55", confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function (isConfirm) {
            if (isConfirm) {
                var index = ctrl.displayed.indexOf(row);
                if (index !== -1) {
                    $http.delete("<?php echo base_url('dashboard/menu/delete'); ?>" + "/" + row.id);
                    $scope.$broadcast('refreshData');
                }
                SweetAlert.swal("Deleted!", "Your record has been deleted.", "success");
            } else {
                SweetAlert.swal("Cancelled", "Your record is safe :)", "error");
            }
        }
    );
}

}]);
</script>
