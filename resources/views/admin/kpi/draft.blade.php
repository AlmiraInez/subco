@extends('admin.layouts.app')

@section('title', 'Tambah Kpi')
@push('breadcrump')
<li class="breadcrumb-item"><a href="{{route('product.index')}}">Kpi</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endpush
@section('stylesheets')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="{{asset('adminlte/component/summernote/css/summernote.min.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/bootstrap-fileinput/css/fileinput.min.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/bootstrap-fileinput/themes/explorer-fas/theme.min.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/daterangepicker/daterangepicker.css')}}" rel="stylesheet">
<style type="text/css">
    .ui-menu {
        width: 150px;
    }

    li.style {
        border: 0 none;
        padding: 2%;
        color: grey;
        margin-right: 8%;
        cursor: pointer;
        font-weight: 600;
    }

    li.style:hover,
    li.selected {
        color: #df4759;
        background-color: #f5f5f5;
    }

    /* li.style:active {
        color: red;
    } */

</style>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <form id="form" action="{{route('kpi.create')}}" class="form-horizontal" method="get" autocomplete="off">
            {{ csrf_field() }}

            <div class="card card-{{config('configs.app_theme')}} card-outline">
                <div class="card-header">
                    <h3 class="card-title">Kpi</h3>
                    <!-- tools box -->
                    <div class="pull-right card-tools">
                        <button form="form" type="submit" class="btn btn-sm btn-{{config('configs.app_theme')}} text-white" title="Simpan"><i
                                class="fa fa-save"></i></button>
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-default" title="Kembali"><i
                                class="fa fa-reply"></i></a>
                    </div>
                    <!-- /. tools -->
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Tanggal <b
                                class="text-danger">*</b></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="result_date" name="result_date" placeholder="Tanggal" required value="{{date('d/m/Y')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" value="{{ Auth::user()->id }}">
                        <label for="name" class="col-sm-2 col-form-label">Nama Karyawan <b
                                class="text-danger">*</b></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control select2" id="employee_id" name="employee_id" placeholder="Nama Karyawan" required>
                        </div>
                    </div>

                {{-- <div class="row mt-2">
                    <div for="category_select" class="col-sm-2"><b>Kategori dipilih</b> <b
                            class="text-danger">*</b></div>
                    <div class="col-sm-6">
                        <div class="text-{{config('configs.app_theme')}}"><b id="category_select">tidak ada kategori dipilih</b></div>
                        <input type="hidden" name="product_category_id">
                        <input type="hidden" name="category_name">
                    </div>
                </div> --}}

            </div>
    </div>
    </form>

    <div class="overlay d-none">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('adminlte/component/validate/jquery.validate.min.js')}}"></script>
<script src="{{asset('adminlte/component/summernote/js/summernote.min.js')}}"></script>
<script src="{{asset('adminlte/component/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script src="{{asset('adminlte/component/bootstrap-fileinput/themes/explorer-fas/theme.min.js')}}"></script>
<script src="{{asset('adminlte/component/daterangepicker/moment.min.js')}}"></script>
<script src="{{asset('adminlte/component/daterangepicker/daterangepicker.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('input[name=result_date]').daterangepicker({
                singleDatePicker: true,
                autoUpdateInput: false,
                timePicker: false,
                locale: {
                    format: 'DD/MM/YYYY'
                }
        },
        function(chosen_date) {
            $('input[name=result_date]').val(chosen_date.format('DD/MM/YYYY'));
        });
        $("#employee_id").select2({
            ajax: {
                url: "{{ route('employees.select') }}",
                type: 'GET',
                dataType: 'json',
                data: function (term, page) {
                    return {
                        name: term,
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
                            text: `${item.name}`
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
        $(document).on("change", "#employee_id", function () {
            if (!$.isEmptyObject($('#form').validate().submitted)) {
                $('#form').validate().form();
            }
        });
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


        $("#form").validate({
            errorElement: 'div',
            errorClass: 'invalid-feedback',
            focusInvalid: false,
            rules: {
                product_name: {
                    required: true,
                },
            },
            highlight: function (e) {
                $(e).closest('.form-group').removeClass('has-success').addClass('was-validated has-error');
            },

            success: function (e) {
                $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
                $(e).remove();
            },
        });

       
    });


</script>


@endpush
