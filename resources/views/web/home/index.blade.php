@extends('layouts.web')

@section('content')
    <x-web.title :title="get_setting('home_page_title')"/>
    <x-web.sub-title>
        {{ get_setting('home_page_description') }}
    </x-web.sub-title>

    <div>
        @foreach ($posts as $year_posts)
            <div>
                <x-web.year>{{ $year_posts['year'] ?? '' }}</x-web.year>
                <x-web.divider/>
                @foreach ($year_posts['posts'] as $post)
                    <x-web.article-card
                        link="{{ route('web.posts.show', $post->slug) }}"
                        :title="$post->title"
                        :date="$post->formatted_publish_date"
                    >{{ $post->excerpt }}</x-web.article-card>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection
