@extends('layouts.home')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add Recommended') }}</div>

                <div class="card-body">
                <form class="form-inline" method="GET">
                <div class="form-group mb-2">
                    <label for="filter" class="col-sm-2 col-form-label">Filter</label>
                    <input type="text" class="form-control" id="filter" name="filter" placeholder="Product name..." value="{{$filter}}">
                </div>
                <button type="submit" class="btn btn-default mb-2">Filter</button>
                </form>
                <table class="table table-bordered table-hover">
                    <thead>
                        <th>title</th>
                        <th>sinopsis</th>
                        
                        <th>Actions</th>
                    </thead>
                    <tbody>
                        @if ($story->count() == 0)
                        <tr>
                            <td colspan="5">No products to display.</td>
                        </tr>
                        @endif

                        @foreach ($story as $value)
                        <tr>
                            <td>{{ $value->title }}</td>
                            <td>{{$stringCut = substr($value['sinopsis'] , 0, 100) }}</td>
                            <td>
                            <form style="display:inline-block" action="{{ route('save-recommended', ['storyId'=>$idRecomended,'id' => $value->id]) }}"  method="POST">
                                @method('POST')
                                @csrf
                                <button class="btn btn-sm btn-primary"> Add</button>
                            </form>
                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                {{$story->links()}}

                <p>
                    Displaying {{$story->count()}} of {{ $story->total() }} story(s).
                </p>

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