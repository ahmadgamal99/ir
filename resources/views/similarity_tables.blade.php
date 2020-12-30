@extends('layout')
@section('content')
    <div>
        <h1 class="text-center pt-25 font-weight-bolder">Similarity Tables</h1>
    <div class="flex-center p-35 d-block">
        @foreach($similarity_tables as $item)
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

               <tr>
                   <td>{{$item['term']}}</td>
                   <td>{{$item['tf']}}</td>
                   <td>{{$item['tf-weight']}}</td>
                   <td>{{$item['tf-idf']}}</td>
                   <td>{{$item['normalize']}}</td>

               </tr>

            </tbody>
        </table>
        @endforeach
    </div>
    </div>

    <div class="mx-auto">
            <a href="/similarity" class="btn btn-primary shadow-lg font-size-h1 mx-auto text-center mb-20" type="submit" style="width:fit-content">Similarity</a>
    </div>

@endsection
