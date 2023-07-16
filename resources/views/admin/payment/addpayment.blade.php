@extends('admin.layouts.app')

@section('title', 'Tambah Pembayaran')
@section('stylesheets')
<link href="{{asset('adminlte/component/daterangepicker/daterangepicker.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/bootstrap-fileinput/css/fileinput.min.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/bootstrap-fileinput/themes/explorer-fas/theme.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('adminlte/component/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">

<style>
   
</style>
@endsection

@push('breadcrump')
<li class="breadcrumb-item"><a href="{{route('payment.index')}}">Pembayaran</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endpush

@section('content')
<form id="form" action="{{ route('payment.savepayment', ['id'=>$transaction->id])}}" method="post" autocomplete="off">
<div class="wrapper wrapper-content">
    {{ csrf_field() }}
    {{ method_field('put') }}
    <div class="row">
      <div class="col-lg-8">
        <div class="card card-{{ config('configs.app_theme') }} card-outline">
          <div class="card-header" style="height: 57px;">
            <h3 class="card-title">Tambah Pembayaran</h3>
          </div>
          <div class="card-body">
              <div class="col-md-12">
                <div class="row">
                    <div class="row mb-3 col-md-6">
                        <div class="col-md-4"><b>Kode Transaksi</b></div>
                        <div class="col-md-8">{{ $transaction->transaction->code }}</div>
                        
                    </div>
                    <div class="row mb-3 col-md-6">
                        <div class="col-md-4"><b>Tgl Transksi</b></div>
                        <div class="col-md-8">{{ $transaction->transaction->transaction_date }}</div>
                        
                    </div>
                    <div class="row mb-3 col-md-6">
                        <div class="col-md-4"><b>Kode Invoice</b></div>
                        <div class="col-md-8">{{ $transaction->code }}</div>
                        
                    </div>
                    <div class="row mb-3 col-md-6">
                        <div class="col-md-4"><b>Tgl Invoice</b></div>
                        <div class="col-md-8">{{ $transaction->invoice_date }}</div>
                    </div>
                    <div class="row mb-3 col-md-6">
                        <div class="col-md-4"><b>Durasi Sewa</b></div>
                        <div class="col-md-8">{{ $transaction->transaction->period_rent }}</div>
                    </div>
                    <div class="row mb-3 col-md-6">
                        <div class="col-md-4"><b>Kategori</b></div>
                        <div class="col-md-8">{{ $transaction->category->category }}</div>
                    </div>
                    <div class="row mb-3 col-md-6">
                        <div class="col-md-4"><b>Ruangan</b></div>
                        <div class="col-md-8">{{ $transaction->room->name }}</div>
                    </div>
                    <div class="row mb-3 col-md-6">
                        <div class="col-md-4"><b>Harga Ruangan</b></div>
                        <div class="col-md-8">Rp. {{ number_format($transaction->price,0,',','.') }}</div>
                    </div>
                    
                    <div class="row mb-3 col-md-6">
                        <div class="col-md-4"><b>Tenan</b></div>
                        <div class="col-md-8">{{ $transaction->tenant->name }}</div>

                    </div>
                    <div class="row mb-3 col-md-6">
                        <div class="col-md-4"><b>Perusahaan</b></div>
                        <div class="col-md-8">{{ $transaction->tenant->company_name }}</div>
                    </div>
                    <div class="row mb-3 col-md-6">
                        <div class="col-md-4"><b>Tgl Sewa</b></div>
                        <div class="col-md-8">{{ $transaction->transaction->start_date }} - {{ $transaction->transaction->end_date }}</div>
                    </div>
                    <div class="row mb-3 col-md-6">
                        <div class="col-md-4"><b>Jam</b></div>
                        <div class="col-md-8">{{ $transaction->transaction->start_time }} - {{ $transaction->transaction->end_time }}</div>
                    </div>
                    <div class="row mb-3 col-md-6">
                        <div class="col-md-4"><b>Periode Bayar</b></div>
                        <div class="col-md-8">{{ $transaction->transaction->payment_period }}</div>
                    </div>
                    <div class="row mb-3 col-md-6">
                        <div class="col-md-4"><b>Jumlah Tagihan</b></div>
                        <div class="col-md-8">Rp. {{ number_format($transaction->amount,0,',','.') }}</div>
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
              <button form="form" type="submit" class="btn btn-sm btn-{{ config('configs.app_theme') }}" title="Save"><i
                  class="fa fa-save"></i></button>
              <a href="{{ url()->previous() }}" class="btn btn-sm btn-default" title="Back"><i
                  class="fa fa-reply"></i></a>
            </div>
          </div>
          <div class="card-body">
              <div class="row">
                <div class="col-sm-12">
                  <!-- text input -->
                  <div class="form-group">
                    <label>Catatan</label>

                    {{-- {{ $transaction->notes }} --}}
                    <textarea class="form-control" name="notes" placeholder="Notes"></textarea>
                  </div>
                </div>              
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="image1" class="col-sm-12 col-form-label">Bukti Transfer</label>
                    <div class="col-sm-12">
                        <input type="file" class="form-control" name="image1" id="image1" accept="image/*" 
                           data-overwrite-initial="false" />
                    </div>
                  </div>
                </div>
              </div>
              <div style="height: 60px;"></div>
          </div>
          <div class="overlay d-none">
            <i class="fa fa-2x fa-sync-alt fa-spin"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
  @endsection

  @push('scripts')
  <script src="{{asset('adminlte/component/validate/jquery.validate.min.js')}}"></script>
  <script src="{{asset('adminlte/component/daterangepicker/moment.min.js')}}"></script>
  <script src="{{asset('adminlte/component/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
  <script src="{{asset('adminlte/component/bootstrap-fileinput/themes/explorer-fas/theme.min.js')}}"></script>
  <script src="{{asset('adminlte/component/daterangepicker/daterangepicker.js')}}"></script>
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
                  beforeSend:function(){
                   $('.overlay').removeClass('d-none');
                 }
               }).done(function(response){
                $('.overlay').addClass('d-none');
                if(response.status){
                  document.location = response.results;
                   $.gritter.add({
                        title: 'Success!',
                        text: response.message,
                        class_name: 'gritter-success',
                        time: 1000,
                  });}
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
          $("#image1").fileinput({
              browseClass: "btn btn-{{config('configs.app_theme')}}",
              showRemove: false,
              showUpload: false,
              maxFileCount: 6,
              dropZoneEnabled: false,
              initialPreviewFileType: 'image',
            
              theme: 'explorer-fas'
          });

          // validation
          $(document).on("change", "#image1", function () {
              if (!$.isEmptyObject($('#form').validate().submitted)) {
                  $('#form').validate().form();
              }
          });
          $('input[name=status]').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
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
                   
       
        });
  </script>
  @endpush