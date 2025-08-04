document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('.search-form');
  const input = document.querySelector('.search-input');
  const pageNumberEl = document.getElementById('pageNumber');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const allBoxes = Array.from(document.querySelectorAll('.mangas-content .box'));

  const mangasPerPage = 6;
  let currentPage = 1;
  let filteredBoxes = [...allBoxes];

  function getTotalPages() {
    return Math.ceil(filteredBoxes.length / mangasPerPage);
  }

  function showPage(page) {
    const start = (page - 1) * mangasPerPage;
    const end = start + mangasPerPage;

    allBoxes.forEach(box => box.style.display = 'none');

    filteredBoxes.forEach((box, index) => {
      if (index >= start && index < end) {
        box.style.display = 'flex';
      }
    });

    pageNumberEl.textContent = page;
  }

  function searchManga() {
    const query = input.value.toLowerCase().trim();

    if (query === '') {
      filteredBoxes = [...allBoxes];
    } else {
      filteredBoxes = allBoxes.filter(box => {
        const title = box.querySelector('h4').innerText.toLowerCase();
        const genre = box.querySelector('.genres').innerText.toLowerCase();
        const desc = box.querySelector('.rating p').innerText.toLowerCase();
        return title.includes(query) || genre.includes(query) || desc.includes(query);
      });
    }

    currentPage = 1;
    showPage(currentPage);
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    searchManga();
  });

  input.addEventListener('input', searchManga);

  prevBtn.addEventListener('click', function () {
    if (currentPage > 1) {
      currentPage--;
      showPage(currentPage);
    }
  });

  nextBtn.addEventListener('click', function () {
    if (currentPage < getTotalPages()) {
      currentPage++;
      showPage(currentPage);
    }
  });

  // Load pertama
  showPage(currentPage);
});
