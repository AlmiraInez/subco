@extends('admin.layouts.app')

@section('title', 'Ubah Tenan')
@section('stylesheets')
<link href="{{asset('adminlte/component/bootstrap-fileinput/css/fileinput.min.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/bootstrap-fileinput/themes/explorer-fas/theme.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('adminlte/component/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
@endsection

@push('breadcrump')
<li class="breadcrumb-item"><a href="{{route('tenant.index')}}">Tenan</a></li>
<li class="breadcrumb-item active">Ubah</li>
@endpush

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-{{ config('configs.app_theme') }} card-outline">
                <div class="card-header">
                    <h3 class="card-title">Ubah Tenan</h3>
                    <!-- tools box -->
                    <div class="pull-right card-tools">
                        <button form="form" type="submit" class="btn btn-sm btn-{{ config('configs.app_theme')}}" title="Simpan"><i
                                class="fa fa-save"></i></button>
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-default" title="Kembali"><i
                                class="fa fa-reply"></i></a>
                    </div>
                    <!-- /. tools -->
                </div>
                <div class="card-body">
                    <form id="form" action="{{route('tenant.update',['id'=>$tenant->id])}}" method="post" autocomplete="off">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nama <b class="text-danger">*</b></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="name" value="{{$tenant->name}}" id="name" placeholder="Nama" required>
                            </div>
                        </div>
                         <div class="form-group row">
                            <label class="col-sm-2 col-form-label">No. HP <b class="text-danger">*</b></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="phone" value="{{$tenant->phone}}" id="phone" placeholder="No HP" required>
                            </div>
                        </div>
                        <!-- text input -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Tgl Lahir <b class="text-danger">*</b></label>
                            <div class="col-sm-6">
                                <!-- <input type="date" class="form-control" placeholder="Birthaday" name="birth_date"> -->
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" value="{{$tenant->birth_date}}" name="birth_date" class="form-control datepicker" id="birth_date" placeholder="Tgl Lahir">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jenis Kelamin</label>
                            <div class="col-sm-6">
                                <select name="gender" id="gender" class="form-control select2"
                                    data-placeholder="Select Gender">
                                    <option @if ($tenant->gender == "laki-laki") selected @endif value="laki-laki">Laki-Laki</option>
                                    <option  @if ($tenant->gender == "perempuan") selected @endif value="perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                         <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Email <b class="text-danger">*</b></label>
                            <div class="col-sm-6">
                                <!-- <input type="date" class="form-control" placeholder="Birthaday" name="birth_date"> -->
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                    </div>
                                    <input type="email" name="email" class="form-control" value="{{ $tenant->email }}" id="email" placeholder="Email">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jabatan <b class="text-danger">*</b></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="title" value="{{ $tenant->title }}" id="title" placeholder="Jabatan" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nama Perusahaan <b class="text-danger">*</b></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="company_name" value="{{ $tenant->company_name }}" id="company_name" placeholder="Nama Perusahaan" required>
                            </div>
                        </div>
                         <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Alamat <b class="text-danger">*</b></label>
                            <div class="col-sm-6">
                                <textarea type="text" class="form-control" name="address"placeholder="Alamat">{{ $tenant->address }}</textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="overlay d-none">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('adminlte/component/validate/jquery.validate.min.js')}}"></script>
<script src="{{asset('adminlte/component/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script src="{{asset('adminlte/component/bootstrap-fileinput/themes/explorer-fas/theme.min.js')}}"></script>
<script src="{{asset('adminlte/component/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script>
    $(document).ready(function(){
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
					beforeSend: function () {
                        $('.overlay').removeClass('hidden');
                    }
				}).done(function(response){
                    $('.overlay').addClass('hidden');
					if(response.status){
                        $.gritter.add({
                            title: 'Success!',
                            text: response.message,
                            class_name: 'gritter-success',
                            time: 10000,
                        });
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
		});
        $("#gender").select2();
        $('input[name=birth_date]').datepicker({
				autoclose: true,
				format: 'yyyy-mm-dd'
			})
			$('input[name=birth_date]').on('change', function(){
				if (!$.isEmptyObject($(this).closest("form").validate().submitted)) {
					$(this).closest("form").validate().form();
				}
			});
        $("input[name=ratio]").inputmask('decimal', {
			rightAlign: false
		});
        $('.select2').select2();
       
    });
</script>
@endpush
