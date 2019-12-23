<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'PhotoLife') }}</title>
    <link rel="stylesheet" href="{{ asset('css/embed/post.css') }}">
</head>
<body>
<div class="box p-0">
    <article>
        <div class="media-content">
            <article class="media p-10">
                <figure class="media-left">
                    <a href="{{ url()->to($post->author->username) }}" class="">
                        <p class="image is-32x32">
                            <img src="{{ $post->author->avatar }}" class="is-rounded">
                        </p>
                    </a>
                </figure>
                <div class="media-content">
                    <div class="content">
                        <p>
                            <a href="{{ url()->to($post->author->username) }}">
                                <strong>{{ $post->author->username }}</strong>
                            </a>
                        </p>
                    </div>
                </div>
                <div class="media-right">
                    <a href="{{ url()->to('/post/'.$post->id) }}" class="has-text-grey">
                        <time>{{ $post->created_at->diffForHumans() }}</time>
                    </a>
                </div>
            </article>
            <figure class="image">
                <img src="{{ $post->media->first()->getFullUrl() }}" alt="{{ $post->caption }}">
            </figure>
            <nav class="level is-mobile m-0 pl-10">
                <div class="level-left">
                    <span class="level-item">{{ $post->likers->count() }} likes</span>
                </div>
            </nav>
            <div class="mb-5 pl-10 pr-10">
                <a href="{{ url()->to($post->author->username) }}">
                    <strong class="has-text-black">{{ $post->author->username }}</strong>
                </a>
                <span>{{ $post->caption }}</span>
            </div>
        </div>
    </article>
</div>
</body>
</html>