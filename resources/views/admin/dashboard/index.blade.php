@extends('admin.layouts.panel')

@section('title', 'Dashboard')

@section('subtitle', 'Control Panel')

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
        <div class="col-lg-12">
            <div class="row">
                <div class="col-md-6 col-sm-12 col-lg-4">
                    <div class="infobox-3 nunito">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="info-icon">
                                    <img src="{{ asset('img/icon/attendance approval.png') }}">
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="info-box-content">
                                    <h5 class="info-heading text-right mb-0">{{ $roomavailable }}</h5>
                                    <p class="info-text text-right mb-3">Ruangan<br>Tersedia</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs pt-3 small-box-footer" href="{{route('room.index')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                    </div>
                </div>
            	<div class="col-md-6 col-sm-12 col-lg-4">
            		<div class="infobox-3 nunito">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="info-icon">
                                    {{-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-x"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="18" y1="8" x2="23" y2="13"></line><line x1="23" y1="8" x2="18" y2="13"></line></svg> --}}
                                    <img src="{{ asset('img/icon/leave approval.png') }}">
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="info-box-content">
                    				<h5 class="info-heading text-right mb-0">{{$checkin}}</h5>
                                    <p class="info-text text-right mb-3">Ruangan<br>Terpakai</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs pt-3 pt-3 small-box-footer" href="{{route('transaction.index')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                    </div>
            	</div>
                <div class="col-md-6 col-sm-12 col-lg-4">
                    <div class="infobox-3 nunito">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="info-icon">
                                    {{-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> --}}
                                    <img src="{{ asset('img/icon/loan approval.png') }}">
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="info-box-content">
                                    <h5 class="info-heading text-right mb-0">{{ $booking }}</h5>
                                    <p class="info-text text-right mb-3">Ruangan <br>Dipesan</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs pt-3 small-box-footer" href="{{route('transaction.index')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
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
                                    <h5 class="info-heading text-right mb-0">{{ $transapproval }}</h5>
                                    <p class="info-text text-right mb-3">Persetujuan <br>Pemesanan</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs pt-3 small-box-footer" href="{{route('admin.transaction.approval.index')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
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
                    				<h5 class="info-heading text-right mb-0">{{ $payapproval }}</h5>
                                    <p class="info-text text-right mb-3">Persetujuan <br>Pembayaran</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs pt-3 small-box-footer" href="{{route('paymentapproval.index')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
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
                                    <h5 class="info-heading text-right mb-0">{{ $invapproval }}</h5>
                                    <p class="info-text text-right mb-3">Jumlah Tagihan<br> Belum Lunas</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs pt-3 small-box-footer" href="{{route('leaveapproval.indexapproval')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
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
	var donutChart = {
		chart: {
			height: 330,
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
	    series: [44, 55, 41, 17],
	    labels: ['Permanent', 'Probation', 'Contract', 'Intership'],
	    colors: ['#EBC443', '#367f88', '#565551', '#E05D57'],
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

	var donut = new ApexCharts(
	    document.querySelector("#status-karyawan"),
	    donutChart
	);

	donut.render();

	var absenKaryawanChart = {
		chart: {
			height: 270,
	        type: 'donut'
		},
		legend: {
	        show: true,
	        position: 'right',
	        horizontalAlign: 'right'
		},
	    stroke: {
			colors: '#0e1726'
	    },
		plotOptions: {
	        pie: {
				donut: {
			        labels: {
						show: true,
				        position: 'right',
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
	    series: [44, 55, 41, 17],
	    labels: ['Hadir', 'Ijin', 'Off', 'Alpha'],
	    colors: ['#EBC443', '#367f88', '#565551', '#E05D57'],
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

	var absenKaryawan = new ApexCharts(
	    document.querySelector("#absen-karyawan"),
	    absenKaryawanChart
	);

	absenKaryawan.render();

	var masaKerjaKaryawan = {
		chart: {
			height: 330,
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
	    series: [44, 55, 41, 17],
	    labels: ['0 - 5 Tahun', '5 - 10 Tahun', '10 - 15 Tahun', '> 15 Tahun'],
	    colors: ['#EBC443', '#367f88', '#565551', '#E05D57'],
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

	var masaKerja = new ApexCharts(
	    document.querySelector("#masa-kerja"),
	    masaKerjaKaryawan
	);

	masaKerja.render();

  var lineChartOptions = {
    chart: {
      height: 310,
      type: 'line',
      zoom: {
        enabled: false
      }
    },
    colors: ['#EBC443', '#367f88', '#565551', '#E05D57'],
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'straight'
    },
    series: [{
      name: "Desktops",
      data: [10, 41, 35, 51, 49, 62, 69,],
    }],
    title: {
      text: 'Rata - rata lembur mingguan',
      align: 'left'
    },
    grid: {
      row: {
        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
        opacity: 0.5
      },
    },
    xaxis: {
      categories: ['Mon', 'Sun', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
    },
    yaxis: {
      tickAmount: 5,
    }
  }
  var lineChart = new ApexCharts(
    document.querySelector("#line-chart"),
    lineChartOptions
  );
  lineChart.render();
</script>
<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
<script type="text/javascript">
	function blockMessage(element,message,color){
		$(element).block({
	    	message: '<span class="text-semibold"><i class="icon-spinner4 spinner position-left"></i>&nbsp; '+message+'</span>',
	        overlayCSS: {
	            backgroundColor: color,
	            opacity: 0.8,
	            cursor: 'wait'
	        },
	        css: {
	            border: 0,
	            padding: '10px 15px',
	            color: '#fff',
	            width: 'auto',
	            '-webkit-border-radius': 2,
	            '-moz-border-radius': 2,
	            backgroundColor: '#333'
	        }
	    });
	}
</script>
@endsection