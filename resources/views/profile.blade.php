@extends('layout')

@section('title')
Profile
@endsection

@section('content')
<div class="row">
	<ul>
	@foreach($accounts as $account)
		<li>
			<< $account->site->name >> / << $account->name >>
		</li>
	@endforeach
	</ul>
</div>
@endsection
