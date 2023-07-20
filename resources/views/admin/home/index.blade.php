@extends('admin.layouts.panel')

@section('title', 'Home')

@section('subtitle', 'Control Panel')

@section('stylesheets')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link href="{{asset('adminlte/component/dataTables/css/datatables.min.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('adminlte/component/daterangepicker/daterangepicker.css') }}">

@endsection



@section('content')
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="w-100" src="{{ asset('img/slide2.jpg') }}" alt="First slide">
    </div>
    <div class="carousel-item">
      <img class="w-100" src="{{ asset('img/slide2.jpg') }}" alt="Second slide">
    </div>
    <div class="carousel-item">
      <img class="w-100" src="{{ asset('img/slide2.jpg') }}" alt="Third slide">
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
<div class="col-md-12" style="padding-top:50px;">
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<a href="#" onclick="filter()" class="btn btn-block btn-outline-info btn-md">
				</i> Cari Ruangan</span>
			</a>
		</div>
		<div class="col-md-3"></div>
	</div>

</div>
 <section class="ftco-section">
        <div class="container" style="padding-top:50px;">
            <div class="row">
                @foreach ($product as $prd)
               <div class="col-md-4">
					<div class="card mb-4 box-shadow">
						<img class="card-img-top" src="{{asset('assets/rooms/img/'.$prd->img1)}}" alt="Card image cap">
						<div class="card-body">
						<p class="card-text"><b>{{ $prd->name }}</b></p>
						<span> <b> Kategori :</b> {{ $prd->category->category }} <br></span>
						<span><b>Luas :</b>  {{ $prd->width }} m2 <br></span>
						<p><b>Lokasi :</b> {{$prd->location  }}</p>
						<div class="d-flex justify-content-between align-items-center">
							<div class="btn-group">
							{{-- <button type="button" class="btn btn-sm btn-outline-secondary">View</button> --}}
							<a href="{{url('admin/home/home')}}/{{ $prd->id }}" class="btn btn-sm btn-outline-secondary">Detail</a>
							</div>
							<strong>RP. {{ number_format($prd->price,0,',','.') }}</strong>
						</div>
						</div>
					</div>
				</div>
                 @endforeach
            </div>

            <div class="row mt-5">
                {{-- {{$product->links()}} --}}
                <div class="col text-center">
                    <div class="block-27">
                        <ul>
                            {{-- @foreach ($product as $item) --}}
                            <li class="active">{{$product->links()}}</li>
                            {{-- @endforeach --}}
                            {{-- <li><a href="#">&lt;</a></li>
                            <li class="active"><span>1</span></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li><a href="#">&gt;</a></li> --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
		<div class="modal fade" id="add-filter" tabindex="-1" role="dialog"  aria-hidden="true" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Cari Ruangan</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					</div>
					<div class="modal-body">
						<form id="form-search" autocomplete="off" action="{{ route('admin.home.home.index') }}" method="GET">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="uom_name">Nama</label>
										<input type="text" name="name" class="form-control" placeholder="Nama">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="category_id">Kategori</label>
										<input type="text" id="category_id" name="category_id" class="form-control" placeholder="Kategori">
									</div>
								</div>
								
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button form="form-search" type="submit" class="btn btn-info" title="Apply"><i class="fa fa-search"></i></button>
					</div>
				</div>
			</div>
		</div>
    </section>
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