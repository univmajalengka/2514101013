
/* GALERI: MEMBUKA FOTO DALAM UKURAN BESAR */
document.addEventListener("DOMContentLoaded", function () {
  const galleryImages = document.querySelectorAll(".gallery-item img");

  // Membuat wadah foto besar
  const lightbox = document.createElement("div");
  lightbox.className = "photo-lightbox";
  lightbox.setAttribute("role", "dialog");
  lightbox.setAttribute("aria-label", "Pratinjau foto");

  // Tombol tutup
  const closeButton = document.createElement("button");
  closeButton.className = "lightbox-close";
  closeButton.innerHTML = "&times;";
  closeButton.setAttribute("aria-label", "Tutup foto");

  // Gambar yang diperbesar
  const enlargedImage = document.createElement("img");
  enlargedImage.alt = "";

  lightbox.appendChild(enlargedImage);
  lightbox.appendChild(closeButton);
  document.body.appendChild(lightbox);

  // Klik foto galeri untuk memperbesar
  galleryImages.forEach(function (photo) {
    photo.addEventListener("click", function () {
      enlargedImage.src = photo.src;
      enlargedImage.alt = photo.alt;
      lightbox.classList.add("active");
      document.body.classList.add("lightbox-open");
    });
  });

  // Fungsi menutup foto
  function closeLightbox() {
    lightbox.classList.remove("active");
    document.body.classList.remove("lightbox-open");
    enlargedImage.src = "";
  }

  closeButton.addEventListener("click", function (event) {
    event.stopPropagation();
    closeLightbox();
  });

  lightbox.addEventListener("click", function (event) {
    if (event.target === lightbox) {
      closeLightbox();
    }
  });

  // Menutup foto dengan tombol Escape
  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      closeLightbox();
    }
  });
});

/* NAVBAR BERUBAH SAAT SCROLL */
document.addEventListener("DOMContentLoaded", function () {
  const navbar = document.querySelector(".navbar");

  function updateNavbar() {
    navbar.classList.toggle("scrolled", window.scrollY > 60);
  }

  window.addEventListener("scroll", updateNavbar);
  updateNavbar();
});

/* EFEK MUNCUL SAAT SCROLL */
document.addEventListener("DOMContentLoaded", function () {
  const items = document.querySelectorAll(
    ".hero-about, .info-card, .vehicle-box, .package-panel, .gallery-item, .video-content"
  );

  const observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add("show");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.15 }
  );

  items.forEach(function (item) {
    item.classList.add("reveal");
    observer.observe(item);
  });
});