var swipeContainer = document.querySelector('.swipe-container');
var description = document.querySelector('.description');

var initialX = null;
var threshold = 100;

swipeContainer.addEventListener('touchstart', function(event) {
  initialX = event.touches[0].clientX;
});

swipeContainer.addEventListener('touchmove', function(event) {
  if (initialX === null) {
    return;
  }

  var currentX = event.touches[0].clientX;
  var deltaX = currentX - initialX;

  if (Math.abs(deltaX) > threshold) {
    swipeContainer.style.background = (deltaX > 0) ? 'linear-gradient(to right, #FFB75E, #ED8F03)' : 'linear-gradient(to right, #ED8F03, #FFB75E)';

    // Check if description tab is visible
    if (swipeContainer.classList.contains('show-description')) {
      // Swipe left to close description tab
      if (deltaX < 0) {
        swipeContainer.classList.remove('show-description');
      }
    } else {
      // Swipe right to show description tab
      if (deltaX > 0) {
        swipeContainer.classList.add('show-description');
      }
    }

    initialX = null;
  }
});

swipeContainer.addEventListener('touchend', function(event) {
  if (initialX === null) {
    return;
  }

  var currentX = event.changedTouches[0].clientX;
  var deltaX = currentX - initialX;

  if (deltaX > threshold && !swipeContainer.classList.contains('show-description')) {
    window.location.href = 'your-app-url';
  }
});