@component('mail::message')
# Ride Cancelled

{{ $cancelledByUser->name }} has cancelled their RSVP for Friday's lunch, so the ride arrangement has been cancelled.

@if($rideRequest->helper_id === $cancelledByUser->id)
They were going to pick up {{ $rideRequest->user->name }} from: **{{ $rideRequest->location }}**
@else
They were going to be picked up by {{ $rideRequest->helper->name }} from: **{{ $rideRequest->location }}**
@endif

@if($rideRequest->notes)
Additional notes that were included: {{ $rideRequest->notes }}
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
