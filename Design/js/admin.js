$(function () {
	'use strict';

	// Dashboard
	// Funvtion To hide and show latest items and users on click on icon 
	$('.toggle-info').click(function () {
		$(this).toggleClass('selected').parent().next('.card-body').fadeToggle(200); 

		if ($(this).hasClass('selected')) {
			$(this).html('<i class="fa fa-plus fa-lg"></i>')
		}else {
			$(this).html('<i class="fa fa-minus fa-lg"></i>')
		}
	});

	// Fire Select Boxit Plugin
	$('select').selectBoxIt({
		autoWidth: false
	});

	// Hide placeholder on form focus
	$('[placeholder]').focus(function () {
		$(this).attr('data_text', $(this).attr('placeholder'));
		$(this).attr('placeholder', '');

	}).blur(function () {
		$(this).attr('placeholder', $(this).attr('data_text'));
	});

	// Add Asterisk on required input field
	$('[required]').each(function () {
		
			$(this).after('<span style="font-size:30px; color:red;position:absolute;right:0px;top:5px;font-family:Arial;">*</span>');
		
	});
	// Function to show password on hover
	var passField = $('.password');
	$('.show-pass').hover(function () {
		
		passField.attr("type","text");
	}, function () {
		passField.attr("type","password");

	});

	// Confirmation message when click on delete button
	$('.confirm').click(function (){
		return confirm('Are You Sure You Want To Delete That Member?');
	});

	// Category View Options

	$('.cat h3').click(function () {
		$(this).next('.full-view').fadeToggle(200);
	});

	// Add Active Class To View Span
	$('.option span').click(function () {
		$(this).addClass('active').siblings('span').removeClass('active');

		if ($(this).data('view') === 'full') {
			$('.cat .full-view').fadeIn(200);
		} else {
			$('.cat .full-view').fadeOut(200);
		}
	});
});