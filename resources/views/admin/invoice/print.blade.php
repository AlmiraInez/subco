<?php
use app\components\Helper;
?>
<style>
	table.detail thead th{
	border: 1px solid;
	font-size: medium;
	}
	table.detail tbody td{
	border: 1px solid;
	font-size: medium;
	}
	table.detail {
	border-collapse: collapse;

	}
	.price{
		text-align: right;
	}
	.text-center{
		text-align: center;
	}
	.checkmark{
		top: 0;
		left: 0;
		height: 15px;
		width: 15px;
		background-color: #eee;
	}
	.font-medium{
		font-size: xx-large;
	}
</style>
<div style="text-align: center; font-size: xx-large;">
	<strong>SUBCO</strong>
</div>
<div style="text-align: center; font-size: medium;">
	Jalan Mayjen Yono Suwoyo kav 3, Spazio 1st Floor, Pradahkalikendal,<br> Kec. Dukuhpakis, Surabaya, Jawa Timur 60227<br />
	Telp. 0811-3587-878
</div>
<br>
<hr style="margin: 0px; border: 1px double;" />
<div style="text-align: center; font-size: x-large;">
	<strong><u>TAGIHAN</u></strong>
</div>
<table width="100%">
	<tr>
		<td width="60%" style="vertical-align: top;">
			Kepada,<br />
			{{ $invoice->tenant->name }} <br>
			{!! nl2br(e($invoice->tenant->address)) !!} <br>
			{{ $invoice->tenant->phone }} <br>
		</td>
		<td width="40%" style="vertical-align: middle;" style="margin-top: 50px;">
			<table style="font-size: medium; font-weight:bold;" width="100%">
				<tr>
					<td width="50%" style="padding: 1px;">Nomor Tagihan</td>
					<td width="50%" style="padding: 1px;">: {{ $invoice->code }}</td>
				</tr>
				<tr>
					<td style="padding: 1px;">Tanggal</td>
					<td style="padding: 1px;">: {{ $invoice->invoice_date }} </td>
				</tr>
				<tr>
					<td style="padding: 1px;">Status Pembayaran</td>
					<td style="padding: 1px;">: {{ $invoice->payment_status == 0 ? 'Belum Dibayar' : 'Lunas' }}</td> 
				</tr>
			</table>
		</td>
	</tr>
</table>
<h5 style="padding-top: 15px;"><strong>Daftar Tagihan</strong></h5>
<table cellpadding="3" class="detail" width="100%" style=" border-collapse: collapse; border: 1px;">
	<thead>
		<tr>
			<th width="3%" class="text-center">#</th>
			<th>Nama Ruangan</th>
			<th class="price">Harga Ruangan</th>
			<th width="10%" class="text-center">Durasi Sewa</th>
			<th width="10%" >Periode Pembayaran</th>
			<th width="20%" class="price">Subtotal (Rp)</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$no = 1;
		?>
		<tr>
			<td class="text-center">1</td>
			<td style="font-family: Verdana;">{{ $invoice->room->name }}</td>
			<td style="font-family: Verdana;" class="price">Rp. {{ number_format($invoice->room->price,0,',','.') }}</td>
			<td class="text-center">{{ $invoice->transaction->period_rent }}</td>
			<td class="text-center">{{ $invoice->transaction->payment_period }}</td>
			<td class="price">Rp. {{ number_format($invoice->amount,0,',','.') }}</td>
		</tr>
		<?php for($i=$no; $i<11; $i++): ?>
		<tr>
			<td class="text-center"><br/></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<?php endfor; ?>

		<tr style="border-top: 1px solid black;">
			<td width="10%" colspan="5" style="text-align: right; font-weight:bold;">Total</td>
			<td width="20%" class="price" style="text-align: right; font-weight:bold;">Rp. {{ number_format($invoice->amount,0,',','.') }}</td>
		</tr>
	</tbody>
</table>
<br/>
<em><u>Catatan:</u></em><br />
<span style="font-size:medium;">{{ nl2br($invoice->notes) }}</span>
<table style="font-size: medium;" width="100%" >
	<tr>
		<td width="40%" style="border: none;"></td>
		<td width="30%" style="text-align: center; font-size:medium;">
			Menyetujui,<br /><br /><br /><br /><br /><br />
			(<u>________________________________</u>)
		</td>
		<td width="30%" style="text-align: center; font-size:medium;">
			Admin,<br /><br /><br /><br /><br /><br />
			(<u>________________________________</u>)
		</td>
	</tr>
</table>

<script>
    window.print();
</script>
