@extends('admin.layouts.app')

@section('title', 'Pesan Ruangan')
@section('stylesheets')
<link href="{{asset('adminlte/component/daterangepicker/daterangepicker.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/bootstrap-fileinput/css/fileinput.min.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/bootstrap-fileinput/themes/explorer-fas/theme.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('adminlte/component/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">

<style>
   
</style>
@endsection

@push('breadcrump')
<li class="breadcrumb-item"><a href="{{route('admin.transaction.booking.index')}}">Pesan Ruangan</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endpush

@section('content')
<form id="form" action="{{ route('admin.transaction.booking.savebooking', ['id'=>$room->id])}}" method="post" autocomplete="off">
<div class="wrapper wrapper-content">
        {{ csrf_field() }}
        {{ method_field('put') }}

    <div class="row">
      <div class="col-lg-8">
        <div class="card card-{{ config('configs.app_theme') }} card-outline">
          <div class="card-header" style="height: 57px;">
            <h3 class="card-title">Pesan Ruangan</h3>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Kategori <b class="text-danger">*</b></label>
                    <input type="text" class="form-control" value="{{ $room->category->category }}" placeholder="Pilih Kategori" readonly>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Ruangan <b class="text-danger">*</b></label>
                    <input type="text" class="form-control" value="{{ $room->name }}"  placeholder="Pilih Ruangan" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Harga Ruangan <b class="text-danger">*</b></label>
                    <input type="text" class="form-control" value="{{ $room->price }}" readonly name="price" id="price" placeholder="Harga Ruangan" readonly="readonly">
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Durasi Sewa <b class="text-danger">*</b></label>
                    <input type="number" class="form-control" name="period_rent" id="period_rent" placeholder="Durasi Sewa">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Tenan <b class="text-danger">*</b></label>
                    <input type="text" class="form-control" value="{{ $user->tenant->name }}" readonly placeholder="Pilih Tenan">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                     <label>Tgl Mulai <b class="text-danger">*</b></label>
                      <div class="input-group">
                          <div class="input-group-prepend">
                              <span class="input-group-text">
                                  <i class="far fa-calendar-alt"></i>
                              </span>
                          </div>
                          <input type="text" name="start_date" class="form-control datepicker"
                              id="start_date" placeholder="Tgl Mulai">
                      </div>
                 </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                      <label>Tgl Akhir <b class="text-danger">*</b></label>
                      <div class="input-group">
                          <div class="input-group-prepend">
                              <span class="input-group-text">
                                  <i class="far fa-calendar-alt"></i>
                              </span>
                          </div>
                          <input type="text" name="end_date" class="form-control datepicker"
                              id="end_date" placeholder="Tgl Akhir">
                      </div>
                  </div>
                </div>
                 <div class="col-sm-6">
                    <div class="form-group">
                        <label>Periode Bayar <b class="text-danger">*</b></label>
                      <input type="text" class="form-control" name="payment_period" id="payment_period" placeholder="Periode Pembayaran">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Jam Mulai</label>
                    <input class="form-control timepicker" id="start_time" name="start_time">
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Jam Akhir</label>
                    <input class="form-control timepicker" id="finish_time" name="finish_time">
                  </div>
                </div>
              </div>
              {{-- <div style="height: 23px;"></div> --}}
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
                    <label>Notes</label>
                    <textarea class="form-control" name="notes" placeholder="Notes"></textarea>
                  </div>
                </div>
              </div>
              
              <div style="height: 225px;"></div>
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
          $("#status").select2();
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
            $(document).on("change", "#category_id", function () {
                if (!$.isEmptyObject($('#form').validate().submitted)) {
                    $('#form').validate().form();
                }
                $('#room_id').select2('val','');
                $('#price').val('');
            });
            $( "#room_id" ).select2({
              ajax: {
                url: "{{route('room.select')}}",
                type:'GET',
                dataType: 'json',
                data: function (term,page) {
                  return {
                    category_id:$('#category_id').val(),
                    name:term,
                    page:page,
                    limit:30,
                  };
                },
                results: function (data,page) {
                  var more = (page * 30) < data.total;
                  var option = [];
                  var price =0;
                  $.each(data.rows,function(index,item){
                    option.push({
                      id:item.id,
                      text: `${item.name} - Rp. ${item.price}`,
                      price: item.price,
                    });
                  });
                  return {
                    results: option, more: more,
                  };
                },
              },
              allowClear: true,
          });
          
          $(document).on("change", "#room_id", function () {
            if (!$.isEmptyObject($('#form').validate().submitted)) {
              $('#form').validate().form();
            }
             $('#price').val('');
             var price = $('#room_id').select2('data').price;
              $('#price').val(price);
          });
           $('input[name=start_date]').datepicker({
              autoclose: true,
              format: 'yyyy-mm-dd'
            })
            $('input[name=start_date]').on('change', function(){
              if (!$.isEmptyObject($(this).closest("form").validate().submitted)) {
                $(this).closest("form").validate().form();
              }
            });
            $('input[name=end_date]').datepicker({
              autoclose: true,
              format: 'yyyy-mm-dd'
            })
            $('input[name=end_date]').on('change', function(){
              if (!$.isEmptyObject($(this).closest("form").validate().submitted)) {
                $(this).closest("form").validate().form();
              }
            });
          $( "#tenant_id" ).select2({
            ajax: {
              url: "{{route('tenant.select')}}",
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
                    text: `${item.name} - ${item.company_name}`
                  });
                });
                return {
                  results: option, more: more,
                };
              },
            },
            allowClear: true,
          });
          $(document).on("change", "#tenant_id", function () {
            if (!$.isEmptyObject($('#form').validate().submitted)) {
              $('#form').validate().form();
            }
          });
          //Bootstrap fileinput component
         $("#video").fileinput({
            browseClass: "btn btn-{{config('configs.app_theme')}}",
            showRemove: false,
            showUpload: false,
            maxFileCount: 6,
            dropZoneEnabled: false,
            initialPreviewFileType: 'image',
           
            theme: 'explorer-fas'
        });

          // validation
          $(document).on("change", "#vidio", function () {
              if (!$.isEmptyObject($('#form').validate().submitted)) {
                  $('#form').validate().form();
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
          $("#image2").fileinput({
              browseClass: "btn btn-{{config('configs.app_theme')}}",
              showRemove: false,
              showUpload: false,
              maxFileCount: 6,
              dropZoneEnabled: false,
              initialPreviewFileType: 'image',
            
              theme: 'explorer-fas'
          });

          // validation
          $(document).on("change", "#image2", function () {
              if (!$.isEmptyObject($('#form').validate().submitted)) {
                  $('#form').validate().form();
              }
          });
          $("#image3").fileinput({
              browseClass: "btn btn-{{config('configs.app_theme')}}",
              showRemove: false,
              showUpload: false,
              maxFileCount: 6,
              dropZoneEnabled: false,
              initialPreviewFileType: 'image',
            
              theme: 'explorer-fas'
          });

          // validation
          $(document).on("change", "#image3", function () {
              if (!$.isEmptyObject($('#form').validate().submitted)) {
                  $('#form').validate().form();
              }
          });
          $("#image4").fileinput({
              browseClass: "btn btn-{{config('configs.app_theme')}}",
              showRemove: false,
              showUpload: false,
              maxFileCount: 6,
              dropZoneEnabled: false,
              initialPreviewFileType: 'image',
            
              theme: 'explorer-fas'
          });

          $("#image5").fileinput({
              browseClass: "btn btn-{{config('configs.app_theme')}}",
              showRemove: false,
              showUpload: false,
              maxFileCount: 6,
              dropZoneEnabled: false,
              initialPreviewFileType: 'image',
            
              theme: 'explorer-fas'
          });
          $("#image6").fileinput({
              browseClass: "btn btn-{{config('configs.app_theme')}}",
              showRemove: false,
              showUpload: false,
              maxFileCount: 6,
              dropZoneEnabled: false,
              initialPreviewFileType: 'image',
              theme: 'explorer-fas'
          });
       
        });
  </script>
  @endpush