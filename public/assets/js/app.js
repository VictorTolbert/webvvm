var sock = new SockJS(App.stompURL);
var client = Stomp.over(sock);
var onConnect = function () {
	client.subscribe(App.stompDestination, function (message) {

		var json = JSON.parse(message.body);

		console.log(json.fields.key, json.operation);

		if (json.operation == 'add') {
			setTimeout(function () {
				window.location.reload();
				$('#' + json.fields.key).highlight().addClass('animated bounceIn').delay(800).slideDown();
			}, 100);
		}

		if(json.operation == 'update') {

			$('#'+json.fields.key).highlight().find('.mark-read').hide();

			setTimeout(function() {
				window.location.reload();
			}, 100);
		}

		if (json.operation == 'delete') {

			$('#' + json.fields.key).highlight().addClass('animated bounceOut').delay(800).slideUp();
			setTimeout(function () {
				window.location.reload();
			}, 1600);

		}

	});
}

var onError = function (error) {
	console.log(error.headers.message);
}

client.connect(App.stompLogin, App.stompPasscode, onConnect, onError);

$(function() {

	$.fn.highlight = function () {
		return $(this).each(function () {
			var el = $(this);
			$("<div/>")
				.width(el.outerWidth())
				.height(el.outerHeight())
				.css({
					"position": "absolute",
					"left": el.offset().left,
					"top": el.offset().top,
					"background-color": "#ffff99",
					"opacity": ".7",
					"z-index": "9999999"
				})
				.appendTo('body')
				.fadeOut(1000)
				.queue(function () {
					$(this).remove();
				});
		});
	}

	$('.delete-message').on('click', function (event) {
		var message = $(this).closest('.message');
		var message_id = message.attr('id');
		$(this).find('i').removeClass('fa-times-circle').addClass('fa-spinner fa-spin');
		$.ajax({
			url: '/messages/' + message_id,
			type: "DELETE"
		});
		event.preventDefault();
	});

	$('.mark-read').on('click', function (event) {
		var message = $(this).closest('.message');
		var message_id = message.attr('id');
		$(this).find('i').removeClass('fa-times-circle').addClass('fa-spinner fa-spin');
		$.ajax({
			url: '/messages/' + message_id,
			type: "PUT"
		});
		event.preventDefault();
	});

	$('#read-selected, #delete-selected').on('click', function (event) {
		var message_ids, operation;
		message_ids = $(".message :checkbox:checked").map(function () {
			return $(this).closest('.message').attr('id');
		}).get();

		if (event.target.id == 'delete-selected') {
			operation = "DELETE";
		}
		if (event.target.id == 'read-selected') {
			operation = "PUT";
		}
		$.ajax({
			url: '/messages',
			type: operation,
			contentType: "application/json; charset=utf-8",
			dataType: "json",
			data: JSON.stringify({ "messages": message_ids })
		});
		event.preventDefault();
	});

	$('.checkall').on('click', function (event) {
		$(this).closest('table').find(':checkbox').prop('checked', this.checked);
		$(this).closest('table').find(':checkbox').each(function () {
			if ($(this).prop('checked')) {
				$(this).closest('.message').addClass('warning');
			} else {
				$(this).closest('.message').removeClass('warning');
			}
		});
	});

	$('.message :checkbox').on('click', function () {
		!$(this).prop('checked') ? removeCheckbox(this) : addCheckbox(this);
	});

	function removeCheckbox(checkbox) {
		$('.checkall').prop('checked', false);
		$(checkbox).closest('.message').removeClass('warning');
	}

	function addCheckbox(checkbox) {
		$(checkbox).closest('.message').addClass('warning');
		if ($('.message :checkbox').size() == $('.message :checkbox:checked').size()) {
			$('.checkall').prop('checked', true);
		}
	}

	$('.refresh').on('click', function (event) {
		window.location.reload();
		event.preventDefault();
	});

	$(document).on('click', '.media-player', function (event) {
		var id = $(this).closest('.message').attr('id');
		var audio = $(this).closest('.message').find('audio')[0];

		if (audio.paused) {
			$.get('/messages/' + id).done(function(){
				if( audio.currentTime == 0) {
					audio.load();
				}
				audio.play();
			});
			$(this).find('i').removeClass('fa-play-circle').addClass('fa-pause');
			$('audio').not(audio).each(function(){
				var duration = $(this).closest('.media-body').find('.duration').html();
				this.load();
			});
		} else {
			audio.pause();
			$(this).find('i').removeClass('fa-play-pause').addClass('fa-play-circle');
		}
		event.preventDefault();
	});

	document.addEventListener('play', function (e) {
		$('audio').each(function () {
			var duration = $(this).closest('.media-body').find('.duration').html()
			$(this).on('timeupdate', function () {
				var minutes = parseInt((this.currentTime / 60) % 60);
				var seconds = parseInt(this.currentTime % 60);
				var widthOfProgressBar = Math.floor((100 / this.duration) * this.currentTime);
				minutes = (minutes < 10) ? "0" + minutes : minutes;
				seconds = (seconds < 10) ? "0" + seconds : seconds;
				if( this.currentTime != 0) {
					$(this).closest('.media-body').find('.duration').html(minutes + ":" + seconds);
				}

				$(this).closest('.message').find('.progress-bar').css({
					'width': widthOfProgressBar + '%'
				});

			});

			$(this).on('ended', function () {
				$(this).closest('.media-body').find('.media-icon').removeClass('fa-play-pause').addClass('fa-play-circle');
				this.load();
				$.ajax({
					url: '/messages/' + $(this).closest('.message').attr('id'),
					type: "PUT"
				});
			});
		});
	}, true);

});

window.onload = function () {

//	var ids = $(".message").map(function () {
//		return this.id;
//	}).get();
//
//	$('.message').each(function () {
//		$.ajax({
//			url: 'http://localhost:8000/audio/' + this.id
//		});
//	});
//
//	$('audio').load();
};