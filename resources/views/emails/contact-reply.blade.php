<x-mail::message>
# Hello, {{ $contact->name }}

You have received a reply regarding your recent inquiry.

---

## Our Response:
<x-mail::panel>
{{ $replyMessage }}
</x-mail::panel>

For your reference, your original message was:
> "{{ $contact->message }}"

Thanks,<br>
The Team at {{ config('app.name') }}
</x-mail::message>