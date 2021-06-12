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
                <div class="card-header">{{ __('Add Banner') }}</div>

                <div class="card-body">
                    <form method="post" enctype="multipart/form-data" action="{{ url('/savebanner') }}">
                        {{csrf_field()}}
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Title</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="staticEmail" value="" name="title">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label">description</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label">Image</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control-file" id="exampleFormControlFile1" name="image">
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