@extends('admin.layouts.app')

@section('title', 'Detail Pertanyaan')
@section('stylesheets')
<link href="{{asset('adminlte/component/dataTables/css/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/summernote/css/summernote.min.css')}}" rel="stylesheet">
<style type="text/css">
    .overlay-wrapper {
        position: relative;
    }
</style>
@endsection
@push('breadcrump')
<li class="breadcrumb-item"><a href="{{route('question.index')}}">Pertanyaan</a></li>
<li class="breadcrumb-item active">Detail</li>
@endpush
@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card card-{{ config('configs.app_theme') }} card-outline">
            <div class="card-header">
                <h3 class="card-title">Detail Pertanyaan</h3>
                 <div class="pull-right card-tools">
                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-default" title="Kembali"><i class="fa fa-reply"></i></a>
                </div>
            </div>
            <div class="card-body" style="height:185%">
                <ul class="list-group">
                    <li class="list-group-item">
                        <b>Jenis</b> <br><span>{{$question->type}}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Deskripsi</b><br><span>{{$question->description}}</span>
                    </li>
                </ul>
                
            </div>
            <div class="overlay d-none">
                <i class="fa fa-2x fa-sync-alt fa-spin"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
       <div class="card card-{{ config('configs.app_theme') }} card-outline">
             <div class="card-header">
                <h3 class="card-title">Tambah Jawaban</h3>
                <div class="pull-right card-tools">
                    <a class="btn btn-{{ config('configs.app_theme') }} pull-right btn-sm" href="#" onclick="adddetail()"><i class="fa fa-plus"></i></a>
                </div>
            </div>
            <div class="card-body">
            <table class="table table-bordered table-striped" id="table-subcategory" style="width: 100%">
                <thead>
                    <tr>
                        <th style="text-align:center" width="10">#</th>
                        <th width="50">Urutan</th>
                        <th width="300">Deskripsi</th>
                        <th width="50">Bobot</th>
                        <th width="10">#</th>
                    </tr>
                </thead>
            </table>
            <div class="overlay hidden">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
       </div>

    </div>
</div>
<div class="modal fade" id="add-detail" tabindex="-1" role="dialog" aria-hidden="true" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Jawaban</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form" method="post" action="{{route('answer.store')}}" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="question_id" value="{{$question->id}}" />
                    <input type="hidden" name="_method" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="order">Urutan</label>
                                <input type="number" name="order" class="form-control" placeholder="Urutan" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="description">Deskripsi</label>
                                <input type="text" name="description" class="form-control" placeholder="Deskripsi" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="rating">Bobot</label>
                                <input type="text" name="rating" class="form-control numberfield" placeholder="Bobot" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="receipt_header">Informasi</label>
                                <textarea class="form-control summernote" name="information" id="information"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button form="form" type="submit" class="btn btn-{{ config('configs.app_theme') }} btn-sm" title="Simpan"><i class="fa fa-save"></i></button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('adminlte/component/validate/jquery.validate.min.js')}}"></script>
<script src="{{asset('adminlte/component/dataTables/js/datatables.min.js')}}"></script>
<script src="{{asset('adminlte/component/summernote/js/summernote.min.js')}}"></script>
<script>
    function adddetail() {
        $('#add-detail .modal-title').html('Tambah Jawaban');
        $('#add-detail').modal('show');
        $('#form')[0].reset();
        $('#form').attr('action', '{{route('answer.store')}}');
        $('#form input[name=_method]').attr('value', 'POST');
        $('#form .invalid-feedback').each(function () {
            $(this).remove();
        });
        $('#form').find('.form-group').removeClass('has-error').removeClass('has-success');
        $('#form textarea[name=information]').summernote('code', '');
        $('#form').find('select[name=type]').select2('val', '');
        $('#form').find('input[name=min]').closest('.row').hide();
        $('#form').find('input[name=max]').closest('.row').hide();
    }
    //Text Editor Component
    $('.summernote').summernote({
        height: 180,
        placeholder: 'Tulis sesuatu disini...',
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
    });
    $(document).on("change", "#form select[name=answer_type]", function () {
        if (!$.isEmptyObject($('#form').validate().submitted)) {
            $('#form').validate().form();
        }
    });
    $(document).ready(function () {
        $(".numberfield").inputmask('decimal', {
            rightAlign: false
        });
        $('.select2').select2({
            allowClear: true
        });
        dataTable = $('#table-subcategory').DataTable({
            stateSave: true,
            processing: true,
            serverSide: true,
            filter: false,
            info: false,
            lengthChange: false,
            responsive: true,
            order: [
                [4, "asc"]
            ],
            ajax: {
                url: "{{route('answer.read')}}",
                type: "GET",
                data: function (data) {
                    data.question_id = {{$question->id}};
                }
            },
            columnDefs: [{
                    orderable: false,
                    targets: [0]
                },
                {
                    className: "text-right",
                    targets: [0,1,3]
                },
                {
                    className: "text-center",
                    targets: [4]
                },
                {
                    render: function (data, type, row) {
                        return `<div class="dropdown">
                    <button class="btn  btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bars"></i>
                    </button>
                      <ul class="dropdown-menu dropdown-menu-right">
                        <li><a class="dropdown-item edit" href="#" data-id="${row.id}"><i class="fas fa-pencil-alt mr-2"></i> Edit</a></li>
                        <li><a class="dropdown-item delete" href="#" data-id="${row.id}"><i class="fas fa-trash mr-2"></i> Delete</a></li>
                      </ul></div>`
                    },
                    targets: [4]
                }
            ],
            columns: [{
                    data: "no"
                },
                {
                    data: "order"
                },
                {
                    data: "description"
                },
                {
                    data: "rating"
                },
                {
                    data: "id"
                }
            ]
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
                if (element.is(':file')) {
                    error.insertAfter(element.parent().parent().parent());
                } else
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else
                if (element.attr('type') == 'checkbox') {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function () {
                $.ajax({
                    url: $('#form').attr('action'),
                    method: 'post',
                    data: new FormData($('#form')[0]),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function () {
                        $('#subcategory .overlay').removeClass('hidden');
                    }
                }).done(function (response) {
                    $("#add-detail").modal('hide');
                    $('#subcategory .overlay').addClass('hidden');
                    if (response.status) {
                        dataTable.draw();
                        $.gritter.add({
                            title: 'Success!',
                            text: response.message,
                            class_name: 'gritter-success',
                            time: 1000,
                        });
                    } else {
                        $.gritter.add({
                            title: 'Warning!',
                            text: response.message,
                            class_name: 'gritter-warning',
                            time: 1000,
                        });
                    }
                    return;

                }).fail(function (response) {
                    var response = response.responseJSON;
                    $('#subcategory .overlay').addClass('hidden');
                    $.gritter.add({
                        title: 'Error!',
                        text: response.message,
                        class_name: 'gritter-error',
                        time: 1000,
                    });
                })
            }
        });
        $(document).on('click', '.edit', function () {
            var id = $(this).data('id');
            $.ajax({
                url: `{{url('admin/answer')}}/${id}/edit`,
                method: 'GET',
                dataType: 'json',
                beforeSend: function () {
                    $('#subcategory .overlay').removeClass('hidden');
                },
            }).done(function (response) {
                $('#subcategory .overlay').addClass('hidden');
                if (response.status) {
                    $('#add-detail .modal-title').html('Ubah Jawaban');
                    $('#add-detail').modal('show');
                    $('#form')[0].reset();
                    $('#form .invalid-feedback').each(function () {
                        $(this).remove();
                    });
                    $('#form .form-group').removeClass('has-error').removeClass('has-success');
                    $('#form input[name=_method]').attr('value', 'PUT');
                    $('#form input[name=order]').attr('value', response.data.order);
                    $('#form input[name=description]').attr('value', response.data.description);
                    $('#form select[name=answer_type]').select2('val', response.data.answer_type);
                    $('#form input[name=rating]').attr('value', response.data.rating);
                    $('#form textarea[name=information]').summernote('code', response.data
                        .information);
                    $('#form').attr('action',
                        `{{url('admin/answer')}}/${response.data.id}`);
                }
            }).fail(function (response) {
                var response = response.responseJSON;
                $('#subcategory .overlay').addClass('hidden');
                $.gritter.add({
                    title: 'Error!',
                    text: response.message,
                    class_name: 'gritter-error',
                    time: 1000,
                });
            })
        });
        
        $(document).on('click','.delete',function(){
            var id = $(this).data('id');
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
                title:'Delete Jawaban?',
                message:'Data that has been deleted cannot be recovered',
                callback: function(result) {
                        if(result) {
                            var data = {
                                _token: "{{ csrf_token() }}"
                            };
                            $.ajax({
                                url: `{{url('admin/answer')}}/${id}`,
                                dataType: 'json',
                                data:data,
                                type:'DELETE',
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
                                $('.overlay').addClass('d-none');
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
        })
    });

</script>
@endpush