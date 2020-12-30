@extends('layout')
@section('content')
    <div>
        <h1 class="text-center pt-25 font-weight-bolder">Inverse Document Frequency</h1>
    <div class="flex-center p-35">
        <table class="table table-dark rounded p-10 text-center" style="">
            <thead>
            <tr>
                <th>Term</th>
                <th>Document Frequency</th>
                <th>Inverse Document Frequency</th>
            </tr>
            </thead>
            <tbody>
           @foreach($docFrequencies as $term)
               <tr>
                   <td>{{$term['term']}}</td>
                   <td>{{$term['df']}}</td>
                   <td>{{$term['idf']}}</td>
               </tr>
            @endforeach
            </tbody>
        </table>

    </div>
    </div>

    <div class="mx-auto">
            <a href="/tf-idf-matrix" class="btn btn-primary shadow-lg font-size-h1 mx-auto text-center mb-20" type="submit" style="width:fit-content">TF IDF matrix </a>
    </div>
@endsection
