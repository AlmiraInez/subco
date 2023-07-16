@extends('admin.layouts.app')

@section('title', 'Formula ')
@section('stylesheets')
@endsection

@push('breadcrump')
<li class="breadcrumb-item"><a href="{{route('formula.index')}}">Formula</a></li>
<li class="breadcrumb-item active">Create</li>
@endpush

@section('content')
{{-- <div class="wrapper wrapper-content"> --}}
<form id="form" action="{{ route('formula.store') }}" method="post" autocomplete="off">
    {{ csrf_field() }}
  <div class="row">
    <div class="col-lg-8">
      <div class="card card-{{ config('configs.app_theme') }} card-outline">
        <div class="card-header" style="height: 57px;">
          <h3 class="card-title">Formula Data</h3>
        </div>
        <div class="card-body">
            <div class="row">
              <div class="col-sm-6">
                <!-- text input -->
                <div class="form-group">
                  <label>Nama <b class="text-danger">*</b></label>
                  <input type="text" class="form-control" name="name" id="name" placeholder="Nama" required>
                </div>
              </div>
              <div class="col-sm-6">
                {{--  --}}
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
                <div class="form-group">
                  <label>Status <b class="text-danger">*</b></label>
                  {{-- <input type="text" class="form-control" name="status" id="status" value="Active" readonly> --}}
                  <select name="status" id="status" class="form-control select2" data-placeholder="Select Status">
                    <option value="1">Active</option>
                    <option value="0">Non Active</option>
                  </select>
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
            <h3 class="card-title">Jawaban</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="answer_id" name="answer_id"
                            data-placeholder="Pilih Jabawan">
                        <br>
                        <a class="nohover btn btn-outline-{{config('configs.app_theme')}} col-sm-12" onclick="add()" style="border-style: dashed; cursor:pointer;">Tambah</a>
                    </div>
                </div>
                <table class="table table-bordered table-striped" id="table-formula">
                    <thead>
                        <th width="100">Operator</th>
                        <th width="250">Nilai</th>
                        <th width="100">ID</th>
                        <th width="100">Operator</th>
                        <th  width="50" class="text-center">#</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <input type="hidden" name="calculate"/>
                <b><p id="result" class="form-control-static"></p></b>
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
    var sort = 0;
    function add(){
        var id = $('#answer_id').select2('val');
        if(id){
        var text = $('#answer_id').select2('data').text;
        var description = `<input type="hidden" name="value_${sort}" value="#${id}#" class="value"/><p class="form-control-static">${text}</p>`;
        }
        else{
        var description = `<input type="text" name="value_${sort}" class="form-control value" required onkeyup="formula()"/>`;
        }
        $('#table-formula tbody').append(`
        <tr>
            <td>
            <input type="hidden" name="formula[]" value="${sort}"/>
            <input type="hidden" name="answer_${sort}" value="${id}"/>
            <select name="operation_before_${sort}" onchange="formula()" class="form-control select2 before">
                <option value=""></option>
                <option value="+">+</option>
                <option value="-">-</option>
                <option value="*">*</option>
                <option value="/">/</option>
                <option value="(">(</option>
                <option value=")">)</option>
            </select>
            </td>
            <td>${description}</td>
            <td>#${id}#</td>
            <td>
            <select name="operation_${sort}" onchange="formula()" class="form-control select2 after">
                <option value=""></option>
                <option value="+">+</option>
                <option value="-">-</option>
                <option value="*">*</option>
                <option value="/">/</option>
                <option value="(">(</option>
                <option value=")">)</option>
            </select>
            </td>
            <td class="text-center">
            <a class="btn btn-danger btn-sm" onclick="remove(this)"><i class="fa fa-times"></i></a>
            </td>
        </tr>
        `);
        $(`select[name=operation_${sort}]`).select2({
        allowClear:true
        });
        $(`select[name=operation_before_${sort}]`).select2({
        allowClear:true
        });
        $(`input[name=value_${sort}]`).inputmask('decimal', {
        rightAlign: true
        });
        sort++;
        formula();
        $('#answer_id').select2('val','');
    }
    function remove(e){
        $(e).closest('tr').remove();
        formula();
    }
    function formula(){
        var string = '';
        $( "input[name^=formula]" ).each(function(  ) {
        var before = $(this).closest('tr').find('.before').select2('val');
        var value = $(this).closest('tr').find('.value').val();
        var after = $(this).closest('tr').find('.after').select2('val');
        string += (before+' '+value+' '+after+' ');
        });
        $('#result').html(string==''?'Belum Ada Formula':string+' = ');
        $('input[name=calculate]').attr('value',string);
    }
    $(document).ready(function(){
        $('.select2').select2();
        formula();
        $("#answer_id").select2({
        ajax: {
            url: "{{route('answer.select')}}",
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
                text: `${item.question.description} - ${item.description}`
                });
            });
            return {
                results: option, more: more,
            };
            },
        },
        allowClear: true,
        });
        $("#form").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            focusInvalid: false,
            highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-success').addClass('has-error');
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
            })		
            }
        });
    });
 </script>
  @endpush