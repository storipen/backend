@extends('layouts.home')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Category') }}</div>

                <div class="card-body">

                    <div class="container">
                        <a class="btn btn-primary" href="{{ url('/addcategory') }}" role="button">Add</a>
                        <h2></h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">title</th>
                                    
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category as $value)
                                <tr>
                                    <th scope="row">{{ $value['code'] }}</th>
                                    <td>{{ $value['title'] }}</td>
                                    
                                      <td>
                                        <a class="btn btn-link" href="{{ url('/category/'.$value['id']) }}" role="button">edit</a>
                                    </td>   
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <nav aria-label="Page navigation example">
                            {{$category->links()}}
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