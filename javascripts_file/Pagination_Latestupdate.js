document.addEventListener('DOMContentLoaded', async function () {
  const wishlistIcons = document.querySelectorAll('.wishlist-icon');
  const mangasPerPage = 6;
  let currentPage = 1;

  let bookmarkedTitles = [];
  try {
    const res = await fetch('check_bookmarks.php');
    bookmarkedTitles = await res.json();
  } catch (err) {
    console.error('Gagal memuat data bookmark:', err);
  }

  const currentPath = window.location.pathname;
  let basePath = currentPath.includes('/mangas/') ? '../../' : './';

  wishlistIcons.forEach(icon => {
    const title = icon.dataset.title?.trim();
    if (bookmarkedTitles.includes(title)) {
      icon.classList.add('active');
    }

    icon.addEventListener('click', async function (e) {
      e.preventDefault();

      const title = this.dataset.title;
      const image = this.dataset.image || '';
      const genre = this.dataset.genre || '';
      const chapter = this.dataset.chapter || '';
      const link = this.dataset.link || '';
      const isActive = this.classList.contains('active');
      const isOnBookmarkPage = window.location.pathname.includes('Bookmark.php');
      const action = isActive && isOnBookmarkPage ? 'remove' : 'add';

      try {
        const response = await fetch(basePath + 'wishlist_handler.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `action=${action}&title=${encodeURIComponent(title)}&image=${encodeURIComponent(image)}&genre=${encodeURIComponent(genre)}&chapter=${encodeURIComponent(chapter)}&link=${encodeURIComponent(link)}`
        });

        const result = await response.json();

        if (result.success) {
          if (result.action === 'added') {
            this.classList.add('active');
            alert(result.message || 'Berhasil menambahkan ke bookmark!');
          } else if (result.action === 'removed') {
            this.classList.remove('active');
            alert(result.message || 'Berhasil menghapus dari bookmark!');
            if (isOnBookmarkPage) {
              this.closest('.box').remove();
              showPage(currentPage); // update setelah remove
            }
          }
        } else {
          alert(result.error || 'Terjadi kesalahan.');
        }
      } catch (error) {
        console.error('Fetch error:', error);
        alert('Gagal melakukan permintaan.');
      }
    });
  });

  // === FILTER GENRE SECTION ===
  const urlParams = new URLSearchParams(window.location.search);
  const genre = urlParams.get('genre');

  if (genre) {
    let found = false;
    document.querySelectorAll('.box').forEach(box => {
      const genreSpans = box.querySelectorAll('.genres span');
      const genres = Array.from(genreSpans).map(span =>
        span.textContent.trim().toLowerCase()
      );

      if (genres.includes(genre.toLowerCase())) {
        box.classList.remove('filtered-out');
        found = true;
      } else {
        box.classList.add('filtered-out');
      }
    });

    if (!found) {
      const message = document.createElement('p');
      message.textContent = "Tidak ada manga dengan genre tersebut.";
      message.style.textAlign = "center";
      message.style.fontWeight = "bold";
      message.style.marginTop = "20px";
      document.querySelector('.mylibrary-content')?.appendChild(message);
    }
  }

  // === PAGINATION ===
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');

  function getVisibleBoxes() {
    return Array.from(document.querySelectorAll('.mylibrary-content .box'))
      .filter(box => !box.classList.contains('filtered-out'));
  }

  function showPage(page) {
    const visibleBoxes = getVisibleBoxes();
    const totalPages = Math.ceil(visibleBoxes.length / mangasPerPage);
    const start = (page - 1) * mangasPerPage;
    const end = start + mangasPerPage;

    document.querySelectorAll('.mylibrary-content .box').forEach(box => {
      box.style.display = 'none'; // sembunyikan semua dulu
    });

    visibleBoxes.forEach((box, index) => {
      if (index >= start && index < end) {
        box.style.display = 'flex';
      }
    });

    const pageNumber = document.getElementById('pageNumber');
    if (pageNumber) pageNumber.textContent = page;

    currentPage = page;

    if (prevBtn) prevBtn.disabled = currentPage === 1;
    if (nextBtn) nextBtn.disabled = currentPage === totalPages || totalPages === 0;
  }

  if (prevBtn && nextBtn) {
    prevBtn.addEventListener('click', function () {
      if (currentPage > 1) {
        showPage(currentPage - 1);
      }
    });

    nextBtn.addEventListener('click', function () {
      showPage(currentPage + 1);
    });
  }

  showPage(1);
});
