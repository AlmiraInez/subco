@extends('admin.layouts.panel')

@section('title', 'Dashboard Inventory')

@section('stylesheets')
<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('apex/apexcharts.css') }}" />
<link href="{{asset('adminlte/component/dataTables/css/datatables.min.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('adminlte/component/daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
<div class="container-dashboard pl-4 pr-4">
	<div class="row mt-3">
        <div class="col-lg-9">
            <div class="row">
                <div class="col-md-6 col-sm-12 col-lg-4">
                    <div class="infobox-3 nunito">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="info-icon">
                                    <img src="{{ asset('img/icon/attendance approval.png') }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="info-box-content">
                                    <h5 class="info-heading text-right mb-0">0</h5>
                                    <p class="info-text text-right mb-3">Total Persediaan</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs  small-box-footer" href="{{route('leaveapproval.indexapproval')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                    </div>
                </div>
            	<div class="col-md-6 col-sm-12 col-lg-4">
            		<div class="infobox-3 nunito">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="info-icon">
                                    {{-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-x"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="18" y1="8" x2="23" y2="13"></line><line x1="23" y1="8" x2="18" y2="13"></line></svg> --}}
                                    <img src="{{ asset('img/icon/leave approval.png') }}">
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="info-box-content">
                    				<h5 class="info-heading text-right mb-0">0</h5>
                                    <p class="info-text text-right mb-3">Persediaan Hampir Habis</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs  small-box-footer" href="{{route('leaveapproval.indexapproval')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                    </div>
            	</div>
                <div class="col-md-6 col-sm-12 col-lg-4">
                    <div class="infobox-3 nunito">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="info-icon">
                                    {{-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> --}}
                                    <img src="{{ asset('img/icon/loan approval.png') }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="info-box-content">
                                    <h5 class="info-heading text-right mb-0">0</h5>
                                    <p class="info-text text-right mb-3">Persediaan Habis</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs  small-box-footer" href="{{route('leaveapproval.indexapproval')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 col-lg-4">
                    <div class="infobox-3 nunito">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="info-icon">
									{{-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> --}}
                                    <img src="{{ asset('img/icon/oprec.png') }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="info-box-content">
                                    <h5 class="info-heading text-right mb-0">0</h5>
                                    <p class="info-text text-right mb-3">Persetujuan Pinjam Barang</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs  small-box-footer" href="{{route('leaveapproval.indexapproval')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                    </div>
                </div>
            	<div class="col-md-6 col-sm-12 col-lg-4">
            		<div class="infobox-3 nunito">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="info-icon">
                                    <img src="{{ asset('img/icon/submit cv.png') }}">
									{{-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg> --}}
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="info-box-content">
                    				<h5 class="info-heading text-right mb-0">0</h5>
                                    <p class="info-text text-right mb-3">Persetujuan Penerimaan Barang</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs  small-box-footer" href="{{route('leaveapproval.indexapproval')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                    </div>
            	</div>
                <div class="col-md-6 col-sm-12 col-lg-4">
                    <div class="infobox-3 nunito">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="info-icon">
                                    {{-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> --}}
                                    <img src="{{ asset('img/icon/contract expired.png') }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="info-box-content">
                                    <h5 class="info-heading text-right mb-0">0</h5>
                                    <p class="info-text text-right mb-3">Persetujuan Pengeluaran Barang</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs  small-box-footer" href="{{route('leaveapproval.indexapproval')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
        	<div class="infobox-3">
        		<div class="">
	                <h4>Stock Barang</h4>
        		</div>
    			<div id="available-stock"></div>
    		</div>
        </div>
    	<div class="col-lg-6">
    		<div class="infobox-3">
    			<div id="trend-material-usage"></div>
    		</div>
    	</div>
    	<div class="col-lg-6">
    		<div class="infobox-3 nunito">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="info-icon">
                        	{{-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg> --}}
                            <img src="{{ asset('img/icon/sallary estimation.png') }}">
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="info-box-content">
                            <h5 class="info-heading text-right mb-0">Rp. 76.820.000,00</h5>
                            <p class="info-text text-right mb-3">Nilai Persediaan</p>
                        </div>
                    </div>
                    <div class="col-lg-12 pt-3">
                    	<table class="table">
							<tbody>
								<tr>
									<td>Bahan Baku</td>
									<td class="text-right">Rp. 54.250.000,00</td>
								</tr>
								<tr>
									<td>Produk Jadi</td>
									<td class="text-right">Rp. 10.750.000,00</td>
								</tr>
								<tr>
									<td>Bahan Sisa Produksi</td>
									<td class="text-right">Rp. 11.000.000,00</td>
								</tr>
								<tr>
									<td>Bahan Pembantu</td>
									<td class="text-right">Rp. 820.000,00</td>
								</tr>
								<tr>
									<td>Barang Dalam Perjalanan</td>
									<td class="text-right">Rp. 0,00</td>
								</tr>
							</tbody>
						</table>
                    </div>
                </div>
            </div>
    	</div>
	</div>
</div>
@endsection

@section('scripts')
<script src="{{asset('adminlte/component/dataTables/js/datatables.min.js')}}"></script>
<script src="{{asset('adminlte/component/validate/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('adminlte/component/daterangepicker/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('adminlte/component/daterangepicker/daterangepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('adminlte/component/blockui/jquery.blockUI.js') }}"></script>
<script type="text/javascript" src="{{ asset('apex/apexcharts.min.js') }}"></script>
<script type="text/javascript">
	var AvailableStockOption = {
		chart: {
			height: 310,
	        type: 'donut'
		},
		legend: {
	        show: true,
	        position: 'bottom',
            horizontalAlign: 'left'
		},
	    stroke: {
			colors: '#0e1726'
	    },
		plotOptions: {
	        pie: {
				donut: {
			        labels: {
						show: true,
				        position: 'bottom',
						name: {
			                fontSize: '1rem',
			                fontFamily: 'Montserrat',
			                label: 'Status Karyawan'
			            },
						value: {
			                fontSize: '1rem',
			                fontFamily: 'Montserrat',
			                formatter: function (val) {
				                return parseInt(val) + '%';
							}
						},
					}
				}
			}
		},
	    series: [44, 55, 41],
	    labels: ['Stock Aman', 'Di Bawah Stock', 'Stock Habis'],
	    colors: ['#EBC443', '#367f88', '#E05D57'],
	    responsive: [
	    {
	    	breakpoint: 992,
	    	options: {
	    		chart: {
	    			height: 380
	    		}
	    	}
	    },
        ]
	}

	var availableStock = new ApexCharts(
	    document.querySelector("#available-stock"),
	    AvailableStockOption
	);

	availableStock.render();

	var trendMaterialUsageOption = {
        series: [{
            name: 'Daging',
            data: [31, 40, 28, 51, 42, 109, 100]
        }, {
            name: 'Bayem',
            data: [11, 32, 45, 32, 34, 52, 41]
        }],
        chart: {
            height: 350,
            type: 'area'
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            type: 'datetime',
            categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
        },
        tooltip: {
            x: {
                format: 'dd/MM/yy HH:mm'
            },
        },
    }
    var trendMaterialUsage = new ApexCharts(
    document.querySelector("#trend-material-usage"),
    trendMaterialUsageOption
    );
    trendMaterialUsage.render();
</script>
@endsection