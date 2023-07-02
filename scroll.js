document.addEventListener('DOMContentLoaded', function() {
    var scrollLinks = document.querySelectorAll('.scroll-link');
    for (var i = 0; i < scrollLinks.length; i++) {
      scrollLinks[i].addEventListener('click', scrollToSection);
    }
  
    function scrollToSection(e) {
      e.preventDefault();
      var targetSelector = this.getAttribute('data-target');
      var targetElement = document.querySelector(targetSelector);
      if (targetElement) {
        targetElement.scrollIntoView({ behavior: 'smooth' });
      }
    }
  });
  
  