@extends('admin.layouts.app')

@section('title', 'Checkin Ruangan')
@section('stylesheets')
<link href="{{asset('adminlte/component/dataTables/css/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/daterangepicker/daterangepicker.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('adminlte/component/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
@endsection

@push('breadcrump')
<li class="breadcrumb-item active">Checkin Ruangan</li>
@endpush

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-{{ config('configs.app_theme') }} card-outline">
                <div class="card-header">
                    <h3 class="card-title">Daftar Checkin Ruangan</h3>
                    <!-- tools box -->
                    <div class="pull-right card-tools">
                        <a href="#" onclick="filter()" class="btn btn-default btn-sm" data-toggle="tooltip" title="Search">
                            <i class="fa fa-search"></i>
                        </a>
                    </div>
                    <!-- /. tools -->
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered datatable" style="width:100%">
                        <thead>
                            <tr>
                                <th width="10">#</th>
                                <th width="10">Kode Transaksi</th>
                                <th width="30">Masa Sewa</th>
                                <th width="20">Kategori Sewa</th>
                                <th width="20">Tenan</th>
                                <th width="20">Tanggal Sewa</th>
                                <th width="30">Harga</th>
                                <th width="10">Status</th>
                                <th width="10">#</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="overlay d-none">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="add-filter" tabindex="-1" role="dialog"  aria-hidden="true" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Filter</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
            <div class="modal-body">
                <form id="form-search" autocomplete="off">
                    <div class="row">
                       <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="uomcategory_name">Kode Transaksi</label>
                                <input type="text" name="code" class="form-control" placeholder="Kode Transaksi">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="uom_name">Tanggal Pembayaran</label>
                                 <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="transaction_date" class="form-control datepicker"
                                        id="transaction_date" placeholder="Tgl Transaksi">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="uom_name">Nama Tenan</label>
                                <input type="text" id="tenant_id" name="tanant_id" class="form-control" placeholder="Nama Tenan">
                            </div>  
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="uomcategory_name">Perusahaan</label>
                                <input type="text" name="company" class="form-control" placeholder="Perusahaan">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button form="form-search" type="submit" class="btn btn-{{ config('configs.app_theme') }}" title="Apply"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('adminlte/component/dataTables/js/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/bootbox/bootbox.min.js')}}"></script>
<script src="{{asset('adminlte/component/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('adminlte/component/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script><script src="{{asset('assets/js/plugins/bootbox/bootbox.min.js')}}"></script>
<script type="text/javascript">
function filter(){
    $('#add-filter').modal('show');
}
$(function(){
    dataTable = $('.datatable').DataTable( {
        stateSave:true,
        processing: true,
        serverSide: true,
        filter:false,
        info:false,
        lengthChange:true,
        responsive: true,
        order: [[ 8, "asc" ]],
        ajax: {
            url: "{{route('checkin.read')}}",
            type: "GET",
            data:function(data){
                var code = $('#form-search').find('input[name=code]').val();
                var transaction_date = $('#form-search').find('input[name=transaction_date]').val();
                var company = $('#form-search').find('input[name=company]').val();
                var tenant_id = $('#form-search').find('input[name=tenant_id]').val();
                data.code = code;
                data.transaction_date = transaction_date;
                data.company = company;
                data.tenant_id = tenant_id;
            }
        },
        columnDefs:[
            {
                orderable: false,targets:[0]
            },
            { className: "text-right", targets: [6] },
            { className: "text-center", targets: [0,7,8] },
            {
                render: function (data, type, row) {
                        return `${row.code}</br> ${row.transaction_date}`
                },
                targets: [1]
            },
             {
                render: function (data, type, row) {
                        return `${row.tenant_name}</br> ${row.company_name}`
                },
                targets: [4]
            },
            {
                render: function (data, type, row) {
                        return `${row.start_date} s/d ${row.end_date}`
                },
                targets: [5]
            },
            
             {
                render: function (data, type, row) {
                     return `<p>Rp. `+ $.fn.dataTable.render.number( '.', ',', 0, ).display(row.price) +`</p>`
                },
                targets: [6]
            },
            {
            render: function (data, type, row) {
                    if (row.status == 1) {
                        return `<span class="badge badge-warning">Booking</span>`
                    }else if(row.status == 2) {
                        return `<span class="badge badge-success">Checkin</span>`
                    }else{
                        return `<span class="badge badge-danger">Checkout</span>`
                    }
                },targets: [7]
            },
            { render: function ( data, type, row ) {
                return `<div class="dropdown">
                    <button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{url('admin/checkin')}}/${row.id}"><i class="fas fa-info mr-3"></i> Detail</a></li>
                    </ul></div>`
            },targets: [8]
            }
        ],
        columns: [
            { data: "no" },
            { data: "code" },
            { data: "period_rent" },
            { data: "room_category" },
            { data: "tenant_name" },
            { data: "start_date" },
            { data: "price" },
            { data: "status" },
            { data: "id" },
        ]
    });
     $('input[name=transaction_date]').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
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
    $('#form-search').submit(function(e){
        e.preventDefault();
        dataTable.draw();
        $('#add-filter').modal('hide');
    })

    $(document).on('click','.delete',function(){
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
			title:'Anda yakin menghapus data ini?',
			message:'Data yg dihapus tidak dapat dikembalikan',
			callback: function(result) {
					if(result) {
						var data = {
                            _token: "{{ csrf_token() }}",
                            id: id
                        };
						$.ajax({
							url: `{{url('admin/tenant')}}/${id}`,
							dataType: 'json',
							data:data,
							type:'DELETE',
                            beforeSend: function () {
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
    })
})
</script>
@endpush
