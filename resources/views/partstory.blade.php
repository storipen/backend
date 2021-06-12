@extends('layouts.home')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Part Story') }}</div>

                <div class="card-body">

                    <div class="container">
                        <a class="btn btn-primary" href="{{ url('/addpartStory/'.$id) }}" role="button">Add</a>
                        <h2></h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <!-- <th scope="col">#</th> -->
                                    <th scope="col">title</th>
                                    <th scope="col">content</th>
                                    <th scope="col">view</th>
                                    <th scope="col">image</th>
                                    <th scope="col">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($partStory as $value)

                                <tr>
                                    <!-- <th scope="row">{{ $loop->iteration }}</th> -->
                                    <td>{{ $value['titlePart'] }}</td>
                                    <td>{{ $stringCut = substr($value['content'] , 0, 100)}}</td>
                                    <td>{{ $value['countView'] }}</td>
                                    <td><img class="img-thumbnail" src="{{URL::to('/images').'/'. $value['imageHeader']}}" alt="" width=" 90" height="70" /></td>
                                    <td>
                                        <a class="btn btn-link" href="{{ url('/editpartstory/'.$value['id']) }}" role="button">edit</a>
                                        <a class="btn btn-link" href="{{ url('/deletePartStory/'.$value['idstory']) .'/'.$value['id']}}" role="button">delete</a>
                                    </td>
                                </tr>

                                @endforeach

                            </tbody>

                        </table>
                        <nav aria-label="Page navigation example">
                            {{$partStory->links()}}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection