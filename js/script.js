$(document).ready(function() {
    let selectedLocation = "Unknown";

    // Location Detection on Page Load
    function setLocation(location) {
        selectedLocation = location;
        $('.location-display-bar').text(`Ordering from: ${location}`);
        $('#location-modal').hide();
        // Store location (temporary - will integrate with order later)
        console.log("Selected Location for order: " + selectedLocation);
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                let location = "Unknown";
                if (latitude > 29.77 && latitude < 29.79 && longitude > -95.15 && longitude < -95.13) {
                    location = "Woodville"; // Approximate range for Woodville, TX
                }
                setLocation(location);
            },
            () => {
                $('#location-modal').show(); // Show modal if geolocation fails
                console.log("Geolocation failed, showing manual selection.");
            }
        );
    } else {
        $('#location-modal').show(); // Show modal if geolocation not supported
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

    // Carousel
    let slideIndex = 0;
    const slides = $('.carousel-slide');
    function showSlide(index) {
        if (index >= slides.length) slideIndex = 0;
        if (index < 0) slideIndex = slides.length - 1;
        slides.removeClass('active').eq(slideIndex).addClass('active');
    }
    function nextSlide() {
        slideIndex++;
        showSlide(slideIndex);
    }
    showSlide(slideIndex);
    setInterval(nextSlide, 5000); // Change slide every 5 seconds
});