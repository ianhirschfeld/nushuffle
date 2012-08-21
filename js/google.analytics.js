jQuery(document).ready(function(){
	// Notifications
	jQuery('.announcement a').click(function(){
		try{
			if(jQuery(this).attr('class') == 'close'){
				_gaq.push(['_trackEvent', 'Notifications', 'Close', (jQuery(this).text() || jQuery(this).children('img:first').attr('alt') || jQuery(this).attr('id'))]);
			}else{
				_gaq.push(['_trackEvent', 'Notifications', 'Click', (jQuery(this).text() || jQuery(this).children('img:first').attr('alt') || jQuery(this).attr('id'))]);
			}
		} catch(err){}
		
		var date = new Date();
		var curDate = null;
		do{
			curDate = new Date();
		}while(curDate-date < 300);
	});
	
	// Report Card
	jQuery('#reportCard a').click(function(){
		try{
			_gaq.push(['_trackEvent', 'Report Card', 'Click', (jQuery(this).text() || jQuery(this).children('img:first').attr('alt') || jQuery(this).attr('id'))]);
		} catch(err){}
		
		var date = new Date();
		var curDate = null;
		do{
			curDate = new Date();
		}while(curDate-date < 300);
	});
	
	// Right Column
	jQuery('#userInfo a').click(function(){
		try{
			if(jQuery(this).attr('id') != ''){
				_gaq.push(['_trackEvent', 'Right Column Bio', 'Click', (jQuery(this).attr('id') || jQuery(this).text() || jQuery(this).children('img:first').attr('alt'))]);
			}else{
				_gaq.push(['_trackEvent', 'Right Column Bio', 'Click', (jQuery(this).text() || jQuery(this).children('img:first').attr('alt') || jQuery(this).attr('id'))]);
			}
		} catch(err){}
		
		var date = new Date();
		var curDate = null;
		do{
			curDate = new Date();
		}while(curDate-date < 300);
	});
	jQuery('#redeem a').click(function(){
		try{
			_gaq.push(['_trackEvent', 'Redeem Points', 'Click', (jQuery(this).text() || jQuery(this).children('img:first').attr('alt') || jQuery(this).attr('id'))]);
		} catch(err){}
		
		var date = new Date();
		var curDate = null;
		do{
			curDate = new Date();
		}while(curDate-date < 300);
	});
	
	// Header and Footer
	jQuery('#logo').click(function(){
		try{
			_gaq.push(['_trackEvent', 'Header', 'Click', 'Logo']);
		} catch(err){}
		
		var date = new Date();
		var curDate = null;
		do{
			curDate = new Date();
		}while(curDate-date < 300);
	});
	jQuery('#formSearch').submit(function(){
		try{
			if(jQuery('#searchDept').val() != 'search departments' && jQuery('#searchDept').val() != '')
				_gaq.push(['_trackEvent', 'Header', 'Search', jQuery('#searchDept').val()]);
		} catch(err){}
		
		var date = new Date();
		var curDate = null;
		do{
			curDate = new Date();
		}while(curDate-date < 300);
	});
	jQuery('#globalFooter a').click(function(){
		try{
			_gaq.push(['_trackEvent', 'Footer', 'Click', (jQuery(this).text() || jQuery(this).children('img:first').attr('alt') || jQuery(this).attr('id'))]);
		} catch(err){}
		
		var date = new Date();
		var curDate = null;
		do{
			curDate = new Date();
		}while(curDate-date < 300);
	});
	
	// Shuffle/Good/Tip Forms
	jQuery('#shuffleTabs a').click(function(){
		try{
			_gaq.push(['_trackEvent', 'Ratings Form', 'Tabs', (jQuery(this).text() || jQuery(this).children('img:first').attr('alt') || jQuery(this).attr('id'))]);
		} catch(err){}
		
		var date = new Date();
		var curDate = null;
		do{
			curDate = new Date();
		}while(curDate-date < 300);
	});
	jQuery('#shuffleSelections a').click(function(){
		try{
			_gaq.push(['_trackEvent', 'Ratings Form', 'Shuffle Selections', (jQuery(this).text() || jQuery(this).children('img:first').attr('alt') || jQuery(this).attr('id'))]);
		} catch(err){}
		
		var date = new Date();
		var curDate = null;
		do{
			curDate = new Date();
		}while(curDate-date < 300);
	});
	jQuery('#shuffle a').click(function(){
		try{
			_gaq.push(['_trackEvent', 'Ratings Form', 'Form Clicks', (jQuery(this).text() || jQuery(this).children('img:first').attr('alt') || jQuery(this).attr('id'))]);
		} catch(err){}
		
		var date = new Date();
		var curDate = null;
		do{
			curDate = new Date();
		}while(curDate-date < 300);
	});
	jQuery('.formSocial').click(function(){
		try{
			_gaq.push(['_trackEvent', 'Ratings Form', 'Social Checkboxes', jQuery(this).attr('id')]);
		} catch(err){}
		
		var date = new Date();
		var curDate = null;
		do{
			curDate = new Date();
		}while(curDate-date < 300);
	});
	
	// Account
	jQuery('#account a').click(function(){
		try{
			_gaq.push(['_trackEvent', 'Account', 'Click', (jQuery(this).text() || jQuery(this).children('img:first').attr('alt') || jQuery(this).attr('id'))]);
		} catch(err){}
		
		var date = new Date();
		var curDate = null;
		do{
			curDate = new Date();
		}while(curDate-date < 300);
	});
	jQuery('#account .accountCheckbox').click(function(){
		try{
			_gaq.push(['_trackEvent', 'Account', 'Settings Checkboxes', jQuery(this).attr('id')]);
		} catch(err){}
		
		var date = new Date();
		var curDate = null;
		do{
			curDate = new Date();
		}while(curDate-date < 300);
	});
	
	// Recent Activity
	jQuery('#posts a').click(function(){
		try{
			if(jQuery(this).attr('id') != ''){
				_gaq.push(['_trackEvent', 'Recent Activity', 'Click', (jQuery(this).attr('id') || jQuery(this).text() || jQuery(this).children('img:first').attr('alt'))]);
			}else{
				_gaq.push(['_trackEvent', 'Recent Activity', 'Click', (jQuery(this).text() || jQuery(this).children('img:first').attr('alt') || jQuery(this).attr('id'))]);
			}
		} catch(err){}
		
		var date = new Date();
		var curDate = null;
		do{
			curDate = new Date();
		}while(curDate-date < 300);
	});
	
	// Department(s)
	jQuery('#departments a').click(function(){
		try{
			_gaq.push(['_trackEvent', 'Departments', 'Click', (jQuery(this).text() || jQuery(this).children('img:first').attr('alt') || jQuery(this).attr('id'))]);
		} catch(err){}
		
		var date = new Date();
		var curDate = null;
		do{
			curDate = new Date();
		}while(curDate-date < 300);
	});
	
	// Search Results
	jQuery('#searchResults a').click(function(){
		try{
			_gaq.push(['_trackEvent', 'Search Results', 'Click', (jQuery(this).text() || jQuery(this).children('img:first').attr('alt') || jQuery(this).attr('id'))]);
		} catch(err){}
		
		var date = new Date();
		var curDate = null;
		do{
			curDate = new Date();
		}while(curDate-date < 300);
	});
	
	// User
	jQuery('#infoSection.user a').click(function(){
		try{
			_gaq.push(['_trackEvent', 'Users', 'Click', (jQuery(this).text() || jQuery(this).children('img:first').attr('alt') || jQuery(this).attr('id'))]);
		} catch(err){}
		
		var date = new Date();
		var curDate = null;
		do{
			curDate = new Date();
		}while(curDate-date < 300);
	});
});