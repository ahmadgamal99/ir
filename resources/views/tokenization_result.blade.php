@extends('layout')
@section('content')
    <div>
        <h1 class="text-center pt-25 font-weight-bolder">Tokenization</h1>
    <div class="flex-center p-35">
        <table class="table table-dark rounded p-10 text-center" style="">
            <thead>
            <tr>
                <th>Term</th>
                <th>Doc ID</th>
            </tr>
            </thead>
            <tbody>
           @foreach($tokensWithDocID as $item)
               <tr>
                   <td>{{$item['token']}}</td>
                   <td>{{$item['docID']}}</td>
               </tr>
            @endforeach
            </tbody>
        </table>

    </div>
    </div>

    <div class="mx-auto">
            <a href="/Stop-word-removal" class="btn btn-primary shadow-lg font-size-h1 mx-auto text-center mb-20" type="submit" style="width:fit-content">Stop word removal</a>
    </div>
@endsection
