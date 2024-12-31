@component('mail::message')
# You're Invited to Friday Lunch!

Hi {{ $inviteeUsername }},

You've been invited by **{{ $inviterName }}** to join us for Friday Lunch on **{{ $lunchDateFormatted }}**!

We'd love to have you join us for lunch and some fun games. Please click the button below to RSVP. This invitation link will expire in 5 days.

@component('mail::button', ['url' => $acceptUrl])
RSVP now
@endcomponent

Best regards,<br>
{{ $inviterName }} and the {{ config('app.name') }} Team
@endcomponent 