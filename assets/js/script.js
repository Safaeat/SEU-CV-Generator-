//popup
document
  .getElementById("generateCVLink")
  .addEventListener("click", function (event) {
    event.preventDefault(); // Prevent the default link behavior
    document.getElementById("popup").style.display = "flex";
  });

document.querySelector(".close").addEventListener("click", function () {
  document.getElementById("popup").style.display = "none";
});

// Close the popup when clicking outside of it
window.addEventListener("click", function (event) {
  if (event.target == document.getElementById("popup")) {
    document.getElementById("popup").style.display = "none";
  }
});

// form repeater
$(document).ready(function () {
  $(".repeater").repeater({
    initEmpty: false,
    defaultValues: {
      "text-input": "",
    },
    show: function () {
      $(this).slideDown();
    },
    hide: function (deleteElement) {
      $(this).slideUp(deleteElement);
      setTimeout(() => {
        generateCV();
      }, 500);
    },
    isFirstItemUndeletable: true,
  });
});
