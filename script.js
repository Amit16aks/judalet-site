$(document).ready(function() {
    let selectedLocation = "Unknown";
    let lastScroll = 0;
    let slideIndex = 0;
    const slides = $('.carousel-slide');
    const totalSlides = slides.length;

    // Location Detection on Page Load
    function setLocation(location) {
        selectedLocation = location;
        $('.location-display-bar').text(`Ordering from: ${location}`);
        $('#location-modal').hide();
        console.log("Selected Location for order: " + selectedLocation);
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                let location = "Unknown";
                if (latitude > 29.77 && latitude < 29.79 && longitude > -95.15 && longitude < -95.13) {
                    location = "Woodville";
                }
                setLocation(location);
            },
            () => {
                $('#location-modal').show();
                console.log("Geolocation failed, showing manual selection.");
            }
        );
    } else {
        $('#location-modal').show();
        console.log("Geolocation not supported, showing manual selection.");
    }

    // Modal Location Selection
    $('#confirm-location').click(function() {
        const location = $('#location-select').val();
        setLocation(location);
    });

    // Navigation Toggle
    $('.menu-toggle').click(function() {
        $('.nav ul').toggleClass('active');
    });
    $(document).click(function(e) {
        if (!$(e.target).closest('.nav, .menu-toggle').length) {
            $('.nav ul').removeClass('active');
        }
    });

    // Logo Click to Home
    $('.logo a').click(function(e) {
        e.preventDefault();
        window.location.href = 'index.php';
    });

    // Carousel Functionality
    function showSlide(index) {
        if (index >= totalSlides) slideIndex = 0;
        if (index < 0) slideIndex = totalSlides - 1;
        slides.removeClass('active').eq(slideIndex).addClass('active');
    }

    function nextSlide() {
        slideIndex++;
        showSlide(slideIndex);
    }

    function prevSlide() {
        slideIndex--;
        showSlide(slideIndex);
    }

    // Auto Slide (10 seconds)
    showSlide(slideIndex);
    setInterval(nextSlide, 10000); // Changed to 10 seconds

    // Manual Navigation (Arrows)
    $('.carousel-next').click(function() {
        nextSlide();
    });

    $('.carousel-prev').click(function() {
        prevSlide();
    });

    // Swipe Support
    let touchStartX = 0;
    let touchEndX = 0;

    $('.carousel-slides').on('touchstart', function(event) {
        touchStartX = event.originalEvent.touches[0].clientX;
    });

    $('.carousel-slides').on('touchmove', function(event) {
        touchEndX = event.originalEvent.touches[0].clientX;
    });

    $('.carousel-slides').on('touchend', function() {
        const diffX = touchStartX - touchEndX;
        if (diffX > 50) {
            nextSlide(); // Swipe left
        } else if (diffX < -50) {
            prevSlide(); // Swipe right
        }
    });

    // Header Scroll Behavior
    $(window).scroll(function() {
        let currentScroll = $(this).scrollTop();
        if (currentScroll > lastScroll && currentScroll > 100) {
            $('.header').css('top', '-120px');
        } else {
            $('.header').css('top', '0');
        }
        lastScroll = currentScroll;
    });
});