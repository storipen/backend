@extends('layouts.home')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Part Story') }}</div>

                <div class="card-body">
                    <form method="post" enctype="multipart/form-data" action="{{ url('/updatepartstory') }}">
                        {{csrf_field()}}

                        <div class="form-group row">
                            <input type="hidden" class="form-control" id="staticEmail" value="{{$partStory['id']}}" name="id">
                            <input type="hidden" class="form-control" id="staticEmail" value="{{$partStory['idstory']}}" name="idstory">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Title</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="staticEmail" value=" {{$partStory['titlePart']}}" name="title">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label">Image</label>

                            <div class="col-sm-10">
                                <!-- @ifNull
                                <img class="img-thumbnail" src="{{URL::to('/images').'/'. $partStory['thumbnail']}}" alt="" width=" 90" height="70" />
                                @end -->
                                <input type="file" class="form-control-file" id="exampleFormControlFile1" name="image">
                                <input type="hidden" class="form-control-file" id="exampleFormControlFile1" name="imageold" value=" {{$partStory['thumbnail']}}">
                            </div>
                        </div>
                        <div class="form-group row">

                            <label for="inputPassword" class="col-sm-2 col-form-label">Content</label>
                            <div class="col-sm-10">
                                <textarea class="ckeditor form-control" name="content">{{$partStory['content']}}</textarea>
                            </div>
                        </div>
                        <div class="float-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-btn fa-sign-in"></i> Update
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