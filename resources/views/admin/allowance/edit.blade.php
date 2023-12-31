@extends('admin.layouts.app')

@section('title', 'Edit Allowance')
@section('stylesheets')
<link href="{{asset('adminlte/component/dataTables/css/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/bootstrap-fileinput/css/fileinput.min.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/bootstrap-fileinput/themes/explorer-fas/theme.min.css')}}" rel="stylesheet">
@endsection

@push('breadcrump')
<li class="breadcrumb-item active"><a href="{{route('allowance.index')}}">Allowance</a></li>
<li class="breadcrumb-item active">Edit</li>
@endpush

@section('content')
<div class="row">
  <div class="col-lg-8">
    <div class="card card-{{ config('configs.app_theme')}} card-outline">
      <div class="card-header">
        <h3 class="card-title">Allowance List</h3>
      </div>
      <div class="card-body">
        <form id="form" action="{{ route('allowance.update',['id'=>$allowance->id]) }}" class="form-horizontal"
          method="post" autocomplete="off">
          {{ csrf_field() }}
          <input type="hidden" name="_method" value="put">
          <div class="box-body">
            <div class="row">
              <div class="col-sm-6">
                <!-- text input -->
                <div class="form-group">
                  <label>Allowance Name</label>
                  <input type="text" class="form-control" placeholder="Allowance" id="allowance" name="allowance"
                    value="{{ $allowance->allowance }}">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Category</label>
                  <select name="category" id="category" class="form-control select2" style="width: 100%"
                    aria-hidden="true">
                    @foreach (config('enums.allowance_category') as $key=>$value)
                    <option @if ($allowance->category == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row account-section">
              <div class="col-sm-6">
                <!-- text input -->
                <div class="form-group">
                  <label>Account</label>
                  <input type="text" class="form-control" placeholder="Account" id="account" name="account"
                    value="{{ $allowance->acc_name }}">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Recurrence</label>
                  <select name="recurrence" id="recurrence" class="form-control select2" style="width: 100%"
                    aria-hidden="true">
                    <option @if ($allowance->reccurance == 'hourly') selected @endif value="hourly">Hourly</option>
                    <option @if ($allowance->reccurance == 'daily') selected @endif value="daily">Daily</option>
                    <option @if ($allowance->reccurance == 'monthly') selected @endif value="monthly">Monthly</option>
                    <option @if ($allowance->reccurance == 'yearly') selected @endif value="yearly">Yearly</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row account-section">
              <div class="col-sm-6">
                <!-- text input -->
                <div class="form-group">
                  <label>Group Allowance</label>
                  <input type="text" class="form-control" placeholder="Select Group Allowance" id="groupallowance" name="groupallowance"
                    value="">
                </div>
              </div>
              <div class="col-sm-6 working-time-section d-none">
                <div class="form-group">
                  <label>Working Time</label>
                  <input type="text" class="form-control" placeholder="Working Time" id="working_time" name="working_time" value="{{ $allowance->workingtime_id }}">
                </div>
              </div>
            </div>
            {{-- <div class="row ">
              
            </div> --}}
            <div class="row days-devisor-section d-none">
              <div class="col-sm-6">
                <!-- text input -->
                <div class="form-group">
                  <label>Days Devisor</label>
                  <input type="text" class="form-control" id="days_devisor" name="days_devisor" placeholder="Days Devisor" value="{{ $allowance->days_devisor }}">
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="overlay d-none">
        <i class="fa fa-2x fa-sync-alt fa-spin"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card card-{{ config('configs.app_theme')}} card-outline">
      <div class="card-header">
        <h3 class="card-title">Other</h3>
        <div class="pull-right card-tools">
          <button form="form" type="submit" class="btn btn-sm btn-{{ config('configs.app_theme')}}" title="Simpan"><i
              class="fa fa-save"></i></button>
          <a href="{{ url()->previous() }}" class="btn btn-sm btn-default" title="Kembali"><i
              class="fa fa-reply"></i></a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-sm-12">
            <!-- text input -->
            <div class="form-group">
              <label>Notes</label>
              <textarea class="form-control" id="notes" name="notes"
                placeholder="Notes"> {{ $allowance->notes }}</textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label>Status</label>
              <select name="status" id="status" class="form-control select2" style="width: 100%" aria-hidden="true">
                <option @if($allowance->status == 1) selected @endif value="1">Active</option>
                <option @if($allowance->status == 0) selected @endif value="0">Non-Active</option>
              </select>
            </div>
          </div>
        </div>
        </form>
      </div>
      <div class="overlay d-none">
        <i class="fa fa-2x fa-sync-alt fa-spin"></i>
      </div>
    </div>
  </div>
</div>
<div class="row basic-salary-section-rules" style="display:none;">
  <div class="col-lg-12">
    <div class="card card-{{ config('configs.app_theme')}} card-outline">
      <div class="card-header">
        <h3 class="card-title">Rules</h3>
        <div class="pull-right card-tools">
          <a href="#" class="btn btn-{{ config('configs.app_theme')}} btn-sm text-white add_rules" data-toggle="tooltip"
            title="Tambah">
            <i class="fa fa-plus"></i>
          </a>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-striped table-bordered datatable" id="table-rules" style="width:100%">
          <thead>
            <tr>
              <th width="10">No</th>
              <th width="250">Qty Absent (Days)</th>
              <th width="250">Qty Allowance (Days)</th>
              <th width="10">#</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="row rule-service" style="display:none;">
  <div class="col-lg-12">
    <div class="card card-{{ config('configs.app_theme')}} card-outline">
      <div class="card-header">
        <h3 class="card-title">Rules</h3>
        <div class="pull-right card-tools">
          <a href="#" class="btn btn-{{ config('configs.app_theme')}} btn-sm text-white add_rule_service" data-toggle="tooltip"
            title="Tambah">
            <i class="fa fa-plus"></i>
          </a>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-striped table-bordered" id="table-rule-service" style="width:100%">
          <thead>
            <tr>
              <th width="10">No</th>
              <th width="250">Rule</th>
              <th width="250">Value</th>
              <th width="10">#</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Modal Rules --}}
<div class="modal fade" id="add_rules" tabindex="-1" role="dialog" aria-hidden="true" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay-wrapper">
        <div class="modal-header">
          <h4 class="modal-title">Add Rules</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_rules" class="form-horizontal" method="post" autocomplete="off">
            <div class="row">
              <input type="hidden" name="allowance_id">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="absent" class="control-label">Quantity Absent</label>
                  <input type="text" class="form-control" id="qty_absent" name="qty_absent" placeholder="Absent"
                    required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="qty_allowance" class="control-label">Quantity Allowance</label>
                  <input type="text" class="form-control" id="qty_allowance" name="qty_allowance"
                    placeholder="Allowance" required>
                </div>
              </div>
              {{ csrf_field() }}
              <input type="hidden" name="_method" />
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button form="form_rules" type="submit" class="btn btn-sm btn-{{ config('configs.app_theme')}} text-white"
            title="Simpan"><i class="fa fa-save"></i></button>
        </div>
        <div class="overlay d-none">
          <i class="fa fa-2x fa-sync-alt fa-spin"></i>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Modal Rule Service --}}
<div class="modal fade" id="add_rule_service" tabindex="-1" role="dialog" aria-hidden="true" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="overlay-wrapper">
        <div class="modal-header">
          <h4 class="modal-title">Add Rules</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_rule_service" class="form-horizontal" method="post" autocomplete="off">
            <div class="row">
              <input type="hidden" name="allowance_id">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="first" class="control-label">First</label>
                  <input type="number" class="form-control" id="first" name="first" placeholder="First"
                    required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="until" class="control-label">Until</label>
                  <input type="number" class="form-control" id="until" name="until"
                    placeholder="Until" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="type" class="control-label">Type</label>
                  <select class="form-control select2" data-placeholder="Type" name="type" id="type">
                      <option value=""></option>
                      <option value="Percentage">Percentage</option>
                      <option value="Nominal">Nominal</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="val" class="control-label">Value</label>
                  <input type="number" class="form-control" id="value" name="value"
                    placeholder="Value" required>
                </div>
              </div>
              {{ csrf_field() }}
              <input type="hidden" name="_method" />
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button form="form_rule_service" type="submit" class="btn btn-sm btn-{{ config('configs.app_theme')}} text-white"
            title="Simpan"><i class="fa fa-save"></i></button>
        </div>
        <div class="overlay d-none">
          <i class="fa fa-2x fa-sync-alt fa-spin"></i>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('adminlte/component/validate/jquery.validate.min.js')}}"></script>
<script src="{{asset('adminlte/component/dataTables/js/datatables.min.js')}}"></script>
<script src="{{asset('accounting/accounting.min.js')}}"></script>
<script>
  $(document).ready(function(){
    $('.select2').select2();
    $( "#groupallowance" ).select2({
      ajax: {
        url: "{{route('groupallowance.select')}}",
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
    });
    @if($allowance->group_allowance_id)
    $("#groupallowance").select2('data',{id:{{$allowance->group_allowance_id}},text:'{{$allowance->groupallowance->name}}'}).trigger('change');
    @endif
    $(document).on("change", "#parent_id", function () {
      if (!$.isEmptyObject($('#form').validate().submitted)) {
        $('#form').validate().form();
      }
    });
    $("#account").select2({
			ajax: {
				url: "{{route('account.select')}}",
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
							text: `${item.acc_name}`
						});
					});
					return {
						results: option, more: more,
					};
				},
			},
			allowClear: true,
		});
    @if ($allowance->account_id)
		$("#account").select2('data',{id:{{$allowance->account_id}},text:'{{$allowance->account->acc_name}}'}).trigger('change');
		@endif
		$(document).on("change", "#account", function () {
			if (!$.isEmptyObject($('#form').validate().submitted)) {
				$('#form').validate().form();
			}
		});
    $("#working_time").select2({
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
      multiple: true
		});
    var data = [];
    @if ($allowance->allowanceworkingtime)
    @foreach ($allowance->allowanceworkingtime as $value)
    data.push({id: '{{ $value->workingtime_id }}', text: '{{ $value->workingtime->description }}'});
    @endforeach
    @endif
    $('#working_time').select2('data', data).trigger('change');
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
              $('.overlay').removeClass('hidden');
          }
        }).done(function(response){
              $('.overlay').addClass('hidden');
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
            $('.overlay').addClass('hidden');
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
    $('#category').change(function () {
      var value = $(this).val();
      switch (value) {
        case 'tunjanganLain':
          $('.working-time-section').removeClass('d-none');
          $('.days-devisor-section').addClass('d-none');
          $('.basic-salary-section').addClass('d-none');
          $('.basic-salary-section-rules').fadeOut();
          $('.rule-service').fadeOut();
          break;
        case 'tunjanganJkkJkm':
          $('.working-time-section').removeClass('d-none');
          $('.days-devisor-section').addClass('d-none');
          $('.basic-salary-section').removeClass('d-none');
          $('.rule-service').fadeOut();
          $('.basic-salary-section-rules').fadeIn();
          break;
        case 'tunjanganKehadiran':
          $('.working-time-section').removeClass('d-none');
          $('.days-devisor-section').removeClass('d-none');
          $('.basic-salary-section').addClass('d-none');
          $('.rule-service').fadeOut();
          $('.basic-salary-section-rules').fadeIn();
          break;
          case 'uangService':
          $('.working-time-section').removeClass('d-none');
          $('.days-devisor-section').removeClass('d-none');
          $('.basic-salary-section').addClass('d-none');
          $('.rule-service').fadeIn();
          $('.basic-salary-section-rules').fadeOut();
          break;
        default:
          $('.working-time-section').addClass('d-none');
          $('.days-devisor-section').addClass('d-none');
          $('.basic-salary-section').addClass('d-none');
          $('.rule-service').fadeOut();
          $('.basic-salary-section-rules').fadeOut();
          break;
      }
    });
    $('#category').val('{!! $allowance->category !!}').trigger('change');
    $('#form_rules').validate({
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
          url:$('#form_rules').attr('action'),
          method:'post',
          data: new FormData($('#form_rules')[0]),
          processData: false,
          contentType: false,
          dataType: 'json',
          beforeSend:function(){
              $('.overlay').removeClass('hidden');
          }
        }).done(function(response){
              $('.overlay').addClass('hidden');
              if(response.status){
                $('#add_rules').modal('hide');
                dataTableRules.draw();
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
            $('.overlay').addClass('hidden');
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
    $(document).on('click','.deleterule',function(){
        var id = $(this).data('id');
        bootbox.confirm({
          buttons: {
            confirm: {
              label: '<i class="fa fa-check"></i>',
              className: 'btn-{{ config('configs.app_theme')}}'
            },
            cancel: {
              label: '<i class="fa fa-undo"></i>',
              className: 'btn-default'
            },
          }
        });
    });
    dataTableRules = $('#table-rules').DataTable({
        stateSave:true,
        processing: true,
        serverSide: true,
        filter:false,
        info:false,
        lengthChange:true,
        responsive: true,
        order: [[ 3, "asc" ]],
        ajax: {
            url: "{{route('allowancerule.read')}}",
            type: "GET",
            data:function(data){
              var qty_absent = $('#form-search').find('input[name=qty_absent]').val();
              data.qty_absent = qty_absent;
              data.allowance_id = {{$allowance->id}};
            }
        },
        columnDefs:[
            {
                orderable: false,targets:[0]
            },
            { className: "text-right", targets: [0] },
            { className: "text-center", targets: [3] },
            { render: function ( data, type, row ) {
                return `<div class="dropdown">
                    <button type="button" class="btn  btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-bars"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a class="dropdown-item editrule" href="#" data-id="${row.id}"><i class="fa fa-pencil-alt mr-2"></i> Edit</a></li>
                        <li><a class="dropdown-item deleterule" href="#" data-id="${row.id}"><i class="fa fa-trash mr-2"></i> Delete</a></li>
                    </ul>
                    </div>`
            },targets: [3]
            }
        ],
        columns: [
            { data: "no" },
            { data: "qty_absent" },
            { data: "qty_allowance" },
            { data: "id" },
        ]
    });
    $('.add_rules').on('click', function() {
      $('#form_rules')[0].reset();
      $('#form_rules').attr('action',"{{route('allowancerule.store')}}");
      $('#form_rules input[name=_method]').attr('value','POST');
      $('#form_rules input[name=allowance_id]').attr('value',{{ $allowance->id }});
      $('#form_rules input[name=qty_absent]').attr('value','');
      $('#form_rules input[name=qty_allowance]').attr('value','');
      $('#add_rules .modal-title').html('Add Rules');
      $('#add_rules').modal('show');
    });
    $(document).on('click','.editrule',function(){
      var id = $(this).data('id');
      $.ajax({
          url:`{{url('admin/allowancerule')}}/${id}/edit`,
          method:'GET',
          dataType:'json',
          beforeSend:function(){
              $('#box-menu .overlay').removeClass('d-none');
          },
      }).done(function(response){
          $('#box-menu .overlay').addClass('d-none');
          if(response.status){
              $('#add_rules .modal-title').html('Ubah Rule');
              $('#add_rules').modal('show');
              $('#form_rules')[0].reset();
              $('#form_rules .invalid-feedback').each(function () { $(this).remove(); });
              $('#form_rules .form-group').removeClass('has-error').removeClass('has-success');
              $('#form_rules input[name=_method]').attr('value','PUT');
              $('#form_rules input[name=allowance_id]').attr('value',{{$allowance->id}});
              $('#form_rules input[name=qty_absent]').attr('value',response.data.qty_absent);
              $('#form_rules input[name=qty_allowance]').attr('value',response.data.qty_allowance);
              $('#form_rules').attr('action',`{{url('admin/allowancerule/')}}/${response.data.id}`);
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
    $(document).on('click','.deleterule',function(){
        var id = $(this).data('id');
        bootbox.confirm({
          buttons: {
            confirm: {
              label: '<i class="fa fa-check"></i>',
              className: 'btn-danger'
            },
            cancel: {
              label: '<i class="fa fa-undo"></i>',
              className: 'btn-default'
            },
          },
          title:'Menghapus Rule?',
          message:'Data yang telah dihapus tidak dapat dikembalikan',
          callback: function(result) {
              if(result) {
                var data = {
                  _token: "{{ csrf_token() }}",
                  id: id
                  };
                $.ajax({
                  url: `{{url('admin/allowancerule')}}/${id}`,
                  dataType: 'json',
                  data:data,
                  type:'DELETE',
                    beforeSend:function(){
                      $('.overlay').removeClass('hidden');
                      }
                }).done(function(response){
                    if(response.status){
                        $('.overlay').addClass('hidden');
                        $.gritter.add({
                            title: 'Success!',
                            text: response.message,
                            class_name: 'gritter-success',
                            time: 1000,
                        });
                        dataTableRules.ajax.reload( null, false );
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
                    var response = response.responseJSON;
                    $('.overlay').addClass('hidden');
                    $.gritter.add({
                        title: 'Error!',
                        text: response.message,
                        class_name: 'gritter-error',
                        time: 1000,
                    });
                })
              }
          }
        });
    });

    // Rule Service
    dataTableRuleService = $('#table-rule-service').DataTable({
        stateSave:true,
        processing: true,
        serverSide: true,
        filter:false,
        info:false,
        lengthChange:true,
        responsive: true,
        order: [[ 3, "asc" ]],
        ajax: {
            url: "{{route('holidayallowancerule.read')}}",
            type: "GET",
            data:function(data){
              // var qty_absent = $('#form-search').find('input[name=qty_absent]').val();
              // data.qty_absent = qty_absent;
              data.allowance_id = {{$allowance->id}};
            }
        },
        columnDefs:[
            {
                orderable: false,targets:[0]
            },
            { className: "text-right", targets: [2] },
            { className: "text-center", targets: [3] },
            {
              render: function (data, type, row) {
                return `${row.first} to ${row.until}`;
              },
              targets: [1]
            },
            {
              render: function (data, type, row) {
                if (row.type == 'Nominal') {
                  return `Rp. ${(accounting.formatMoney(row.value,'',0, '.', ','))}`;
                }
                return `${row.value}%`;
              },
              targets: [2]
            },
            { render: function ( data, type, row ) {
                return `<div class="dropdown">
                    <button type="button" class="btn  btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-bars"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a class="dropdown-item editruleservice" href="#" data-id="${row.id}"><i class="fa fa-pencil-alt mr-2"></i> Edit</a></li>
                        <li><a class="dropdown-item deleteruleservice" href="#" data-id="${row.id}"><i class="fa fa-trash mr-2"></i> Delete</a></li>
                    </ul>
                    </div>`
            },targets: [3]
            }
        ],
        columns: [
            { data: "no" },
            { data: "first" },
            { data: "value" },
            { data: "id" },
        ]
    });
    $('#form_rule_service').validate({
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
          url:$('#form_rule_service').attr('action'),
          method:'post',
          data: new FormData($('#form_rule_service')[0]),
          processData: false,
          contentType: false,
          dataType: 'json',
          beforeSend:function(){
              $('.overlay').removeClass('hidden');
          }
        }).done(function(response){
              $('.overlay').addClass('hidden');
              if(response.status){
                $('#add_rule_service').modal('hide');
                dataTableRuleService.draw();
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
            $('.overlay').addClass('hidden');
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
    $('.add_rule_service').on('click', function() {
      $('#form_rule_service')[0].reset();
      $('#form_rule_service').attr('action',"{{route('holidayallowancerule.store')}}");
      $('#form_rule_service input[name=_method]').attr('value','POST');
      $('#form_rule_service input[name=allowance_id]').attr('value',{{ $allowance->id }});
      $('#form_rule_service input[name=first]').attr('value','');
      $('#form_rule_service input[name=until]').attr('value','');
      $('#form_rule_service select[name=type]').select2('val','');
      $('#form_rule_service input[name=value]').attr('value','');
      $('#add_rule_service .modal-title').html('Add Rules');
      $('#add_rule_service').modal('show');
    });
     $(document).on('click','.editruleservice',function(){
      var id = $(this).data('id');
      $.ajax({
          url:`{{url('admin/holidayallowancerule')}}/${id}/edit`,
          method:'GET',
          dataType:'json',
          beforeSend:function(){
              $('#box-menu .overlay').removeClass('d-none');
          },
      }).done(function(response){
          $('#box-menu .overlay').addClass('d-none');
          if(response.status){
              $('#add_rule_service .modal-title').html('Ubah Rule');
              $('#add_rule_service').modal('show');
              $('#form_rule_service')[0].reset();
              $('#form_rule_service .invalid-feedback').each(function () { $(this).remove(); });
              $('#form_rule_service .form-group').removeClass('has-error').removeClass('has-success');
              $('#form_rule_service input[name=_method]').attr('value','PUT');
              $('#form_rule_service input[name=allowance_id]').attr('value',{{$allowance->id}});
              $('#form_rule_service input[name=first]').attr('value',response.data.first);
              $('#form_rule_service input[name=until]').attr('value',response.data.until);
              $('#form_rule_service select[name=type]').select2('val',response.data.type);
              $('#form_rule_service input[name=value]').attr('value',response.data.value);
              $('#form_rule_service').attr('action',`{{url('admin/holidayallowancerule/')}}/${response.data.id}`);
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
    $(document).on('click','.deleteruleservice',function(){
        var id = $(this).data('id');
        bootbox.confirm({
          buttons: {
            confirm: {
              label: '<i class="fa fa-check"></i>',
              className: 'btn-{{ config('configs.app_theme')}}'
            },
            cancel: {
              label: '<i class="fa fa-undo"></i>',
              className: 'btn-default'
            },
          },
          title:'Menghapus Rule?',
          message:'Data yang telah dihapus tidak dapat dikembalikan',
          callback: function(result) {
              if(result) {
                var data = {
                  _token: "{{ csrf_token() }}",
                  id: id
                  };
                $.ajax({
                  url: `{{url('admin/holidayallowancerule')}}/${id}`,
                  dataType: 'json',
                  data:data,
                  type:'DELETE',
                    beforeSend:function(){
                      $('.overlay').removeClass('hidden');
                      }
                }).done(function(response){
                    if(response.status){
                        $('.overlay').addClass('hidden');
                        $.gritter.add({
                            title: 'Success!',
                            text: response.message,
                            class_name: 'gritter-success',
                            time: 1000,
                        });
                        dataTableRuleService.ajax.reload( null, false );
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
                    var response = response.responseJSON;
                    $('.overlay').addClass('hidden');
                    $.gritter.add({
                        title: 'Error!',
                        text: response.message,
                        class_name: 'gritter-error',
                        time: 1000,
                    });
                })
              }
          }
        });
    });
  });
</script>
@endpush