@extends('admin.layouts.app')

@section('title', 'Ubah Ruangan')
@section('stylesheets')
<link href="{{asset('adminlte/component/daterangepicker/daterangepicker.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/bootstrap-fileinput/css/fileinput.min.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/bootstrap-fileinput/themes/explorer-fas/theme.min.css')}}" rel="stylesheet">
<style>
   
</style>
@endsection

@push('breadcrump')
<li class="breadcrumb-item"><a href="{{route('room.update',['id'=>$room->id])}}">Ruangan</a></li>
<li class="breadcrumb-item active">Ubah Ruangan</li>
@endpush

@section('content')
<form id="form" action="{{ route('room.update',['id'=>$room->id]) }}" method="post" autocomplete="off">
<div class="wrapper wrapper-content">
        {{ csrf_field() }}
        @method('PUT')

    <div class="row">
      <div class="col-lg-8">
        <div class="card card-{{ config('configs.app_theme') }} card-outline">
          <div class="card-header" style="height: 57px;">
            <h3 class="card-title">Ubah Ruangan</h3>
          </div>
          <div class="card-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Nama Ruangan <b class="text-danger">*</b></label>
                    <input type="text" class="form-control" value="{{ $room->name }}" name="name" id="name" placeholder="Nama Ruangan">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Lokasi Ruangan <b class="text-danger">*</b></label>
                    <input type="text" class="form-control" value="{{ $room->location }}" name="location" id="location" placeholder="Lokasi Ruangan">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Kategori <b class="text-danger">*</b></label>
                    <input type="text" class="form-control" name="category_id" id="category_id" placeholder="Kategori">
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label>Luas Ruangan <b class="text-danger">*</b></label>
                    <input type="text" value="{{ $room->width }}" class="form-control" name="width" id="width" placeholder="Luas Ruangan">
                  </div>
                </div>
                 <div class="col-sm-3">
                  <div class="form-group">
                    <label>Harga Sewa <b class="text-danger">*</b></label>
                    <input type="number" value="{{ $room->price }}" class="form-control" name="price" id="price" placeholder="Harga Sewa">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Fasilitas <b class="text-danger">*</b></label>
                    <input type="text" class="form-control" name="fasility" id="fasility" data-placeholder="Fasilitas">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Alamat <b class="text-danger">*</b></label>
                    <textarea class="form-control" name="address" placeholder="address">{{ $room->address }}</textarea>
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
                    <textarea class="form-control" name="notes" placeholder="Notes">{{ $room->notes }}</textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Status <b class="text-danger">*</b></label>
                    <select name="status" id="status" class="form-control select2" data-placeholder="Select Status">
                      {{-- <option value=""></option> --}}
                      <option @if($room->status == 1) selected @endif value="1">Aktif</option>
					            <option @if($room->status == 2) selected @endif value="2">Tidak Aktif</option>
                    </select>
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
      <div class="col-lg-12">
        <div class="card card-{{ config('configs.app_theme') }} card-outline">
            <div class="card-header">
                <h3 class="card-title">Pengaturan Media</h3>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label for="video" class="col-sm-2 col-form-label">Vidio</label>
                    <div class="col-sm-6">
                        <input type="file" class="form-control" name="video" id="video" accept="video/*" 
                           value="{{ $room->video }}" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="image1" class="col-sm-2 col-form-label">Gambar 1</label>
                    <div class="col-sm-6">
                        <input type="file" class="form-control" name="image1" id="image1" accept="image/*" 
                           value="{{ $room->img1 }}"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="image2" class="col-sm-2 col-form-label">Gambar 2</label>
                    <div class="col-sm-6">
                        <input type="file" class="form-control" name="image2" id="image2" accept="image/*" 
                           data-overwrite-initial="false" value="{{ $room->img2 }}" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="image3" class="col-sm-2 col-form-label">Gambar 3</label>
                    <div class="col-sm-6">
                        <input type="file" class="form-control" name="image3" id="image3" accept="image/*" 
                           data-overwrite-initial="false" value="{{ $room->img3 }}" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="image4" class="col-sm-2 col-form-label">Gambar 4</label>
                    <div class="col-sm-6">
                        <input type="file" class="form-control" name="image4" id="image4" accept="image/*" 
                           data-overwrite-initial="false" value="{{ $room->img4 }}" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="image5" class="col-sm-2 col-form-label">Gambar 5</label>
                    <div class="col-sm-6">
                        <input type="file" class="form-control" name="image5" id="image5" accept="image/*" 
                           data-overwrite-initial="false" value="{{ $room->img5 }}" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="image6" class="col-sm-2 col-form-label">Gambar 6</label>
                    <div class="col-sm-6">
                        <input type="file" class="form-control" name="image6" id="image6" accept="image/*" 
                           data-overwrite-initial="false" value="{{ $room->img6 }}" />
                    </div>
                </div>
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
            @if($room->category_id)
                $("#category_id").select2('data',{id:{{$room->category_id}},text:'{{$room->category->category}}'}).trigger('change');
            @endif
            $(document).on("change", "#category_id", function () {
                if (!$.isEmptyObject($('#form').validate().submitted)) {
                    $('#form').validate().form();
                }
            });
          $( "#fasility" ).select2({
            multiple: true,
            tags: true,
            ajax: {
              url: "{{route('fasility.select')}}",
              type:'GET',
              dataType: 'json',
              data: function (term,page) {
                return {
                  fasility:term,
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
                    text: `${item.fasility}`
                  });
                });
                return {
                  results: option, more: more,
                };
              },
            },
            allowClear: true,
          });
          $(document).on("change", "#fasility", function () {
            if (!$.isEmptyObject($('#form').validate().submitted)) {
              $('#form').validate().form();
            }
          });
          $.ajax({
                url:  "{{route('room.multi')}}",
                type: 'POST',
                dataType: 'json',
                data: { 
               room_id: {{$room->id}},
              _token: "{{csrf_token()}}" 
              },
                success: function (result) {                  
               console.log(result.results);
            var data = [];
                $.each(result.results, function(key, val){
            //    console.log(val.workgroup_id);
            data.push({ id:val.fasility_id,text:val.fasility_name });
              // $("#workgroup").append('<option value="'+val.workgroup_id+'" selected>'+val.workgroup_name+'</option>');
          });
				  $("#fasility").select2('data',data).trigger('change');    

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
            initialPreview: '<video width="200" height="100" controls  class="kv-preview-data file-preview-video"><source src="{{asset('assets/rooms/video/'.$room->video)}}" type="video/mp4"></video>',
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
            	initialPreview: '<img src="{{asset('assets/rooms/img/'.$room->img1)}}" class="kv-preview-data file-preview-image">',
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
              initialPreview: '<img src="{{asset('assets/rooms/img/'.$room->img2)}}" class="kv-preview-data file-preview-image">',
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
              initialPreview: '<img src="{{asset('assets/rooms/img/'.$room->img3)}}" class="kv-preview-data file-preview-image">',
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
              initialPreview: '<img src="{{asset('assets/rooms/img/'.$room->img4)}}" class="kv-preview-data file-preview-image">',
              theme: 'explorer-fas'
          });

          $("#image5").fileinput({
              browseClass: "btn btn-{{config('configs.app_theme')}}",
              showRemove: false,
              showUpload: false,
              maxFileCount: 6,
              dropZoneEnabled: false,
              initialPreviewFileType: 'image',
              initialPreview: '<img src="{{asset('assets/rooms/img/'.$room->img5)}}" class="kv-preview-data file-preview-image">',
              theme: 'explorer-fas'
          });
          $("#image6").fileinput({
              browseClass: "btn btn-{{config('configs.app_theme')}}",
              showRemove: false,
              showUpload: false,
              maxFileCount: 6,
              dropZoneEnabled: false,
              initialPreviewFileType: 'image',
              initialPreview: '<img src="{{asset('assets/rooms/img/'.$room->img6)}}" class="kv-preview-data file-preview-image">',
              theme: 'explorer-fas'
          });
       
        });
  </script>
  @endpush