@extends('layout')
@section('content')
    <div>
        <h1 class="text-center pt-25 font-weight-bolder">Search Result ( without ranking)</h1>
        <div class="p-35 text-left d-block">
        @foreach( $relevantDocs as $doc)
            <div>
            <a href="/file/{{$doc}}" target="_blank" class="font-weight-bolder font-size-h1" style="text-decoration:underline">{{'file ' . $doc}}</a>
            </div>
        @endforeach
        </div>
    </div>

    <div class="mx-auto">
        <a href="/term_frequency_matrix" class="btn btn-primary shadow-lg font-size-h1 mx-auto text-center mb-20" type="submit" style="width:fit-content">Term Frequency</a>
    </div>

@endsection
