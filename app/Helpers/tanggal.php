<?php
	function dateID($tanggal)
	{
		if (empty($tanggal) || $tanggal == '0000-00-00') {
			return '-';
		}

		$bulan = array(
		1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 
			 'September', 'Oktober', 'November', 'Desember'
		);

		$pecahkan = explode('-', $tanggal);
		return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
	}
	?>
