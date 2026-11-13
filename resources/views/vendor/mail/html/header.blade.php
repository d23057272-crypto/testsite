@props(['url'])

<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
    @if(setting('email_header_logo'))
        <img src="{{ asset(setting('email_header_logo')) }}" class="logo" alt="{{ setting('company_name') }}">
    @else
        {{ setting('company_name', config('app.name')) }}
    @endif
</a>
</td>
</tr>
