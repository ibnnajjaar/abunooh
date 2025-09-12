@extends('layouts.web')

@section('content')
    <h1 class="text-title text-4xl leading-normal font-bold text-orange-500 mb-4">{{ $post->title }}</h1>
    <div class="flex flex-row justify-between text-slate-500">
        <div class="flex flex-row space-x-2">
            <div class="">{{ $post->author?->name }}</div>
            <span>/</span>
            <div class="">{{ $post->formatted_long_publish_date }}</div>
        </div>
    </div>

    <div class="post-content mt-10 text-gray-700 line-numbers">
        {!! $post->formatted_content !!}
    </div>
@endsection
