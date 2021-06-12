@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form action="{{url('/v1/login')}}" method="post">

                        <h4 class="modal-title">Login to Your Account</h4>
                        <input type="hidden" name="_token" value="<?php echo csrf_token() ?>">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Username" required="required" name="username">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" placeholder="Password" required="required" name="password">
                        </div>

                        <input type="submit" class="btn btn-primary btn-block btn-lg" value="Login">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection