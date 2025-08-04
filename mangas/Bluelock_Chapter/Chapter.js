// Track read chapters
const readChapters = JSON.parse(localStorage.getItem("readChapters")) || [];

// Get chapter ID from URL
const urlParams = new URLSearchParams(window.location.search);
const chapterId = urlParams.get("chapter");

// Mark the chapter as read
if (chapterId && !readChapters.includes(chapterId)) {
    readChapters.push(chapterId);
    localStorage.setItem("readChapters", JSON.stringify(readChapters));
}

// List lokal chapter (harus sesuai urutan)
const localChapters = [
    { id: "c001", chapter: "1" },
    { id: "c002", chapter: "2" },
    { id: "c003", chapter: "3" },
    { id: "c004", chapter: "4" },
    { id: "c005", chapter: "5" },
    { id: "c006", chapter: "6" },
    { id: "c007", chapter: "7" },
    { id: "c008", chapter: "8" },
    { id: "c009", chapter: "9" },
    { id: "c0010", chapter: "10" },
];

// Tampilkan halaman gambar
async function fetchChapterPages(chapterId) {
    const pagesContainer = document.getElementById("pages");
    pagesContainer.innerHTML = "";

    const maxPages = 50;
    for (let i = 1; i <= maxPages; i++) {
        const pageNum = String(i).padStart(3, "0");
        const img = document.createElement("img");
        img.src = `../Bluelock_Chapter/Chapters/${chapterId}/${pageNum}.jpg`;
        img.alt = `Page ${i}`;

        try {
            const res = await fetch(img.src, { method: "HEAD" });
            if (res.ok) {
                pagesContainer.appendChild(img);
            } else {
                break;
            }
        } catch (error) {
            break;
        }
    }
}

fetchChapterPages(chapterId);

// Navigation controls
const prevChapterButton = document.getElementById("prev-chapter");
const nextChapterButton = document.getElementById("next-chapter");
const chapterSelect = document.getElementById("chapter-select");

const prevChapterButtonBottom = document.getElementById("prev-chapter-bottom");
const nextChapterButtonBottom = document.getElementById("next-chapter-bottom");
const chapterSelectBottom = document.getElementById("chapter-select-bottom");

// Populate dropdown
function populateChapterSelect() {
    localChapters.forEach(ch => {
        const option = document.createElement("option");
        option.value = ch.id;
        option.textContent = `Chapter ${ch.chapter}`;
        chapterSelect.appendChild(option.cloneNode(true));
        chapterSelectBottom.appendChild(option);
    });

    chapterSelect.value = chapterId;
    chapterSelectBottom.value = chapterId;
}

populateChapterSelect();

// Event listeners
chapterSelect.addEventListener("change", (e) => {
    window.location.href = `Chapter.php?chapter=${e.target.value}`;
});
chapterSelectBottom.addEventListener("change", (e) => {
    window.location.href = `Chapter.php?chapter=${e.target.value}`;
});

function goToPreviousChapter() {
    const currentIndex = localChapters.findIndex(ch => ch.id === chapterId);
    if (currentIndex > 0) {
        const prevChapterId = localChapters[currentIndex - 1].id;
        window.location.href = `Chapter.php?chapter=${prevChapterId}`;
    }
}

function goToNextChapter() {
    const currentIndex = localChapters.findIndex(ch => ch.id === chapterId);
    if (currentIndex < localChapters.length - 1) {
        const nextChapterId = localChapters[currentIndex + 1].id;
        window.location.href = `Chapter.php?chapter=${nextChapterId}`;
    }
}

prevChapterButton.addEventListener("click", goToPreviousChapter);
nextChapterButton.addEventListener("click", goToNextChapter);
prevChapterButtonBottom.addEventListener("click", goToPreviousChapter);
nextChapterButtonBottom.addEventListener("click", goToNextChapter);
