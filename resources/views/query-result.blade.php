@extends('layout')
@section('content')
<img src="logo.png" style="height:100px;width:300px"  class="mx-auto mt-40">

<div class="flex-center full-height">

    <input type="text" class="input-group m-15 p-5 rounded shadow text-center" placeholder="enter a text to search">

</div>l;0
<form method="POST" action="/do-query" class="mx-auto pb-48">
    @csrf
    <button class="btn btn-primary shadow-lg font-size-h1">Search</button>
</form>
@endsection
