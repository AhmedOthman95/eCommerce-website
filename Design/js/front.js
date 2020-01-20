$(function () {
	'use strict';



    // Show the Name Of File Uploaded
	   $(".custom-file-input").on("change", function() {
	  var fileName = $(this).val().split("\\").pop();
	  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	}); 
	   
	// Switch Between Login And Signup
	$('.login-page h1 span').click(function () {
		$(this).addClass('selected').siblings().removeClass('selected');

		$('.login-page form').hide();
		$('.' + $(this).data('class')).fadeIn(200);
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
		return confirm('Are You Sure You Want To Delete ?');
	});

	// Write The Name, description and price Of Item On The Item picture as soon as it's written in the input field
	$('.live').keyup(function () {
		$('.' + $(this).data('class')).text($(this).val()); 
	});

});