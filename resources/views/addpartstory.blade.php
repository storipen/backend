@extends('layouts.home')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add Part Story') }}</div>

                <div class="card-body">
                    <form method="post" enctype="multipart/form-data" action="{{ url('/savepartstory') }}">
                        {{csrf_field()}}
                        <div class="col-sm-6">
                            <input type="hidden" class="form-control" id="staticEmail" value="{{$id}}" name="idstory">
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Title</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="staticEmail" value="" name="title">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Sub Title</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="staticEmail" value="" name="subtitle">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label">Image</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control-file" id="exampleFormControlFile1" name="image">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label">Content</label>
                            <div class="col-sm-10">
                                <textarea class="ckeditor form-control" name="content"></textarea>
                            </div>
                        </div>
                        <div class="float-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-btn fa-sign-in"></i> save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script type="text/javascript" defer>
    if (document.getElementById("ckeditor")) {
        CKEDITOR.replace("ckeditor");
    }
</script>
@endsection