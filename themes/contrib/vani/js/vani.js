/* Load jQuery.
--------------------------*/
jQuery(document).ready(function ($) {
  // Mobile menu.
  $('.mobile-menu').click(function () {
    $(this).next('.primary-menu-wrapper').toggleClass('active-menu');
  });
  $('.close-mobile-menu').click(function () {
    $(this).closest('.primary-menu-wrapper').toggleClass('active-menu');
  });
  // Full page search.
  $('.search-icon').on('click', function() {
    $('.search-box').addClass('open');
    return false;
  });

  $('.search-box-close').on('click', function() {
    $('.search-box').removeClass('open');
    return false;
  });
  $(document).keyup(function(e) {
    if (e.key === "Escape") {
      $('.search-box').removeClass('open');
   }
  });
  // Scroll To Top.
  $(window).scroll(function () {
    if ($(this).scrollTop() > 80) {
      $('.scrolltop').css('display', 'grid');
    } else {
      $('.scrolltop').fadeOut('slow');
    }
  });
  $('.scrolltop').click(function () {
    $('html, body').scrollTop(0);
  });

  // Wrap homepage blocks
  $(".region-content-home .block").wrapInner( '<div class="container"></div>' );
  $(".region-content-home .block:nth-child(even)").prepend( '<div class="home-block-cicle1"></div><div class="home-block-cicle2"></div><div class="home-block-cicle3"></div>' );
/* End document
--------------------------*/
});

 const navbar = document.querySelector('.header');
 window.onscroll = () => {
     if (window.scrollY > 100) {
        navbar.classList.add('scrolled');
     } else {
        navbar.classList.remove('scrolled');
    }
 };
// const navbar1 = document.querySelector('.header, .header-main');
// window.onscroll = () => {
//     if (window.scrollY > 70) {
//         navbar1.classList.add('scrolled');
//     } else {
//         navbar1.classList.remove('scrolled');
//     }
// };
// const a = document.querySelector('menulink');
// window.onscroll = () => {
//     if (window.scrollY > 70) {
//         a.classList.add('scrolled');
//     } else {
//         a.classList.remove('scrolled');
//     }
// };

