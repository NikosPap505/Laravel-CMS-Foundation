<x-mail::message>
# New Contact Form Submission

You have received a new message from your website's contact form.

**Name:** {{ $data['name'] }}
**Email:** {{ $data['email'] }}

---

**Message:**

{{ $data['message'] }}

</x-mail::message>