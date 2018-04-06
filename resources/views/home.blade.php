@extends('layouts.master')

@section('content')
    <div id="app" class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 pt-10">
                <h1 class="ui header">Dashboard</h1>
                @verbatim
                    <dashboard></dashboard>
                    <passport-clients></passport-clients>
                    <passport-authorized-clients></passport-authorized-clients>
                    <passport-personal-access-tokens></passport-personal-access-tokens>
                @endverbatim
            </div>
        </div>
    </div>
@endsection
