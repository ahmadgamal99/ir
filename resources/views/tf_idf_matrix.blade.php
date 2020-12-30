@extends('layout')
@section('content')
    <div>
        <h1 class="text-center pt-25 font-weight-bolder">TF IDF Matrix</h1>
    <div class="flex-center p-35">
        <table class="table table-dark rounded p-10 text-center" style="">
            <thead>
            <tr>
                <th>Term</th>
                <th>File 1</th>
                <th>File 2</th>
                <th>File 3</th>
                <th>File 4</th>
                <th>File 5</th>
                <th>File 6</th>
                <th>File 7</th>
                <th>File 8</th>
                <th>File 9</th>
                <th>File 10</th>

            </tr>
            </thead>
            <tbody>
           @foreach($tf_Idf_Matrix as $term => $docs)
               <tr>
                   <td>{{$term}}</td>
                   @foreach( $docs as $tfIDF)
                   <td>{{$tfIDF}}</td>
                   @endforeach
               </tr>
            @endforeach
            </tbody>
        </table>

    </div>
    </div>

    <div class="mx-auto">
            <a href="/idf" class="btn btn-primary shadow-lg font-size-h1 mx-auto text-center mb-20" type="submit" style="width:fit-content">Similarity</a>
    </div>
@endsection
