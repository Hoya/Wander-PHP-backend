<script type="text/javascript">
//<![CDATA[
	$(document).ready(function()
	{
        $(".contentleft").pngFix();
        
        $('[placeholder]').focus(function()
        {
			var input = $(this);
			if (input.val() == input.attr('placeholder'))
			{
				input.val('');
				input.removeClass('placeholder');
			}
		}).blur(function() 
		{
			var input = $(this);
			if (input.val() == '' || input.val() == input.attr('placeholder'))
			{
				input.addClass('placeholder');
				input.val(input.attr('placeholder'));
			}
		}).blur();
		
		$('[placeholder]').parents('form').submit(function()
		{
			$(this).find('[placeholder]').each(function()
			{
				var input = $(this);
				if (input.val() == input.attr('placeholder'))
				{
					input.val('');
				}
			})
		});
    });

	function registerEmail()
	{
		var input = $("#emailData");
		if (input.val() == input.attr('placeholder'))
		{
			input.val('');
		}
		
		<? if($currentLanguage == "ko"): ?>
		if(isNaN($("#cellphone2").val()))
		{
			$("<div title=\"앗\"><div class=\"popup\"><p>휴대폰 번호는 숫자로만 입력해주세요.</p></div></div>").dialog
					({
						height: 250,
						width: 350,
						modal: true,
						buttons: { "오케이": function() { $(this).dialog("close"); $("#cellphone2").select(); } }
					});
		}
		<? endif; ?>
		
		if(!$("#emailData").val())
		{
			$("<div title=\"<?=lang('home_error_title')?>\"><div class=\"popup\"><p><?=lang('home_error_emptyEmailMsg_p1')?></p></div></div>").dialog
						({
							height: 250,
							width: 350,
							modal: true,
							buttons: { "<?=lang('home_btn_confirm')?>": function() { $(this).dialog("close"); $("#emailData").select(); } }
						});
		}
		else
		{
			$("#regEmail").attr("class", "ysubmitLoading");
			$("#regEmail").blur();
			$("#regEmail").attr("disabled", "disabled");

			$.post(
				"/home/registerEmail",
				{ email: $("#emailData").val() },
				function(data)
				{
					$("#regEmail").attr("class", "ysubmit");
					$("#regEmail").removeAttr("disabled");
					
					if(data == 100)
					{
						$("#signupDoneDialog").dialog
						({
							height: 250,
							width: 350,
							modal: true
						});
					}
					else if(data == 105)
					{
						$("<div title=\"<?=lang('home_error_title')?>\"><div class=\"popup\"><p><?=lang('home_error_notUnivEmailMsg_p1')?></p></div></div>").dialog
						({
							height: 250,
							width: 350,
							modal: true,
							buttons: { "<?=lang('home_btn_confirm')?>": function() { $(this).dialog("close"); $("#emailData").select(); } }
						});
					}
					else if(data == 110)
					{
						$("<div title=\"<?=lang('home_error_title')?>\"><div class=\"popup\"><p><?=lang('home_error_existingEmail_p1')?></p><p><?=lang('home_error_existingEmail_p2')?></p><p><?=lang('home_error_existingEmail_p3')?></p></div></div>").dialog
						({
							height: 270,
							width: 350,
							modal: true,
							buttons: { "<?=lang('home_btn_confirm')?>": function() { $(this).dialog("close"); $("#emailData").select(); } }
						});
					}
					else if(data == 115)
					{
						$("<div title=\"앗!\"><div class=\"popup\"><p>이미 등록된 휴대폰 번호입니다.</p><p>새로운 소식을 이메일 및 휴대폰 문자 메세지로 전달해 드리고 있으니 조금만 기다려주시기 바랍니다.</p><p>기타 의견이나 질문은 hello@wanderwith.us으로 주시기 바랍니다.</p></div></div>").dialog
						({
							height: 270,
							width: 350,
							modal: true,
							buttons: { "오케이": function() { $(this).dialog("close"); $("#emailData").select(); } }
						});
					}
					else
					{
						$("<div title=\"<?=lang('home_error_title')?>\"><div class=\"popup\"><p><?=lang('home_error_invalidEmail_p1')?></p><p><?=lang('home_error_invalidEmail_p2')?></p></div></div>").dialog
						({
							height: 250,
							width: 350,
							modal: true,
							buttons: { "<?=lang('home_btn_confirm')?>": function() { $(this).dialog("close"); $("#emailData").select(); } }
						});
					}
				}
			);
		}

		return false;
	}
	
	function setCookie( name, value, expires, path, domain, secure )
	{
		// set time, it's in milliseconds
		var today = new Date();
		today.setTime( today.getTime() );

		/*
		if the expires variable is set, make the correct
		expires time, the current script below will set
		it for x number of days, to make it for hours,
		delete * 24, for minutes, delete * 60 * 24
		*/
		if( expires )
		{
			expires = expires * 1000 * 60 * 60 * 24;
		}
		var expires_date = new Date( today.getTime() + (expires) );
		
		document.cookie = name + "=" +escape( value ) +
		( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
		( ( path ) ? ";path=" + path : "" ) +
		( ( domain ) ? ";domain=" + domain : "" ) +
		( ( secure ) ? ";secure" : "" );
	}
	
	function getCookie( check_name )
	{
		// first we'll split this cookie up into name/value pairs
		// note: document.cookie only returns name=value, not the other components
		var a_all_cookies = document.cookie.split( ';' );
		var a_temp_cookie = '';
		var cookie_name = '';
		var cookie_value = '';
		var b_cookie_found = false; // set boolean t/f default f

		for ( i = 0; i < a_all_cookies.length; i++ )
		{
			// now we'll split apart each name=value pair
			a_temp_cookie = a_all_cookies[i].split( '=' );

			// and trim left/right whitespace while we're at it
			cookie_name = a_temp_cookie[0].replace(/^\s+|\s+$/g, '');

			// if the extracted name matches passed check_name
			if ( cookie_name == check_name )
			{
				b_cookie_found = true;
				// we need to handle case where cookie has no value but exists (no = sign, that is):
				if ( a_temp_cookie.length > 1 )
				{
					cookie_value = unescape( a_temp_cookie[1].replace(/^\s+|\s+$/g, '') );
				}
				// note that in cases where cookie is initialized but no value, null is returned
				return cookie_value;
				break;
			}
			a_temp_cookie = null;
			cookie_name = '';
		}
		if ( !b_cookie_found )
		{
			return null;
		}
	}
//]]>
</script>

<div id="wrapper">
<div id="main">

<div id="headbox">
	<? if($currentLanguage != 'zh'): ?><div class="headitem"><a href="http://cn.wanderwith.us/">中文</a></div><? endif; ?>
    <? if($currentLanguage != 'ja'): ?><div class="headitem"><a href="http://jp.wanderwith.us/">日本語</a></div><? endif; ?>
	<? if($currentLanguage != 'ko'): ?><div class="headitem"><a href="http://kr.wanderwith.us/">한국어</a></div><? endif; ?>
	<? if($currentLanguage != 'en'): ?><div class="headitem"><a href="/home/index/en">English</a></div><? endif; ?>
<!--   
    <div id="logobox">
    <a href="/" ><img src="/img/logo.png" /></a>
    </div>
-->    
</div>

<div id="topbox">
<!--
    <?
    	$data['navmenu'] = $navmenu;
    	$this->load->view('home/topnav.php', $data);
    ?>
-->    
</div>
<div id="contentbox">
	<div class="contentleft">
    <div class="mainpic"></div>
	</div>
    <div class="contentright">
    <div class="righttop"></div>
    <div class="rightmid">
    	<h1><img src="/img/title.png" /></h1>
        <? if(lang('home_main_p1')): ?><p><?=lang('home_main_p1')?></p><? endif; ?>
        <? if(lang('home_main_p2')): ?><p><?=lang('home_main_p2')?></p><? endif; ?>
        <? if(lang('home_main_p3')): ?><p><?=lang('home_main_p3')?></p><? endif; ?>
        <div class="downloadbutton">
   		<a href="http://wndrw.me/ppE0MC"><img src="/img/viewPhoto/btn_download.png" /></a>     
        </div>
        <!--
		<div class="downloadbutton">
   		<a href="http://android.wanderwith.us"><img src="/img/btn_andy.png" /></a>     
        </div>
        -->
        <div style="clear:both;"></div>
<!--    
		<h4><?=lang('home_sub_title')?></h4>
		<form onsubmit="return registerEmail()">
		<p><?=lang('home_sub_p1')?></p>
		<? if(lang('home_sub_p2')): ?><p><?=lang('home_sub_p2')?></p><? endif; ?>
			<? if($currentLanguage == "ko"): ?>
			<select name="cellphone1" id="cellphone1" class="ydrop">
        		<option>010</option>
        		<option>011</option>
        		<option>016</option>
        		<option>017</option>
        		<option>018</option>
        		<option>019</option>
        	</select>
        	<input type="text" class="ymobi" name="cellphone2" id="cellphone2" placeholder="휴대폰 번호를 입력하세요" />
        	<br />
        	<? endif; ?>
        	<input type="text" class="ytext" id="emailData" placeholder="<?=lang('home_emailplaceholder')?>"/>
            <input type="submit" class="ysubmit" id="regEmail" value="" />
		</form>
-->
		<br />
		<!-- tweet button start -->
		<a href="http://twitter.com/share" class="twitter-share-button" data-url="http://wndrw.me/ppE0MC" data-text="<?=lang('home_tweet')?>" data-count="none">Tweet</a>
		<!-- tweet button end -->
        
		<!-- small facebook like button start -->
		<iframe src="http://www.facebook.com/plugins/like.php?href=<?=rawurlencode("http://wanderwith.us")?>&layout=button_count&show_faces=false&width=80&action=like&font=lucida+grande&colorscheme=light" allowtransparency="true" style="border: medium none; overflow: hidden; width: 80px; height: 21px; margin-left:10px;" frameborder="0" scrolling="no"></iframe>
		<br /><br /><br />
        <!-- small facebook like button end -->

		</div>
        <div class="rightbot"></div>
		
    </div><!-- end contentright-->
</div><!-- end contentbox -->
</div><!-- end main -->
<div class="copyright">

<ul id="botnav">
	<li><a href="/home" >Home</a></li> 
	<li><a href="/home/about">About us</a></li> 
	<li><a href="mailto:hello@wanderwith.us">Contact us</a></li> 
	<li><a href="https://twitter.com/#/wanderapp">@wanderapp</a></li>
	<li>&copy;2011 YongoPal, Inc. All rights reserved.</li>
</ul>

</div>
</div> <!-- end wrapper -->

<div id="bottomcap">
	<div id="footerbg">
		<div id="footerfg">
        	<div id="footer">
            </div>
        </div>
    </div>
</div><!-- end bottomcap -->

<!-- signup success dialog start -->
<div title="<?=lang('home_invitation_title')?>" id="signupDoneDialog" style="display: none">
	<div class="popup">
		<p><?=lang('home_invitation_p1')?></p>
		<p><?=lang('home_invitation_p2')?></p>
		<p>
		<a href="http://twitter.com/share" class="twitter-share-button" data-text="<?=lang('home_tweet')?>" data-count="horizontal">Tweet</a>
		<iframe src="http://www.facebook.com/plugins/like.php?href=<?=rawurlencode("http://wanderwith.us")?>&layout=button_count&show_faces=false&width=80&action=like&font=lucida+grande&colorscheme=light" allowtransparency="true" style="border: medium none; overflow: hidden; width: 80px; height: 21px;" frameborder="0" scrolling="no"></iframe>
		</p>
	</div>
</div>
<!-- signup success dialog end -->

