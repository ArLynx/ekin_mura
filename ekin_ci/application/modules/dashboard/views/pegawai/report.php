<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PRINT PEGAWAI</title>
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
			<!-- <?= var_dump($pns)?> -->
			<!-- <strong>DAFTAR PEGAWAI PNS</strong> -->
			 <br>
				<strong><?= $unor_text?></strong> <br>
			<strong>KABUPATEN KOTAWARINGIN BARAT</strong> <br>
			
		</h5>
	</div>
	<div>
		<table class="table">
			<thead class="with-border">
				<tr>
					<th>No</th>
					<th>ID</th>
					<th>NIP</th>
					<th>NAMA </th>
				
			
				</tr>
			</thead>
				
	<tbody class="with-border">
	<?php $count = 1; ?>
		<?php foreach ($pns as $row): ?>
			<?= var_dump($row) ?>
	<tr>
		<td class="text-center">
				<?php echo $count++  ?>	
		</td>
		<td class="text-center">
				<?php echo $row->id ?>	
		</td>
			<td class="text-center">
				<?php echo  $row->PNS_PNSNIP;?>	
		</td>
        	<td class="text-center">
				<?php echo  $row->PNS_GLRDPN . " ". $row->PNS_PNSNAM ." ". $row->PNS_GLRBLK?>	
		</td>
	
    </tr>
	<?php endforeach; ?>
    </tbody>
		</table>
	</div>
</body>

</html>
