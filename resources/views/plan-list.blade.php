@extends('layouts.app')

@section('content')
    
    <div class="col py-3">

        @foreach($all_plans as $plan)
            <div class="card py-x" style="width: 18rem; float:left; height:13rem; margin:5px">
                <div class="card-body js-upgrade-plan">
                    <h5 class="card-title">{{$plan['plan_name']}}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">INR {{$plan['price']}} {{ $plan['plan_expiry_term'] == 'M' ? 'Monthly' : 'Yearly' }} </h6>
                    <p class="card-text">{{$plan['plan_desc']}}</p>
                    <a href="javascript:void(0)" class="card-link upgrade" data-planid="{{ $plan['id'] }}" > Click to Upgrade </a>
                </div>
            </div>
        @endforeach

    </div>

@endsection