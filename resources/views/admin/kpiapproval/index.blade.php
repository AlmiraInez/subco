@extends('admin.layouts.app')

@section('title', 'KPI Approval')
@section('stylesheets')
<link href="{{asset('adminlte/component/dataTables/css/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/daterangepicker/daterangepicker.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">
<style>
  .shift:hover {
    cursor: pointer;
  }

  .scheme:hover {
    cursor: pointer;
  }

  .time_in:hover {
    cursor: pointer;
  }

  .time_out:hover {
    cursor: pointer;
  }

  .worktime:hover {
    cursor: pointer;
  }

  table.dataTable tbody td {
    height: 60px;
  }

  .ui-state-active {
    background: #28a745 !important;
    border-color: #28a745 !important;
  }

  .ui-menu {
    overflow: auto;
    height: 200px;
  }

  .back-to-top {
    position: fixed;
    bottom: 25px;
    right: 25px;
    display: none;
  }
</style>
@endsection
@if (!strpos(url()->previous(), 'kpiapproval'))
{{ Session::forget('name') }}
{{ Session::forget('date') }}
@endif
@push('breadcrump')
<li class="breadcrumb-item active">KPI Approval</li>
@endpush

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card ">
                <div class="card-header">
                    <h3 class="card-title">KPI Approval List</h3>
                    <!-- tools box -->
                    <div class="pull-right card-tools">
                        <a href="javascript:void(0)" onclick="approvemass()" class="btn btn-success btn-sm text-white" data-toggle="tooltip" title="Approve">
                            <i class="fa fa-check"></i>
                        </a>
                       
                    </div>
                    <!-- /. tools -->
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                            <label class="control-label" for="name">Employee Name</label>
                            <input type="text" name="employee_name" id="employee_name" class="form-control" placeholder="Employee Name">
                            </div>
                            <div id="employee-container"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                            <label class="control-label" for="nid">NIK</label>
                            <input type="text" name="nid" id="nid" class="form-control" placeholder="NIK">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                            <label for="from">From</label>
                            <input type="text" class="form-control datepicker" id="from" placeholder="From" name="from">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                            <label for="to">To</label>
                            <input type="text" class="form-control datepicker" id="to" placeholder="To" name="to">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="department">Department</label>
                                <input type="text" name="department" id="department" class="form-control" placeholder="Department">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                            <label class="control-label" for="workgroup">Workgroup Combination</label>
                            <input type="text" name="workgroup" id="workgroup" class="form-control" placeholder="Workgroup Combination">
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-bordered datatable" style="width:100%">
                        <thead>
                            <tr>
                                <th width="10">#</th>
                                <th width="200">Nama Karyawan</th>
                                <th width="50">Tanggal</th>
                                <th width="50">Bobot</th>
                                <th width="50">Tanggal Dibuat</th>
                                <th width="50">Terakhir Dirubah</th>
                                <th width="10" class="text-center"><input type="checkbox" name="check_all" id="check_all"></th>
                                <th width="50">#</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="overlay d-none">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="add-filter" tabindex="-1" role="dialog" aria-hidden="true" tabindex="-1" role="dialog"
    aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Filter</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="form-search" autocomplete="off">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="name">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Name">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button form="form-search" type="submit" class="btn btn-{{ config('configs.app_theme') }}"
                    title="Apply"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('adminlte/component/dataTables/js/datatables.min.js')}}"></script>
<script src="{{asset('adminlte/component/daterangepicker/moment.min.js')}}"></script>
<script src="{{asset('adminlte/component/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('adminlte/component/jquery-ui/jquery-ui.min.js')}}"></script>
<script type="text/javascript">
    function filter(){
    $('#add-filter').modal('show');
}
$(document).ready(function() {
    $("#check_all").click(function(){
      var checked = $(this).is(':checked');
      if(checked){
        $(".checkbox").each(function(){
          $(this).prop("checked",true);
        });
      }else{
        $(".checkbox").each(function(){
          $(this).prop("checked",false);
        });
      }
    });

    $('.checkbox').change(function(){ 
      if(false == $(this).prop("checked")){
        $("#check_all").prop('checked', false);
      }
      if ($('.checkbox:checked').length == $('.checkbox').length ){
        $("#check_all").prop('checked', true);
      }
    });
  });
$(function(){
    dataTable = $('.datatable').DataTable( {
        stateSave:true,
        processing: true,
        serverSide: true,
        filter:false,
        info:false,
        lengthChange:true,
        responsive: true,
        order: [[ 7, "asc" ]],
        ajax: {
            url: "{{route('kpiapproval.read')}}",
            type: "GET",
            data:function(data){
               var employee_id = $('input[name=employee_name]').val();
                var nid = $('input[name=nid]').val();
                var from = $('input[name=from]').val();
                var to = $('input[name=to]').val();
                var department = $('input[name=department]').val();
                var workgroup = $('input[name=workgroup]').val();
               
                data.employee_id = employee_id;
                data.nid = nid;
                data.from = from;
                data.to = to;
                data.department = department;
                data.workgroup = workgroup;
            }
        },
        columnDefs:[
            {
                orderable: false,targets:[0,1,5,6]
            },
            // { className: "text-right", targets: [0] },
            { className: "text-center", targets: [0,2,3,4,5,6,7] },
            // {
            //     render: function (data, type, row) {
            //         if (row.status == 0) {
            //             return `<span class="badge badge-warning">Waiting Approval</span>`
            //         }else if(row.status == 1){
            //             return `<span class="badge badge-success">Approved</span>`
            //         } else {
            //             return `<span class="badge badge-danger">Rejected</span>`
            //         }
            //     },
            //     targets: [5]
            // },
            { 
                render: function ( data, type, row ) {
                if (row.status == 0) {
                return `<input type="checkbox" class="checkbox" name="approve[]" id="check_${row.id}" data-employee_id="${row.employee.id}" data-id="${row.id}" value="${row.id}">`
                } else {
                return `<span class="badge badge-success"><i class="fa fa-check"></i></span>`
                }
                },targets: [6]
            },
            
            {
                render: function (data, type, row) {
                    return `${row.employee}<br><small>${row.nik}</small>`
                },
                targets: [1]
            },
            { 
                render: function ( data, type, row ) {
                return `<div class="dropdown">
                    <button class="btn  btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a class="dropdown-item" href="{{url('admin/kpi')}}/${row.id}"><i class="fas fa-info mr-3"></i> Detail</a></li>
                    </ul></div>`
            },targets: [7]
            }
        ],
        columns: [
            { 
                data: "no" 
            },
            { 
                data: "employee" 
            },
            { 
                data: "result_date" 
            },
            {
                data: "value_total"
            },
            { 
                data:"created_at"
            },
            { 
                data:"updated_at"
            },
            { 
                data: "status" 
            },
            { 
                data: "id" 
            },
        ]
    });
     $('#department').select2({
      ajax: {
        url: "{{route('department.select')}}",
        type:'GET',
        dataType: 'json',
        data: function (term,page) {
          return {
            name:term,
            page:page,
            limit:30,
          };
        },
        results: function (data,page) {
          var more = (page * 30) < data.total;
          var option = [];
          $.each(data.rows,function(index,item){
            option.push({
              id:item.name,
              text: `${item.path}`
            });
          });
          return {
            results: option, more: more,
          };
        },
      },
      allowClear: true,
      multiple: true
    });
    $('#workgroup').select2({
      ajax: {
        url: "{{route('workgroup.select')}}",
        type:'GET',
        dataType: 'json',
        data: function (term,page) {
          return {
            name:term,
            page:page,
            limit:30,
          };
        },
        results: function (data,page) {
          var more = (page * 30) < data.total;
          var option = [];
          $.each(data.rows,function(index,item){
            option.push({
              id:item.id,
              text: `${item.name}`
            });
          });
          return {
            results: option, more: more,
          };
        },
      },
      allowClear: true,
      multiple: true
    });
     dataTable.on('page.dt', function() {
      $('html, body').animate({
        scrollTop: $(".dataTables_wrapper").offset().top
      }, 'slow');
    });
    $('#from').daterangepicker({
      startDate: moment().subtract(1, 'days'),
      singleDatePicker: true,
      timePicker: false,
      timePickerIncrement: 30,
      locale: {
      format: 'MM/DD/YYYY'
      }
    }, function(chosen_date) {
      $('#from').val(chosen_date.format('MM/DD/YYYY'));
      dataTable.draw();
    });
    $('#to').daterangepicker({
      startDate: moment(),
      singleDatePicker: true,
      timePicker: false,
      timePickerIncrement: 30,
      locale: {
      format: 'MM/DD/YYYY'
      }
    }, function(chosen_date) {
      $('#to').val(chosen_date.format('MM/DD/YYYY'));
      dataTable.draw();
    });
   
});
$(document).ready(function() {
    var employees = [
				@foreach($employees as $employee)
                	"{!!$employee->name!!}",
            	@endforeach
			];
			$( "input[name=employee_name]" ).autocomplete({
			source: employees,
			minLength:0,
			appendTo: '#employee-container',
			select: function(event, response) {
				if(event.preventDefault(), 0 !== response.item.id){
					$(this).val(response.item.value);
					dataTable.draw();
				}
			}
			}).focus(function () {
				$(this).autocomplete("search");
			});
			$("input[name=employee_name]").keydown(function(event){
				if(event.keyCode == 13) {
					event.preventDefault();
					$('input[name=employee_name]').autocomplete('close');
					return false;
				}
		});
    var employees = [
				@foreach($employees as $nik)
                	"{!!$nik->nid!!}",
            	@endforeach
			];
			$( "input[name=nid]" ).autocomplete({
			source: employees,
			minLength:0,
			appendTo: '#employee-container',
			select: function(event, response) {
				if(event.preventDefault(), 0 !== response.item.id){
					$(this).val(response.item.value);
					dataTable.draw();
				}
			}
			}).focus(function () {
				$(this).autocomplete("search");
			});
			$("input[name=nid]").keydown(function(event){
				if(event.keyCode == 13) {
					event.preventDefault();
					$('input[name=nid]').autocomplete('close');
					return false;
				}
		});
    $(document).on('keyup', '#employee_name', function() {
      dataTable.draw();
    });
    $(document).on('keyup', '#nid', function() {
      dataTable.draw();
    });
    $(document).on('change', '#department', function() {
      dataTable.draw();
    });
    $(document).on('change', '#workgroup', function() {
      dataTable.draw();
    });
    $(document).on('apply.daterangepicker', function() {
      dataTable.draw();
    }).trigger('apply.daterangepicker');
    
});
function approvemass() {
    var isChecked = $('input[name="approve[]"]').is(":checked");
    if (isChecked) {
        bootbox.confirm({
            buttons: {
                confirm: {
                    label: '<i class="fa fa-check"></i>',
                    className: `btn-{{ config('configs.app_theme') }}`
                },
                cancel: {
                    label: '<i class="fa fa-undo"></i>',
                    className: 'btn-default'
                },
            },
            title: 'Approve Masal?',
            message: 'Data Akan Di Approve Sesuai Dengan Yang Dipilih',
            callback: function (result) {
                if (result) {
                    var data = {
                        approve: getid(),
                        _token: "{{ csrf_token() }}"
                    };
                    $.ajax({
                        url: `{{ route('kpi.approvemass') }}`,
                        dataType: 'json',
                        data: data,
                        method: 'post',
                        beforeSend: function () {
                            $('.overlay').removeClass('d-none');
                        }
                    }).done(function (response) {
                        if (response.status) {
                            $('.overlay').addClass('d-none');
                            $.gritter.add({
                                title: 'Success!',
                                text: response.message,
                                class_name: 'gritter-success',
                                time: 1000,
                            });
                            dataTable.ajax.reload(null, false);
                        } else {
                            $.gritter.add({
                                title: 'Warning!',
                                text: response.message,
                                class_name: 'gritter-warning',
                                time: 1000,
                            });
                        }
                    }).fail(function (response) {
                        var response = response.responseJSON;
                        $('.overlay').addClass('d-none');
                        $.gritter.add({
                            title: 'Error!',
                            text: response.message,
                            class_name: 'gritter-error',
                            time: 1000,
                        });
                    });
                }
            }
        });
    } else {
        $.gritter.add({
            title: 'Warning!',
            text: 'Please check',
            class_name: 'gritter-warning',
            time: 1000,
        });
    }
}
function getid(){
    var ids = $('input:checkbox:checked').map(function(){
        return $(this).data('id');
    }).get();

    return ids;
}
function getEmployeeId(){
    var employees = $('input:checkbox:checked').map(function(){
        return $(this).data('employee_id');
    }).get();

    return employees;
}

</script>
@endpush