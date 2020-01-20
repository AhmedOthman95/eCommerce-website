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

    // Show the Name Of File Uploaded
	   $(".custom-file-input").on("change", function() {
	  var fileName = $(this).val().split("\\").pop();
	  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	}); 
	   
	// Fire Select Boxit Plugin
	$('select').selectBoxIt({
		autoWidth: false
	});

	// Fire Tag it Plugin
    $(document).ready(function() {
    $('#myTags').tagit();	
    $('#add-tags').tagit();
    $("#add-tags").tagit("createTag", "Ahmed");
    $("#add-tags").tagit("createTag", "Handmade");
    $("#add-tags").tagit("createTag", "Discount");
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
		
			$(this).after('<span class="asterisk">*</span>');
		
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

	// Show Delete Buttons On Child Cats

	$('.child-link').hover(function () {

		$(this).find('.show-delete').fadeIn(400);

	}, function () {
		$(this).find('.show-delete').fadeOut(400);

	});
});