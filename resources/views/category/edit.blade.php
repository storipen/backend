@extends('layouts.home')

@section('content')
@section('script')
<script>
    window.addEventListener('load', function() {
        $('select').selectpicker();
    })
</script>
@endsection
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add Category') }}</div>

                <div class="card-body">
                @if (session('alert'))
                    <div class="alert alert-danger">
                        {{ session('alert') }}
                    </div>
                @endif
                    <form method="post" enctype="multipart/form-data" action="{{ url('/categoryUpdate') }}">
                        {{csrf_field()}}
                        <div class="form-group row">
                        <input type="hidden" class="form-control" id="staticEmail" value="{{$category['id']}}" name="id">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Code</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="staticEmail" value="{{$category['code']}}" name="code" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Title</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="staticEmail" value="{{$category['title']}}" name="title">
                            </div>
                        </div>
                        
                        
                       
                        
                        <div class="float-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-btn fa-sign-in"></i> Lanjut
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection