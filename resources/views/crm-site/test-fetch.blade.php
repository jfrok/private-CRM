@extends('layouts.app')

@section('content')

@forelse(json_decode($projects) as $project)
    {{$project->title}}
    <a href="{{route('test-fetch-content',$project->id)}}">show</a>
    <img src="https://ob.customerr.nl{{$project->thumbnail}}" alt="">
@empty
none
@endforelse
@endsection