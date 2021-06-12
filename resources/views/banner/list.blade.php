@extends('layouts.home')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Banner') }}</div>

                <div class="card-body">

                    <div class="container">
                        <a class="btn btn-primary" href="{{ url('/addbanner') }}" role="button">Add</a>
                        <h2></h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">title</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">image</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($databanner as $value)
                                <tr>
                                    <th scope="row">{{ $value['id'] }}</th>
                                    <td>{{ $value['title'] }}</td>
                                    <td>{{
                                        $stringCut = substr($value['description'] , 0, 100)
                                         }}</td>
                                         <td><img class="img-thumbnail" src="{{$value['url']}}" alt="" width=" 90" height="70" /></td>
                                      <td>
                                        <a class="btn btn-link" href="{{ url('/deletebanner/'.$value['id']) }}" role="button">delete</a>
                                    </td>   
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <nav aria-label="Page navigation example">
                            {{$databanner->links()}}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
</style>
@endsection