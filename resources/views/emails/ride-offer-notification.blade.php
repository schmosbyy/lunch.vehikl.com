@component('mail::message')
# Ride Offer Update

@if($rideRequest->helper)
**{{ $rideRequest->helper->name }}** has offered to give you a ride to Friday's lunch!

They will pick you up from: **{{ $rideRequest->location }}**

@if($rideRequest->notes)
Additional notes: {{ $rideRequest->notes }}
@endif

@else
{{ $rideRequest->user->name }} has requested a ride to Friday's lunch, and you've offered to help!

Pickup location: **{{ $rideRequest->location }}**

@if($rideRequest->notes)
Additional notes: {{ $rideRequest->notes }}
@endif

@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
