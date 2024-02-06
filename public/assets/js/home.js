

// **************************************
document.addEventListener('DOMContentLoaded', function () {
    var sliders = document.querySelectorAll('.slider');

    sliders.forEach(function(slider) {
        var nextButton = slider.parentElement.querySelector('.next-slide');
        var prevButton = slider.parentElement.querySelector('.prev-slide');
        var currentSlide = 0;

        nextButton.addEventListener('click', function () {
            if (currentSlide < slider.children.length - 1) {
                currentSlide++;
                slider.style.transform = 'translateX(' + (-currentSlide * 100) + '%)';
            }
        });

        prevButton.addEventListener('click', function () {
            if (currentSlide > 0) {
                currentSlide--;
                slider.style.transform = 'translateX(' + (-currentSlide * 100) + '%)';
            }
        });
    });
});
// ********************************************