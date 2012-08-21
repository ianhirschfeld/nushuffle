/* ============================== VARIABLES ============================== */
var _baseurl = 'http://www.northeasternshuffle.com';
var _imagesurl = 'http://www.northeasternshuffle.com/images';

var _formSelectSpeed = 400;
var _joinTitleSlideSpeed = 800;
var _accountSpeed = 400;
var _shuffleAddSpeed = 400;
var _fadeOutSpeed = 400;
var _badgeFadeDelaySpeed = 15000;
var _notifFadeDelaySpeed = 8000;
var _replyFadeSpeed = 400;

/* ============================== JQUERY FUNCTIONS ============================== */
jQuery(document).ready(function(){
	// Join title slide
	if(jQuery('#misc').length){
		jQuery('#misc').animate({
			marginTop: '75px',
			opacity: 'toggle'
		}, _joinTitleSlideSpeed);
	}

	// Post tabs
	if(jQuery('#shuffleTabs').length){
		jQuery('#shuffleTabs').tabs('#shufflePanes .pane', {effect: 'fade'});
	}
	
	// Shuffle selection form loading
	if(jQuery('#shuffleSelections a').length){
		jQuery('#shuffleSelections a').click(function(){
			var _img = jQuery(this).children('img').attr('src');
			var _txt = jQuery(this).children('span').html();
			
			jQuery('#shuffleSelections').fadeOut(_formSelectSpeed, function(){
				jQuery('#shuffle .icon').attr('src', _img);
				jQuery('#shuffle .icon').attr('alt', _txt);
				jQuery('#shuffle .icon').attr('title', _txt);
				jQuery('#shuffle h3').html(_txt);
				jQuery('#shuffle #hiddenType').val(_txt);
				jQuery('#shuffle').fadeIn(_formSelectSpeed);
			});
			
			return false;
		});
	}
	
	// Shuffle form cancel
	if(jQuery('#shuffle a.cancel').length){
		jQuery('#shuffle a.cancel').click(function(){
			jQuery('#shuffle').fadeOut(_formSelectSpeed, function(){
				jQuery('#shuffleSelections').fadeIn(_formSelectSpeed);
			});
			
			return false;
		});
	}
	
	// Add another shuffle
	if(jQuery('.addShuffle').length){
		var _count = 1;
		var _html;
	
		jQuery('.addShuffle').live('click', function(){
			_count++;
			_html =
				'<p>' +
					'<input id="shuffle_'+_count+'" class="shuffle text" name="departments[]" type="text" tabindex="1" value="start typing a college, department, or office" />' +
					'<a class="addShuffle" href="#"><img class="add" src="'+_imagesurl+'/add.png" width="16" height="16" alt="Add" title="Add" /></a>' +
				'</p>';
			jQuery(this).remove();
			jQuery(_html).hide().appendTo('#shuffleAddWrapper').fadeIn(_shuffleAddSpeed, function(){
				jQuery('#shuffle_'+_count).autocomplete({
					serviceUrl: _baseurl+'/handlers/dept.lookup.handler.php'
				});
			});
		
			return false;
		});
	}
	
	// Shuffle star ratings
	jQuery('#shuffleRating .star').hover(function(){
		jQuery(this).removeClass('empty').addClass('full');
		jQuery(this).prevAll('.star').removeClass('empty').addClass('full');
	},function(){
		jQuery(this).removeClass('full').addClass('empty');
		jQuery(this).prevAll('.star').removeClass('full').addClass('empty');
	});
	jQuery('#shuffleRating .star').click(function(){
		jQuery('.star.selected').removeClass('selected');
		jQuery(this).addClass('selected');
		jQuery(this).prevAll('.star').addClass('selected');
		jQuery('#hiddenRating').val(jQuery('.star.selected').length);
	});
	
	// Disable post button after one click
	jQuery('#submitShuffle').click(function(){
		jQuery(this).css('display', 'none');
	});
	
	// AJAX Dept Lookup
	if(jQuery('#searchDept').length){
		jQuery('#searchDept').autocomplete({
			serviceUrl: _baseurl+'/handlers/dept.lookup.handler.php'
		});
	}
	if(jQuery('#shuffleAddWrapper').length){
		jQuery('#shuffle_0').autocomplete({
			serviceUrl: _baseurl+'/handlers/dept.lookup.handler.php'
		});
		jQuery('#shuffle_1').autocomplete({
			serviceUrl: _baseurl+'/handlers/dept.lookup.handler.php'
		});
	}
	
	// Load more ratings
	jQuery('#viewMorePosts').click(function(){
		jQuery('#viewMorePosts a').addClass('hide');
		jQuery('#viewMorePosts').append('<img id="loading" src="'+_imagesurl+'/ajax-loader.gif" alt="loading" title="loading" />');
	
		jQuery.ajax({
			url: _baseurl+'/handlers/load.posts.handler.php',
			type: 'POST',
			data: 'settings='+jQuery('#viewMorePosts a').attr('href'),
			success: function(results){
				jQuery('#posts').append(results);
				jQuery('#loading').remove();
				_p = jQuery('#viewMorePosts a').attr('href').substr(1,1);
				_p = parseInt(_p) + 1;
				_vmp = jQuery('#viewMorePosts a').attr('href').substr(2);
				jQuery('#viewMorePosts a').attr('href','#'+_p+_vmp);
				jQuery('#viewMorePosts a').removeClass('hide');
			}
		});
		
		return false;
	});
	
	// Reply to post
	jQuery('.postReply').live('click', function(){
		_li = jQuery(this).closest('li');
		_postId = _li.attr('id').substr(5);
		_html =
			'<div class="formWrapper formReply">' +
				'<form id="formReply-'+_postId+'" name="formReply" method="post" action="'+_baseurl+'/handlers/reply.handler.php">' +
					'<p><textarea id="commentsReply" name="comment">comments</textarea></p>' +
					'<p>' +
						'<input id="hiddenPostId" name="hiddenPostId" type="hidden" value="'+_postId+'" />' +
						'<input id="hiddenReturnReply" name="hiddenReturn" type="hidden" value="'+window.location.pathname+'" />' +
						'<input id="hiddenSubmitReply" name="hiddenSubmit" type="hidden" value="true" />' +
						'<input id="submitReply" class="submit" name="submit" type="submit" value="Post" />' +
					'</p>' +
				'</form>' +
			'</div>';
		jQuery('.formReply').fadeOut(_replyFadeSpeed).remove();
		jQuery(_html).hide().appendTo(_li).fadeIn(_replyFadeSpeed, function(){
			jQuery('#commentsReply').focus();
		});
		return false;
	});
	
	// Disable reply button after one click
	jQuery('#submitReply').click(function(){
		jQuery(this).css('display', 'none');
	});
	
	// Annonymizer slide down/up
	jQuery('#makeAnonymous').click(function (){
		jQuery('#anonymous').slideDown(_accountSpeed);
		return false;
	});
	jQuery('#anonymous .cancel').click(function () {
		jQuery('#anonymous').slideUp(_accountSpeed);
		return false;
	});
	
	// Account delete slide down/up
	jQuery('#deleteAccount').click(function () {
		jQuery('#delete').slideDown(_accountSpeed);
		return false;
	});
	jQuery('#delete .cancel').click(function () {
		jQuery('#delete').slideUp(_accountSpeed);
		return false;
	});
	
	// Tooltip effects
	jQuery('.userBadges img[title]').tooltip({
		effect: 'slide',
		slideOffset:-12
	});
	jQuery('span.social img[title]').tooltip({
		effect: 'slide',
		slideOffset:-7,
		tipClass: 'tooltip twolines'
	});
	
	// Fade out badge notifications
	if(jQuery('.announcement.badgewin').length){
		setTimeout("fadeBadgeNotifications()", _badgeFadeDelaySpeed)
	}
	// Fade out success/fail notifications
	if(jQuery('.announcement.success').length || jQuery('.announcement.fail').length){
		setTimeout("fadeSuccessFailNotifications()", _notifFadeDelaySpeed)
	}
	
	// Remove notifications for this session
	if(jQuery('.announcement.note').length){
		jQuery('.announcement.note a.close').click(function(){
			jQuery.ajax({
				url: _baseurl+'/inc/close.notification.handler.php',
				type: 'POST',
				data: 'nid='+jQuery(this).attr('id'),
				success: function(results){
					window.location.reload();
				}
			});
			
			return false;
		});
	}
	
	// Form blur/focus
	inputFB('#searchDept', 'search departments');
	
	inputFB('input.shuffle', 'start typing a college, department, or office');
	inputFB('#commentsShuffle', 'comments');
	inputFB('#commentsReply', 'comments');
	
	inputFB('#first_name', 'First Name');
	inputFB('#last_name', 'Last Name');
	inputFB('#addr1', 'Address');
	inputFB('#addr2', 'Apt or Suite');
	inputFB('#city', 'City');
	inputFB('#state', 'State');
	inputFB('#zip', 'Zip');
	
	inputFB('#email', 'Email');
	inputFB('#password', 'Password', true);
	inputFB('#password_confirm', 'Confirm Password', true);
});

/* ============================== MISC FUNCTIONS ============================== */
// Hide badge notifications
function fadeBadgeNotifications(){
	jQuery('.announcement.badgewin').fadeOut(_fadeOutSpeed);
}
// Hide success/fail notifications
function fadeSuccessFailNotifications(){
	jQuery('.announcement.success').fadeOut(_fadeOutSpeed);
	jQuery('.announcement.fail').fadeOut(_fadeOutSpeed);
}
// Input focus/blur function
function inputFB(){
	var _selector = arguments[0];
	var _value = arguments[1];
	if(arguments.length == 3)
		var _pw = arguments[2];
	else
		var _pw = null;

	jQuery(_selector).live('focus', function(){
		if (jQuery(this).val() == _value) {
			if (_pw) {
				var _id = jQuery(this).attr('id');
				jQuery(this).parent('span').html('<input id="'+_id+'" class="text selected" name="'+_id+'" type="password" value="" />');
				jQuery(_selector).focus();
			} else {
				jQuery(this).val('');
				jQuery(this).addClass('selected');
			}
		}
	});
	jQuery(_selector).live('blur', function(){
		if (jQuery(this).val() == '') {
			if (_pw) {
				var _id = jQuery(this).attr('id');
				jQuery(this).parent('span').html('<input id="'+_id+'" class="text" name="'+_id+'" type="text" value="'+_value+'" />');
			} else {
				jQuery(this).removeClass('selected');
				jQuery(this).val(_value);
			}
		}
	});
}