@extends('layout')
@section('content')
    <div>
        <h1 class="text-center pt-25 font-weight-bolder">Positional Index Model</h1>
    <div class="flex-center p-35">
        <table class="table table-dark rounded p-10 text-center" style="">
            <thead>
            <tr>
                <th>Term</th>
                <th>Freq</th>
                <th>Occurrences</th>
            </tr>
            </thead>
            <tbody>
       @foreach($positionalIndex as $item)


            <tr>
                <td>{{$item['term']}}</td>
                <td>{{$item['frequency']}}</td>
                <td>
                    @foreach($item['positions'] as $docID => $occurrences)
                    {{$docID . ' => ' . implode(', ', $occurrences)}}<br>
                    @endforeach
                </td>
            </tr>

        @endforeach
            </tbody>
        </table>

    </div>
    </div>

    <div class="mx-auto">
        <a href="/do-query" class="btn btn-primary shadow-lg font-size-h1 mx-auto text-center mb-20" type="submit" style="width:fit-content">Do A Query</a>
    </div>

@endsection
