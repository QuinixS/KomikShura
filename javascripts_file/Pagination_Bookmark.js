document.getElementById('prevBtn')
document.getElementById('nextBtn')
document.getElementById('pageNumber')
const mangasPerPage = 6; 
const boxes = document.querySelectorAll('.mangas-content .box');
const totalPages = Math.ceil(boxes.length / mangasPerPage);

let currentPage = 1;

function showPage(page) {
    const start = (page - 1) * mangasPerPage;
    const end = start + mangasPerPage;

    boxes.forEach((box, index) => {
        if (index >= start && index < end) {
            box.style.display = 'flex'; 
        } else {
            box.style.display = 'none';
        }
    });

    document.getElementById('pageNumber').textContent = page;
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('prevBtn').addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            showPage(currentPage);
        }
    });

    document.getElementById('nextBtn').addEventListener('click', function() {
        if (currentPage < totalPages) {
            currentPage++;
            showPage(currentPage);
        }
    });

    showPage(currentPage); 
});
