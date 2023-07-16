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
                                    <h5 class="info-heading text-right mb-0">0</h5>
                                    <p class="info-text text-right mb-3">Persetujuan Absensi</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs pt-3 small-box-footer" href="{{route('leaveapproval.indexapproval')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
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
                    				<h5 class="info-heading text-right mb-0">0</h5>
                                    <p class="info-text text-right mb-3">Persetujuan Cuti</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs pt-3 pt-3 small-box-footer" href="{{route('leaveapproval.indexapproval')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
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
                                    <h5 class="info-heading text-right mb-0">0</h5>
                                    <p class="info-text text-right mb-3">Persetujuan Pinjaman</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs pt-3 small-box-footer" href="{{route('leaveapproval.indexapproval')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
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
                                    <p class="info-text text-right mb-3">Kebutuhan Posisi</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs pt-3 small-box-footer" href="{{route('leaveapproval.indexapproval')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
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
                                    <p class="info-text text-right mb-3">Lamaran Kerja</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs pt-3 small-box-footer" href="{{route('leaveapproval.indexapproval')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
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
                                    <p class="info-text text-right mb-3">Kontrak Habis</p>
                                </div>
                            </div>
                        </div>
                        <a class="info-link mb-0 text-xs pt-3 small-box-footer" href="{{route('leaveapproval.indexapproval')}}">Lihat Semua Data <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                    </div>
                </div>
            </div>
        </div>
    	<div class="col-lg-6">
    		<div class="infobox-3">
                <div class="row">
                    <div class="col-lg-6">
        				<h4>Status Absensi</h4>
                    </div>
                    <div class="col-lg-6 text-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown"><i>Mei 2021</i></button>
                            <div class="dropdown-menu float-right" role="menu">
                                <a href="#" class="dropdown-item">Juni 2021</a>
                                <a href="#" class="dropdown-item">Juli 2021</a>
                                <a href="#" class="dropdown-item">Agustus 2021</a>
                                <a href="#" class="dropdown-item">September 2021</a>
                                <a href="#" class="dropdown-item">Oktober 2021</a>
                                <a href="#" class="dropdown-item">November 2021</a>
                                <a href="#" class="dropdown-item">Desember 2021</a>
                            </div>
                        </div>
                    </div>
                </div>
    			<div class="text-center">
    			</div>
                <div id="absen-karyawan"></div>
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
                            <p class="info-text text-right mb-3">Estimasi Gaji Bulan Berjalan</p>
                        </div>
                    </div>
                    <div class="col-lg-12 pt-3">
                    	<table class="table">
							<tbody>
								<tr>
									<td>Gaji Pokok</td>
									<td class="text-right">Rp. 54.250.000,00</td>
								</tr>
								<tr>
									<td>Lembur</td>
									<td class="text-right">Rp. 10.750.000,00</td>
								</tr>
								<tr>
									<td>Tunjangan</td>
									<td class="text-right">Rp. 11.000.000,00</td>
								</tr>
								<tr>
									<td>Bonus</td>
									<td class="text-right">Rp. 820.000,00</td>
								</tr>
								<tr>
									<td>Lain - Lain</td>
									<td class="text-right">Rp. 0,00</td>
								</tr>
							</tbody>
						</table>
                    </div>
                </div>
            </div>
    	</div>
    	<div class="col-lg-6">
    		<div class="row">
                <div class="col-md-6 col-sm-12 col-lg-6">
    				<div class="infobox-3">
                        <div class="text-center">
                            <h4>Lama Masa Kerja</h4>
                        </div>
		                <div id="masa-kerja"></div>
		            </div>
                </div>
                <div class="col-md-6 col-sm-12 col-lg-6">
                    <div class="infobox-3">
                        <div class="text-center">
                            <h4>Status Karyawan</h4>
                        </div>
                        <div id="status-karyawan"></div>
                    </div>
                </div>
    		</div>
    	</div>
    	<div class="col-lg-6">
    		<div class="infobox-3">
    			<div id="line-chart"></div>
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

<script>
	var departement = [];
	var attend = [];
	var notAttend = [];
	var gross = [];
	var label = @JSON($donutChart['label']);
	var data = @JSON($donutChart['data']);
	var labelAttend = [];
	@foreach($yesterdayAttendancebyDept as $value)
	departement.push('{!! $value->name !!}');
	attend.push('{!! $value->attend !!}');
	notAttend.push('{!! $value->notAttend !!}');
	@endforeach
	@foreach($grossSalaryYear as $gross)
	gross.push('{!! round($gross) !!}');
	@endforeach
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
<script type="text/javascript">
	function contract(){
			$('#add_contract').modal('show');
	}
	$('#add_contract').on('shown.bs.modal', function () {
		dataTable.columns.adjust().responsive.recalc();
	})
	function documentexpired(){
		$('#add_document').modal('show');
	}
	$('#add_document').on('shown.bs.modal', function () {
		dataTableDocument.columns.adjust().responsive.recalc();
	})
	$(document).ready(function () {
			var url = "{!! url('admin/' . 'attendanceapproval') !!}";
			var urlNow = window.location.href;
			if (urlNow.indexOf(url) === -1) {
					localStorage.clear();
			}
	});
	$(document).on('click','.editdocument',function(){
		var id = $(this).data('id');
		$.ajax({
			url:`{{url('admin/documentmanagement')}}/${id}/edit`,
			method:'GET',
			dataType:'json',
			beforeSend:function(){
				$('#box-menu .overlay').removeClass('d-none');
			},
		}).done(function(response){
			$('#box-menu .overlay').addClass('d-none');
			if(response.status){
				$('#edit_document .modal-title').html('Edit Document');
				$('#edit_document').modal('show');
				$('#form_document')[0].reset();
				$('#form_document .invalid-feedback').each(function () { $(this).remove(); });
				$('#form_document .form-group').removeClass('has-error').removeClass('has-success');
				$('#form_document input[name=_method]').attr('value','PUT');
				$('#form_document input[name=name]').attr('value',response.data.name);
				$('#form_document input[name=nilai]').attr('value',response.data.nilai);
				$('#form_document input[name=code]').attr('value',response.data.code);
				$('#form_document input[name=file]').attr('value',response.data.file);
				$('#form_document input[name=pic]').attr('value',response.data.pic);
				$('#form_document input[name=expired_date]').attr('value',response.data.expired_date);
				$('#form_document textarea[name=description]').html(response.data.description);
				$('#document-preview').html(response.data.file).attr('data-url',response.data.link);
				$('#form_document').attr('action',`{{url('admin/documentmanagement/')}}/${response.data.id}`);
			}          
		}).fail(function(response){
			var response = response.responseJSON;
			$('#box-menu .overlay').addClass('d-none');
			$.gritter.add({
				title: 'Error!',
				text: response.message,
				class_name: 'gritter-error',
				time: 1000,
			});
		})	
	});
	$("#form_document").validate({
			errorElement: 'div',
			errorClass: 'invalid-feedback',
			focusInvalid: false,
			highlight: function (e) {
			$(e).closest('.form-group').removeClass('has-success').addClass('was-validated has-error');
			},

			success: function (e) {
			$(e).closest('.form-group').removeClass('has-error').addClass('has-success');
			$(e).remove();
			},
			errorPlacement: function (error, element) {
			if(element.is(':file')) {
					error.insertAfter(element.parent().parent().parent());
			}else
			if(element.parent('.input-group').length) {
					error.insertAfter(element.parent());
			}
			else{
					error.insertAfter(element);
			}
			},
			submitHandler: function() {
			$.ajax({
					url:$('#form_document').attr('action'),
					method:'post',
					data: new FormData($('#form_document')[0]),
					processData: false,
					contentType: false,
					dataType: 'json',
					beforeSend:function(){
					$('.overlay').removeClass('d-none');
					}
			}).done(function(response){
							$('.overlay').addClass('d-none');
							if(response.status){
							$('#edit_document').modal('hide');
							dataTableDocument.draw();
							}
							else{
							$.gritter.add({
									title: 'Warning!',
									text: response.message,
									class_name: 'gritter-warning',
									time: 1000,
							});
							}
							return;
			}).fail(function(response){
					$('.overlay').addClass('d-none');
					var response = response.responseJSON;
					$.gritter.add({
							title: 'Error!',
							text: response.message,
							class_name: 'gritter-error',
							time: 1000,
					});
			})
			}
	});
	
	function showDocument(e){
		$('#url-document').attr("src",$(e).data('url'));
		$('.download-button').attr("href",$(e).data('url'));
		$('#show-document').modal('show');
  	}
	$(function(){
		$('#expired_date').daterangepicker({
      singleDatePicker: true,
      timePicker: false,
      timePickerIncrement: 1,
      locale: {
      format: 'YYYY/MM/DD'
      }
    });
		attendanceDepartment = $('#attendance-table').DataTable( {
				processing: true,
				serverSide: true,
				filter:false,
				info:false,
				lengthChange:true,
				responsive: true,
				paging: false,
				order: [[ 1, "asc" ]],
				ajax: {
					url: "{{route('dashboard.departmentdetail')}}",
					type: "GET",
					data:function(data){
						var department_name = $('input[name=department_name]').val();
						data.department_name = department_name;
					}
				},
				columnDefs:[
				{
					orderable: false,targets:[0]
				},
				{ className: "text-center", targets: [2,3] },
				
			],
			columns: [
				{ 
					data: "no" 
				},
				{ 
					data: "department_name" 
				},
				{ 
					data: "not_attend" 
				},
				{ 
					data: "attend" 
				}
			]
		});
		dataTable = $('.datatable').DataTable( {
				stateSave:true,
				processing: true,
				serverSide: true,
				filter:false,
				info:false,
				lengthChange:true,
				responsive: true,
				order: [[ 5, "asc" ]],
				ajax: {
					url: "{{route('dashboard.readcontract')}}",
					type: "GET",
					data:function(data){
						var employee_id = $('input[name=employee_name]').val();
						var nid = $('input[name=nid]').val();
						var date = $('input[name=birthday]').val();
						var department = $('input[name=department]').val();
						var position = $('input[name=position]').val();
						var workgroup = $('input[name=workgroup]').val();
						var day = $('select[name=day] option').filter(':selected').val()
						var month = $('select[name=month] option').filter(':selected').val();
						var year = $('select[name=year] option').filter(':selected').val();
						data.employee_id = employee_id;
						data.nid = nid;
						data.date = date;
						data.department = department;
						data.workgroup = workgroup;
						data.position = position;
						data.day = day;
						data.month = month;
						data.year = year;
					}
				},
				columnDefs:[
				{
					orderable: false,targets:[0]
				},
				{ className: "text-right", targets: [0] },
				{ className: "text-center", targets: [0] },
				{
					render: function ( data, type, row ) {
					return `<a href="{{url('admin/employees')}}/${row.id}/">${row.name}</a>`;
					},targets: [2]
				}
				
			],
			columns: [
				{ 
					data: "no" 
				},
				{ 
					data: "nid" 
				},
				{ 
					data: "name" 
				},
				{ 
					data: "department_name" 
				},
				{ 
					data: "workgroup_name" 
				},
				{ 
					data: "end_date" 
				},
			]
		});
		dataTableDocument = $('.table-document').DataTable( {
        stateSave:true,
        processing: true,
        serverSide: true,
        filter:false,
        info:false,
        lengthChange:true,
        responsive: true,
        order: [[ 6, "asc" ]],
        ajax: {
            url: "{{route('dashboard.readdocument')}}",
            type: "GET",
            data:function(data){
                var code = $('#form-search').find('input[name=code]').val();
                var code = $('#form-search').find('input[name=name]').val();
                data.code = code;
                data.name = name;
            }
        },
        columnDefs:[
					{
						orderable: false,targets:[0]
					},
					{ className: "text-right", targets: [0] },
                    { className: "text-center", targets: [5,6] },
                   
					{
					render: function (data, type, row) {
						// return `<a href="${row.file}" target="_blank"><img class="img-fluid" src="${row.file}" height=\"100\" width=\"150\"/><a/>`
							return `<a onclick="showDocument(this)" data-url="${row.link}" href="#"><span class="badge badge-info">Preview</span><a/>`
					},
					targets: [5]
                    },
                    {
						render: function (data, type, row) {
							if (row.status == 'Active') {
								return `<span class="badge badge-success">Active</span>`
							}else{
								return `<span class="badge badge-danger">Expired</span>`
							}
						},
						targets: [6]
										},
					{ render: function ( data, type, row ) {
						return `<div class="dropdown">
							<button type="button" class="btn  btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-bars"></i>
							</button>
							<ul class="dropdown-menu dropdown-menu-right">
								<li><a class="dropdown-item editdocument" href="#" data-id="${row.id}"><i class="fa fa-pencil-alt mr-2"></i> Edit</a></li>
							</ul>
							</div>`
					},targets: [7]
					}
                    
				
				],
				columns: [
                    { data: "no" },
                    { data: "code"},
                    { data: "name" },
										{ data: "expired_date"},
										{ data: "pic"},
                    { data: "file" },
										{ data: "status" },
										{ data: "id"}
				]
    });
	});
</script>
@endsection