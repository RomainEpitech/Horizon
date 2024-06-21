Welcome to homepage

{@form => 'loginForm'}
<div class="bg-blue-500 text-white p-4">
    Hello, Tailwind!
</div>


{@foreach ($migration as $m)}
    <li>{{ $m->migration, $m->executed_at }}</li>
{@endforeach}