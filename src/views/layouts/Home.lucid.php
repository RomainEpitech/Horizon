Welcome to homepage

{@form => 'loginForm'}

@foreach ($migration as $m)
    <li>{{ $m->migration }}</li>
@endforeach