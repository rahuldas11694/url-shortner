@extends('layouts.app')

@section('content')

    <div class="col py-3">
	    <div class="container">
	        <div class="table-wrapper">
	            
	            <div class="table-title">
	                <div class="row">
	                    <div class="col-sm-6">
							<h2>Shortened URLs</h2>
						</div>
						{{-- <div class="col-sm-6">
							<a href="/home" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Generate New Short URL</span></a>					
						</div> --}}
	                </div>
	            </div>

	            <table class="table table-striped table-hover">
	                <thead>
	                    <tr>
	                        <th>Short URL</th>
	                        <th>Long URL</th>
	                        <th>Actions</th>
	                    </tr>
	                </thead>
	                <tbody>
	                	@foreach($short_urls as $su)
	                    <tr>
	                        <td>{{ $short_url_domain ."". $su['short_url']}}</td>
	                        <th>{{$su['og_url']}}</th>
	                        <td class="actions js-short-url-list">
	                            
	                            <a href="/short-url/edit/{{$su['id']}}" class="edit" data-action="edit" data-short-url-id="{{$su['id']}}"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>

	                            <a href="javascript:void(0)" class="disable" data-action="disable" data-short-url-id="{{$su['id']}}"><i class="material-icons" data-toggle="tooltip" title="Disable">&#xE254;</i></a>

	                            <a href="javascript:void(0)" class="delete" data-action="delete" data-short-url-id="{{$su['id']}}"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>

	                        </td>
	                    </tr>
	                    @endforeach
	                    @if(empty($short_urls))
	                    	<tr><td colspan="3">No Data Found</td></tr>
	                    @endif
	                </tbody>
	            </table>

				{{-- <div class="clearfix">
	                <div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
	                <ul class="pagination">
	                    <li class="page-item disabled"><a href="#">Previous</a></li>
	                    <li class="page-item"><a href="#" class="page-link">1</a></li>
	                    <li class="page-item"><a href="#" class="page-link">2</a></li>
	                    <li class="page-item active"><a href="#" class="page-link">3</a></li>
	                    <li class="page-item"><a href="#" class="page-link">4</a></li>
	                    <li class="page-item"><a href="#" class="page-link">5</a></li>
	                    <li class="page-item"><a href="#" class="page-link">Next</a></li>
	                </ul>
	            </div> --}}

	        </div>
	    </div>
    </div>
    
@endsection
