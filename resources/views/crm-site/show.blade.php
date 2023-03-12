
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
    @forelse($showResulat as $show)


        <div class="col s12 m4">
            <div class="card">
                <div class="card-image">
                    <img src="https://ob.customerr.nl/{{$show->thumbnail}}">
                    <a href="{{route('siteProjects.content', + $show->id)}}">  <span class="card-title">{{$show->title}}</span></a>
                </div>
                <div class="card-content">
{{--                    {{ $show->contents->count() }} row:--}}{{--                        {{$d->description}}--}}
{{--                        {!! $d->description !!}--}}
{{--                        @endforeach--}}
                    {{$show->customer->company_name}}
                </div>
                <div class="card-action">
                    <a href="{{route('siteProjects.content', + $show->id)}}">{{($show->project ? $show->project->title : 'Toon')}}</a>
                </div>
            </div>
        </div>


    @empty
        nothing
    @endforelse
        </div>
    </div>
@endsection

@section('scripts')

@endsection
