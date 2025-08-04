document.addEventListener('DOMContentLoaded', async function () {
  const wishlistIcons = document.querySelectorAll('.wishlist-icon');

  let bookmarkedTitles = [];
  try {
    const res = await fetch('check_bookmarks.php');
    bookmarkedTitles = await res.json(); 
  } catch (err) {
    console.error('Gagal memuat data bookmark:', err);
  }

  const currentPath = window.location.pathname;
  let basePath = '';

  if (currentPath.includes('/mangas/')) {
    basePath = '../../';
  } else {
    basePath = './';
  }

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
            if (window.location.pathname.includes('Bookmark.php')) {
              this.closest('.box').remove();
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
});


