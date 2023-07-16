@extends('admin.layouts.empty')
@section('title', 'Registrasi')
@section('class', 'login-page')

@section('stylesheets')
<link href="{{asset('adminlte/component/bootstrap-fileinput/css/fileinput.min.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/bootstrap-fileinput/themes/explorer-fas/theme.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('adminlte/component/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
<style type="text/css">
  .login-page,
  .register-page {
    background: white;
  }

  .login-section-wrapper {
    display: flex;
    flex-direction: column;
    padding-right: 100px;
    padding-left: 100px;
  }

  .login-img {
    width: 100%;
    height: 100vh;
    object-fit: cover;
    object-position: left;
  }

  @media screen and (max-width: 991px) {
    .login-section-wrapper {
      padding-right: 50px;
      padding-left: 50px;
    }
  }
</style>

@endsection
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-5 login-section-wrapper my-auto">
      {{-- <div class="brand-wrapper" > --}}
        {{-- <img src="{{ asset(config('configs.app_logo')) }}" style="height: 200; width:200;" alt="brand-image" class="img-fluid"> --}}
      {{-- </div> --}}
      <h3 class="text-center" style="margin-bottom: 50px;">Register</h3>
      <div class="login-wrapper">
        <form action="{{route('admin.login.post')}}" method="post" autocomplete="off">
          @csrf
          <div class="form-group row">
              <label class="col-sm-3 col-form-label">Nama <b class="text-danger">*</b></label>
              <div class="col-sm-9">
                  <input type="text" class="form-control" name="name" id="name" placeholder="Nama" required>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-sm-3 col-form-label">No.HP <b class="text-danger">*</b></label>
              <div class="col-sm-9">
                  <input type="text" class="form-control" name="phone" id="phone" placeholder="No.HP" required>
              </div>
          </div>
           <!-- text input -->
          <div class="form-group row">
              <label class="col-sm-3 col-form-label">Tgl Lahir <b class="text-danger">*</b></label>
              <div class="col-sm-9">
                  <!-- <input type="date" class="form-control" placeholder="Birthaday" name="birth_date"> -->
                  <div class="input-group">
                      <div class="input-group-prepend">
                          <span class="input-group-text">
                              <i class="far fa-calendar-alt"></i>
                          </span>
                      </div>
                      <input type="text" name="birth_date" class="form-control datepicker"
                          id="birth_date" placeholder="Tgl Lahir">
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-sm-3 col-form-label">Jenis Kelamin</label>
              <div class="col-sm-9">
                  <select name="gender" id="gender" class="form-control select2"
                      data-placeholder="Select Gender">
                      <option value="laki-laki">Laki-Laki</option>
                      <option value="perempuan">Perempuan</option>
                  </select>
              </div>
          </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Email <b class="text-danger">*</b></label>
              <div class="col-sm-9">
                  <!-- <input type="date" class="form-control" placeholder="Birthaday" name="birth_date"> -->
                  <div class="input-group">
                      <div class="input-group-prepend">
                          <span class="input-group-text">
                              <i class="fas fa-envelope"></i>
                          </span>
                      </div>
                      <input type="email" name="email" class="form-control"
                          id="email" placeholder="Email">
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-sm-3 col-form-label">Jabatan <b class="text-danger">*</b></label>
              <div class="col-sm-9">
                  <input type="text" class="form-control" name="title" id="title" placeholder="Jabatan" required>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-sm-3 col-form-label">Nama Perusahaan <b class="text-danger">*</b></label>
              <div class="col-sm-9">
                  <input type="text" class="form-control" name="company_name" id="company_name" placeholder="Nama Perusahaan" required>
              </div>
          </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Alamat <b class="text-danger">*</b></label>
              <div class="col-sm-9">
                  <textarea type="text" class="form-control" name="address"placeholder="Alamat"> </textarea>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-sm-3 col-form-label">Password <b class="text-danger">*</b></label>
              <div class="col-sm-9">
                  <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
              </div>
          </div>
          <div class="row d-flex h-100">
            <div class="col-sm-6 align-self-center">
              <a href="#" class="text-{{ config('configs.app_theme') }}">Login?</a>
            </div>
            <div class="col-sm-6">
              <button type="submit" class="btn btn-dark float-right">Register</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="col-sm-7 px-0 d-none d-sm-block">
      <img src="{{ asset('img/subco.jpg') }}" alt="login image" class="login-img">
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
						document.location = response.results;
                        $.gritter.add({
                            title: 'Success!',
                            text: response.message,
                            class_name: 'gritter-success',
                            time: 1000,
                        });
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
