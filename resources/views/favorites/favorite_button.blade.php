@if (Auth::user()->id == $micropost->user_id)
    @if(Auth::user()->is_favoriting($micropost->id))
        {!! Form::open(['route' => ['user.favorite_delete', $micropost->id], 'method' => 'delete']) !!}
            {!! Form::submit('Unfavorite', ['class' => "btn btn-success btn-xs"]) !!}
        {!! Form::close() !!}
        
    @else
        {!! Form::open(['route' => ['user.favorite_add', $micropost->id]]) !!}
            {!! Form::submit('favorite', ['class' => "btn btn-default btn-xs"]) !!}
        {!! Form::close() !!}
    
    @endif
@endif