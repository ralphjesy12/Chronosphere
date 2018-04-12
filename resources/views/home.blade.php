@extends('layouts.master')

@section('content')
    <div id="app" class="container">
        <div class="row pt-10">
            <div class="col-md-2">
                <div class="ui vertical menu">
                    <div class="item">
                        <div class="ui input"><input type="text" placeholder="Search..."></div>
                    </div>
                    <router-link to="/dashboard" class="item"><i class="grid layout icon"></i> Dashboard</router-link>
                    <div class="item">
                        Home
                        <div class="menu">
                            <a class="active item">Search</a>
                            <a class="item">Add</a>
                            <a class="item">Remove</a>
                        </div>
                    </div>
                    <a class="item">
                        Messages
                    </a>
                    <div class="ui dropdown item">
                        Account
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <a class="item"><i class="user icon"></i> Profile Settings</a>
                            <a class="item"><i class="settings icon"></i> Account Preferences</a>
                            <a href="{{ route('logout') }}" class="item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="sign out icon"></i> Sign Out
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                <router-view></router-view>
            </div>
        </div>
    </div>
@endsection
