@extends('admin.layouts.app')

@section('title', 'Detail Kpi')
@section('stylesheets')
<link rel="stylesheet" href="{{asset('adminlte/component/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
<style>
.direct-chat-img {
      object-fit: contain;
      border:1px #d2d6de solid;
  }
  .direct-chat-messages {
    height: 400px !important;
  }
  .direct-chat-text{
    margin-right: 20% !important;
  }
  .right .direct-chat-text {
    margin-right: 10px !important;
    float: right;
  }
  .checkbox input[type="checkbox"], .checkbox-inline input[type="checkbox"], .radio input[type="radio"], .radio-inline input[type="radio"] {
      position: unset;
      margin-left: 0;
  }
  .dot-typing {
    position: relative;
    left: -9999px;
    width: 8px;
    height: 8px;
    border-radius: 5px;
    background-color: #444;
    color: #444;
    box-shadow: 9984px 0 0 0 #444, 9999px 0 0 0 #444, 10014px 0 0 0 #444;
    animation: dotTyping 1.5s infinite linear;
  }

  @keyframes dotTyping {
    0% {
      box-shadow: 9984px 0 0 0 #444, 9999px 0 0 0 #444, 10014px 0 0 0 #444;
    }
    16.667% {
      box-shadow: 9984px -10px 0 0 #444, 9999px 0 0 0 #444, 10014px 0 0 0 #444;
    }
    33.333% {
      box-shadow: 9984px 0 0 0 #444, 9999px 0 0 0 #444, 10014px 0 0 0 #444;
    }
    50% {
      box-shadow: 9984px 0 0 0 #444, 9999px -10px 0 0 #444, 10014px 0 0 0 #444;
    }
    66.667% {
      box-shadow: 9984px 0 0 0 #444, 9999px 0 0 0 #444, 10014px 0 0 0 #444;
    }
    83.333% {
      box-shadow: 9984px 0 0 0 #444, 9999px 0 0 0 #444, 10014px -10px 0 0 #444;
    }
    100% {
      box-shadow: 9984px 0 0 0 #444, 9999px 0 0 0 #444, 10014px 0 0 0 #444;
    }
  }
</style>
@endsection

@push('breadcrump')
<li class="breadcrumb-item active"><a href="{{route('account.index')}}">Kpi</a></li>
<li class="breadcrumb-item active">Detail</li>
@endpush

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card card-{{ config('configs.app_theme') }} card-outline direct-chat direct-chat-primary">
      <div class="card-header">
        <h3 class="card-title">Detail KPI</h3>
        {{-- <div class="pull-right card-tools">
          <button form="form" type="submit" class="btn btn-sm btn-{{ config('configs.app_theme') }}" title="Simpan"><i
              class="fa fa-save"></i></button>
          <a href="{{ url()->previous() }}" class="btn btn-sm btn-default" title="Kembali"><i
              class="fa fa-reply"></i></a>
        </div> --}}
      </div>
      <div class="card-body">
        <div class="direct-chat-messages">
          @foreach (json_decode($bot->description) as $conversation)
          <div class="{{$conversation->class}}" id="{{@$conversation->id}}" @if(@$conversation->style) style="{{$conversation->style}}" @endif>
            {!!$conversation->text!!}
          </div>
          @endforeach
        </div>
      </div>
      <div class="overlay d-none">
        <i class="fa fa-2x fa-sync-alt fa-spin"></i>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
    $(document).ready(function(){
        $(".direct-chat-messages").stop().animate({ scrollTop: $(".direct-chat-messages")[0].scrollHeight}, 1000); 
    });
</script>
@endpush
@endsection