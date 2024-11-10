@extends('master')

@section('content')

<a href="{{route('home')}}">Home</a>

<h2>Login</h2>

<form action="{{route('login.store')}}"method="post">
    <input type="text" name="email" value="test@example.com">
    <input type="password" name="password" value="$2y$12$lnuDstNXJn8ijSc/HCBni.YrgXAwlmrxDzfyodFm7zx...">
    <button type="submit" >Login</button>
</form>

@section()

