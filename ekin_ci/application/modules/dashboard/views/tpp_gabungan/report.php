<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>LAPORAN TPP PERIODE <?php echo $month_text . ' ' . $year; ?></title>
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
			font-size: 9px;
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

	</style>
</head>

<body>
	<div class="text-center">
		<h5>
			<strong>DAFTAR PENERIMA TAMBAHAN PENGHASILAN PNS (TPP)</strong> <br>
			<strong><?php echo $unor_text; ?> KABUPATEN KOTAWARINGIN BARAT</strong> <br>
			<strong>PERIODE <?php echo $month_text . ' ' . $year ; ?></strong>
		</h5>
	</div>
	<div>
		<table class="table">
			<thead class="with-border">
				<tr>
					<th>No</th>
					<th>NAMA / NIP / GOLRU</th>
					<th>PANGKAT / JABATAN</th>
					<th>GRADE</th>
					<th>AKTIVITAS KERJA (MENIT)</th>
					<th>PRESTASI KERJA (60%)</th>
					<th>BEBAN KERJA (40%) / SPK</th>
					<th>TKS / SANKSI / PENGURANGAN (<?php echo $pengurangan_tpp ? $pengurangan_tpp->pengurangan . '%' : '-'; ?>) / PENGURANGAN CPNS</th>
					<th>JUMLAH TPP / TUNJ. PLT / RAPEL</th>
					<th>1% BPJS</th>
					<th>PPH</th>
					<th>JUMLAH TPP YG DITERIMA</th>
				</tr>
			</thead>
			<tbody class="with-border">
				<?php if ($tpp_gabungan): ?>
				<?php
				$sum_tpp_prestasi_kerja       = 0;
				$sum_tpp_beban_kerja          = 0;
				$sum_besaran_hukuman_tks      = 0;
				$sum_sanksi                   = 0;
				$sum_pengurangan              = 0;
				$sum_pengurangan_cpns		  = 0;
				$sum_tpp_gabungan             = 0;
				$sum_tunjangan_plt            = 0;
				$sum_nominal_rapel            = 0;
				$sum_cost_bpjs                = 0;
				$sum_pph                      = 0;
				$sum_tpp_gabungan_setelah_pph = 0;
				?>
				<?php foreach ($tpp_gabungan->data as $key => $row): ?>
					<?php
					$sum_tpp_prestasi_kerja += $row->tpp_prestasi_kerja;
					$sum_tpp_beban_kerja += $row->tpp_beban_kerja;
					$sum_besaran_hukuman_tks += $row->besaran_hukuman_tks;
					$sum_sanksi += $row->nominal_sanksi;
					$sum_pengurangan += $row->pengurangan;
					$sum_pengurangan_cpns += $row->pengurangan_cpns;
					$sum_tpp_gabungan += $row->tpp_gabungan;
					$sum_tunjangan_plt += $row->tunjangan_plt;
					$sum_nominal_rapel += $row->nominal_rapel;
					$sum_cost_bpjs += $row->cost_bpjs;
					$sum_pph += $row->pph;
					$sum_tpp_gabungan_setelah_pph += $row->tpp_gabungan_setelah_pph;
					?>
				<tr>
					<td class="text-center">
						<?php echo ++$key; ?>
					</td>
					<td>
						<strong><?php echo $row->PNS_NAMA; ?></strong><br><?php echo $row->PNS_PNSNIP; ?><br><?php echo $row->pangkat; ?>
					</td>
					<td>
						<?php echo $row->NM_GOL . ' / ' . (!is_null($row->nama_jabatan) ? $row->nama_jabatan . " ({$row->unit_organisasi})" : '-') . '<br><strong>' . $row->bank . '</strong><br><strong>' . $row->PNS_NO_REK . '</strong>'; ?>
					</td>
					<td class="text-center">
						<?php echo $row->kelas_jabatan; ?>
					</td>
					<td class="text-right">
						<?php echo format_currency($row->total_norma_waktu, false); ?>
					</td>
					<td class="text-right">
						<?php echo ($month <= 4 && $year == 2020 ? format_currency($row->tpp_prestasi_kerja, false) : format_currency($row->tpp_prestasi_kerja, false)); ?>
					</td>
					<td class="text-right">
						<?php echo ($month <= 4 && $year == 2020 ? format_currency($row->tpp_beban_kerja, false) : format_currency($row->tpp_beban_kerja, false)) . '<br><strong>' . $row->persentase_indikator_kehadiran . '%</strong>'; ?>
					</td>
					<td class="text-right">
						<?php echo ($month <= 4 && $year == 2020 ? format_currency($row->besaran_hukuman_tks, false) : format_currency($row->besaran_hukuman_tks, false)) . '<br>' . format_currency($row->nominal_sanksi, false) . '<br>' . format_currency($row->pengurangan, false) . '<br>' . format_currency($row->pengurangan_cpns, false); ?>
					</td>
					<td class="text-right">
						<?php echo ($month <= 4 && $year == 2020 ? format_currency($row->tpp_gabungan, false) : format_currency($row->tpp_gabungan, false)) . '<br>' . format_currency($row->tunjangan_plt, false) . '<br>' . format_currency($row->nominal_rapel, false); ?>
					</td>
					<td class="text-right">
						<?php echo ($month <= 4 && $year == 2020 ? format_currency($row->cost_bpjs, false) : format_currency($row->cost_bpjs, false)); ?>
					</td>
					<td class="text-right">
						<?php echo ($month <= 4 && $year == 2020 ? format_currency($row->pph, false) : format_currency($row->pph, false)); ?>
					</td>
					<td class="text-right">
						<?php echo ($month <= 4 && $year == 2020 ? format_currency($row->tpp_gabungan_setelah_pph, false) : format_currency($row->tpp_gabungan_setelah_pph, false)); ?>
					</td>
				</tr>
				<?php if (!empty($row->keterangan_rapel) && !is_null($row->keterangan_rapel)): ?>
				<tr>
					<td></td>
					<td colspan="11">
						<strong>Keterangan Rapel: </strong> <?php echo $row->keterangan_rapel; ?>
					</td>
				</tr>
				<?php endif;?>
				<?php endforeach;?>
				<?php endif;?>
			</tbody>
			<tbody class="with-border ">
				<tr>
					<td class="text-center" colspan="5">
						<strong>JUMLAH</strong>
					</td>
					<td class="text-right"><strong><?php echo ($month <= 4 && $year == 2020 ? format_currency($sum_tpp_prestasi_kerja, false) : format_currency($sum_tpp_prestasi_kerja, false)); ?></strong></td>
					<td class="text-right"><strong><?php echo ($month <= 4 && $year == 2020 ? format_currency($sum_tpp_beban_kerja, false) : format_currency($sum_tpp_beban_kerja, false)); ?></strong></td>
					<td class="text-right">
						<strong>
							<?php echo ($month <= 4 && $year == 2020 ? format_currency($sum_besaran_hukuman_tks, false) : format_currency($sum_besaran_hukuman_tks, false)); ?>
							<br><?php echo ($month <= 4 && $year == 2020 ? format_currency($sum_sanksi, false) : format_currency($sum_sanksi, false)); ?>
							<br><?php echo ($month <= 4 && $year == 2020 ? format_currency($sum_pengurangan, false) : format_currency($sum_pengurangan, false)); ?>
							<br><?php echo ($month <= 4 && $year == 2020 ? format_currency($sum_pengurangan_cpns, false) : format_currency($sum_pengurangan_cpns, false)); ?>
						</strong>
					</td>
					<td class="text-right">
						<strong>
							<?php echo ($month <= 4 && $year == 2020 ? format_currency($sum_tpp_gabungan, false) : format_currency($sum_tpp_gabungan, false)); ?>
							<br><?php echo ($month <= 4 && $year == 2020 ? format_currency($sum_tunjangan_plt, false) : format_currency($sum_tunjangan_plt, false, 3)); ?>
							<br><?php echo ($month <= 4 && $year == 2020 ? format_currency($sum_nominal_rapel, false) : format_currency($sum_nominal_rapel, false)); ?>
						</strong>
					</td>
					<td class="text-right"><strong><?php echo ($month <= 4 && $year == 2020 ? format_currency($sum_cost_bpjs, false) : format_currency($sum_cost_bpjs, false, 3)); ?></strong></td>
					<td class="text-right"><strong><?php echo ($month <= 4 && $year == 2020 ? format_currency($sum_pph, false) : format_currency($sum_pph, false, 3)); ?></strong></td>
					<!-- <td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td> -->
					<td class="text-right"><strong><?php echo ($month <= 4 && $year == 2020 ? format_currency($sum_tpp_gabungan_setelah_pph, false) : format_currency($sum_tpp_gabungan_setelah_pph, false, 3)); ?></strong></td>
				</tr>
			</tbody>
			<tfoot style="border: none;  " fostyle="keep-together.within-page: always;">
				<tr>
					<td colspan="8" class="border-none"></td>
					<td colspan="4" style="padding-top: 5em;" class="text-center border-none">
						<p>
							Kotawaringin Barat,
							<?php echo date('d') . ' ' . get_indo_month_name(date('n')) . ' ' . date('Y'); ?>
						</p>
						<p>
							<strong><?php  echo $selected_penanda_tangan_plt?'PLT '. $selected_penanda_tangan_plt->nama_jabatan_plt : ($selected_penanda_tangan ? $selected_penanda_tangan->nama_jabatan: ($atasan_skpd ? $atasan_skpd->title : '')); ?></strong>
						</p>
					</td>
				</tr>
				<tr>
					<td colspan="8" class="border-none"></td>
					<td colspan="4" class="text-center border-none" style="padding-top: 3em;">
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
