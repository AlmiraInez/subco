@extends('admin.layouts.app')

@section('title', 'Add Leave Application')
@section('stylesheets')
<link href="{{asset('adminlte/component/dataTables/css/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/bootstrap-fileinput/css/fileinput.min.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/bootstrap-fileinput/themes/explorer-fas/theme.min.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/daterangepicker/daterangepicker.css')}}" rel="stylesheet">
@endsection

@push('breadcrump')
<li class="breadcrumb-item"><a href="{{route('leave.index')}}">Leave</a></li>
<li class="breadcrumb-item active">Create</li>
@endpush

@section('content')
<form id="form" action="{{ route('leave.store') }}" class="form-horizontal no-gutters" method="post" enctype="multipart/form-data" autocomplete="off">
  <div class="row">
    {{ csrf_field() }}
    <div class="col-lg-8">
      <div class="row">
        <div class="col-lg-12">
          <div class="card card-{{ config('configs.app_theme') }} card-outline">
            <div class="card-header">
              <div class="card-title">Data Employee</div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Employee</label>
                    <input type="text" class="form-control" placeholder="Employee" id="employee" name="employee" required>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>NID</label>
                    <input type="hidden" class="form-control" placeholder="Employee ID" id="employee_id" name="employee_id" readonly>
                    <input type="text" class="form-control" placeholder="NID" id="nid" name="nid" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" placeholder="Title" id="title" name="title" readonly>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Department</label>
                    <input type="text" class="form-control" placeholder="Department" id="department" name="department" readonly>
                  </div>
                </div>
              </div>
            </div>
            <div class="overlay d-none">
              <i class="fa fa-2x fa-sync-alt fa-spin"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="card card-{{ config('configs.app_theme')}} card-outline">
            <div class="card-header">
              <h3 class="card-title">List Leave</h3>
              <div class="pull-right card-tools">
                <a href="#" class="btn btn-{{ config('configs.app_theme')}} btn-sm text-white add_leave" data-toggle="tooltip" title="Add" onclick="showList()">
                  <i class="fa fa-plus"></i>
                </a>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <input type="hidden" name="total_days" id="total_days" value="0">
                <table id="leave-list" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th style="text-align: right" width="25">No</th>
                      <th style="text-align: center">Date</th>
                      <th style="text-align: center">Time</th>
                      <th style="text-align: center">Type</th>
                      <th width="100" style="text-align: center">#</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="no-data">
                      <td colspan="5" class="text-center">No data.</td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th colspan="2" class="text-right">Total (Day)</th>
                      <th colspan="3" data-total_days="0">0 Days</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="overlay d-none">
              <i class="fa fa-2x fa-sync-alt fa-spin"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card card-{{ config('configs.app_theme') }} card-outline">
        <div class="card-header">
          <div class="card-title">Others</div>
          <div class="pull-right card-tools">
            <button form="form" type="submit" class="btn btn-sm btn-{{ config('configs.app_theme') }}" id="simpan" title="Simpan"><i class="fa fa-save"></i></button>
            <a href="javascript:void(0)" onclick="backurl()" class="btn btn-sm btn-default" title="Kembali"><i class="fa fa-reply"></i></a>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label>Status</label>
                <input type="text" class="form-control" placeholder="Status" id="status" name="status" readonly value="Waiting Approval">
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label" for="leave_type">Leave Type</label>
                <input type="hidden" name="type" value="create">
                <input type="text" class="form-control" placeholder="Leave Type" id="leave_type" name="leave_type" required>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label" for="remaining">Remaining Balance</label>
                <input type="text" class="form-control" placeholder="Remaining Balance" id="remaining" name="remaining" readonly>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <label for="document">Supporting Document(s)</label>
                <div class="custom-file">
                  <input type="file" class="form-control" name="document" id="document" accept="image/jpeg,image/png,application/pdf" />
                </div>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label" for="notes">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="5" placeholder="Notes"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="overlay d-none">
          <i class="fa fa-2x fa-sync-alt fa-spin"></i>
        </div>
      </div>
    </div>
  </div>
</form>

{{-- Modal Leave --}}
<div class="modal fade" id="list-leave" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header no-print">
        <h5 class="modal-title">Add Date</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_date" action="{{ route('leave.createdate') }}" class="form-horizontal" method="post" autocomplete="off">
          {{ csrf_field() }}
          <input type="hidden" name="status_list" value="add">
          <input type="hidden" name="key_list" value="0">
          <div class="form-group">
            <label for="list_type">Type</label>
            <div class="controls">
              <select name="list_type" id="list_type" class="form-control select2" data-placeholder="Select Type" required>
                <option value="fullday">Full Day</option>
                <option value="hours">Hours</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-12">
              <label>Range Date</label>
              <div class="controls">
                <div class="row">
                  <div class="col-md-2">
                    <label for="range_date_start">Start</label>
                  </div>
                  <div class="col-md-4">
                    <input type="text" name="range_date_start" id="range_date_start" class="form-control datepicker" placeholder="Start" value="<?= date('d/m/Y') ?>">
                  </div>
                  <div class="col-md-2">
                    <label for="range_date_finish">Finish</label>
                  </div>
                  <div class="col-md-4">
                    <input type="text" name="range_date_finish" id="range_date_finish" class="form-control datepicker" placeholder="Start" value="<?= date('d/m/Y') ?>">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-12">
              <label>Range Time</label>
              <div class="controls">
                <div class="row">
                  <div class="col-md-2">
                    <label for="range_time_start">Start</label>
                  </div>
                  <div class="col-md-4">
                    <input type="text" name="range_time_start" id="range_time_start" class="form-control timepicker" value="09:00" placeholder="Start" readonly>
                  </div>
                  <div class="col-md-2">
                    <label for="range_time_finish">Finish</label>
                  </div>
                  <div class="col-md-4">
                    <input type="text" name="range_time_finish" id="range_time_finish" class="form-control timepicker" value="17:00" placeholder="Start" readonly>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer no-print">
        <button form="form_date" type="submit" class="btn btn-sm btn-{{ config('configs.app_theme') }}" title="Save"><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-secondary btn-sm color-palette btn-labeled legitRipple" data-dismiss="modal">
          <b><i class="fa fa-reply"></i></b>
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('adminlte/component/validate/jquery.validate.min.js')}}"></script>
<script src="{{asset('adminlte/component/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script src="{{asset('adminlte/component/bootstrap-fileinput/themes/explorer-fas/theme.min.js')}}"></script>
<script src="{{asset('adminlte/component/daterangepicker/moment.min.js')}}"></script>
<script src="{{asset('adminlte/component/daterangepicker/daterangepicker.js')}}"></script>
<script type="text/javascript">
  var availableDates = [];
  $(function() {
    $("#document").fileinput({
      browseClass: "btn btn-{{ config('configs.app_theme') }}",
      showRemove: false,
      showUpload: false,
      allowedFileExtensions: ["png", "jpg", "jpeg", "pdf"],
      dropZoneEnabled: false,
      theme: 'explorer-fas'
    });

    $(document).on("change", "#document", function () {
      if (!$.isEmptyObject($('#form').validate().submitted)) {
        $('#form').validate().form();
      }
    });
    $('.select2').select2();
    $('#employee').select2({
      ajax: {
        url: "{{route('leave.selectemployee')}}",
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
              text: `${item.name}`,
              employee_id: `${item.id}`,
              nid: `${item.nid}`,
              department_id:`${item.department_id}`,
              department_name:`${item.department_name}`,
              title_id:`${item.title_id}`,
              title_name:`${item.title_name}`,
              join_date:`${item.join_date}`,
              first_month:`${item.first_month}`,
              third_month:`${item.third_month}`,
              sixth_month:`${item.sixth_month}`,
              one_year:`${item.one_year}`,
              calendar_id: `${item.calendar_id}`
            });
          });
          return {
            results: option, more: more,
          };
        },
      },
    });
  
    $(document).on('change', '#employee', function () {
      var department_id = $('#employee').select2('data').department_id;
      var department_name = $('#employee').select2('data').department_name;
      var title_id = $('#employee').select2('data').title_id;
      var title_name = $('#employee').select2('data').title_name;
      var employee_id = $('#employee').select2('data').id;
      var nid = $('#employee').select2('data').nid;
      $('#nid').val(`${nid}`);
      $('#employee_id').val(`${employee_id}`);
      $('#department').val(`${department_name}`);
      $('#title').val(`${title_name}`);
      $('#leave_type').select2('data', null);
      $('#remaining').val('');
    });
    
    $('#leave_type').select2({
      ajax: {
        url: "{{route('leave.selectleave')}}",
        type:'GET',
        dataType: 'json',
        data: function (term,page) {
          return {
            employee_id: $('#employee_id').val() ? $('#employee_id').val() : null,
            type: $('input[name=type]').val(),
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
              id: item.id,
              text: `${item.leave_name}`,
              balance: `${item.remaining_balance}`
            });
          });
          return {
            results: option, more: more,
          };
        },
      }
    });
  
    $(document).on('change', '#leave_type', function() {
      $('#leave_name').find('option').remove();
      $('#leave_name').select2('val', '');
      var leave_type = $('#leave_type').select2('data').text;
      var leave_id = $('#leave_type').select2('data').id;
      var balance = $('#leave_type').select2('data').balance;
      var total_days = $('#total_days').val();
      if (balance == -1) {
        $('#remaining').val('∞');
      } else {
        var remaining = balance - total_days;
        if (remaining <= 0) {
          $('#remaining').val(0);
          $.gritter.add({
            title: 'Warning!',
            text: 'Your leave balance has reached the limit. All kinds of new leave will be accumulated in the following year.',
            class_name: 'gritter-warning',
            time: 1000,
          });
        } else {
          $('#remaining').val(remaining);
        }
      }
      if (leave_type == 'Others') {
				$('#notes').attr('required', 'required');
			} else {
				$('#notes').removeAttr('required');
			}
      var use_time = $('#leave_type').select2('data').use_time;
      var join_date = new Date($('#employee').select2('data').join_date);
      var first_month = new Date($('#employee').select2('data').first_month);
      var third_month = new Date($('#employee').select2('data').third_month);
      var sixth_month = new Date($('#employee').select2('data').sixth_month);
      var one_year = new Date($('#employee').select2('data').one_year);
      var now = new Date();
      switch (use_time) {
        case 'firstmonth':
          if (first_month > now) {
            $.gritter.add({
              title: 'Warning!',
              text: 'You are not yet eligible to take this leave.',
              class_name: 'gritter-warning',
              time: 1000,
            });
            $('#simpan').prop("disabled", true);
          }
          break;
        case 'thirdmonth':
          if (third_month > now) {
            $.gritter.add({
              title: 'Warning!',
              text: 'You are not yet eligible to take this leave.',
              class_name: 'gritter-warning',
              time: 1000,
            });
          }
          $('#simpan').prop("disabled", true);
          break;
        case 'sixthmonth':
          if (sixth_month > now) {
            $.gritter.add({
              title: 'Warning!',
              text: 'You are not yet eligible to take this leave.',
              class_name: 'gritter-warning',
              time: 1000,
            });
          }
          $('#simpan').prop("disabled", true);
          break;
        case 'oneyear':
          if (one_year > now) {
            $.gritter.add({
              title: 'Warning!',
              text: 'You are not yet eligible to take this leave.',
              class_name: 'gritter-warning',
              time: 1000,
            });
          }
          $('#simpan').prop("disabled", true);
          break;
      
        default:
          $('#simpan').prop("disabled", false);
          break;
      }
    });
    $('.timepicker').daterangepicker({
			singleDatePicker: true,
			timePicker: true,
			timePicker24Hour: true,
			timePickerIncrement: 1,
			timePickerSeconds: false,
			locale: {
				format: 'HH:mm'
			}
		}).on('show.daterangepicker', function(ev, picker) {
			picker.container.find('.calendar-table').hide();
    });
    $('.datepicker').daterangepicker({
			singleDatePicker: true,
			timePicker: false,
			locale: {
				format: 'DD/MM/YYYY'
			}
    });
    $('#list_type').change(function(e) {
			e.preventDefault();

			switch ($(this).val()) {
				case "hours":
					$('#range_time_start').val('09:00');
					$('#range_time_finish').val('17:00');
					$('#range_date_start').prop('readonly', false);
					$('#range_date_finish').prop('readonly', false);
					$('.timepicker').prop('readonly', false);
					break;

				default:
					$('#range_time_start').val('09:00');
					$('#range_time_finish').val('17:00');
					$('#range_date_start').prop('readonly', false);
					$('#range_date_finish').prop('readonly', false);
					$('.timepicker').prop('readonly', true);
					break;
			}
		});

    $('#form').validate({
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
        var formData = new FormData($('#form')[0]);
        $.each($('#leave-list > tbody').find('tr[data-date]'), function (key, value) {
          formData.append('date[]', $(value).data('date'));
          formData.append('time_start[]', $(value).data('time_start'));
          formData.append('time_finish[]', $(value).data('time_finish'));
          formData.append('type[]', $(value).data('type'));
        });
        $.ajax({
          url:$('#form').attr('action'),
          method:'post',
          data: formData,
          processData: false,
          contentType: false,
          dataType: 'json',
          beforeSend:function(){
              $('.overlay').removeClass('d-none');
          }
        }).done(function(response){
          $('.overlay').addClass('d-none');
          if(response.status){
            document.location = response.results;
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

  function showList() {
    $('#list-leave').find('.modal-title').html('Add Date');
    $('input[name=status_list]').val('add');
    $('#list-leave').find('button[type=submit]').html(`<b><i class="fa fa-plus"></i></b>`);
    $('#list-leave').modal('show');
  }

  $('#form_date').validate({
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
      var start_date = $('#range_date_start').val(),
        field_start_date = start_date.split('/'),
        date_1 = field_start_date[0],
        month_1 = (parseInt(field_start_date[1]) - 1),
        year_1 = field_start_date[2],
        start = moment([parseInt(year_1), parseInt(month_1), parseInt(date_1)]);
      var finish_date = $('#range_date_finish').val(),
        field_finish_date = finish_date.split('/'),
        date_2 = field_finish_date[0],
        month_2 = (parseInt(field_finish_date[1]) - 1),
        year_2 = field_finish_date[2],
        finish = moment([parseInt(year_2), parseInt(month_2), parseInt(date_2)]),
        diff = finish.diff(start, 'days');
      if (`${year_1}${month_1}${date_1}` > `${year_2}${month_2}${date_2}` || ($('#range_time_start').val() < '09:00' || $('#range_time_finish').val() > '17:00')) {
        $.gritter.add({
          title: 'Error!',
          text: 'Invalid date',
          class_name: 'gritter-error',
          time: 1000,
        });

        return;
      }
      var employee = $('#employee_id').val() ? $('#employee_id').val() : null;
      var balance = $('#remaining').val() ? $('#remaining').val() : null;
      if (employee == null || balance == null) {
        $.gritter.add({
          title: 'Error!',
          text: 'You have to select an employee and leave type first.',
          class_name: 'gritter-error',
          time: 1000,
        });

        return;
      }
      if ($('input[name=status_list]').val() == 'update') {
        var key = $('input[name=key_list]').val(),
          list_type = $('#list_type').val(),
          time_start = $('#range_time_start').val(),
          time_finish = $('#range_time_finish').val();

        availableDates[key].type = list_type;
        availableDates[key].start_time = time_start;
        availableDates[key].finish_time = time_finish;

        generateHTML(availableDates);
        var balance = $('#leave_type').select2('data').balance;
        var total_days = $('#total_days').val();
        var remaining = balance - total_days;
        if (balance == -1) {
          $('#remaining').val('∞');
        } else { 
          if (remaining <= 0) {
            $('#remaining').val(0);
            $.gritter.add({
              title: 'Warning!',
              text: 'Your leave balance has reached the limit. All kinds of new leave will be accumulated in the following year.',
              class_name: 'gritter-warning',
              time: 1000,
            });
          } else {
            $('#remaining').val(remaining);
          }
        }

        $('#list_type').find(':selected').removeAttr('selected');
        $('#range_date_start').val(moment().format('DD/MM/YYYY'));
        $('#range_date_finish').val(moment().format('DD/MM/YYYY'));
        $('#range_time_start').val('09:00');
        $('#range_time_finish').val('17:00');

        $('#list-leave').modal('hide');

        return;
      }
      $.ajax({
        url:$('#form_date').attr('action'),
        method:'post',
        data: {
          status: `create`,
          _token: $('input[name="_token"]').val(),
          employee_id: $('#employee_id').val(),
          calendar_id: $('#employee').select2('data').calendar_id,
          range_date_start: start.format('YYYY-MM-DD'),
          range_date_finish: finish.format('YYYY-MM-DD'),
          routine: `1 day`,
          range_time_start: $('#range_time_start').val(),
          range_time_finish: $('#range_time_finish').val(),
          type: $('#list_type').val(),
          availableDates: availableDates
        },
        dataType: 'json',
        beforeSend:function(){
            $('.overlay').removeClass('d-none');
        }
      }).done(function(response){
            $('.overlay').addClass('d-none');
            availableDates = [];
            $.each(response, function(key, value) {
              availableDates.push(value);
            });

            generateHTML(response);
            var balance = $('#leave_type').select2('data').balance;
            var total_days = $('#total_days').val();
            var remaining = balance - total_days;
            if (balance == -1) {
              $('#remaining').val('∞');
            } else { 
              if (remaining <= 0) {
                $('#remaining').val(0);
                $.gritter.add({
                  title: 'Warning!',
                  text: 'Your leave balance has reached the limit. All kinds of new leave will be accumulated in the following year.',
                  class_name: 'gritter-warning',
                  time: 1000,
                });
              } else {
                $('#remaining').val(remaining);
              }
            }

            $('#list_type').find(':selected').removeAttr('selected');
            $('#range_date_start').val(moment().format('DD/MM/YYYY'));
            $('#range_date_finish').val(moment().format('DD/MM/YYYY'));
            $('#range_time_start').val('09:00');
            $('#range_time_finish').val('17:00');

            $('#list-leave').modal('hide');
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

  function removeLeave(that) {
    let _token   = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
      url: "{{ route('leave.removedate') }}",
      method: "POST",
      data: {
        dates: availableDates,
        selected_date: $(that).data('selected_date'),
        _token: _token,
      },
      dataType: 'json'
    })
    .done(function(response) {
      if ($('#leave-list > tbody').find('tr:not(.no-data)').length <= 0) {
        var html = `<tr class="no-data"><td class="text-center" colspan="5">No data.</td></tr>`;
        $('#leave-list > tbody').append(html);
        return;
      }

      availableDates = [];
      $.each(response, function(key, value) {
        availableDates.push(value);
      });

      generateHTML(response);
      var balance = $('#leave_type').select2('data').balance;
      var total_days = $('#total_days').val();
      var remaining = balance - total_days;
      if (balance == -1) {
        $('#remaining').val('∞');
      } else { 
        if (remaining <= 0) {
          $('#remaining').val(0);
          $.gritter.add({
            title: 'Warning!',
            text: 'Your leave balance has reached the limit. All kinds of new leave will be accumulated in the following year.',
            class_name: 'gritter-warning',
            time: 1000,
          });
        } else {
          $('#remaining').val(remaining);
        }
      }
    });
	}

  function editLeave(that) {
		var date = $(that).parents('tr').data('date').split('-'),
			day = date[2],
			month = (parseInt(date[1]) - 1),
			year = date[0],
			date = moment([parseInt(year), parseInt(month), parseInt(day)]),
			time_start = $(that).parents('tr').data('time_start'),
			time_finish = $(that).parents('tr').data('time_finish'),
			type = $(that).parents('tr').data('type'),
			key = $(that).parents('tr').data('key');
		$('#list_type').val(type).trigger('change');
		$('#range_date_start').val(date.format('DD/MM/YYYY'));
		$('#range_date_finish').val(date.format('DD/MM/YYYY'));
		$('#range_date_start').prop('disabled', true);
		$('#range_date_finish').prop('disabled', true);
		$('#range_time_start').val(time_start);
		$('#range_time_finish').val(time_finish);
		if (type == 'hours') {
			$('#range_time_start').prop('readonly', false);
			$('#range_time_finish').prop('readonly', false);
		}
		$('#list-leave').find('.modal-title').html('Update Date');
		$('input[name=status_list]').val('update');
		$('input[name=key_list]').val(key);
		$('#list-leave').find('button[type=submit]').html(`<i class="fa fa-plus"></i>`);
		$('#list-leave').modal('show');
	}

  function generateHTML(data) {
		var html = '',
			total_time = 0;

		$.each(data, function(key, value) {
			var no = (parseInt(key) + 1),
				date = value.date.split('-'),
				day = date[2],
				month = date[1],
				year = date[0],
				time_start = value.start_time,
				time_finish = value.finish_time;

			switch (value.type) {
				case "hours":
					var type_txt = "Hours";
					var time_s = new Date(`${year}-${month}-${day} ${time_start}:00`).getHours();
					var time_f = new Date(`${year}-${month}-${day} ${time_finish}:00`).getHours();
					total_time += (time_f - time_s);
					break;

				default:
					var type_txt = "Full Day";
					total_time += 8;
					break;
			}

			html += `<tr data-key="${key}" data-date="${value.date}" data-time_start="${time_start}" data-time_finish="${time_finish}" data-type="${value.type}">
				<td class="text-center">${no}</td>
				<td class="text-center">${day}/${month}/${year}</td>
				<td class="text-center">${time_start} - ${time_finish}</td>
				<td class="text-center">${type_txt}</td>
				<td class="text-center"><a href="javascript:;" title="Edit Data"><i class="fas fa-edit" onclick="editLeave(this)"></i></a> / <a href="javascript:;" class="link-red" title="Delete Data"><i class="fa fa-trash" onclick="removeLeave(this)" data-key="${key}" data-selected_date="${value.date}"></i></a></td>
			</tr>`;
		});

		total_time = (parseFloat(total_time) / 8);

		$('#leave-list > tbody').find('tr').remove();
		$('#leave-list > tbody').append(html);
		$('#leave-list > tfoot').find('th[data-total_days]').attr('data-total_days', total_time);
		$('#leave-list > tfoot').find('th[data-total_days]').html(`${total_time} Days`);
		$('input[name=total_days]').val(total_time);
	}
</script>
@endpush