@extends('admin.layouts.empty')
@section('title', 'Login')
@section('class', 'login-page')

@section('stylesheets')
<style type="text/css">
  .login-page,
  .register-page {
    background: white;
  }

  .login-section-wrapper {
    display: flex;
    flex-direction: column;
    padding-right: 100px;
    padding-left: 100px;
  }

  .login-img {
    width: 100%;
    height: 100vh;
    object-fit: cover;
    object-position: left;
  }

  @media screen and (max-width: 991px) {
    .login-section-wrapper {
      padding-right: 50px;
      padding-left: 50px;
    }
  }
</style>
@endsection
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-5 login-section-wrapper my-auto">
      <div class="brand-wrapper" >
        <img src="{{ asset(config('configs.app_logo')) }}" style="height: 500px; width:500px;" alt="brand-image" class="img-fluid">
        <h5>Login</h5>
      </div>
      <div class="login-wrapper">
        <form action="{{route('admin.login.post')}}" method="post" autocomplete="off">
          @csrf
          <div class="form-group">
            <div class="input-group">
              <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" autofocus placeholder="{{ __('E-Mail') }}">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
              {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
              {!! $errors->first('password', '<div class="invalid-feedback">:message</div>') !!}
            </div>
          </div>
          <div class="row d-flex h-100">
            <div class="col-sm-6 align-self-center">
              <a href="{{route('admin.register')}}" class="text-{{ config('configs.app_theme') }}">Register?</a>
            </div>
            <div class="col-sm-6">
              <button type="submit" class="btn btn-dark float-right">Login</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="col-sm-7 px-0 d-none d-sm-block">
      <img src="{{ asset('img/subco.jpg') }}" alt="login image" class="login-img">
    </div>
  </div>
</div>
@endsection