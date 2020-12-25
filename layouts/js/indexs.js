/*global $, jQuery, alert, window*/
$(function () { 
    "use strict";   
// Hide Placeholder On Form Focus
  $('[placeholder]').focus(function () {
    $(this).attr('data-text', $(this).attr('placeholder'));
    $(this).attr('placeholder', '');
  }).blur(function (){
        $(this).attr ('placeholder', $(this).attr('data-text'));
  });
//scroll top     
    var scrollButton = $("#scroll-top");
      $(window).scroll(function () {
    if ( $(this).scrollTop() >= 600 ) { 
      scrollButton.show();
      } else {
    scrollButton.hide();
      }
    });
    scrollButton.click(function () {
      $("html,body").animate({ scrollTop: 0}, 600);
    });
//login and signup    
    $('span.shift').click(function () {
    $(this).addClass('selected').siblings().removeClass('selected');
    $('.login-page form').hide();
    $('.' + $(this).data('class')).fadeIn(100);
    });
 // Add Asterisk On Required Field
	$('input').each(function () {
		if ($(this).attr('required') === 'required') {
			$(this).before('<span class="asterisk">*</span>');
		}
	});
	// Convert Password Field To Text Field On Hover
	var passField = $('.showpass');
	$('.show-pass').hover(function () {
		passField.attr('type', 'text');
	}, function () {
		passField.attr('type', 'password');
	});
    // Trigger The Selectboxit
	$("select").selectBoxIt({
		autoWidth: false
	});
// form control       
    $('.foot-lnk, .header .click').on("click", function () {
      $('.login-up').toggle("slow"); 
      $('.signup').toggle("slow");
    }); 
  // scroll down
  $('.header .arrow i').click(function () {
    $('html, body').animate({
      scrollTop: $('.features').offset().top
      }, 1000);
  });
  // scroll down login
  $('.header .button-big #checked').click(function () {
    $('html, body').animate({
      scrollTop: $('.login-page').offset().top
      }, 1000);
  });
  $('.header .button-big #checked_2').click(function () {
    $('html, body').animate({
      scrollTop: $('.login-page').offset().top
      }, 1000);
  });
  //confirm massege
  $('.confirm').click(function () {
      return confirm('لديك حساب الان للدخول للمنصة يجب عليك تسجيل الدخول  بالاسم وكلمة المرور   ');
      });
  //moon and sun themes
  var color= $(".profile .setting .lis i");
    color.click(function () {
    $("link[href*='theme']").attr("href", $(this).attr("data-value"));
  });
  //post button
  $('.buttons-box .button_1').on("click", function () {
    $(this).next('div').toggle("slow");
  });
  //answer 
  var i = 0;
  $('.fafafa #com-no').one("click", function () {
    var l = $(this).attr("value");
      i= parseInt(i) + parseInt(l);
      return false;
  }); 
  $('.all .btnnns').one("click", function () {
      $('.result').append(i);
      $('.bam').val(i);
      $(this).next('div').fadeToggle(200);
      $('.spec').addClass('specialgreen');
      $('.speci').addClass('specialred');
  });
  return i;
});
//email
    var focus, length ;
    function validte(focus, length) {
        if (focus.val().length < length ) {
            focus.css('border-bottom', '2px solid #f00').parent().find('.custom_alert').fadeIn(200);
        } else {
            focus.css('border-bottom', '2px solid #080').parent().find('.custom_alert').fadeOut(200);
        }
    }
    $('.username').blur(function () {
       validte($('.username'), 4); 
    });
    $('.username2').blur(function () {
       validte($('.username2'), 4); 
    });
     $('.password').blur(function () {
       validte($('.password'), 4); 
    });
     $('.password2').blur(function () {
       validte($('.password2'), 4); 
    });
    $('.password3').blur(function () {
       validte($('.password3'), 4); 
    });
    $('.phone').blur(function () {
       validte($('.phone'), 8); 
    });  
