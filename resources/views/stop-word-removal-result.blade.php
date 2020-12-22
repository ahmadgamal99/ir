@extends('layout')
@section('content')
    <div>
        <h1 class="text-center pt-25 font-weight-bolder">Stop Word Removal</h1>
    <div class="flex-center p-35">
        <table class="table table-dark rounded p-10 text-center" style="">
            <thead>
            <tr>
                <th>Term</th>
                <th>Doc ID</th>
            </tr>
            </thead>
            <tbody>
       @foreach($tokens as $token)

        @foreach($token as $term => $docID)
            <tr>
                <td>{{$term}}</td>
                <td>{{$docID}}</td>
            </tr>
        @endforeach
        @endforeach
            </tbody>
        </table>

    </div>
    </div>

    <div class="mx-auto">
            <a href="/inverted-index" class="btn btn-primary shadow-lg font-size-h1 mx-auto text-center mb-20" type="submit" style="width:fit-content">Inverted Index</a>
    </div>
@endsection
