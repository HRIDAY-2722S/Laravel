@component('mail::message')

<p>Hello {{ $user->name }},</p>

<P>We understand it happens, and we're here to help you reset your password.</P>

@component('mail::button', ['url' => url('resetpassword', $user->remember_token)])
Reset Your Password
@endcomponent

<p>If you're having trouble clicking the button, you can copy and paste the following URL into your browser:</p>

<a href="{{ url('resetpassword', $user->remember_token) }}">{{ url('resetpassword', $user->remember_token) }}</a>

In case you have any issues recovering your password, please don't hesitate to contact us.

<p>Thanks,</p><br />
@endcomponent