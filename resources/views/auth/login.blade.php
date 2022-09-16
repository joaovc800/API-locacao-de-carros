@extends('layouts.app')

@section('content')
    <login-component token="{{@csrf_token()}}"></login-component>
@endsection
