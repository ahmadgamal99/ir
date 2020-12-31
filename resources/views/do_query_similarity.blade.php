@extends('layout')
@section('content')
<img src="logo.png" style="height:100px;width:300px"  class="mx-auto mt-40">

<div class="flex-center full-height">

    <form method="POST" action="/do-query-similarity" class="mx-auto pb-48">
        @csrf

        <input type="text" name="queryInput" style="width:700px" class="m-15 p-5 rounded shadow text-center" placeholder="enter a text to search">

        <button class="btn btn-primary shadow-lg font-size-h1">Search</button>
    </form>
</div>

@endsection
