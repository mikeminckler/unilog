$(function(){


	if ($('input.login-email').length > 0) {
		$('input.login-email').focus();
	}

	$('body').on('click', 'div.expand-all', function() {

		if ($(this).html() == 'Expand All') {
			$(this).html('Collapse All');
			$(this).parents('div#search_results, div#message_list').find('.message-contents').slideDown(250);
		} else {
			$(this).html('Expand All');
			$(this).parents('div#search_results, div#message_list').find('.message-contents').slideUp(250);
		}

	});


	$('input.datepicker').datepicker({ 
		dateFormat: 'yy-mm-dd', 
		onSelect: function(date) {
			$.ajax ({
				type: 'GET',
				url: '/' + $(this).attr('name'),
				data: 'date='+date,
				success: function(response) {
					if (window.location.href.lastIndexOf("?") != -1) {
						window.location = window.location.href.substring(0, window.location.href.lastIndexOf("?"));
					} else {
						location.reload();
					}
				}
			});
		}
	});

	$('body').on('click', 'div.attachment-icon', function() {
		$(this).parents('.message-container').find('.log-attachment').slideToggle();
	});

	$('body').on('click', 'div.create-email', function() {
		$(this).parents('form').attr('action', '/email/student').submit();
	});

	$('body').on('click', 'div.create-log', function() {
		$(this).parents('form').attr('action', '/messages/create/student').submit();
	});

	$('body').on('click', 'div.export-csv', function() {
		$(this).parents('form').attr('action', '/export-csv').submit();
	});

	$('div#content').on('click', 'div.select-all', function() {
		if ($(this).html() == 'Select All') {
			$(this).parents('form').find('input[type="checkbox"]').prop('checked', true);
			$(this).html('Unselect All');
		} else {
			$(this).parents('form').find('input[type="checkbox"]').prop('checked', false);
			$(this).html('Select All');
		}
	});

	$("input.student-list").autocomplete({
		source: "/search/student",
		minLength: 2,
		select: function(event, ui) {
			var student_id = ui.item.id;
		
			$(this).next('select.autocomplete').find('option[value="' + student_id + '"]').prop('selected', true);

			if ($("input.email-to").length > 0) {
				if ($("input.email-to").val() == "") {
					$("input.email-to").val(ui.item.email);
				} else {
					$("input.email-to").val($("input.email-to").val() + ', ' + ui.item.email);
				}
			}

			$("div.student-list").append('<div class="student-name">' + ui.item.value + ' <img src="/images/error.png" data-id="' + student_id + '" class="clickable remove-student" /></div>');

			$('img.remove-student').click(function() {

				$(this).parents('div.input').find('select.autocomplete').find('option[value="' + $(this).attr('data-id') + '"]').prop('selected', false);
				$(this).parent('div.student-name').slideUp(200, function() {
					$(this).remove();
				});
			});

			if (!$(this).next('select.autocomplete').hasClass('single')) {
				this.value = "";
				return false;
			}

		}
	});

	$('select.student-list').find('option[selected="selected"]').each(function() {

		if ($(this).val() != "") {
			$("div.student-list").append('<div class="student-name">' + $(this).html() + ' <img src="/images/error.png" data-id="' + $(this).val() + '" class="clickable remove-student" /></div>');
		}
/*
		$('img.remove-student').click(function() {

			$(this).parents('div.input').find('select.autocomplete').find('option[value="' + $(this).attr('data-id') + '"]').prop('selected', false);
			$(this).parent('div.student-name').slideUp(200, function() {
				$(this).remove();
			});
		});
*/

	});

	$('img.remove-student').click(function() {

		$(this).parents('div.input').find('select.autocomplete').find('option[value="' + $(this).attr('data-id') + '"]').prop('selected', false);
		$(this).parent('div.student-name').slideUp(200, function() {
			$(this).remove();
		});
	});

	$('img.remove-attachment').click(function() {
		var isGood = confirm('Are you sure you want to delete this Attachment?');
		if (isGood) {

			$(this).parents('div.input').slideUp(250, function() {
				$(this).html('<input id="attachment" type="file" name="attachment">').slideDown();
			});
			
			$.ajax({

				type: 'GET',
				url: '/messages/delete-attachment?message_id=' + $(this).attr('data-message-id'),
				success: function(response) {

				}
			});


		} else {
		}	


	});

	$("input.email-to").autocomplete({
                source: "/search/email",
                minLength: 2,
                select: function(event, ui) {
			$(this).val(ui.item.value);
                }
        });

	$("input.email-from").autocomplete({
                source: "/search/email",
                minLength: 2,
                select: function(event, ui) {
                        $(this).val(ui.item.value);
                }
        });

	$('div#delete').click(function() {

		var isGood = confirm('Are you sure you want to delete this Log?');
		if (isGood) {

			$(this).parents('div.message-container').slideUp();
			$.ajax({

				type: 'GET',
				url: '/messages/delete?message_id=' + $(this).attr('data-message-id'),
				success: function(response) {

				}
			});


		} else {
		}	


	});

	$("form.ajax").submit(function(e) {

		$("div#search_results").slideUp(100);

		e.preventDefault();

		var form = $(this);
		var method = $(this).attr("method");
		var url = $(this).prop("action");
		
		$.ajax({

			type: method,
			url: url,
			data: form.serialize(),
			success: function(response) {
				$("div#search_results").delay(100).html(response);

				$("div#search_results").slideDown(200);

				
				$("div#search_results").find(".expand").click(function() {
					$(this).closest("div.message-container").find("div.message-contents").slideToggle(250);
				});


				$("div#search_results").find('div#delete').click(function() {

					var isGood = confirm('Are you sure you want to delete this Log?');
					if (isGood) {

						$(this).parents('div.message-container').slideUp();
						$.ajax({

							type: 'GET',
							url: '/messages/delete?message_id=' + $(this).attr('data-message-id'),
							success: function(response) {

							}
						});


					} else {
					}	


				});


			}

		});


	});


	$(".expand").click(function() {
		$(this).closest("div.message-container").find("div.message-contents").slideToggle(250);
	});

	$("div#search_results").on('click', 'div.message-header', function() {
		$(this).next("div.message-contents").slideToggle(250);
	});

	$("div#container").css({"min-height": ($(window).height() - 65) + "px"});

	$("input.filter").focus(function() {
		$(this).animate({width: 100}, 100);
	});

	$("input.filter").blur(function() {
		$(this).animate({width: 10}, 100).val('');
	});

	$("input.filter").bind('change keyup', function() {

		var filter = $(this).val().toLowerCase();
		$(this).prev('select').find('option').each(function() {

			//console.log($(this).html().indexOf(filter));
			if ($(this).html().toLowerCase().indexOf(filter) == -1) {
				$(this).hide();
				if( $(this).parent('span.toggleOption').length == 0 ) {
					$(this).wrap( '<span class="toggleOption" style="display: none;" />' );
				}

			} else {
				$(this).show();
				if ($(this).parent('span.toggleOption').length) {
					$(this).unwrap();
				} 
			}

		});
	});



	$("div#menu_link").click(function() {

		var menu_height = 0;
		menu_height = $("a.menu-item").length * 22;

		//console.log($("div#menu").height());

		if ($("div#menu").height() > 0) {
			$("div#menu").animate({height: 0}, 250);
		} else {
			$("div#menu").animate({height: menu_height}, 250);
		}
		


	});



	$("div.form").each(function() {

		var label_width = 0;
		$(this).find("div.label").each(function() {

			if ($(this).width() > label_width) {
				label_width = $(this).width();
			}

		});

		$(this).find("div.label").width(label_width);

	});

	// Form Validation
	$("form").submit(function(e) {

		var required = $(this).find(".required");
		var error = false;
		var error_count = 0;

		if ($(required).length > 0) {
			var error_message = '<div class="error-header">Error</div><ul class="feedback">';

			$(required).each(function() {

				if ($(this).val() == "") {

					error = true;
					var e_message = '<li class="feedback error"><span class="mono">' + upperCase($(this).parents('.input-block').find('label').html()) + '</span> is required</li>';
					error_message += e_message;
					error_count ++;	
				}

			});

			error_message += '</ul>';

			if (error == true) {

				e.preventDefault();
				$("div#feedback").html(error_message);

			}

		}
	});


	MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

        // define a new observer
        var obs = new MutationObserver(function(mutations, observer) {
		showFeedback();
        });

        // have the observer observe foo for changes in children
        if ($("#feedback").length > 0) {
                obs.observe($("#feedback").get(0), {
                        childList: true
                });
        }

	//console.log($("div#feedback").html());
	if ($("div#feedback").html().indexOf("div") != -1) {
		showFeedback();
	}

	$("div#feedback").css("left", (($(window).width() - $("div#feedback").width()) / 2) + 'px');

});

function showFeedback() {
	var new_height = $("div#feedback").find("ul").height();
	var feedback_count = $("div#feedback").find("li").length;
	var feedback_time = feedback_count * 1000;
	if (feedback_time < 2500) {
		feedback_time = 2250;
	}

	$("div#feedback").stop(true).animate({height: (50 + (new_height))}, 200, function() {
		$(this).delay(feedback_time).animate({height: 0}, 200, function() {
			//$(this).html("");
		})
	});

}

function upperCase(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
