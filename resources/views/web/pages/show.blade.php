@extends('layouts.web')

@section('content')
    <h1 class="text-title text-4xl leading-normal font-bold text-orange-500 mb-4">{{ $page->title }}</h1>

    <div class="post-content mt-10 text-gray-700 line-numbers">
        {!! $page->formatted_content !!}
    </div>
@endsection
