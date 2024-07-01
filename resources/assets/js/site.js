//import './mobileNavigation.plugin';
//import './dropdown.plugin';
//import './stickyHeader.plugin';
import './accordion.plugin';
import './sortHighlighter.plugin';
import Vue from 'vue';
import Reviews from './Reviews.vue';
import Product from './Product.vue';
import LoginCta from './LoginCta.vue';
import CartCount from './CartCount.vue';
import PhotoManager from './PhotoManager.vue';
import Conversation from './Conversation.vue';
import MessageWatcher from './MessageWatcher.vue';
import MeetTheDerm from './MeetTheDerm.vue';
import NotesInput from './NotesInput.vue';
import Products from './Products.vue';
import WeightProducts from './WeightProducts.vue';
import PhoneField from './PhoneField.vue';
import CouponCodeField from './CouponCodeField.vue';
import PrescriberCouponCodeField from './PrescriberCouponCodeField.vue';
import RefillFrequencySetter from './RefillFrequencySetter.vue';
import PrescriptionCanceler from './PrescriptionCanceler.vue';
import CampaignEffectsManager from './CampaignEffectsManager.vue';
import CampaignEffect from './CampaignEffect.vue';
import OfferManager from './OfferManager.vue';
import OfferManagerModal from './OfferManagerModal.vue';
import ConfirmationModal from './ConfirmationModal.vue';
import conditioner from 'offhand-conditioner';
import creditCardType from 'credit-card-type';
import CodeBrowser from './CodeBrowser.vue';
import swal from 'sweetalert';
import fancybox from '@fancyapps/fancybox';
import "@fancyapps/fancybox/dist/jquery.fancybox.min.css";
import 'es6-promise/auto';
import $ from 'jquery';
import Inputmask from "inputmask";
import Flickity from 'flickity';
import { Splide } from '@splidejs/splide';
import { AutoScroll } from '@splidejs/splide-extension-auto-scroll';

require('formdata-polyfill');

window.$ = $;
window.ccType = creditCardType;

Vue.component('v-reviews', Reviews);
Vue.component('v-product', Product);
Vue.component('v-login-cta', LoginCta);
Vue.component('v-photo-manager', PhotoManager);
Vue.component('v-cart-count', CartCount);
Vue.component('v-conversation', Conversation);
Vue.component('v-message-watcher', MessageWatcher);
Vue.component('v-meet-the-derm', MeetTheDerm);
Vue.component('v-notes-input', NotesInput);
Vue.component('v-coupon-code', CouponCodeField);
Vue.component('v-prescriber-coupon-code', PrescriberCouponCodeField);
Vue.component('v-products', Products);
Vue.component('v-weight-products', WeightProducts);
Vue.component('v-phone-field', PhoneField);
Vue.component('v-refill-frequency-setter', RefillFrequencySetter);
Vue.component('v-prescription-canceler', PrescriptionCanceler);
Vue.component('campaign-effects-manager', CampaignEffectsManager);
Vue.component('campaign-effect', CampaignEffect);
Vue.component('offer-manager', OfferManager);
Vue.component('offer-manager-modal', OfferManagerModal);
Vue.component('confirmation-modal', ConfirmationModal);
Vue.component('code-browser', CodeBrowser);

new Vue({
    el: '#site-container',
});

Flickity.prototype._touchActionValue = 'pan-y pinch-zoom';

const buttons = document.querySelectorAll('[data-product-id]')
if(buttons) {
    buttons.forEach(button => {
        button.addEventListener('click', function(event) {
            let url;
            event.preventDefault()
            const productIdStorageKey = button.hasAttribute('data-product-id-weight') ? 'weightProductId' : 'productId';
            const productId = button.getAttribute('data-product-id');
            if (productId) {
                localStorage.setItem(productIdStorageKey, productId);
            } else {
                localStorage.removeItem(productIdStorageKey);
            }

            if (productIdStorageKey === 'weightProductId') {
                url = '/questions-weight';
                localStorage.removeItem('questionsWeightFormData');
                localStorage.setItem('productType', 'weight');
            } else {
                url = '/questions';
                localStorage.removeItem('questionsFormData');
                localStorage.setItem('productType', 'mental_health');
            }

            window.location.href = url;
        })
    })
}

$(function() {

    conditioner('body');

    // Date Masking
    Inputmask({"mask": "99/99/9999"}).mask("#date");

    $(".heroVideo__soundBtn").click( function () {
        if( $(".heroVideo video").prop('muted') ) {
            $(".heroVideo video").prop('muted', false);
            $(".heroVideo__soundBtn").addClass('active')
        } else {
            $(".heroVideo video").prop('muted', true);
            $(".heroVideo__soundBtn").removeClass('active')
        }
    });

    $(".productsTopBanner__images__mute").click( function () {
        if( $(".productsTopBanner__images video").prop('muted') ) {
            $(".productsTopBanner__images video").prop('muted', false);
            $(".productsTopBanner__images__mute").addClass('active')
        } else {
            $(".productsTopBanner__images video").prop('muted', true);
            $(".productsTopBanner__images__mute").removeClass('active')
        }
    });

    $('.productsTopBanner__images video').on('loadeddata', function() {
        $('.productsTopBanner__images__mute').show()
    });

    if($(window).width() < 768) {
        $('.heroVideo video').on('loadeddata', function() {
            setTimeout(function() {
                $('.heroVideo img').fadeOut();
                $('.heroVideo video').show();
                $('.heroVideo__soundBtn').show()
                $('.heroVideo').css('height', 'auto')
                if($('.heroVideo__words').length > 0) {
                    $('.heroVideo__words').hide();
                }
            }, 150)
        });
        let i = 0
        $('.heroVideo__words span i').eq(0).addClass('active')
        $('.heroVideo__words span').css('width', $('.heroVideo__words span i').eq(0).width() + 'px')
        setInterval(function() {
            $('.heroVideo__words span i').removeClass('active')
            $('.heroVideo__words span i').eq(i).addClass('active')
            i++
            if(i > $('.heroVideo__words span i').length - 1) {
                i = 0
            }
            let currentWidth = $('.heroVideo__words span i.active').width()
            $('.heroVideo__words span').css('width', currentWidth + 'px')
        }, 2000)
        // $('.background-video-text.mobile--video .background-video-text__video video').on('loadeddata', function() {
        //     $('.background-video-text.mobile--video .background-video-text__video img').remove()
        //     $(this).show()
        // });
    }else {
        $('.background-video-text__video video').on('loadeddata', function() {
            $('.background-video-text img.desktopImage').hide();
        });
        $('.heroVideo video').on('loadeddata', function() {
            $('.heroVideo img').fadeOut();
            $('.heroVideo video').show();
            $('.heroVideo__soundBtn').show()
        });
    }

    // Sticky Header
    $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
           $('header.home').addClass('header-fixed');
        } else {
           $('header.home').removeClass('header-fixed');
        }
        let videoHeight = $('.heroVideo').height() / 2

        if($(this).scrollTop() > videoHeight) {
            $('.heroVideo video').trigger('pause');
        } else {
            $('.heroVideo video').trigger('play');
        }
    });

    // ----------------------
    // Main navigation
    var body            = $('body'),
        navIcon         = $('.nav-icon'),
        navContainer    = $('.menu'),
        navPrimary      = $('nav.primary');

    navIcon.on('click', function() {
        navContainer.toggleClass('menu-active');
        navIcon.toggleClass('nav-icon--active');
        navPrimary.toggleClass('nav-active');
    });

    // Submenu nav
    var hasDropdown     = $('.has-dropdown > a'),
        dropdownMenu    = $('.dropdown-menu');

    hasDropdown.on('click', function(e) {
        if ($(window).width() < 960) {
            e.preventDefault();
        }
        hasDropdown.parent('li').toggleClass('has-dropdown--active');
        dropdownMenu.toggleClass('dropdown-menu--active');
    });


    // ----------------------
    // Team member area
    var teamMemberArea 		= $('.team-profile-trigger, .team-member--image'),
    	teamMemberInfo		= $('.team-member--info'),
    	teamProfileTrigger	= $('.team-profile-trigger'),
    	teamMemberBio		= $('.team-member--bio');

        $.fn.slideFadeToggle = function(speed, easing, callback) {
            return
                this.animate({
                    opacity: 'toggle',
                    height: 'toggle'
                },
                speed,
                easing,
                callback
            );
        }

    teamMemberArea.each(function() {
    	$(this).on('click', function() {
    		if ($(this).hasClass('team-member--image')) {
    			$(this).siblings('.team-member--info').children('.team-member--bio').fadeToggle();
                $(this).siblings('.team-member--info').children('.team-profile-trigger').toggleClass('team-profile-trigger--text');
    		} else if ($(this).hasClass('team-profile-trigger')) {
    			$(this).siblings('.team-member--bio').fadeToggle();
                $(this).toggleClass('team-profile-trigger--text');
    		}
    	});
    });

    // Accordion
    var $accordion = $('.accordion');

    $accordion.accordion({
        hiddenOnLoad: true,
        singleOpen: true
    });


    // Alert
    var stopAutorefillForm = $('form[name=autorefill-form]'),
        reorderForm = $('form[name=reorder-form]');

    stopAutorefillForm.on('submit', function(e) {
        e.preventDefault();
        var form = this;
        swal("Are you sure you want to stop autorefill for this prescription?", {
            buttons: {
                cancel: "No",
                confirm: "Yes, stop autorefill"
            }
        }).then((value) => {
            if (value) {
                form.submit();
                return true;
            } else {
                return false;
            }
        });
    });

    reorderForm.on('submit', function(e) {
        e.preventDefault();
        var form = this;
        swal("Are you sure you want to reorder this product?", {
            buttons: {
                cancel: "No",
                confirm: "Yes, submit order"
            }
        }).then((value) => {
            if (value) {
                form.submit();
                return true;
            } else {
                return false;
            }
        });
    });
});


/**
 * This is commented out because for tretinoin, the price handling happens
 * in the Product vue component, so jQuery can't alter the output. Keeping it
 * here in case the FREE promo needs to get added to other products
 * at a later date.
 */
// if ($('.contentWrapper').hasClass('subOutPrices')) {
//     $('.pricing').hide();
//     $('.cta-with-price__price').hide();
//     $('.cta-with-price__price.-groupon-price').show();
//     $('.cta-with-price__price.-groupon-price').addClass('-crossed-out');
//     $('.cta-with-price__price.-groupon-price').after(`
// <div class="first-shipment-free">
// <ul>
//     <li>FREE shipment</li>
//     <li>Cancel anytime - no commitment</li>
//     <li>Refills are offered at the lowest price of any major US telehealth company</li>
//     <li>First shipment is free</li>
// </ul>
// </div>
//     `)
//     $('.cta-with-price__price.-groupon-price').after('<div class="cta-with-price__free">FREE!</div>')
//     $('.cta-with-price__cta').append(' FREE')
// }


/**
 * Start the auto logout timer if in the dashboard
 */
if( $('body').hasClass('portal-body') ) {
    // Auto-logoout Timer
    var minutesIdle = 0;
    var idleChecker = function() {
        minutesIdle += 1;

        if(minutesIdle >= 5) {
            showLogoutPopup();
        }

        $('body').on('mousemove keyup', () => minutesIdle = 0);
    };
    var showLogoutPopup = function() {
        var logoutTimeout = setTimeout(() => {
            window.location.replace('/dashboard/logout')
        }, 60000);

        clearInterval(idleTimer);
        swal("You will be logged out in 1 minute due to inactivity. Click the button below to remain logged in", {
            buttons: {
                confirm: "Stay Logged In"
            }
        }).then( () => {
            clearTimeout(logoutTimeout);
            var idleTimer = setInterval(idleChecker, 840000);
        } );

    }
    var idleTimer = setInterval(idleChecker, 840000);
}

// Ticker Photos Carousel
(function() {
    const slideshowElements = document.querySelectorAll('.ticker-photos__container.flickity-ticker, .ticker-photos-small__container.flickity-ticker');

    slideshowElements.forEach(slideshowEl => {
        let flickity = null;
        let isPaused = false;

        // ticker speed
        let tickerSpeed = slideshowEl.hasAttribute('data-speed') ? parseFloat(slideshowEl.dataset.speed) : 0.75;

        const update = () => {
            if(isPaused) {
                return
            }else {
                if (flickity.slides) {
                    flickity.x = (flickity.x - tickerSpeed) % flickity.slideableWidth;
                    flickity.selectedIndex = flickity.dragEndRestingSelect();
                    flickity.updateSelectedSlide();
                    flickity.settle(flickity.x);
                }
            }
            window.requestAnimationFrame(update);
        };

        const pause = () => {
            isPaused = true;
        };

        const play = () => {
            if (isPaused) {
                isPaused = false;
                window.requestAnimationFrame(update);
            }
        };

        flickity = new Flickity(slideshowEl, {
            autoPlay: false,
            freeScroll: true,
            prevNextButtons: false,
            pageDots: false,
            contain: true,
            cellAlign: 'center',
            draggable: true,
            wrapAround: true,
            selectedAttraction: 0.015,
            friction: 0.25,
        });
        flickity.x = 0;

        // slideshowEl.addEventListener('mouseenter', pause, false);
        // slideshowEl.addEventListener('focusin', pause, false);
        // slideshowEl.addEventListener('mouseleave', play, false);
        // slideshowEl.addEventListener('focusout', play, false);

        flickity.on('dragStart', () => {
          isPaused = true;
        });
        flickity.on('dragEnd', () => {
            isPaused = false
            setTimeout(() => {
                update()
            }, 2500)
        });

        update();
    })
})();

// Before After Carousels
(function() {
    let beforeSlider1 = document.querySelector('#beforeSlider'),
        beforeSlider2 = document.querySelector('#slideWithInfo'),
        companySlider = document.querySelector('.companies-mobile')

    let flickity1 = null;
    let flickity2 = null;

    if (beforeSlider1) {
        flickity1 = new Flickity(beforeSlider1, {
            fade: true,
            autoPlay: 5000,
            prevNextButtons: false,
            dots: false,
            pauseAutoPlayOnHover: false,
            draggable: false,
            on: {
                select: function(index) {
                    if (index !== 0) {
                        //$('.before-after-slider__words').addClass('goLeft')
                    } else {
                        //$('.before-after-slider__words').removeClass('goLeft')
                    }
                    $('.before-after-slider .progress-slider-bar span').
                        removeClass('active')
                    $('.before-after-slider .progress-slider-bar span').
                        eq(index).
                        addClass('active')
                },
            }
        });
    }

    if (beforeSlider2) {
        flickity2 = new Flickity(beforeSlider2, {
            fade: true,
            autoPlay: 5000,
            prevNextButtons: false,
            dots: false,
            pauseAutoPlayOnHover: false,
            draggable: false,
            on: {
                select: function(index) {
                    $('.expert-derm-team__wrapper .progress-slider-bar span').
                        removeClass('active')
                    $('.expert-derm-team__wrapper .progress-slider-bar span').
                        eq(index).
                        addClass('active')
                    window.requestAnimationFrame(() => {
                        let delay = 500
                        let visibleEls = document.querySelectorAll(
                            '.before-after-slider__words span')
                        for (let i = 0; i < visibleEls.length; i++) {
                            let newDelay = delay + i * 500
                            setTimeout(() => {
                                visibleEls[i].classList.remove('hiddens');
                                visibleEls[i].classList.add('visible', 'again');
                            }, newDelay)
                        }
                    });
                },
                settle: function(index) {
                    let visibleEls = document.querySelectorAll(
                        '.before-after-slider__words span')
                    for (let i = 0; i < visibleEls.length; i++) {
                        visibleEls[i].classList.remove('visible', 'again');
                        visibleEls[i].classList.add('hiddens');
                    }
                }
            }
        });
    }


    if(window.innerWidth < 768 ) {
        document.addEventListener('DOMContentLoaded', function () {
            if(companySlider !== null) {
                const comSliderFlickity = new Flickity(companySlider, {
                    cellAlign: 'left',
                    contain: false,
                    autoPlay:4000,
                    prevNextButtons:false,
                    pageDots:false,
                    pauseAutoPlayOnHover:false,
                    draggable: true,
                    contain:true,
                    wrapAround: true,
                    on: {
                        ready: function() {
                            document.querySelector('.companies-wrapper').style.display = 'none'
                            document.querySelector('.companies-wrapper-mobile').style.opacity = 1
                        }
                    }
                })
            }
        })
    }

    $('.videoSlider__carousel__item').on('click', function() {
        let vidSrc = $(this).attr('data-src')
        $('.siteOverlay').fadeIn()
        $('.videoPopupWrapper').fadeIn().find('video').attr('src', vidSrc)
        $('.videoPopupWrapper video')[0].play()
    })

    $('.videoPopupWrapper__close').on('click', function() {
        $('.siteOverlay').fadeOut()
        $('.videoPopupWrapper').fadeOut().find('video').attr('src', '')
    })

    $('.brxAccordeon__item h3').on('click', function() {
        $('.accordHidden').stop().slideUp()
        $('.brxAccordeon__item h3').removeClass('active')
        if($(this).next().is(':visible')) {
            $(this).next().stop().slideUp()
            $(this).removeClass('active')
        }else {
            $(this).next().stop().slideToggle()
            $(this).toggleClass('active')
        }
    })

    $('.expert-slider-info__item').first().addClass('active')
    $('.expert-slider-info').height($('.expert-slider-info__item').first().outerHeight())
    if (flickity2 !== null) {
         flickity2.on( 'select', function(currentIndex) {
            $('.expert-slider-info__item').removeClass('active')
            $('.expert-slider-info__item').eq(currentIndex).addClass('active')
            $('.expert-slider-info').height($('.expert-slider-info__item').eq(currentIndex).outerHeight())
        });
    }

    $('.before-after-carousel .play--button').on('click', function() {
        $('.before-after-carousel__item__video video').get(0).pause()
        $('.before-after-carousel__item__video').hide()
        $('.before-after-carousel__item__preview').show()
        $('.play--button').show()
        $(this).hide()
        $(this).parent().find('.before-after-carousel__item__preview').hide()
        $(this).parent().find('.before-after-carousel__item__video').show()
        $(this).parent().find('.before-after-carousel__item__video video').get(0).play()
    })

    // main carousel
    document.querySelectorAll('.before-after-carousel__carousel').forEach(carousel => {
        const flickity = new Flickity(carousel, {
            contain: true,
            cellAlign: 'center',
            keyboard:true,
            percentPosition: false,
            selectedAttraction: 0.005,
            freeScroll: true,
            arrowShape: 'M100,43.8H23.9L58.9,8.8L50,0L0,50l50,50l8.8-8.8L23.9,56.2H100V43.8z'
        });
    });

    const reviewCarousel = document.querySelector('.reviews-carousel')
    const videoSlider = document.querySelector('.videoSlider__carousel')
    const productSlider = document.querySelector('.showColumnsMobile__slider')

    if (productSlider) {
        const productSliderFlickity = new Flickity(productSlider, {
            contain: true,
            cellAlign: 'center',
            percentPosition: false,
            selectedAttraction: 0.01,
            freeScroll: false,
            arrowShape: 'M100,43.8H23.9L58.9,8.8L50,0L0,50l50,50l8.8-8.8L23.9,56.2H100V43.8z',
            draggable: true,
            pageDots:false
        })
        .on('change', function(index) {
            $('.showColumnsMobile__button').removeClass('active')
            $('.showColumnsMobile__button').eq(index).addClass('active')
        })

        $('.showColumnsMobile__button').each(function(idx, item) {
            $(item).on('click', function() {
                $('.showColumnsMobile__button').removeClass('active')
                $(this).addClass('active')
                productSliderFlickity.select(idx)
            })
        })
    }

    function initializeFlickity() {
        var options = {
            contain: true,
            cellAlign: 'center',
            percentPosition: false,
            selectedAttraction: 0.005,
            freeScroll: false,
            arrowShape: 'M100,43.8H23.9L58.9,8.8L50,0L0,50l50,50l8.8-8.8L23.9,56.2H100V43.8z',
            draggable: true,
        };

        const videoSliderFlickity = new Flickity(videoSlider, options);
        let isDragging = false;
        const dragStart = (e) => {
            if (isDragging) return;
            videoSliderFlickity.slider.childNodes.forEach(slide => slide.style.pointerEvents = "none")
            isDragging = true;
        }

        const dragEnd = (e) => {
            if (!isDragging) return;
            videoSliderFlickity.slider.childNodes.forEach(slide => slide.style.pointerEvents = "all")
            isDragging = false
        }
        // touchmove and touchend for iOS bug
        videoSliderFlickity.slider.addEventListener('touchmove', dragStart);
        videoSliderFlickity.slider.addEventListener('touchend', dragEnd);
        videoSliderFlickity.on('dragStart', dragStart);
        videoSliderFlickity.on('dragEnd', dragEnd);
        // videoSliderFlickity.on('dragStart', () => videoSliderFlickity.slider.childNodes.forEach(slide => slide.style.pointerEvents = "none") );
        // videoSliderFlickity.on('dragEnd', () => videoSliderFlickity.slider.childNodes.forEach(slide => slide.style.pointerEvents = "all") );
    }

    document.addEventListener('DOMContentLoaded', function () {
        if(videoSlider) {
            initializeFlickity()
        }
    });

    window.onresize = function() {
        if(videoSlider) {
            initializeFlickity()
        }
    }

    let speed = null

    if($(window).width() > 768) {
        speed = 0.5
    }else {
        speed = 1
    }

    let reviewElement = document.querySelector('.reviews-carousel.splide')

    if(reviewElement !== null) {
        new Splide(reviewElement, {
            type: 'loop',
            arrows: false,
            autoWidth: true,
            perPage:4,
            pagination: false,
            autoScroll: {
              speed,
            },
        }).mount({ AutoScroll });
    }

    // images before/after/during
    document.querySelectorAll('.before-after-carousel__images--carousel').forEach(slider => {
        const prevBtn = slider.querySelector('.before-after-carousel__images--carousel-prev'),
              nextBtn = slider.querySelector('.before-after-carousel__images--carousel-next'),
              allItems = slider.querySelectorAll('.before-after-carousel__images--carousel-item');

        allItems[0].addEventListener('click', () => buttons('prev'))
        allItems[1].addEventListener('click', () => buttons('next'))

        // make second element active by default
        if (allItems[1]) allItems[1].classList.add('active');

        const checkButtons = (itemIndex) => {
            if (itemIndex <= 0) prevBtn.classList.add('disabled');
            else prevBtn.classList.remove('disabled');

            if (itemIndex >= allItems.length -1) nextBtn.classList.add('disabled')
            else nextBtn.classList.remove('disabled')
        }

        const buttons = (dir) => {
            let activeItem = [...allItems].findIndex(el => el.classList.contains('active'));
            if (activeItem === -1) return;
            let isLast = dir === 'prev' ? activeItem === 0 : activeItem === allItems.length - 1;
            let nextIndex = dir === 'prev' ? activeItem - 1 : activeItem + 1;
            if (!isLast) {
                allItems[activeItem].classList.remove('active');
                allItems[nextIndex].classList.add('active');
                checkButtons(nextIndex)
            }
        }

        prevBtn.addEventListener('click', () => buttons('prev'))
        nextBtn.addEventListener('click', () => buttons('next'))
    })

    $('.form-group input').each(function() {
        if (!this.value) {
            $(this).parents('.form-group').removeClass('active')
        }else {
            $(this).parents('.form-group').addClass('active')
        }
    })

    $('.form-group input').on('focusin', function (e) {
        $(this).parents('.form-group').addClass('active')
    })
    $('.form-group input').on('focusout', function (e) {
        if (!this.value) {
            $(this).parents('.form-group').removeClass('active')
        }
    })
})();

// add visible class on scroll when element is visible
(function() {
    const elements = document.querySelectorAll('.redactor-circle, .redactor-underline');
    function isInViewport(element, offset = 0) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top + offset >= 0 &&
            rect.left + offset >= 0 &&
            rect.bottom + offset <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right + offset <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    function addActiveClass() {
        window.requestAnimationFrame(() => {
            let delay = 500
            let visibleEls = Array.from(elements).filter(el => !el.classList.contains('visible') && isInViewport(el, 150));
            for(let i = 0; i < visibleEls.length; i++) {
                let newDelay = delay + i * 500
                setTimeout(() => {
                    visibleEls[i].classList.add(`visible`);
                }, newDelay)
            }
        });
    }
    addActiveClass();
    window.addEventListener('scroll', addActiveClass);
})();