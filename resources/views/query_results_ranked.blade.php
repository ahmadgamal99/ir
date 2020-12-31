@extends('layout')
@section('content')
    <div>
        <h1 class="text-center pt-25 font-weight-bolder">@if(count($rankedDocs) > 0)Search Result ( with ranking) @endif</h1>
        <div class="p-35 text-left d-block">
        @forelse($rankedDocs as $docIndex => $similarity)
            <div>
            <a href="/file/{{$docIndex}}" target="_blank" class="font-weight-bolder font-size-h1" style="text-decoration:underline">
                {{'file ' . $docIndex }}
            </a><small class="ml-5 font-weight-bolder" style="margin-bottom:15px">{{' ( similarity  ' . $similarity * 100 . ' % )'}}</small>

            </div>
        @empty
                <div class="text-center">
                    <a class="font-weight-bolder font-size-h1">
                       Unfortunately No Results Found
                    </a>

                </div>
        @endforelse
        </div>
    </div>

@endsection
