@component('mail::message')
# Game Challenge {{ $isChallenger ? 'Sent' : 'Received' }}!

@if ($isChallenger)
You have challenged **{{ $challenge->challenged->name }}** to a game of **{{ $challenge->game_type }}**!
@else
**{{ $challenge->challenger->name }}** has challenged you to a game of **{{ $challenge->game_type }}**!
@endif

This challenge is for the upcoming Friday lunch on **{{ $challenge->rsvp->lunch_date }}**.

@component('mail::button', ['url' => $gameUrl])
Play {{ $challenge->game_type }}
@endcomponent

@if (!$isChallenger)
You can accept or decline this challenge when you see {{ $challenge->challenger->name }} at lunch.
@else
{{ $challenge->challenged->name }} can accept or decline this challenge at lunch.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent 