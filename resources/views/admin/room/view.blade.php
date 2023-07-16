@extends('admin.layouts.app')

@section('title', 'Detail Ruangan')


@section('stylesheets')
<link rel="stylesheet" href="{{ asset('css/homedetail.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link href="{{asset('adminlte/component/dataTables/css/datatables.min.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('adminlte/component/daterangepicker/daterangepicker.css') }}">


@endsection
@push('breadcrump')
    <li class="breadcrumb-item"><a href="{{route('room.index')}}">Ruangan</a></li>
    <li class="breadcrumb-item active">Detail Ruangan</li>
@endpush
@section('content')
<div class="container">
  <div class="row">
    <div class="col-sm-6">
       <div id="carouselExampleIndicators" class="carousel" >
			<ol class="carousel-indicators">
				<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
				<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
				<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
				<li data-target="#carouselExampleIndicators" data-slide-to="3" ></li>
				<li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
				<li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
				<li data-target="#carouselExampleIndicators" data-slide-to="6"></li>
			</ol>
			<div class="carousel-inner">
				<div class="carousel-item active">
				<img class="w-100" src="{{asset('assets/rooms/img/'.$room->img1)}}" alt="First slide">
				</div>
				<div class="carousel-item">
				<img class="w-100" src="{{asset('assets/rooms/img/'.$room->img2)}}" alt="Second slide">
				</div>
				<div class="carousel-item">
				<img class="w-100" src="{{asset('assets/rooms/img/'.$room->img3)}}" alt="Third slide">
				</div>
				<div class="carousel-item">
				<img class="w-100" src="{{asset('assets/rooms/img/'.$room->img4)}}" alt="First slide">
				</div>
				<div class="carousel-item">
				<img class="w-100" src="{{asset('assets/rooms/img/'.$room->img5)}}" alt="Second slide">
				</div>
				<div class="carousel-item">
				<img class="w-100" src="{{asset('assets/rooms/img/'.$room->img6)}}" alt="Third slide">
				</div>
				<div class="carousel-item">
					<video width="500" height="450" controls style="padding-top:20px;">
					<source src="{{asset('assets/rooms/video/'.$room->video)}}" type="video/mp4">
					Your browser does not support the video tag.
					</video>
				</div>
			</div>
			<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>
    </div> 
	<div class="col-lg-6">
      <div class="card card-{{ config('configs.app_theme') }} card-outline">
        <div class="card-header" style="height: 57px;">
          <h3 class="card-title">Detail Ruangan</h3>
		  
        </div>
        <div class="card-body">
             <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Nama Ruangan : </label>
                    <span>{{ $room->name }}</span>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Lokasi Ruangan : </label>
					 <span>{{ $room->location }}</span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Kategori : </label>
					 <span>{{ $room->category->category }}</span>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Luas Ruangan : </label>
					 <span>{{ $room->width }} m2</span>
                  </div>
                </div>
              </div>
              <div class="row">
				 <div class="col-sm-12">
                  <div class="form-group">
                    <label>Harga Sewa : </label>
					 <span>{{ number_format($room->price,0,',','.') }}</span>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
					<label>Fasilitas : </label>

                   @foreach ($room->fasilities as $fa) 
						{{ $fa->fasility->fasility }}, 
						@endforeach
                  </div>
                </div>
              </div>
			  <div class="row">
				<div class="col-sm-12">
                  <div class="form-group">
                    <label>Alamat : </label>
					 <span>{{ $room->address}}</span>
                  </div>
                </div>
				<div class="col-sm-12">
                  <div class="form-group">
                    <label>Catatan : </label>
					 <span>{{ $room->note}}</span>
                  </div>
                </div>
			  </div>
        </div>
        <div class="overlay d-none">
          <i class="fa fa-refresh fa-spin"></i>
        </div>
      </div>
    </div>
  </div> <!-- /row -->
</div> <!-- /container -->
<div class="wrapper wrapper-content" style="margin-top:50px; margin-right:50px; margin-left:50px;">
  <div class="row">
    
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
function filter(){
    $('#add-filter').modal('show');
}
$("#category_id").select2({
		ajax: {
			url: "{{route('roomcategory.select')}}",
			type: 'GET',
			dataType: 'json',
			data: function (term, page) {
				return {
					category: term,
					page: page,
					limit: 30,
				};
			},
			results: function (data, page) {
				var more = (page * 30) < data.total;
				var option = [];
				$.each(data.rows, function (index, item) {
					option.push({
						id: item.id,
						text: `${item.category}`
					});
				});
				return {
					results: option,
					more: more,
				};
			},
		},
		allowClear: true,
	});
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

@endsection