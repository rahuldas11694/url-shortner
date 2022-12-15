@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center py-10">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Welcome to URL Shortning App') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('Please Login/Register to start Shortning the URLs!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
