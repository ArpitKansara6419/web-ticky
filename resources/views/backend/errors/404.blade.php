@extends('layouts.app')

@section('content')
    <div class="container w-full">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">404 - Page Not Found</div>

                    <div class="card-body">
                        <p class="card-header">{{$reasonMessage}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection