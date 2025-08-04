document.querySelectorAll('.box').forEach(box => {
    box.addEventListener('click', function (e) {
      if (e.target.closest('.wishlist-icon')) return;
  
      const link = this.dataset.link;
      if (link) {
        window.location.href = link;
      }
    });
  });
  