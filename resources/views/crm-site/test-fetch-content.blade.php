@extends('layouts.app')

@section('content')

    @forelse(json_decode($projectsContent) as $content)
        @if($content->type == 'text')
        {{$content->title}}
        {!!$content->description!!}
        @else
        <img src="https://ob.customerr.nl{{$content->image_path}}" alt="">
        @endif
    @empty
        none
    @endforelse
@endsection