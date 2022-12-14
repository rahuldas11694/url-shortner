@extends('layouts.app')

@section('content')
    <div class="col py-3">
        <form id="shortURL">
            <meta name="csrf-token" content="{{ csrf_token() }}" />
            <div class="mb-3">
                <label for="original-url" class="form-label">URL to Short</label>
                <input type="text" class="form-control" id="original-url" placeholder="Enter URL to short it">
            </div>
            <div class="mb-3" id="js-generated-short-url">
            </div>
            <div class="btn btn-primary" id="js-generate-short-url">Generate</button>
        </form>
    </div>
@endsection


