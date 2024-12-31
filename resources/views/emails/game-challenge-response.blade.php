@component('mail::message')
# Game Challenge {{ ucfirst($challenge->status) }}!

@if ($isChallenger)
**{{ $challenge->challenged->name }}** has {{ $challenge->status }} your game challenge for **{{ $challenge->game_type }}**!
@else
You have {{ $challenge->status }} the game challenge from **{{ $challenge->challenger->name }}** for **{{ $challenge->game_type }}**!
@endif

This challenge was for the Friday lunch on **{{ $challenge->rsvp->lunch_date }}**.

@if ($challenge->status === 'accepted')
@component('mail::button', ['url' => $challenge->game_url])
Play {{ $challenge->game_type }}
@endcomponent

Get ready for an exciting game at lunch!
@else
Maybe next time! There's always another Friday lunch to challenge someone to a game.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent 