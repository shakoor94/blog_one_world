/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

window.addEventListener('DOMContentLoaded', function() {
  // Dropdown menu
  var destination = document.querySelector("#destination_down");
  var sous_menu = document.querySelector(".sub_menu");

  destination.addEventListener("click", function() {
      if (getComputedStyle(sous_menu).display !== "block") {
          sous_menu.style.display = "block";
      } else {
          sous_menu.style.display = "none";
      }
  });

  // Close dropdown menu on outside click
  document.addEventListener("click", function(event) {
      if (!destination.contains(event.target) && getComputedStyle(sous_menu).display === "block") {
          sous_menu.style.display = "none";
      }
  });

  // Mobile menu
  var burger = document.querySelector('#burger');
  var menu = document.querySelector('#menu-navigation');
  var cross = document.querySelector('#close-cross');
  var apli = document.querySelector('#nav-apli');

  burger.addEventListener('click', function() {
      menu.classList.add('deploye', 'transition');
      burger.style.display = 'none';
      apli.style.display = 'none';
  });

  cross.addEventListener('click', function() {
      menu.classList.remove('deploye');
      burger.style.display = 'block';
      apli.style.display = 'block';
  });

  // Navbar scroll change
  var navBar = document.querySelector('#menu-navigation');
  var navBarOriginalColor = getComputedStyle(navBar).backgroundColor;
  var navLinks = document.querySelectorAll('.lien');
  var navOriginalColors = Array.from(navLinks).map(function(link) {
      return getComputedStyle(link).color;
  });

  window.addEventListener('scroll', function() {
      var navBarPosition = navBar.getBoundingClientRect().top + window.scrollY;
      if (navBarPosition > 0) {
          navBar.style.backgroundColor = '#0098D1';
          for (var i = 0; i < navLinks.length; i++) {
              navLinks[i].style.color = 'white';
          }
      } else {
          navBar.style.backgroundColor = navBarOriginalColor;
          for (var i = 0; i < navLinks.length; i++) {
              navLinks[i].style.color = navOriginalColors[i];
          }
      }
  });

  var admin = document.querySelector("#admin_down");
  var dashboard = document.querySelector(".sub_menu_admin");

  admin.addEventListener("click", function() {
      if (getComputedStyle(dashboard ).display !== "block") {
        dashboard .style.display = "block";
      } else {
        dashboard .style.display = "none";
      }
  });

  // Close dropdown menu on outside click
  document.addEventListener("click", function(event) {
      if (!admin.contains(event.target) && getComputedStyle(dashboard ).display === "block") {
        dashboard .style.display = "none";
      }
  });
});

function isInViewport(element) {
    var rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.bottom <= window.innerHeight
    );
}

function handleScroll() {
    let sections = document.querySelectorAll('.section-animation');

    sections.forEach(function(section) {
        if (isInViewport(section)) {
            section.classList.add('animate');
        } else {
            section.classList.remove('animate');
        }
    });
}

window.addEventListener('scroll', handleScroll);





