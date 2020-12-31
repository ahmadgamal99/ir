@extends('layout')
@section('content')
    <div>
        <h1 class="text-center pt-25 font-weight-bolder">{{ ' File ' . $file_no . ' Content' }}</h1>
        <div class="p-35 text-left d-block">

            <div>
            <p  class="font-weight-bolder font-size-h1" >{{ $fileContent }}</p>
            </div>

        </div>
    </div>


@endsection
