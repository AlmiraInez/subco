@extends('admin.layouts.app')

@section('title', 'Absen Karyawan')

@push('breadcrump')
<li class="breadcrumb-item active">Attendances</li>
@endpush

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-{{ config('configs.app_theme') }} card-outline">
                <div class="card-header">
                    <h3 class="card-title">Absen karyawan</h3>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2 class="text-muted"><b><i>{{ date('l, d M Y', strtotime(now())) }}</i></b></h2>
                                <div class="p-4 qr-code">
                                    {!! $qrCode !!}
                                </div>
                            </div>
                            <div class="col-lg-4"></div>
                            <div class="col-lg-4">
                                <h5>Gunakan kode di bawah ini jika anda mengalami kendala dalam mesin scan</h5>
                                <h3 class="code">{{ $code }}</h3>
                            </div>
                            <div class="col-lg-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form hidden method="post" name="generate-qr" class="generate-qr" action="{{ route('attendances.store') }}">
    @csrf
    @method('post')
</form>

@endsection

@push('scripts')
<script type="text/javascript">
    function filter(){
		$('#add-filter').modal('show');
	}

    $(function() {
        setInterval(function() {
            // $('.generate-qr').submit(function(event) {
                $.ajax({
                    url: $('.generate-qr').attr('action'),
                    data: $('.generate-qr').serialize(),
                    method: 'post'
                })
                .done(function(response) {
                    // $('.code').html(response);
                    $('.qr-code').empty();
                    $('.qr-code').html(`${response.qrCode}`);
                    $('.code').html(`${response.code}`);
                    console.log(response);
                })
                .fail(function() {
                    $('.code').html('kentod1');
                    console.log("error");
                });
            // });
        }, 5000);
    });
</script>
@endpush