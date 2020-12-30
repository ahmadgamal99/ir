@extends('layout')
@section('content')
    <div>
        <h1 class="text-center pt-25 font-weight-bolder">Similarity Tables</h1>
    <div class="flex-center p-35 d-block">
        @foreach($similarityTables as $key => $item)
        <table class="table table-dark rounded p-10 text-center" style="">
            <thead>
            <tr>
                <th>Term</th>
                <th>TF</th>
                <th>TF-Weight</th>
                <th>Tf - IDF</th>
                <th>Normalize</th>
            </tr>
            </thead>
            <tbody>
                @foreach($item as $term => $value)
                    <tr>
                        <td>{{$term}}</td>
                        <td>{{$value['tf']}}</td>
                        <td>{{$value['tfWeight']}}</td>
                        <td>{{$value['tf-idf']}}</td>
                        <td>{{$value['normalize']}}</td>

                    </tr>
                @endforeach


            </tbody>
        </table>
        @endforeach
    </div>
    </div>

    <div class="mx-auto">
            <a href="/similarity" class="btn btn-primary shadow-lg font-size-h1 mx-auto text-center mb-20" type="submit" style="width:fit-content">Similarity</a>
    </div>

@endsection
