@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-xs-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $user->name }}</h3>
                </div>
                <div class="panel-body">
                    <img class="media-object img-rounded img-responsive" src="{{ Gravatar::src($user->email, 500) }}" alt="">
                </div>
            </div>
            @include('user_follow.follow_button', ['user' => $user])
        </aside>
        <div class="col-xs-8">
            <ul class="nav nav-tabs nav-justified">
                <li role="presentation" class="{{ Request::is('users/' . $user->id) ? 'active' : '' }}"><a href="{{ route('users.show', ['id' => $user->id]) }}">Microposts <span class="badge">{{ $count_microposts }}</span></a></li>
                <li role="presentation" class="{{ Request::is('users/*/followings') ? 'active' : '' }}"><a href="{{ route('users.followings', ['id' => $user->id]) }}">Followings <span class="badge">{{ $count_followings }}</span></a></li>
                <li role="presentation" class="{{ Request::is('users/*/followers') ? 'active' : '' }}"><a href="{{ route('users.followers', ['id' => $user->id]) }}">Followers <span class="badge">{{ $count_followers }}</span></a></li>
                <li role="presentation" class="{{ Request::is('users/*/favorites') ? 'active' : '' }}"><a href="{{ route('users.favorites', ['id' => $user->id]) }}">Favorites <span class="badge">{{ $count_favorites }}</span></a></li>
             </ul>



            @if (count($microposts) > 0)
                <ul class="media-list">

                        @foreach ($microposts as $micropost)
                        <?php $user = $micropost->user; ?>

                        @if(Auth::user()->is_favoriting($micropost->id))
                            <li class="media">
                                <div class="media-left">
                                    <img class="media-object img-rounded" src="{{ Gravatar::src($user->email, 50) }}" alt="">
                                </div>
                                <div class="media-body">
                                    <div>
                                        {!! link_to_route('users.show', $user->name, ['id' => $user->id]) !!} <span class="text-muted">posted at {{ $micropost->created_at }}</span>
                                    </div>
                                    <div>
                                        <p>{!! nl2br(e($micropost->content)) !!}</p>
                                    </div>
                                    <div>
                                        
                                            @include('favorites.favorite_button', ['micropost' => $micropost])
                                    </div>
                                </div>
                            </li>
                        @endif

                        @endforeach

                </ul>

                {!! $microposts->render() !!}
            @endif
            
        </div>
    </div>
@endsection