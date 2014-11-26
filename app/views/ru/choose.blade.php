@extends('_layout')

@section('content')
	<div class="row language-select">
		@foreach ($syntaxes as $_syntax => $label)
			<div class="col-lg-6 text-center">
				<a href="{{ URL::route('index', ['lang' => $lang, 'syntax' => $_syntax]) }}">
					{{ HTML::image('images/' . $_syntax . '.png') }}
					<h1>{{ $label }}</h1>
				</a>
			</div>
		@endforeach
	</div>
@stop