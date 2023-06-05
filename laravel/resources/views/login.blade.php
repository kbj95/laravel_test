@extends('layout.layout')

@section('title', 'Login')

@section('contents')
    @include('layout.errors')
    <form action="{{route('user.loginpost')}}" method="post">
        @csrf
        <label for="email">Email : </label>
        <input type="text" id="email" name="email">
        <br>
        <label for="password">PW : </label>
        <input type="text" id="password" name="password">
        <br>
        <button type="submit">LOGIN</button>
    </form>
@endsection