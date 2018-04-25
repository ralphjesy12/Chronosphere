@extends('layouts.master')

@section('content')
    <div id="app" class="container fullheight bg-gray" style="    flex-direction: column; display: flex;">
        <header class="row align-items-start py-5">
            <div class="container">
                <p>
                    {{-- Header --}}
                </p>
            </div>
        </header>
        <div class="row align-items-center flex-1">
            <div class="col p-5">
                <div class="container">
                    <div class="row align-items-center px-3">
                        <div class="col-md-4 d-block">
                            <form class="ui form bg-gray-3 p-5 rounded" action="{{ route('login') }}" method="POST">
                                {{ csrf_field() }}

                                <div class="text-center">
                                    <small><b>LOGIN NOW</b></small>
                                    <h2 class="ui header mt-0">
                                        Sign in to Dashboard
                                    </h2>
                                </div>
                                <div class="ui divider">

                                </div>
                                <div class="field">
                                    <label class="">E-Mail Address</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                                </div>
                                @if ($errors->has('email'))
                                    <div class="ui error visible message">
                                        <div class="header">Oops!</div>
                                        <p>{{ $errors->first('email') }}</p>
                                    </div>
                                @endif
                                <div class="field">
                                    <label class="">Password</label>
                                    <input type="password" name="password" required >
                                </div>
                                @if ($errors->has('password'))
                                    <div class="ui error visible message">
                                        <div class="header">Oops!</div>
                                        <p>{{ $errors->first('password') }}</p>
                                    </div>
                                @endif
                                <button class="ui submit button btn-teal">Submit</button>
                            </form>
                        </div>
                        <div class="col-md-8">
                            <p>
                                &nbsp;
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="row align-items-end py-5">
            <div class="container">
                <p>
                    {{-- Footer --}}
                </p>
            </div>
        </footer>
    </div>
@endsection

@push('styles')
    <style>
    body{
            background: #414854;
    }
    </style>
@endpush
