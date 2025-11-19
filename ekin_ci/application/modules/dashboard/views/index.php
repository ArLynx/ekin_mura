<!-- Main content -->
<section class="content container-fluid">

    <?php if (get_session('id_groups') == '2'): ?>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <style>
        .highcharts-data-table table {
            min-width: 310px;
            max-width: 800px;
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #EBEBEB;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }
    </style>

    <div class="row" data-dinas="<?php echo $_user_login->nama_unit; ?>" data-countpns="<?php echo $count_pns ? $count_pns : 0; ?>" data-counttkd="<?php echo $count_tkd ? $count_tkd : 0; ?>">
        <div class="col-md-6 col-md-push-6">
            <!-- <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <div style="margin-bottom: .6em;">
                            <strong><i class="fa fa-info-circle"></i> Informasi</strong>
                        </div>
                        <?php //echo !empty($information) ? $information->title : ''; ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <?php //echo !empty($information) ? $information->content : ''; ?>
                </div>
            </div> -->
        </div>
        <div class="col-md-6 col-md-pull-6">
            <div class="alert alert-info alert-dismissible">
                <h3 style="margin-top: 0;"><i class="icon fa fa-info-circle"></i> <?php echo $status_verifikasi_bkpp->NM_UNOR; ?></h3>
                <strong><span class="label <?php echo $status_verifikasi_bkpp->status == 'belum' ? 'label-danger' : 'label-success'; ?>"><?php echo strtoupper($status_verifikasi_bkpp->status . ' selesai'); ?></span> diverifikasi BKPP untuk periode TPP bulan <?php echo get_indo_month_name(date('n', strtotime("-1 month"))); ?></strong>
			</div>
            <div class="box">
                <div class="box-body">
                    <div id="chartPegawai"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let dinas = $(".row").attr('data-dinas');
        let countpns = $(".row").attr('data-countpns');
        let counttkd = $(".row").attr('data-counttkd');

        Highcharts.chart('chartPegawai', {
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: 'Pegawai ' + dinas
            },
            accessibility: {
                point: {
                    valueSuffix: ''
                }
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y} orang</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}<br>{point.y} orang'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Pegawai',
                data: [
                    ['PNS', parseInt(countpns)],
                    ['Tenaga Kontrak', parseInt(counttkd)]
                ]
            }]
        });
    </script>
    <?php elseif (get_session('id_groups') == '3'): ?>
    <div class="row">
        <div class="col-md-6 col-md-push-3">
            <div class="box box-solid">
                <div class="box-body box-profile">

                    <div style="margin-top: 1em;">
                        <img src="<?php echo !is_null($_user_login->photo) ? base_url(get_config_item('user_path') . $_user_login->photo) : base_url('assets/img/user.png'); ?>" class="profile-user-img img-responsive img-rounded" alt="User Image">
                    </div>

                    <h3 class="profile-username text-center">
                        <?php echo $_user_login->PNS_NAMA; ?>
                    </h3>

                    <p class="text-muted text-center"><?php echo $_user_login->nama_jabatan; ?></p>

                    <table class="table table-borderless">
                        <tr>
                            <th>Nama</th>
                            <td>:</td>
                            <td><?php echo $_user_login->PNS_NAMA; ?></td>
                        </tr>
                        <tr>
                            <th>NIP</th>
                            <td>:</td>
                            <td><?php echo $_user_login->PNS_PNSNIP; ?></td>
                        </tr>
                        <tr>
                            <th>Pangkat/Gol</th>
                            <td>:</td>
                            <td><?php echo $_user_login->NM_PKT . ' / ' . $_user_login->NM_GOL; ?></td>
                        </tr>
                        <tr>
                            <th>Jabatan</th>
                            <td>:</td>
                            <td><?php echo !is_null($mutasi_pending) ? $mutasi_pending->nama_jabatan : $_user_login->nama_jabatan; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Unit Kerja</th>
                            <td>:</td>
                            <td><?php echo !is_null($mutasi_pending) ? $mutasi_pending->unit_organisasi : $_user_login->unit_organisasi; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Atasan Langsung</th>
                            <td>:</td>
                            <td><?php echo $_user_login->PNS_NAMA_ATASAN; ?></td>
                        </tr>
                    </table>

                    <div class="text-center" style="margin: 0.5em 0;">
                        <span class="label label-info" style="font-size: 100%;">
                            <em>Silakan sampaikan keluhan Anda di menu Bantuan, melalui Admin Kepegawaian. Terima
                                kasih..</em>
                        </span>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
    <?php elseif (get_session('id_groups') == '1' || get_session('id_groups') == '5'): ?>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/kegiatan-style.css'); ?>">
	<style>
		.topmenu {
			margin-bottom: 0.15em !important;
            margin-left: 0.15em !important;
            line-height: 15px !important;
            height: auto !important;
            font-size: 1em !important;
            padding: 0.5em !important;
		}
	</style>
    <div class="row">
        <div class="col-md-4">
            <div class="box">
                <div class="box-header with-border">
                    <strong>Status Verifikasi BKPP, bulan:
                        <?php echo get_indo_month_name(date('n', strtotime("-1 month"))); ?></strong>
                </div>
                <div class="box-body">
                    <div class="table-responsive" style="height: 500px;">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="th-top" width="10">No</th>
                                    <th class="th-top">SOPD</th>
                                    <th class="th-top">Status</th>
                                    <th class="th-top">Tanggal Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($skpd_verifikasi_tpp): ?>
                                <?php foreach ($skpd_verifikasi_tpp as $key => $row): ?>
                                <tr>
                                    <td><?php echo ++$key; ?></td>
                                    <td><?php echo $row->NM_UNOR; ?></td>
                                    <td><span class="<?php echo $row->status == 'sudah' ? 'label label-success' : 'label label-warning'; ?>"><?php echo $row->status; ?></span>
                                    </td>
                                    <td><?php echo !is_null($row->tanggal) ? to_date_format($row->tanggal) : ''; ?></td>
                                </tr>
                                <?php endforeach;?>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <!-- <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <div style="margin-bottom: .6em;">
                            <strong><i class="fa fa-info-circle"></i> Informasi</strong>
                        </div>
                        <?php //echo !empty($information) ? $information->title : ''; ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <?php //echo !empty($information) ? $information->content : ''; ?>
                </div>
            </div> -->
            <div class="box">
                <div class="box-header with-border">
                    <strong>Status Penarikan Absen SOPD</strong>
                </div>
                <div class="box-body" style="height: 420px; overflow: auto;">
                    <?php if ($master_device): ?>
                    <?php foreach ($master_device as $row): ?>
                        <span class="btn btn-lg <?php echo !is_null($row->updated_at) ? (((time() - (60 * 60 * 24)) <= strtotime($row->updated_at)) ? 'btn-primary' : 'btn-warning') : 'btn-danger'; ?> topmenu topmenu-mobile">
							<strong><?php echo $row->instansi; ?></strong>
							<small><?php echo !is_null($row->updated_at) ? to_date_format($row->updated_at) : '-'; ?></small>
                        </span>
                    <?php endforeach;?>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
    <?php endif;?>

</section>
<!-- /.content -->