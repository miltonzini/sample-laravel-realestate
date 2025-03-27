// Sticky Navbar
window.addEventListener("scroll", function(){
  var header = document.querySelector("nav");
  header.classList.toggle("sticky", window.scrollY > 70);
})

// Navbar menu
document.querySelector('.menu-toggle').addEventListener('click', function(event) {
  const links = document.querySelector('.navbar .links');
  
  event.stopPropagation();

  if (links.classList.contains('active')) {
      links.classList.add('closing');
      setTimeout(() => {
          links.classList.remove('active');
          links.classList.remove('closing');
      }, 500); 
  } else {
      links.classList.add('active');
  }
});

document.addEventListener('click', function(event) {
  const links = document.querySelector('.navbar .links');

  if (links.classList.contains('active') && !links.contains(event.target)) {
      links.classList.add('closing');
      setTimeout(() => {
          links.classList.remove('active');
          links.classList.remove('closing');
      }, 500);
  }
});