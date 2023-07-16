@extends('admin.layouts.app')

@section('title', 'Question ')
@section('stylesheets')
@endsection

@push('breadcrump')
<li class="breadcrumb-item"><a href="{{route('question.index')}}">Question</a></li>
<li class="breadcrumb-item active">Create</li>
@endpush

@section('content')
{{-- <div class="wrapper wrapper-content"> --}}
<form id="form" action="{{ route('question.store') }}" method="post" autocomplete="off">
    {{ csrf_field() }}
  <div class="row">
    <div class="col-lg-8">
      <div class="card card-{{ config('configs.app_theme') }} card-outline">
        <div class="card-header" style="height: 57px;">
          <h3 class="card-title">Question Data</h3>
        </div>
        <div class="card-body" style="height: 145px;">
            <div class="row">
              <div class="col-sm-6">
                <!-- text input -->
                <div class="form-group">
                  <label>Urutan <b class="text-danger">*</b></label>
                  <input type="number" class="form-control" name="order" id="order" placeholder="Urutan" required>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Jenis <b class="text-danger">*</b></label>
                  <select id="type" name="type" class="form-control select2" placeholder="Pilih Jenis" required>
                    <option value=""></option>
                    <option value="Informasi">Informasi</option>
                    <option value="Pertanyaan">Pertanyaan</option>
                  </select>
                </div>
              </div>
            </div>
        </div>
        <div class="overlay d-none">
          <i class="fa fa-refresh fa-spin"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card card-{{ config('configs.app_theme') }} card-outline">
        <div class="card-header">
          <h3 class="card-title">Other</h3>
          <div class="pull-right card-tools">
            <button form="form" type="submit" class="btn btn-sm btn-{{ config('configs.app_theme') }}" title="Simpan"><i
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
                  <label>Pertanyaan <b class="text-danger">*</b></label>
                  <textarea class="form-control" name="description" placeholder="Pertanyaan" required></textarea>
                </div>
              </div>
            </div>
          {{-- </form> --}}
        </div>
        <div class="overlay d-none">
          <i class="fa fa-2x fa-sync-alt fa-spin"></i>
        </div>
        {{-- </form> --}}
      </div>
    </div>
    <div class="col-lg-12">
        <div class="card card-{{ config('configs.app_theme') }} card-outline">
            <div class="card-header">
            <h3 class="card-title">Department</h3>
            </div>
            <div class="card-body">
            {{-- <form role="form"> --}}
                <table class="table table-bordered table-striped" id="table-department">
                    <thead>
                        <th>Nama</th>
                        <th class="text-center">Status</th>
                    </thead>
                    <tbody>
                        @foreach ($departments as $department)
                        <tr>
                        <td><input type="hidden" name="department[]" value="{{$department->id}}" />{{$department->name}}</td>
                        <td class="text-center"><input type="checkbox" name="department_status[{{$department->id}}]"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
            {{-- </form> --}}
            </div>
            <div class="overlay d-none">
            <i class="fa fa-2x fa-sync-alt fa-spin"></i>
            </div>
            
        </div>
    </div>
  </div>
  </form>
  
  @endsection

  @push('scripts')
  <script src="{{asset('adminlte/component/validate/jquery.validate.min.js')}}"></script>
  <script>
    $(document).ready(function(){
          $(".select2").select2();
          $('input[name^=department_status]').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
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
              })
            }
          });
        });
  </script>
  @endpush