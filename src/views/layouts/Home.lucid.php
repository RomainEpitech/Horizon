Welcome to homepage

{@form => 'loginForm'}

@foreach ($migration as $m)
    <li>{{ $m->migration, $m->executed_at }}</li>
@endforeach