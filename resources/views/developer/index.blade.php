@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <passport-authorized></passport-authorized>
            <passport-clients></passport-clients>
            <passport-tokens></passport-tokens>

        </div>
    </div>
</div>
@endsection
