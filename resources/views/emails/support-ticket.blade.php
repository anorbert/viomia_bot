@component('mail::message')
# New Support Ticket

**Ticket Reference:** {{ $reference }}

## Customer Information
- **Name:** {{ $user->name }}
- **Email:** {{ $user->email }}

## Request Details
- **Subject:** {{ $subject }}
- **Category:** {{ ucfirst($category) }}
- **Priority:** {{ ucfirst($priority) }}
- **Submitted:** {{ $timestamp->format('M d, Y H:i A') }}

## Customer's Message
{{ $message }}

---

## Response Time Expectations
- 🔴 **High Priority:** Respond within 4 hours
- 🟡 **Medium Priority:** Respond within 12 hours
- 🔵 **Low Priority:** Respond within 24 hours

**Use Reference ID {{ $reference }} when replying to this ticket.**

@endcomponent
