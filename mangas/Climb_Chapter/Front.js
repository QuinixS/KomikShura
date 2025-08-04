// Track read chapters
const readChapters = JSON.parse(localStorage.getItem("readChapters")) || [];

// Fungsi untuk mengambil chapter dari API
async function fetchMangaChapters() {
    const mangaId = "bb8310e4-6050-4a43-984e-f7bbdfce23b1"; // Climber ID
    const apiUrl = `https://api.allorigins.win/get?url=${encodeURIComponent(
        `https://api.mangadex.org/manga/${mangaId}/feed?order[chapter]=asc&translatedLanguage[]=en`
    )}`;

    try {
        const response = await fetch(apiUrl);
        const data = await response.json();
        const chapters = JSON.parse(data.contents).data; // Parse JSON API

        // Filter chapter unik agar tidak ada duplikasi
        const seen = new Set();
        const uniqueChapters = chapters.filter((chapter) => {
            const chapterNum = chapter.attributes.chapter;
            if (!seen.has(chapterNum)) {
                seen.add(chapterNum);
                return true;
            }
            return false;
        });

        // **Set First and Latest Chapter Buttons**
        if (uniqueChapters.length > 0) {
            const firstChapter = uniqueChapters[0]; // First Chapter
            const latestChapter = uniqueChapters[uniqueChapters.length - 1]; // Latest Chapter

            // Get buttons
            const firstChapterButton = document.querySelector(".detail-button button:first-of-type");
            const latestChapterButton = document.querySelector(".detail-button button:last-of-type");

            // Update button links
            firstChapterButton.addEventListener("click", () => {
                window.location.href = `Chapter.php?chapter=${firstChapter.id}`;
            });

            latestChapterButton.addEventListener("click", () => {
                window.location.href = `Chapter.php?chapter=${latestChapter.id}`;
            });
        }

        // **Display Chapter List**
        const chaptersContainer = document.getElementById("chapters");
        chaptersContainer.innerHTML = ""; // Clear before adding new data

        uniqueChapters.forEach((chapter) => {
            const chapterNum = chapter.attributes.chapter || "Unknown";
            const chapterDate = new Date(chapter.attributes.createdAt).toLocaleDateString();
            const chapterId = chapter.id;

            // Create div for each chapter
            const chapterElement = document.createElement("div");
            chapterElement.classList.add("box");

            // Chapter HTML
            chapterElement.innerHTML = `
                <div class="left">
                    <h4>Chapter ${chapterNum}</h4>
                    <p>${chapterDate}</p>
                </div>
            `;

            // Add click event
            chapterElement.addEventListener("click", () => {
                window.location.href = `Chapter.php?chapter=${chapterId}`;
            });

            // Mark read chapters
            if (readChapters.includes(chapterId)) {
                chapterElement.style.opacity = "0.6";
            }

            chaptersContainer.appendChild(chapterElement);
        });
    } catch (error) {
        console.error("Error fetching manga chapters:", error);
    }
}

// Call function after page loads
document.addEventListener("DOMContentLoaded", fetchMangaChapters);
