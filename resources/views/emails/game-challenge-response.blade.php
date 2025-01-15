@component('mail::message')
# Game Challenge {{ ucfirst($challenge->status) }}!

@if ($isChallenger)
**{{ $challenge->challenged->name }}** has {{ $challenge->status }} your game challenge for **{{ $challenge->game_type }}**!
@else
You have {{ $challenge->status }} the game challenge from **{{ $challenge->challenger->name }}** for **{{ $challenge->game_type }}**!
@endif

This challenge is for the Friday lunch on **{{ $challenge->rsvp->lunch_date }}**.

Get ready for an exciting game at lunch!

Thanks,<br>
{{ config('app.name') }}
@endcomponent
