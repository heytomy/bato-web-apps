setTimeout(function () {
    var alert = document.querySelector(".alert");
    alert.classList.remove("show");
    alert.classList.add("hide");
    setTimeout(function () {
      alert.remove();
    }, 1000);
  }, 5000);