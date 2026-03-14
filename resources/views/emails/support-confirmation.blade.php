@component('mail::message')
# Support Request Confirmation

Hi {{ $user->name }},

Your support request has been successfully submitted! Here are your request details:

**Subject:** {{ $subject }}
**Reference ID:** {{ $reference }}

Our support team is working on your request and will respond as soon as possible, typically within 24 business hours.

## What happens next?
1. Our team will review your request
2. We'll diagnose the issue or gather more information if needed
3. You'll receive a response via email with a solution or next steps

## Quick Support Options

**Live Chat (WhatsApp):**
[Chat with us on WhatsApp](https://wa.me/0787373722?text=Reference:%20{{ $reference }})

**Email Support:**
reply-all to this email or contact support@viomia.com

---

Thank you for choosing Viomia!

@endcomponent
