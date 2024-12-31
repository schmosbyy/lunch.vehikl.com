@component('mail::message')
# RSVP Cancellation

Hi {{ $name }},

This email confirms that you have cancelled your RSVP for the lunch on {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}.

If this was a mistake, you can always RSVP again through the website.

Thanks,<br>
{{ config('app.name') }}
@endcomponent 