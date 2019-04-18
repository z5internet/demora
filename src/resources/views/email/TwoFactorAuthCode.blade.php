@extends('vendor.ruf.email.layout.notificationsLayout')

@section('content')

<h1 class='name'>Hi {{ $first_name }},</h1>
<h4>Here is the security code for you to login to {{ config('app.name') }}.</h4>

<table class="btn-primary" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<span><h3>{{ $code }}</h3></span>
		</td>
	</tr>
</table>

@endsection
