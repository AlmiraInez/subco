@extends('admin.layouts.app')

@section('title', 'Detail Formula ')
@section('stylesheets')
@endsection

@push('breadcrump')
<li class="breadcrumb-item"><a href="{{route('formula.index')}}">Formula</a></li>
<li class="breadcrumb-item active">Detail</li>
@endpush

@section('content')
{{-- <div class="wrapper wrapper-content"> --}}
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
                  <br>
                  {{$formula->name}}
                  {{-- <input type="text" class="form-control" name="name" id="name" value="" placeholder="Nama" required> --}}
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
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-default" title="Kembali"><i
                class="fa fa-reply"></i></a>
          </div>
        </div>
        <div class="card-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Status <b class="text-danger">*</b></label>
                  <br>
                  @if($formula->status == 1)
                  <span class="badge badge-success">Active</span>
                  @else
                  <span class="badge badge-warning">Non Active</span>
                  @endif
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
                <table class="table table-bordered table-striped" id="table-formula">
                    <thead>
                        <th width="100">Operator</th>
                        <th width="250">Nilai</th>
                        <th width="100">ID</th>
                        <th width="100">Operator</th>
                    </thead>
                    <tbody>
                        @foreach ($formula->detail as $detail)
                        @if($detail->answer)
                            @if($detail->answer->question)
                            <tr>
                            <td class="text-center">{{$detail->operation_before}}</td>
                            <td>{{$detail->answer->question->description.' - '.$detail->answer->description}}</td>
                            <td>{{$detail->value}}</td>
                            <td class="text-center">{{$detail->operation}}</td>
                            </tr>
                            @endif
                        @else
                            <tr>
                            <td class="text-center">{{$detail->operation_before}}</td>
                            <td>{{$detail->value}}</td>
                            <td>-</td>
                            <td class="text-center">{{$detail->operation}}</td>
                            </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
                <b><p class="form-control-static">{{$formula->calculate}} =</p></b>
            </div>
            <div class="overlay d-none">
            <i class="fa fa-2x fa-sync-alt fa-spin"></i>
            </div>
            
        </div>
    </div>
  </div>

  
  @endsection

  