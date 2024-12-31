@component('mail::message')
# RSVP Confirmation

Hi {{ $rsvp->user->name }},

This email confirms that you will be attending the lunch on {{ Carbon\Carbon::parse($rsvp->lunch_date)->format('l, F j, Y') }}.

@component('mail::button', ['url' => "https://calendar.google.com/calendar/render?action=TEMPLATE&text=Vehikl+Friday+Lunch&dates=" . Carbon\Carbon::parse($rsvp->lunch_date)->format('Ymd') . "T120000/" . Carbon\Carbon::parse($rsvp->lunch_date)->format('Ymd') . "T133000&details=Join+us+for+lunch!+RSVP+confirmed.&location=Vehikl+Office"])
Add to Calendar
@endcomponent

We look forward to seeing you there!

Thanks,<br>
{{ config('app.name') }}
@endcomponent 