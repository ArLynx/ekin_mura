<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>REKAPITULASI ABSENSI BULAN <?php echo $month_text . ' ' . $year; ?></title>
	<style>
		body {
			font-size: 16px;
		}

		.text-center {
			text-align: center;
		}

		.text-right {
			text-align: right;
		}

		.table {
			border-collapse: collapse;
			width: 100%;
			height: 1px;
			font-size: 12px;
		}

		.table tbody.with-border td,
		.table thead.with-border th {
			font-size: 9px;
			border: 1px solid #000;
			padding: 8px;
		}

		.border-none {
            border: none !important;
        }

		td>div {
			height: 100%;
			padding: 0.5em;
		}

		.dataTable tbody td {
			vertical-align: middle !important;
		}

		.dataTable tbody tr,
		.dataTable tbody td {
			padding: 0 !important;
		}

		.brown {
			background-color: #ccc;
		}

	</style>
</head>

<body>
	<div class="text-center">
		<h5>
			<strong>REKAPITULASI ABSENSI BULANANAN <?php echo strtoupper($type_text); ?></strong> <br>
			<strong><?php echo $unor_text; ?> KABUPATEN MURUNG RAYA</strong> <br>
			<strong>PERIODE <?php echo $month_text . ' ' . $year; ?></strong>
		</h5>
	</div>
	<div>
		<table class="table dataTable">
			<thead class="with-border">
				<tr>
					<th rowspan="2">No</th>
					<th rowspan="2">Nama</th>
					<th colspan="<?php echo $days_in_month; ?>">Tanggal</th>
				</tr>
				<tr>
					<?php if ($days_in_month): ?>
						<?php for ($i = 1; $i <= $days_in_month; $i++): ?>
							<th><?php echo $i; ?></th>
						<?php endfor;?>
					<?php endif;?>
				</tr>
			</thead>
			<tbody class="with-border">
				<?php if ($rekap_bulanan): ?>
					<?php if (!empty($rekap_bulanan->data)): ?>
						<?php foreach ($rekap_bulanan->data as $key => $row): ?>
							<tr>
								<td><?php echo ++$key; ?></td>
								<td><?php echo $row->PNS_NAMA; ?></td>
								<?php if ($days_in_month): ?>
									<?php for ($i = 1; $i <= $days_in_month; $i++): ?>
										<?php $absen = ($i < 10) ? "absen0{$i}" : "absen{$i}";?>
										<td class="text-center <?php echo (strpos($row->$absen, "class='brown'") !== false) ? 'brown' : ''; ?>"><?php echo $row->$absen; ?></td>
									<?php endfor;?>
								<?php endif;?>
							</tr>
						<?php endforeach;?>
					<?php endif;?>
				<?php endif;?>
			</tbody>

			<tfoot style="border: none;" fostyle="keep-together.within-page: always;">
				
			<!-- <tr>
					<td colspan="<?php echo $days_in_month - 6; ?>" class="border-none"></td>
					<td colspan="8" class="text-center border-none">
						<p>
							Kotawaringin Barat,
							<?php echo date('d') . ' ' . get_indo_month_name(date('n')) . ' ' . date('Y'); ?>
						</p>
						<p>
							<strong><?php echo isset($atasan_skpd) ? $atasan_skpd->title : ''; ?></strong>
						</p>
					</td>
				</tr>
				<tr>
					<td colspan="<?php echo $days_in_month - 6; ?>" class="border-none"></td>
					<td colspan="8" class="text-center border-none" style="padding-top: 4em;">
						<p>
							<strong><?php echo isset($atasan_skpd) ? $atasan_skpd->PNS_NAMA : ''; ?></strong>
						</p>
						<p><?php echo isset($atasan_skpd) ? 'NIP. ' . $atasan_skpd->PNS_PNSNIP : ''; ?></p>
					</td>
				</tr> -->

					<tr>
					<td colspan="<?php echo $days_in_month - 6; ?>" class="border-none"></td>
					<td colspan="8" style="padding-top: 5em;" class="text-center border-none">
						<p>
							Murung Raya,
							<?php echo date('d') . ' ' . get_indo_month_name(date('n')) . ' ' . date('Y'); ?>
						</p>
					<p>
							<strong><?php  echo $selected_penanda_tangan_plt?'PLT '. $selected_penanda_tangan_plt->nama_jabatan_plt : ($selected_penanda_tangan ? $selected_penanda_tangan->nama_jabatan: ($atasan_skpd ? $atasan_skpd->title : '')); ?></strong>
						</p>
					</td>
				</tr>
				<tr>
					<td colspan="<?php echo $days_in_month - 6; ?>" class="border-none"></td>
					<td colspan="8" class="text-center border-none" style="padding-top: 4em;">
						<p>
							<strong><?php echo $selected_penanda_tangan ? $selected_penanda_tangan->PNS_NAMA: ($atasan_skpd ? $atasan_skpd->PNS_NAMA : ''); ?></strong>
						</p>
						<p><?php echo $selected_penanda_tangan ? 'NIP. '. $selected_penanda_tangan->PNS_PNSNIP : ($atasan_skpd ? 'NIP. ' . $atasan_skpd->PNS_PNSNIP : ''); ?></p>
					</td>
				</tr>

				
				

			</tfoot>
		</table>
	</div>
</body>

</html>
