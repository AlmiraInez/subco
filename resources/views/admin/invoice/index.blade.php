@extends('admin.layouts.app')

@section('title', 'Tagihan')
@section('stylesheets')
<link href="{{asset('adminlte/component/dataTables/css/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('adminlte/component/daterangepicker/daterangepicker.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('adminlte/component/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
@endsection

@push('breadcrump')
<li class="breadcrumb-item active">Tagihan</li>
@endpush

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-{{ config('configs.app_theme') }} card-outline">
                <div class="card-header">
                    <h3 class="card-title">Daftar Tagihan</h3>
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
                                <label class="control-label" for="uom_name">Tanggal Transaksi</label>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="description">Status Tagihan</label>
                                <select name="status" id="status" class="form-control select2" style="width: 100%"
                                aria-hidden="true">
                                    <option value="">All</option>
                                    <option value="1">Belum Dibayar</option>
                                    <option value="2">Lunas</option>
                                </select>
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
<script src="{{asset('adminlte/component/daterangepicker/moment.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/bootbox/bootbox.min.js')}}"></script>
<script src="{{asset('adminlte/component/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('adminlte/component/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script><script type="text/javascript">
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
        order: [[ 7, "asc" ]],
        ajax: {
            url: "{{route('invoice.read')}}",
            type: "GET",
            data:function(data){
                var code = $('#form-search').find('input[name=code]').val();
                var transaction_date = $('#form-search').find('input[name=transaction_date]').val();
                var company = $('#form-search').find('input[name=company]').val();
                var tenant_id = $('#form-search').find('input[name=tenant_id]').val();
                var status = $('#form-search').find('select[name=status]').val();
                data.code = code;
                data.transaction_date = transaction_date;
                data.company = company;
                data.status = status;
                data.tenant_id = tenant_id;
            }
        },
        columnDefs:[
            {
                orderable: false,targets:[0]
            },
            { className: "text-right", targets: [5] },
            { className: "text-center", targets: [0,6,7] },
            {
                render: function (data, type, row) {
                        return `${row.code}</br> ${row.invoice_date}`
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
                     return `<p>Rp. `+ $.fn.dataTable.render.number( '.', ',', 0, ).display(row.price) +`</p>`
                },
                targets: [5]
            },
            {
            render: function (data, type, row) {
                    if (row.payment_status == 1) {
                        return `<span class="badge badge-warning">Belum Dibayar</span>`
                    }else{
                        return `<span class="badge badge-success">Lunas</span>`
                    }
                },targets: [6]
            },
            
            { render: function ( data, type, row ) {
                return `<div class="dropdown">
                    <button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{url('admin/invoice')}}/${row.id}"><i class="fas fa-info mr-3"></i> Detail</a></li>
                    </ul></div>`
            },targets: [7]
            }
        ],
        columns: [
            { data: "no" },
            { data: "code" },
            { data: "period_amount" },
            { data: "room_category" },
            { data: "tenant_name" },
            { data: "price" },
            { data: "payment_status" },
            { data: "id" },
        ]
    });
    $('#form-search').submit(function(e){
        e.preventDefault();
        dataTable.draw();
        $('#add-filter').modal('hide');
    });
     $("#status").select2();
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
    $(document).on('click','.edit',function(){
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
        title:'Edit Leave Approval?',
        message:'You will be redirect to leave approval edit page, are you sure?',
        callback: function(result) {
          if(result) {
            document.location = "{{url('admin/transaction/approval')}}/"+id+"/editapproval";
          }
        }
      });
    });
})
</script>
@endpush
