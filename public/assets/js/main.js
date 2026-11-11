/* ===================================================================
    
    Author          : Valid Theme
    Template Name   : Edufix - Education HTML Template
    Version         : 1.0
    
* ================================================================= */
(function($) {
	"use strict";

	$(document).ready(function() {

		/* ==================================================
		    # Tooltip Init
		===============================================*/
		$('[data-toggle="tooltip"]').tooltip();


		/* ==================================================
		    # Youtube Video Init
		 ===============================================*/
		$('.player').mb_YTPlayer();


		/* ==================================================
		    # Wow active
		===============================================*/
		new WOW().init();


		/* ==================================================
		    # imagesLoaded active
		===============================================*/
		$('#gallery-masonary,.blog-masonry').imagesLoaded(function() {

			/* Filter menu */
			$('.mix-item-menu').on('click', 'button', function() {
				var filterValue = $(this).attr('data-filter');
				$grid.isotope({
					filter: filterValue
				});
			});

			/* filter menu active class  */
			$('.mix-item-menu button').on('click', function(event) {
				$(this).siblings('.active').removeClass('active');
				$(this).addClass('active');
				event.preventDefault();
			});

			/* Filter active */
			var $grid = $('#gallery-masonary').isotope({
				itemSelector: '.gallery-item',
				percentPosition: true,
				masonry: {
					columnWidth: '.gallery-item',
				}
			});

			/* Filter active */
			$('.blog-masonry').isotope({
				itemSelector: '.blog-item',
				percentPosition: true,
				masonry: {
					columnWidth: '.blog-item',
				}
			});

		});


		/* ==================================================
		    # Fun Factor Init
		===============================================*/
		$('.timer').countTo();
		$('.fun-fact').appear(function() {
			$('.timer').countTo();
		}, {
			accY: -100
		});


		/* ==================================================
			# Nice Select Init
		===============================================*/
		$('.admission-form-style-one select').niceSelect();
		$('.category-search select').niceSelect();
		$('.form-style-two select').niceSelect();


		/* ==================================================
		    # Magnific popup init
		 ===============================================*/
		$(".popup-link").magnificPopup({
			type: 'image',
			// other options
		});

		$(".popup-gallery").magnificPopup({
			type: 'image',
			gallery: {
				enabled: true
			},
			// other options
		});

		$(".popup-youtube, .popup-vimeo, .popup-gmaps").magnificPopup({
			type: "iframe",
			mainClass: "mfp-fade",
			removalDelay: 160,
			preloader: false,
			fixedContentPos: false
		});

		$('.magnific-mix-gallery').each(function() {
			var $container = $(this);
			var $imageLinks = $container.find('.item');

			var items = [];
			$imageLinks.each(function() {
				var $item = $(this);
				var type = 'image';
				if ($item.hasClass('magnific-iframe')) {
					type = 'iframe';
				}
				var magItem = {
					src: $item.attr('href'),
					type: type
				};
				magItem.title = $item.data('title');
				items.push(magItem);
			});

			$imageLinks.magnificPopup({
				mainClass: 'mfp-fade',
				items: items,
				gallery: {
					enabled: true,
					tPrev: $(this).data('prev-text'),
					tNext: $(this).data('next-text')
				},
				type: 'image',
				callbacks: {
					beforeOpen: function() {
						var index = $imageLinks.index(this.st.el);
						if (-1 !== index) {
							this.goTo(index);
						}
					}
				}
			});
		});


		/* ==================================================
            # Banner Carousel
         ===============================================*/
		const bannerFade = new Swiper(".banner-fade", {
			// Optional parameters
			direction: "horizontal",
			loop: true,
			autoplay: true,
			effect: "fade",
			fadeEffect: {
				crossFade: true
			},


			// If we need pagination
			pagination: {
				el: '.swiper-pagination',
				type: 'bullets',
				clickable: true,
			},

			// Navigation arrows
			navigation: {
				nextEl: ".swiper-button-next",
				prevEl: ".swiper-button-prev"
			}

			// And if we need scrollbar
			/*scrollbar: {
            el: '.swiper-scrollbar',
          },*/
		});


		/* ==================================================
            # Brand Carousel
         ===============================================*/
		const brandOne = new Swiper(".brand-style-one-carousel", {
			// Optional parameters
			loop: true,
			slidesPerView: 2,
			spaceBetween: 30,
			autoplay: false,
			breakpoints: {
				768: {
					slidesPerView: 3,
					spaceBetween: 30,
				},
				992: {
					slidesPerView: 3,
					spaceBetween: 30,
				},
				1200: {
					slidesPerView: 5,
					spaceBetween: 100,
				}
			},
		});


		/* ==================================================
            # Category One Carousel
         ===============================================*/
		const categoryOne = new Swiper(".category-style-one-carousel", {
			// Optional parameters
			loop: true,
			slidesPerView: 1,
			spaceBetween: 0,
			autoplay: true,
			// If we need pagination
			pagination: {
				el: '.category-pagination',
				type: 'fraction',
				clickable: true,
			},
			// Navigation arrows
			navigation: {
				nextEl: ".category-button-next",
				prevEl: ".category-button-prev"
			},
			breakpoints: {
				768: {
					slidesPerView: 2,
				},
				1200: {
					slidesPerView: 3,
				}
			},
		});


		/* ==================================================
            # Category two Carousel
         ===============================================*/
		const categoryTwo = new Swiper(".category-style-two-carousel", {
			// Optional parameters
			loop: true,
			slidesPerView: 1,
			spaceBetween: 30,
			autoplay: true,
			breakpoints: {
				768: {
					slidesPerView: 2,
				},
				1200: {
					slidesPerView: 4,
				}
			},
		});


		/* ==================================================
            # Testimonial One Carousel
         ===============================================*/

		var testimonialOne = new Swiper('.testimonial-style-one-carousel', {
			spaceBetween: 10,
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			loop: false,
			loopedSlides: 3,
		});
		var testimonialBullet = new Swiper('.testimonial-bullet', {
			spaceBetween: 0,
			touchRatio: 0.2,
			slideToClickedSlide: true,
			loop: false,
			loopedSlides: 3,
			effect: "cards",
		});
		testimonialOne.controller.control = testimonialBullet;
		testimonialBullet.controller.control = testimonialOne;



		/* ==================================================
            # Course One Carousel
         ===============================================*/

		var courseOne = new Swiper('.course-style-one-carousel', {
			spaceBetween: 10,
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			loop: true,
			loopedSlides: 5,
		});
		var courseBullet = new Swiper('.course-style-one-bullet', {
			spaceBetween: 0,
			slidesPerView: 'auto',
			touchRatio: 0.2,
			slideToClickedSlide: true,
			loop: true,
			loopedSlides: 5,
			spaceBetween: 15,
			breakpoints: {
				768: {
					slidesPerView: 2,
				},
				992: {
					slidesPerView: 2,
				},
				1200: {
					slidesPerView: 3,
				},
				1400: {
					slidesPerView: 4,
				},
			},
		});
		courseBullet.controller.control = courseOne;


		/* ==================================================
            # Course Inner Carousel
         ===============================================*/
		const courseInnerCarousel = new Swiper(".course-inner-carousel", {
			// Optional parameters
			loop: true,
			slidesPerView: 1,
			spaceBetween: 0,
			autoplay: true,
			breakpoints: {
				768: {
					slidesPerView: 2,
				},
				1400: {
					slidesPerView: 3,
				},
			},
		});


		/* ==================================================
            # Certificate Carousel
         ===============================================*/
		const certificateCarousel = new Swiper(".certificate-carousel", {
			// Optional parameters
			loop: true,
			slidesPerView: 1,
			spaceBetween: 0,
			autoplay: true,
		});


		/* ==================================================
            # Testimonial Two Carousel
         ===============================================*/
		const TestimonialTwo = new Swiper(".testimonial-style-two-carousel", {
			// Optional parameters
			loop: true,
			freemode: true,
			slidesPerView: 'auto',
			spaceBetween: 15,
			centeredSlides: true,
			allowTouchMove: false,
			slidesPerView: 1,
			speed: 6000,
			autoplay: {
				delay: 1,
				disableOnInteraction: true,
			},
			breakpoints: {
				768: {
					slidesPerView: 2,
				},
				992: {
					slidesPerView: 3,
				},
				1200: {
					slidesPerView: 4,
				},
			},
		});


		/* ==================================================
            # Course Two Carousel
         ===============================================*/
		const courseTwoCarousel = new Swiper(".course-style-two-carousel", {
			// Optional parameters
			loop: true,
			slidesPerView: 1,
			spaceBetween: 0,
			autoplay: true,
			// If we need pagination
			pagination: {
				el: '.course-two-pagination',
				type: 'fraction',
				clickable: true,
			},
			// Navigation arrows
			navigation: {
				nextEl: ".course-two-button-next",
				prevEl: ".course-two-button-prev"
			},
			breakpoints: {
				768: {
					slidesPerView: 2,
				},
				1400: {
					slidesPerView: 3,
				}
			},
		});


		/* ==================================================
            # Testimonial Three Carousel
         ===============================================*/
		const testimonialthreeCarousel = new Swiper(".testimonial-style-three-carousel", {
			// Optional parameters
			loop: true,
			slidesPerView: 1,
			spaceBetween: 0,
			autoplay: true,
			breakpoints: {
				768: {
					slidesPerView: 2,
				},
				991: {
					slidesPerView: 2,
				}
			},
		});


		/* ==================================================
            # Testimonial Four
         ===============================================*/
		const testimonialFourCarousel = new Swiper(".testimonial-style-four-carousel", {
			// Optional parameters
			loop: true,
			slidesPerView: 1,
			spaceBetween: 0,
			autoplay: true,
			// Navigation arrows
			navigation: {
				nextEl: ".testimonial-four-button-next",
				prevEl: ".testimonial-four-button-prev"
			},
		});


		/* ==================================================
            # Child Program
         ===============================================*/
		const childProgram = new Swiper(".child-program-carousel", {
			// Optional parameters
			loop: true,
			slidesPerView: 1,
			spaceBetween: 30,
			autoplay: true,
			breakpoints: {
				768: {
					slidesPerView: 2,
				},
				991: {
					slidesPerView: 3,
				},
				1400: {
					slidesPerView: 4,
				}
			},
		});


		/* ==================================================
            # Gallery Carousel
         ===============================================*/
		const gallleryOneCarousel = new Swiper(".gallery-style-one-carousel", {
			// Optional parameters
			loop: true,
			slidesPerView: 1,
			spaceBetween: 30,
			autoplay: true,
			breakpoints: {
				768: {
					slidesPerView: 2,
				},
				991: {
					slidesPerView: 3,
				},
				1400: {
					slidesPerView: 4,
					spaceBetween: 60,
				}
			},
		});


		/* ==================================================
            # Relted Course Carousel
         ===============================================*/
		const relatedCourseCarousel = new Swiper(".related-course-carousel", {
			// Optional parameters
			loop: true,
			slidesPerView: 1,
			spaceBetween: 0,
			autoplay: true,
			breakpoints: {
				768: {
					slidesPerView: 2,
				},
				1400: {
					slidesPerView: 3,
				}
			},
		});


		/* ==================================================
            # Testimonial Five Carousel
         ===============================================*/
		const testimonialFiveCarousel = new Swiper(".testimonial-style-five-carousel", {
			// Optional parameters
			loop: true,
			slidesPerView: 1,
			spaceBetween: 0,
			autoplay: true,
			// Navigation arrows
		});

		/* ==================================================
		    Splite Text
		================================================== */
		let text_split = document.querySelector(".split-text");
		if (text_split) {
			const animEls = document.querySelectorAll('.split-text');
			animEls.forEach(el => {
				var splitEl = new SplitText(el, {
					type: "lines, words",
					linesClass: "line"
				});
				var splitTl = gsap.timeline({
					duration: 0,
					ease: 'power4',
					scrollTrigger: {
						trigger: el,
						start: 'top 90%'
					}
				});

				splitTl.from(splitEl.words, {
					yPercent: "100",
					stagger: 0.025,
				});

			});
		}


		/* ==================================================
		    Contact Form Validations
		================================================== */
		$('.contact-form').each(function() {
			var formInstance = $(this);
			formInstance.submit(function() {

				var action = $(this).attr('action');

				$("#message").slideUp(750, function() {
					$('#message').hide();

					$('#submit')
						.after('<img src="assets/img/ajax-loader.gif" class="loader" />')
						.attr('disabled', 'disabled');

					$.post(action, {
							name: $('#name').val(),
							email: $('#email').val(),
							phone: $('#phone').val(),
							comments: $('#comments').val()
						},
						function(data) {
							document.getElementById('message').innerHTML = data;
							$('#message').slideDown('slow');
							$('.contact-form img.loader').fadeOut('slow', function() {
								$(this).remove()
							});
							$('#submit').removeAttr('disabled');
						}
					);
				});
				return false;
			});
		});

	}); // end document ready function

	/* ==================================================
        Preloader Init
     ===============================================*/
	function loader() {
		$(window).on('load', function() {
			$('#edufix-preloader').addClass('loaded');
			$("#loading").fadeOut(500);
			// Una vez haya terminado el preloader aparezca el scroll

			if ($('#edufix-preloader').hasClass('loaded')) {
				// Es para que una vez que se haya ido el preloader se elimine toda la seccion preloader
				$('#preloader').delay(900).queue(function() {
					$(this).remove();
				});
			}
		});
	}
	loader();

})(jQuery); // End jQuery