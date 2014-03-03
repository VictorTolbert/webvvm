@extends('layouts.default')

@section('footer')
<script>
	App.stompURL = '{{ $configuration_json["https_url"] }}';
	App.stompLogin = '{{ $configuration_json["username"] }}';
	App.stompPasscode = '{{ $configuration_json["password"] }}';
	App.stompDestination = '{{ $configuration_json["resources"]["MessageChanges.VVM"]}}';
</script>
<script src="http://cdn.sockjs.org/sockjs-0.3.min.js"></script>
<script src="/assets/js/stomp.js"></script>
<script src="/assets/js/app.js"></script>
@stop

@section('content')

<h3>Voice Messages <span class="label label-default">{{ $messages->count() }}</span></h3>

<table class="table">
	<thead>
	<tr>
		<th style="width: 20px">
			<input class="checkall" type="checkbox">
		</th>
		<th>
			<div class="btn-group">
				<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
					Actions <span class="caret"></span>
				</button>
				<ul class="dropdown-menu" role="menu">
					<li><a id="read-selected" href="#">Mark as Read</a></li>
					<li><a id="delete-selected" href="#">Delete</a></li>
				</ul>
			</div>
			<div class="btn-group pull-right">
				<button type="button" class="refresh btn btn-default btn-xs">
					<i class="fa fa-refresh"></i>
				</button>
			</div>
		</th>
	</tr>
	</thead>
	<tbody>
	@foreach($messages as $message)
	<tr id="{{ $message->id }}" class="message alert {{ ($message->is_unread) ? 'alert-success' : '' }}">
		<td>
			<input type="checkbox" name="delete">
		</td>
		<td>
			<div class="media">
                <div class="progress-bar"></div>
				<a class="delete-message" href="#">
					<i class="fa fa-trash-o  pull-right"></i>
				</a>
				@if($message->is_unread)
				<a class="mark-read" href="#">
					<span class="label label-success pull-right">New</span>
				</a>
				@endif
				<a class="pull-left" href="#">
					<img class="media-object" src="{{ isset($message->contact->email) ? get_gravatar($message->contact->email, 48, 'mm') : get_gravatar('johndoe@example.com', 48, 'mm') }}" alt="{{ $message->from }}">
				</a>

				<div class="media-body">
					<h4 class="media-heading">
						{{ $message->contact->full_name or format_phone($message->from) }}
						<small>{{ $message->timestamp }}</small>
					</h4>
					<a class="media-player" href="#">
						<i class="media-icon fa fa-play-circle"></i>
						<span class="duration">{{ format_seconds($message->duration) }}</span>

					</a>
					<audio style="display:none" id="{{ $message->id }}-audio" src="/audio/{{ $message->id }}.mp3" controls preload></audio>
				</div>
			</div>
		</td>
	</tr>
	@endforeach
	</tbody>
</table>
@stop
