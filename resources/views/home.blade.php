@extends('layouts.master')

@section('content')
    <div id="app" class="container">
        <div class="row pt-5">
            <div class="col-md-3">
                <div class="ui vertical menu w-auto">
                    <div class="item p-4">
                        <div class="ui list">
                            <div class="item">
                                <img class="ui avatar image" src="https://semantic-ui.com/images/avatar2/small/matthew.png" style=" height: 4em; width: 4em;">
                                <div class="middle aligned content pt-3 ">
                                    <a class="header">RALPH GALINDO</a>
                                    <div class="description">Web Developer </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <router-link to="/dashboard" class="item"><i class="grid layout icon"></i> Dashboard</router-link>
                    <router-link to="/projects" class="item"><i class="server icon"></i> Projects</router-link>


                    <div class="item"><i class="user icon"></i>
                        <div class="header">Account</div>
                        <div class="menu">
                            <a class="item">Preferences</a>
                            <a href="{{ route('logout') }}" class="item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Sign Out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-9">
                <router-view></router-view>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js" charset="utf-8"></script>
@endpush
