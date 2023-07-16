@extends('admin.layouts.app')

@section('title', 'Attendance Approved')
@section('stylesheets')
<link href="{{ asset('adminlte/component/dataTables/css/datatables.min.css') }}" rel="stylesheet">
<link href="{{asset('adminlte/component/daterangepicker/daterangepicker.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">
<style type="text/css">
  .customcheckbox {
    width: 22px;
    height: 22px;
    background: url("/img/green.png") no-repeat;
    background-position-x: 0%;
    background-position-y: 0%;
    cursor: pointer;
    margin: 0 auto;
  }

  .customcheckbox.checked {
    background-position: -48px 0;
  }

  .scheme:hover {
    cursor: pointer;
  }

  .customcheckbox:hover {
    background-position: -24px 0;
  }

  .customcheckbox.checked:hover {
    background-position: -48px 0;
  }

  .customcheckbox input {
    cursor: pointer;
    opacity: 0;
    scale: 1.6;
    width: 22px;
    height: 22px;
    margin: 0;
  }

  .shift:hover {
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

@push('breadcrump')
<li class="breadcrumb-item active">Attendance Approved</li>
@endpush

@section('content')
<div class="wrapper wrapper-content">
  <div class="row">
    <div class="col-lg-12">
      <div class="card card-{{ config('configs.app_theme') }} card-outline">
        <form id="form" action="{{ route('attendanceapproval.approve') }}" class="form-horizontal" method="post" autocomplete="off">
          {{ csrf_field() }}
          {{-- Title, Button Approve & Search --}}
          <div class="card-header">
            <h3 class="card-title">Attendance Approved</h3>
            <div class="pull-right card-tools">
              <a href="#" onclick="deletemass()" class="btn btn-{{ config('configs.app_theme') }} btn-sm"><i class="fa fa-trash"></i></a>
              <a href="#" onclick="exportattendance()" class="btn btn-primary btn-sm text-white"><i class="fa fa-download"></i></a>
            </div>
          </div>
          {{-- .Title, Button Approve & Search --}}
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
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label" for="overtime">Overtime</label>
                  <select name="overtime" id="overtime" class="form-control" style="width: 100%" aria-hidden="true" data-placeholder="Select Overtime">
                    <option value=""></option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                  </select>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label" for="status">Status</label>
                  <select name="status" id="status" class="form-control" style="width: 100%" data-placeholder="Select Status" aria-hidden="true">
                    {{-- <option value=""></option> --}}
                    <option value="1" selected>Approved</option>
                    <option value="-1">Draft</option>
                  </select>
                </div>
              </div>
            </div>
            <table class="table table-striped table-bordered datatable" style="width: 100%">
              <thead>
                <tr>
                  <th width="10">No</th>
                  <th width="50">Date</th>
                  <th width="50">Scheme</th>
                  <th width="50">Department<br>Position</th>
                  <th width="50">Workgroup</th>
                  <th width="100">Employee</th>
                  <th width="50">Working Shift</th>
                  <th width="10">Check In</th>
                  <th width="10">Check Out</th>
                  <th width="10">Summary</th>
                  <th width="10">Status</th>
                  <th width="10">
                    <div class="customcheckbox">
                      <input type="checkbox" class="checkall">
                    </div>
                  </th>
                  <th width="10">Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </form>
        <div class="overlay d-none">
          <i class="fa fa-2x fa-sync-alt fa-spin"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="add-filter" tabindex="-1" role="dialog" aria-hidden="true" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
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
                  <label class="control-label" for="date">Date</label>
                  <input type="text" class="form-control datepicker" name="date" id="date" placeholder="Date">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label" for="name">Employee Name</label>
                  <input type="text" name="name" class="form-control" placeholder="Employee Name">
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button form="form-search" type="submit" class="btn btn-{{ config('configs.app_theme') }}" title="Apply"><i class="fa fa-search"></i></button>
        </div>
      </div>
    </div>
  </div>
</div>
<a class="btn btn-{{ config('configs.app_theme') }} back-to-top" id="go-to-top" title="Go To Top"><i class="fa fa-angle-up"></i></a>
@endsection

@push('scripts')
<script src="{{asset('adminlte/component/validate/jquery.validate.min.js')}}"></script>
<script src="{{asset('adminlte/component/dataTables/js/datatables.min.js')}}"></script>
<script src="{{asset('adminlte/component/daterangepicker/moment.min.js')}}"></script>
<script src="{{asset('adminlte/component/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('adminlte/component/jquery-ui/jquery-ui.min.js')}}"></script>
<script type="text/javascript">
  function filter(){
		$('#add-filter').modal('show');
  }
  function formatTime(date) {
    var now = new Date(), year = now.getFullYear();
    var d = new Date(year + ' ' + date),
            minute = '' + d.getMinutes(),
            hour = '' + d.getHours();
    
    if (minute.length < 2)
      minute = '0' + minute;
    if (hour.length < 2)
      hour = '0' + hour;

    return [hour, minute].join(':');
  }

  function dayName(date) {
    var weekday = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
    var date = new Date(date);

    return weekday[date.getDay()];
  }

  function deletemass() {
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
			title:'Delete attendance?',
			message:'Data that has been deleted cannot be recovered',
			callback: function(result) {
				if(result) {
					var data = {
						_token: "{{ csrf_token() }}"
					};
					$.ajax({
						url: `{{ route('attendanceapproval.deletemass') }}`,
						dataType: 'json',
						data: new FormData($('#form')[0]),
            processData: false,
            contentType: false,
            method:'post',
						beforeSend:function(){
							$('.overlay').removeClass('d-none');
						}
					}).done(function(response){
						if(response.status){
							$('.overlay').addClass('d-none');
							$.gritter.add({
								title: 'Success!',
								text: response.message,
								class_name: 'gritter-success',
								time: 1000,
							});
							dataTable.ajax.reload( null, false );
						} else {
							$.gritter.add({
								title: 'Warning!',
								text: response.message,
								class_name: 'gritter-warning',
								time: 1000,
							});
						}
					}).fail(function(response){
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
  }
  function exportattendance() {
    $.ajax({
        url: "{{ route('dailyreport.export') }}",
        type: 'POST',
        dataType: 'JSON',
        data: $("#form").serialize(),
        beforeSend:function(){
            // $('.overlay').removeClass('d-none');
            waitingDialog.show('Loading...');
        }
    }).done(function(response){
        waitingDialog.hide();
        if(response.status){
          $('.overlay').addClass('d-none');
          $.gritter.add({
              title: 'Success!',
              text: response.message,
              class_name: 'gritter-success',
              time: 1000,
          });
          let download = document.createElement("a");
          download.href = response.file;
          document.body.appendChild(download);
          download.download = response.name;
          download.click();
          download.remove();
        }
        else{
          $.gritter.add({
              title: 'Warning!',
              text: response.message,
              class_name: 'gritter-warning',
              time: 1000,
          });
        }
    }).fail(function(response){
        waitingDialog.hide();
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
  $('#overtime').select2({
    allowClear:true,
  });
  $('#status').select2({
    allowClear:true,
  });
  $(function() {
    $(window).scroll(function () {
			if ($(this).scrollTop() > 50) {
				$('.back-to-top').fadeIn();
			} else {
				$('.back-to-top').fadeOut();
			}
		});
		// scroll body to 0px on click
		$('.back-to-top').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 400);
			return false;
		});
    dataTable = $('.datatable').DataTable({
      stateSave:true,
      processing: true,
      serverSide: true,
      filter:false,
      info:false,
      lengthChange:true,
      responsive: true,
      order: [[ 1, "asc" ]],
      lengthMenu: [ 100, 250, 500, 1000, 2000 ],
      ajax: {
          url: "{{route('dailyreport.read')}}",
          type: "GET",
          data:function(data){
            var employee_id = $('input[name=employee_name]').val();
            var nid = $('input[name=nid]').val();
            var from = $('input[name=from]').val();
            var to = $('input[name=to]').val();
            var department = $('input[name=department]').val();
            var workgroup = $('input[name=workgroup]').val();
            var overtime = $('select[name=overtime]').val();
            var status = $('select[name=status]').val();
            data.employee_id = employee_id;
            data.nid = nid;
            data.from = from;
            data.to = to;
            data.department = department;
            data.workgroup = workgroup;
            data.overtime = overtime;
            data.status = status;
          }
      },
      columnDefs:[
          {
              orderable: false,targets:[0,10,11,12]
          },
          { className: "text-center", targets: [0,1,7,8,9] },
          { render: function ( data, type, row ) {
            var date = new Date(row.attendance_date);
            return `${row.attendance_date} <br> <span class="text-bold ${row.day == 'Off' ? 'text-red' : ''}">${dayName(row.attendance_date)}</span>`;
          },targets: [1]
          },
          { render: function ( data, type, row ) {
            return `<span>${row.scheme_name ? row.scheme_name : '-'}</span>`;
          },targets: [2]
          },
          { render: function ( data, type, row ) {
            return `<span>${row.department_name} <br> ${row.title_name}</span>`;
          },targets: [3]
          },
          { render: function ( data, type, row ) {
            return `${row.name}<br>${row.nid}`;
          },targets: [5]
          },
          { render: function ( data, type, row ) {
            return `<span>${row.description ? row.description : '-'}</span>`;
          },targets: [6]
          },
          { render: function ( data, type, row ) {
            if (row.attendance_in) {
              if (row.attendance_in < row.start_time) {
                return `<span>${row.attendance_in}</span><br><span class="text-bold">${formatTime(row.start_time)}</span><br><span class="text-success text-bold">- ${row.diff_in}</span>`
              } else {
                return `<span>${row.attendance_in}</span><br><span class="text-bold">${formatTime(row.start_time)}</span><br><span class="text-danger text-bold">+ ${row.diff_in}</span>`
              }
            } else {
              return '<span class="text-red text-bold">?</span>';
            }
          },targets: [7]
          },
          { render: function ( data, type, row ) {
            if (row.attendance_out) {
              if (row.attendance_out > row.finish_time) {
                return `<span>${row.attendance_out}</span><br><span class="text-bold">${formatTime(row.finish_time)}</span><br><span class="text-danger text-bold">+ ${row.diff_out}</span>`
              } else {
                return `<span>${row.attendance_out}</span><br><span class="text-bold">${formatTime(row.finish_time)}</span><br><span class="text-success text-bold">- ${row.diff_out}</span>`
              }
            } else {
              return '<span class="text-red text-bold">?</span>';
            }
          },targets: [8]
          },
          { render: function ( data, type, row ) {
              return `WT: ${row.adj_working_time} Hours<br>OT: ${row.adj_over_time} Hours`
          },targets: [9]
          },
          { render: function ( data, type, row ) {
            if (row.status == -1) {
              return '<span class="badge badge-secondary">Draft</span>'
            } else if (row.status == 1) {
              return '<span class="badge badge-success">Approved</span>'
            }
          },targets: [10]
          },
          { render: function ( data, type, row ) {
              return `<label class="customcheckbox"><input data-id="${data}" value="${row.id}" type="checkbox" name="approve[]"><span class="checkmark"></span></label>`
            },targets: [11]
          },
          { render: function ( data, type, row ) {
              return `<div class="dropdown">
                      <button class="btn  btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                          <i class="fa fa-bars"></i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-right">
                          <li><a class="dropdown-item" href="{{url('admin/attendanceapproval')}}/${row.id}/detail"><i class="fa fa-search"></i> Detail</a></li>
                      </ul></div>`
          },targets: [12]
          }
      ],
      columns: [
        { data: "no", className: "align-middle text-center" },
        { data: "attendance_date", className: "align-middle text-center" },
        { data: "scheme_name", className: "align-middle text-center" },
        { data: "department_name", className: "align-middle text-center" },
        { data: "workgroup_name", className: "align-middle text-center" },
        { data: "name", className: "align-middle text-left"},
        { data: "description", className: "align-middle text-center" },
        { data: "attendance_in", className: "align-middle text-center" },
        { data: "attendance_out", className: "align-middle text-center"},
        { data: "adj_working_time", className: "align-middle text-center" },
        { data: "status", className: "align-middle text-center" },
        { data: "status", className: "align-middle text-center" },
        { data: "id", className: "align-middle text-center" }
      ]
    });
    $('#shift_filter').select2({
      ajax: {
        url: "{{route('workingtime.select')}}",
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
              text: `${item.description}`
            });
          });
          return {
            results: option, more: more,
          };
        },
      },
      allowClear: true,
    });
    $('#working_shift').select2({
      ajax: {
        url: "{{route('attendanceapproval.selectworkingtime')}}",
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
              text: `${item.description}`
            });
          });
          return {
            results: option, more: more,
          };
        },
      },
      allowClear: true,
    });
    $('#scheme').select2({
      ajax: {
        url: "{{route('attendanceapproval.selectscheme')}}",
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
              text: `${item.scheme_name}`
            });
          });
          return {
            results: option, more: more,
          };
        },
      },
      allowClear: true,
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
    $(document).ready(function(){
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
    $(document).on('change', '#overtime', function() {
      dataTable.draw();
    });
    $(document).on('change', '#status', function() {
      dataTable.draw();
    });
    $(document).on('apply.daterangepicker', function() {
      dataTable.draw();
    }).trigger('apply.daterangepicker');
    $('#form-search').submit(function(e){
			e.preventDefault();
			dataTable.draw();
			$('#add-filter').modal('hide');
		});
    $("#form").validate({
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
        else
        if (element.attr('type') == 'checkbox') {
          error.insertAfter(element.parent());
        }
        else{
          error.insertAfter(element);
        }
      },
      submitHandler: function() {
        $.ajax({
          url:$('#form').attr('action'),
          method:'post',
          data: new FormData($('#form')[0]),
          processData: false,
          contentType: false,
          dataType: 'json',
          beforeSend:function(){
              $('.overlay').removeClass('d-none');
          }
        }).done(function(response){
              $('.overlay').addClass('d-none');
              if(response.status){
                dataTable.draw();
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
        });
      }
    });
  });
  $(document).on('click', '.customcheckbox input', function() {
    if ($(this).is(':checked')) {
      $(this).parent().addClass('checked');
    } else {
      $(this).parent().removeClass('checked');
    }
  });
  $(document).on('change', '.checkall', function() {
    if (this.checked) {
      $('input[name^=approve]').prop('checked', true);
      $('input[name^=approve]').parent().addClass('checked');
    } else {
      $('input[name^=approve]').prop('checked', false);
      $('input[name^=approve]').parent().removeClass('checked');
    }
  });
</script>
@endpush