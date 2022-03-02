@extends('main')
@section('content')
<div class="ml-12 text-lg leading-7 font-semibold">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>
                        {{ __('Welcome') }} {{ request()->session()->get('user')->full_name }}
                    </h2>
                </div>
                
            </div>
        </div>
    </div>  
</div>
@endsection