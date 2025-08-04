// Track read chapters
const readChapters = JSON.parse(localStorage.getItem("readChapters")) || [];

// Daftar lokal chapter
const chapters = [
    { id: "c001", chapter: "1", date: "2022-01-01" },
    { id: "c002", chapter: "2", date: "2022-01-08" },
    { id: "c003", chapter: "3", date: "2022-01-15" },
    { id: "c004", chapter: "4", date: "2022-01-20" },
    { id: "c005", chapter: "5", date: "2022-01-31" },
    { id: "c006", chapter: "6", date: "2022-02-09" },
    { id: "c007", chapter: "7", date: "2022-02-18" },
    { id: "c008", chapter: "8", date: "2022-02-27" },
    { id: "c009", chapter: "9", date: "2022-03-06" },
    { id: "c0010", chapter: "10", date: "2022-03-12" },
    // Tambahkan lebih banyak chapter lokal di sini...
];

// Fungsi untuk menampilkan daftar chapter
function displayChapters() {
    const chaptersContainer = document.getElementById("chapters");
    chaptersContainer.innerHTML = "";

    chapters.forEach((chapter, index) => {
        const chapterElement = document.createElement("div");
        chapterElement.classList.add("box");

        chapterElement.innerHTML = `
            <div class="left">
                <h4>Chapter ${chapter.chapter}</h4>
                <p>${chapter.date}</p>
            </div>
        `;

        chapterElement.addEventListener("click", () => {
            window.location.href = `Chapter.php?chapter=${chapter.id}`;
        });

        if (readChapters.includes(chapter.id)) {
            chapterElement.style.opacity = "0.6";
        }

        chaptersContainer.appendChild(chapterElement);
    });

    // Set button pertama & terakhir
    const firstChapterButton = document.querySelector(".detail-button button:first-of-type");
    const latestChapterButton = document.querySelector(".detail-button button:last-of-type");

    if (chapters.length > 0) {
        firstChapterButton.addEventListener("click", () => {
            window.location.href = `Chapter.php?chapter=${chapters[0].id}`;
        });

        latestChapterButton.addEventListener("click", () => {
            window.location.href = `Chapter.php?chapter=${chapters[chapters.length - 1].id}`;
        });
    }
}

document.addEventListener("DOMContentLoaded", displayChapters);
