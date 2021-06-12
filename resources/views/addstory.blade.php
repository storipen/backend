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
                <div class="card-header">{{ __('Add Story') }}</div>

                <div class="card-body">
                    <form method="post" enctype="multipart/form-data" action="{{ url('/savestory') }}">
                        {{csrf_field()}}
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Title</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="staticEmail" value="" name="title">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Author</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="staticEmail" value="" name="author">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Tag line</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="staticEmail" value="" name="tagline">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label">sinopsis</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="sinopsis"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label">Image</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control-file" id="exampleFormControlFile1" name="image">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label">Category</label>
                            <div class="col-sm-10">
                                <div class="dropdown">
                                    <select id="multiselect" multiple="multiple" name="category[]">
                                        @foreach($category as $value)
                                        <option value="{{$value['code']}}">{{$value['title']}}</option>

                                        @endforeach
                                    </select>
                                </div>
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

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" type="text/css"/>

<script type="text/javascript" defer>
 $('#multiselect').multiselect({
            buttonWidth: '300px'
        });
</script>

@endsection