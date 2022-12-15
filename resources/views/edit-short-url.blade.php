@extends('layouts.app')

@section('content')

    <div class="col py-3">
        <form id="editShortURL" method="post">
            {{ csrf_field() }}

            <div class="mb-3">
                <div>
                    <label for="original-url" class="form-label">Edit short url</label>
                </div>
                <div style="display: inline-block; width: 100%; text-align: left;">
                    <label>{{$short_url_domain}}</label>
                    <input type="text" name="short_url" class="form-control" id="short-url" placeholder="Short URL" value="{{$data['short_url']}}">
                </div>
            </div>
            <div class="mb-3" id="">
            </div>
            <button type="submit" class="btn btn-primary" id="js-edit-short-url">Save</button>
        </form>
    </div>
    
@endsection
