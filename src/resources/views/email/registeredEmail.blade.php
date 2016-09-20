@extends('vendor.ruf.email.layout.notificationsLayout')

@section('content')

<h1 class='name'>Hi {{ $first_name }},</h1>
<h4>To verify your email address click the button below.</h4>
	
<table class="btn-primary" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<a href="{{ $link }}">Click here to verify your email address.</a>
		</td>
	</tr>
</table>
	
or copy and paste this link into your browser<br><br>{{ $link }}

@endsection
	