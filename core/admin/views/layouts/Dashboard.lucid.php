{@AdminInclude 'Nav'}

<div class="container mt-5">
    <h1>Welcome, {@ $user['name'] }</h1>
    <div class="row" id="cards">
        {@AdminInclude 'card'}
    </div>
</div>