from flask import Flask, request, jsonify
from flask_cors import CORS
from difflib import get_close_matches
from deep_translator import GoogleTranslator
import json
import re
import random
import requests
import time
from urllib.parse import quote

app = Flask(__name__)
CORS(app)

# Genre mapping
genre_ids = {
    "action": 1, "romance": 22, "comedy": 4, "fantasy": 10, "drama": 8,
    "sports": 30, "adventure": 2, "supernatural": 37, "slice of life": 36,
    "mystery": 7, "psychological": 40, "sci-fi": 24, "horror": 14, "school": 23,
    "ecchi": 9, "shounen": 27, "shoujo": 25, "seinen": 42, "josei": 43,
    "music": 19, "parody": 20, "martial arts": 17, "military": 38,
    "historical": 13, "mecha": 18, "thriller": 41, "vampire": 32
}

def shorten_text(text, max_words=25):
    if not text:
        return "Tidak ada deskripsi tersedia."
    words = text.split()
    return ' '.join(words[:max_words]).strip() + ("..." if len(words) > max_words else "")

def translate_to_indo(text):
    try:
        if not text or len(text.strip()) == 0:
            return "Tidak ada deskripsi tersedia."
        return GoogleTranslator(source='auto', target='id').translate(text)
    except Exception as e:
        print(f"Translation error: {e}")
        # Fallback to original text if translation fails
        return text

def is_bad_synopsis(s):
    if not s:
        return True
    # Check for short length or if it starts with digits (common for placeholder text)
    return len(s.strip()) < 20 or len(s.split()) < 5 or any(char.isdigit() for char in s[:10])

def fuzzy_match_genre(word, valid_genres):
    # Get the closest match with a cutoff of 0.6 (can be adjusted)
    match = get_close_matches(word.lower(), valid_genres, n=1, cutoff=0.6)
    return match[0] if match else None

def api_delay():
    # Adding a 1-second delay to be considerate to the Jikan API and avoid hitting rate limits.
    # Jikan API allows up to 3 requests/second on average. This delay helps maintain stability.
    time.sleep(1)

def safe_api_request(url, timeout=15): # Increased timeout for potentially slower responses
    try:
        response = requests.get(url, timeout=timeout)
        response.raise_for_status() # Raise an exception for HTTP errors (4xx or 5xx)
        return response.json()
    except requests.exceptions.HTTPError as http_err:
        print(f"HTTP error occurred: {http_err} - URL: {url}")
        return None
    except requests.exceptions.ConnectionError as conn_err:
        print(f"Connection error occurred: {conn_err} - URL: {url}")
        return None
    except requests.exceptions.Timeout as timeout_err:
        print(f"Request timed out: {timeout_err} - URL: {url}")
        return None
    except requests.exceptions.RequestException as req_err:
        print(f"An unexpected request error occurred: {req_err} - URL: {url}")
        return None

def get_manga_synopsis(manga_title):
    try:
        encoded_title = quote(manga_title)
        url = f"https://api.jikan.moe/v4/manga?q={encoded_title}&limit=1&order_by=score&sort=desc"
        api_delay()
        
        data = safe_api_request(url)
        if not data or not data.get("data"):
            return None
            
        manga = data["data"][0]
        title = manga.get("title", "Tidak diketahui")
        synopsis = manga.get("synopsis", "Sinopsis tidak tersedia")
        genres = [g['name'] for g in manga.get("genres", [])]
        score = manga.get("score", 0)
        status = manga.get("status", "Unknown")
        chapters = manga.get("chapters", "Unknown")
        published = manga.get("published", {}).get("string", "Unknown")
        authors = [a['name'] for a in manga.get("authors", [])]
        
        synopsis_indo = translate_to_indo(synopsis)
        
        reply = f"<b>📖 {title}</b><br><br>"
        if score and score > 0:
            reply += f"⭐ <b>Rating:</b> {score}/10<br>"
        # Only show synopsis if it's not considered "bad"
        if not is_bad_synopsis(synopsis):
            reply += f"<b>Sinopsis:</b><br>{synopsis_indo}<br><br>"
        else:
            reply += "<b>Sinopsis:</b> Tidak ada sinopsis yang detail tersedia untuk manga ini.<br><br>"

        if genres:
            reply += f"<b>Genre:</b> {', '.join(genres)}<br>"
        if authors:
            reply += f"<b>Author:</b> {', '.join(authors[:2])}<br>"
        if chapters and str(chapters) != "Unknown":
            reply += f"<b>Chapters:</b> {chapters}<br>"
        reply += f"<b>Status:</b> {status}<br>"
        if published and published != "Unknown":
            reply += f"<b>Published:</b> {published}<br>"
        
        return reply
    except Exception as e:
        print(f"Error getting synopsis for {manga_title}:", e)
        return None

def get_manga_genre_info(manga_title):
    try:
        encoded_title = quote(manga_title)
        url = f"https://api.jikan.moe/v4/manga?q={encoded_title}&limit=1&order_by=score&sort=desc"
        api_delay()
        
        data = safe_api_request(url)
        if not data or not data.get("data"):
            return None
            
        manga = data["data"][0]
        title = manga.get("title", "Tidak diketahui")
        genres = [g['name'] for g in manga.get("genres", [])]
        themes = [t['name'] for t in manga.get("themes", [])]
        demographics = [d['name'] for d in manga.get("demographics", [])]
        
        reply = f"<b>🎯 Genre dan Tema dari '{title}':</b><br><br>"
        if genres:
            reply += f"<b>Genre Utama:</b> {', '.join(genres)}<br>"
        if themes:
            reply += f"<b>Tema:</b> {', '.join(themes)}<br>"
        if demographics:
            reply += f"<b>Target Audience:</b> {', '.join(demographics)}<br>"
        
        return reply
    except Exception as e:
        print(f"Error getting genre info for {manga_title}:", e)
        return None

def get_manga_characters(manga_title):
    try:
        encoded_title = quote(manga_title)
        url = f"https://api.jikan.moe/v4/manga?q={encoded_title}&limit=1"
        api_delay()
        
        data = safe_api_request(url)
        if not data or not data.get("data"):
            return None
            
        manga_id = data["data"][0]["mal_id"]
        title = data["data"][0].get("title", "Tidak diketahui")
        
        char_url = f"https://api.jikan.moe/v4/manga/{manga_id}/characters"
        api_delay()
        
        char_data = safe_api_request(char_url)
        if not char_data or not char_data.get("data"):
            return None
            
        characters = char_data["data"]
        if characters:
            reply = f"<b>👥 Karakter Utama dari '{title}':</b><br><br>"
            # Prioritize 'Main' characters, then 'Supporting', up to 5
            main_chars = [c for c in characters if c.get("role") == "Main"]
            supporting_chars = [c for c in characters if c.get("role") == "Supporting"]
            
            display_chars = (main_chars + supporting_chars)[:5] # Combine and take top 5
            
            if not display_chars: # Fallback if no main/supporting found or available
                display_chars = characters[:5] # Take first 5 available

            if display_chars:
                for i, char in enumerate(display_chars):
                    char_name = char.get("character", {}).get("name", "Unknown")
                    role = char.get("role", "Unknown")
                    reply += f"{i+1}. <b>{char_name}</b> - {role}<br>"
                return reply
        return None # No characters found or fetched
    except Exception as e:
        print(f"Error getting characters for {manga_title}:", e)
        return None

def get_manga_recommendations_based_on_title(manga_title):
    try:
        encoded_title = quote(manga_title)
        url = f"https://api.jikan.moe/v4/manga?q={encoded_title}&limit=1"
        api_delay()
        
        data = safe_api_request(url)
        if not data or not data.get("data"):
            return None
            
        manga_id = data["data"][0]["mal_id"]
        title = data["data"][0].get("title", "Tidak diketahui")
        
        rec_url = f"https://api.jikan.moe/v4/manga/{manga_id}/recommendations"
        api_delay()
        
        rec_data = safe_api_request(rec_url)
        if not rec_data or not rec_data.get("data"):
            return None
            
        recommendations = rec_data["data"]
        if recommendations:
            reply = f"<b>💡 Rekomendasi manga seperti '{title}':</b><br><br>"
            count = 0
            for rec in recommendations:
                if count >= 5:
                    break
                
                rec_manga = rec.get("entry", {})
                rec_title = rec_manga.get("title", "Unknown")
                rec_id = rec_manga.get("mal_id")
                
                if rec_id:
                    try:
                        detail_url = f"https://api.jikan.moe/v4/manga/{rec_id}"
                        api_delay()
                        
                        detail_data = safe_api_request(detail_url)
                        if detail_data and detail_data.get("data"):
                            detail = detail_data["data"]
                            synopsis = detail.get("synopsis", "")
                            genres = [g['name'] for g in detail.get("genres", [])]
                            score = detail.get("score", 0)
                            
                            # Ensure the recommended manga has a good synopsis before adding
                            if synopsis and not is_bad_synopsis(synopsis):
                                synopsis_short = translate_to_indo(shorten_text(synopsis, 15))
                                reply += f"{count+1}. <b>{rec_title}</b>"
                                if score and score > 0:
                                    reply += f" ⭐ {score}"
                                reply += f"<br>= {synopsis_short}"
                                if genres:
                                    reply += f"<br><i>Genre: {', '.join(genres[:3])}</i>"
                                reply += "<br><br>"
                                count += 1
                    except Exception as inner_e:
                        print(f"Error fetching detail for recommendation {rec_title}: {inner_e}")
                        continue # Skip this recommendation if its detail fetch fails
            
            if count > 0:
                return reply
        return None
    except Exception as e:
        print(f"Error getting recommendations for {manga_title}:", e)
        return None

def detect_manga_query_type(text):
    text_lower = text.lower()
    
    if re.search(r'\b(apa itu|tentang apa|sinopsis|cerita|alur|plot|summary|tentang)\b.*\b\w+', text_lower):
        return "synopsis"
    if re.search(r'\b(genre|jenis|kategori|tipe)\b.*\b\w+', text_lower):
        return "genre"
    if re.search(r'\b(karakter|tokoh|character|pemain)\b.*\b\w+', text_lower):
        return "characters"
    if re.search(r'\b(seperti|mirip|sejenis|rekomendasi.*seperti)\b.*\b\w+', text_lower):
        return "similar"
    return None

def extract_manga_title_from_query(text, query_type):
    text_lower = text.lower()
    patterns = []
    
    # Generic pattern to catch titles more broadly (allowing apostrophes and at least 2 chars)
    generic_title_pattern = r'([a-zA-Z][a-zA-Z0-9\s\-:!?\']{2,})' 

    if query_type == "synopsis":
        patterns = [
            r'(?:apa itu|tentang apa|sinopsis|cerita|alur|plot|summary|tentang)\s+(?:manga\s+)?(.+)',
            r'(?:manga|judul)\s+(.+)\s+(?:tentang apa|itu apa|cerita|sinopsis)',
            r'(.+)\s+(?:itu manga apa|tentang apa|ceritanya)',
            generic_title_pattern # Fallback if specific phrases aren't matched
        ]
    elif query_type == "genre":
        patterns = [
            r'(?:genre|jenis|kategori|tipe)\s+(?:manga\s+)?(.+)',
            r'(?:manga\s+)?(.+)\s+(?:genre|genrenya|jenisnya)',
            generic_title_pattern
        ]
    elif query_type == "characters":
        patterns = [
            r'(?:karakter|tokoh|character|pemain)\s+(?:manga\s+)?(.+)',
            r'(?:manga\s+)?(.+)\s+(?:karakternya|tokohnya)',
            generic_title_pattern
        ]
    elif query_type == "similar":
        patterns = [
            r'(?:seperti|mirip|sejenis)\s+(?:manga\s+)?(.+)',
            r'(?:rekomendasi.*seperti)\s+(.+)',
            generic_title_pattern
        ]
    
    for pattern in patterns:
        match = re.search(pattern, text, re.IGNORECASE)
        if match:
            title = match.group(1).strip()
            # More aggressive cleaning of common chatbot command words and conversational fillers
            title = re.sub(r'\b(manga|apa|itu|ya|dong|sih|tolong|coba|nya|nya ya|ada|coba dong|kasih|berikan|buku|komik)\b', '', title, flags=re.IGNORECASE)
            title = re.sub(r'\s+', ' ', title).strip() # Replace multiple spaces with single space
            
            # Ensure the extracted title is meaningful and not just command words
            if len(title) > 2 and not any(word in title.lower().split() for word in ["manga", "genre", "karakter", "sinopsis", "rekomendasi", "mirip", "seperti", "tentang"]):
                return title
    return None

def fetch_manga_by_genre_api(genre_name, limit=5):
    genre_id = genre_ids.get(genre_name)
    if not genre_id:
        return []
    
    try:
        # Fetch more results to have enough good synopses after filtering
        url = f"https://api.jikan.moe/v4/manga?genres={genre_id}&limit={limit*3}&order_by=score&sort=desc" 
        api_delay()
        
        data = safe_api_request(url)
        if not data or not data.get("data"):
            return []
            
        processed_manga = []
        for manga in data["data"]:
            if len(processed_manga) >= limit:
                break
                
            title = manga.get("title", "Tidak diketahui").strip()
            synopsis = manga.get("synopsis") or ""
            manga_genres = [g['name'] for g in manga.get("genres", [])]
            score = manga.get("score", 0)
            
            # Skip manga with "bad" synopses to ensure quality recommendations
            if is_bad_synopsis(synopsis):
                continue
            
            processed_manga.append({
                'title': title,
                'synopsis': synopsis,
                'genres': manga_genres,
                'score': score
            })
        
        return processed_manga
    except Exception as e:
        print(f"Error fetching {genre_name}:", e)
        return []

def fetch_manga_by_combined_genres(genre_list, search_type="and", limit=5):
    if not genre_list:
        return None
        
    genre_id_list = []
    for genre in genre_list:
        genre_id = genre_ids.get(genre)
        if genre_id:
            genre_id_list.append(str(genre_id))
    
    if not genre_id_list: # No valid genres identified
        return None
    
    try:
        if search_type == "and":
            # For 'AND' search, fetch more to ensure enough results match ALL criteria after filtering
            genre_param = ",".join(genre_id_list)
            url = f"https://api.jikan.moe/v4/manga?genres={genre_param}&limit={limit*5}&order_by=score&sort=desc" 
            genre_text = " + ".join([g.title() for g in genre_list])
            reply_header = f"<b>🎯 Rekomendasi manga dengan genre {genre_text}:</b><br><br>"
            
            api_delay()
            data = safe_api_request(url)
            if not data:
                return None
            manga_data = data.get("data", [])
        else: # search_type == "or"
            all_manga = []
            for genre_id in genre_id_list:
                # For 'OR' search, fetch a reasonable amount for each genre to build a pool
                url = f"https://api.jikan.moe/v4/manga?genres={genre_id}&limit=10&order_by=score&sort=desc" 
                api_delay()
                
                data = safe_api_request(url)
                if data and data.get("data"):
                    all_manga.extend(data["data"])
            
            # Deduplicate manga by title and sort by score for 'OR' results
            seen_titles = set()
            unique_manga = []
            for manga in all_manga:
                title = manga.get("title", "").strip()
                if title and title not in seen_titles:
                    seen_titles.add(title)
                    unique_manga.append(manga)
            
            unique_manga.sort(key=lambda x: x.get("score", 0), reverse=True)
            manga_data = unique_manga 
            
            genre_text = " atau ".join([g.title() for g in genre_list])
            reply_header = f"<b>🎲 Rekomendasi manga genre {genre_text}:</b><br><br>"
            
        if not manga_data:
            return f"Maaf, tidak ditemukan manga dengan kombinasi genre tersebut 😅"
        
        reply = reply_header
        count = 0
        
        for manga in manga_data:
            if count >= limit:
                break
                
            title = manga.get("title", "Tidak diketahui").strip()
            synopsis = manga.get("synopsis") or ""
            manga_genres = [g['name'] for g in manga.get("genres", [])]
            score = manga.get("score", 0)
            
            if is_bad_synopsis(synopsis):
                continue
            
            if search_type == "and":
                requested_genres_lower = [g.lower() for g in genre_list]
                manga_genres_lower = [g.lower() for g in manga_genres]
                
                # Check if ALL requested genres (fuzzy or exact) are present in the manga's genres
                has_all_genres = all(
                    any(req_genre in manga_genre for manga_genre in manga_genres_lower) 
                    for req_genre in requested_genres_lower
                )
                
                if not has_all_genres:
                    continue
            
            synopsis_translated = translate_to_indo(shorten_text(synopsis, max_words=20))
            genres_text = ', '.join(manga_genres[:5]) # Limit genres displayed for brevity
            
            reply += f"{count+1}. <b>{title}</b>"
            if score and score > 0:
                reply += f" ⭐ {score}"
            reply += f"<br>= {synopsis_translated}<br><i>Genre: {genres_text}</i><br><br>"
            count += 1
        
        return reply.strip() if count > 0 else f"Maaf, tidak ditemukan manga yang cocok dengan kriteria tersebut 😅"
    except Exception as e:
        print("ERROR in fetch_manga_by_combined_genres:", e)
        return None

def search_manga_by_query(query, limit=3):
    try:
        # Clean the query more aggressively for general search
        clean_query = re.sub(r'[^\w\s]', ' ', query.lower())
        clean_query = re.sub(r'\b(manga|rekomendasi|cari|carikan|bagus|keren|seru|apa|itu|judul|ini|itu)\b', '', clean_query)
        clean_query = re.sub(r'\s+', ' ', clean_query).strip()
        
        if len(clean_query) < 2: # Don't search for very short or empty queries after cleaning
            return None 
        
        encoded_query = quote(clean_query)
        url = f"https://api.jikan.moe/v4/manga?q={encoded_query}&limit={limit*2}&order_by=score&sort=desc" # Fetch more to filter bad synopses
        api_delay()
        
        data = safe_api_request(url)
        if not data or not data.get("data"):
            return None
        
        manga_list = data["data"]
        replies = []
        count = 0
        
        for manga in manga_list:
            if count >= limit:
                break
                
            title = manga.get("title", "Tidak diketahui")
            synopsis = manga.get("synopsis", "")
            manga_genres = [g['name'] for g in manga.get("genres", [])]
            score = manga.get("score", 0)
            
            if is_bad_synopsis(synopsis):
                continue
            
            synopsis_translated = translate_to_indo(shorten_text(synopsis, max_words=20))
            genres_text = ', '.join(manga_genres[:5])
            
            reply_item = f"{count+1}. <b>{title}</b>"
            if score and score > 0:
                reply_item += f" ⭐ {score}"
            reply_item += f"<br>= {synopsis_translated}"
            if genres_text:
                reply_item += f"<br><i>Genre: {genres_text}</i>"
            
            replies.append(reply_item)
            count += 1
        
        if replies:
            return f"<b>📖 Rekomendasi manga untuk query '{query}':</b><br><br>" + "<br><br>".join(replies)
        return None
    except Exception as e:
        print("Error in search_manga_by_query:", e)
        return None

def get_top_manga(limit=5):
    try:
        url = f"https://api.jikan.moe/v4/top/manga?limit={limit}"
        api_delay()
        
        data = safe_api_request(url)
        if not data or not data.get("data"):
            return None
            
        manga_list = data["data"]
        replies = []
        
        for i, manga in enumerate(manga_list):
            if i >= limit:
                break
                
            title = manga.get("title", "Tidak diketahui")
            synopsis = manga.get("synopsis", "")
            manga_genres = [g['name'] for g in manga.get("genres", [])]
            score = manga.get("score", 0)
            
            # Ensure synopsis is not bad before including
            if is_bad_synopsis(synopsis):
                continue

            synopsis_translated = translate_to_indo(shorten_text(synopsis, max_words=20))
            genres_text = ', '.join(manga_genres[:5])
            
            reply_item = f"{i+1}. <b>{title}</b>"
            if score and score > 0:
                reply_item += f" ⭐ {score}"
            reply_item += f"<br>= {synopsis_translated}"
            if genres_text:
                reply_item += f"<br><i>Genre: {genres_text}</i>"
            
            replies.append(reply_item)
        
        if replies:
            return f"<b>🏆 Top Manga Rekomendasi:</b><br><br>" + "<br><br>".join(replies)
        return None
    except Exception as e:
        print("Error in get_top_manga:", e)
        return None

def get_random_manga(limit=20): # Fetch more to have enough good ones to pick from
    try:
        # Fetch manga from a general search or a broad category
        # Using a general search with a broad query like 'manga' or empty query might return popular ones.
        # Or you could iterate through random pages of top manga.
        # For simplicity, let's try a general search or top manga from a random page.
        
        # Option 1: Fetch a random page from top manga (more likely to be good quality)
        random_page = random.randint(1, 5) # Random page from 1 to 5 for top manga
        url = f"https://api.jikan.moe/v4/top/manga?page={random_page}&limit={limit}"
        api_delay()
        
        data = safe_api_request(url)
        if not data or not data.get("data"):
            # Fallback to general search if top manga fails or is empty
            url = f"https://api.jikan.moe/v4/manga?limit={limit}&order_by=members&sort=desc" # Popular manga by members
            api_delay()
            data = safe_api_request(url)
            if not data or not data.get("data"):
                return None
            
        manga_list = [m for m in data["data"] if not is_bad_synopsis(m.get("synopsis", ""))]
        
        if manga_list:
            random_selected_manga = random.choice(manga_list)
            
            title = random_selected_manga.get("title", "Tidak diketahui")
            synopsis = random_selected_manga.get("synopsis", "Sinopsis tidak tersedia")
            genres = [g['name'] for g in random_selected_manga.get("genres", [])]
            score = random_selected_manga.get("score", 0)
            
            synopsis_indo = translate_to_indo(synopsis)
            genres_text = ', '.join(genres[:5]) # Limit genres displayed for brevity
            
            reply = f"<b>✨ Rekomendasi manga acak untuk Anda:</b><br><br>"
            reply += f"<b>📖 {title}</b>"
            if score and score > 0:
                reply += f" ⭐ {score}"
            reply += f"<br>= {synopsis_indo}"
            if genres_text:
                reply += f"<br><i>Genre: {genres_text}</i>"
            reply += "<br><br>Semoga Anda menyukainya! 😊"
            
            return reply
        return None
    except Exception as e:
        print("Error in get_random_manga:", e)
        return None

def parse_genre_query(text):
    text = text.lower().strip()
    search_type = "or" # Default to 'or' if no explicit 'and' or ','
    
    # Check for explicit 'and' or commas
    if re.search(r'\b(dan|and)\b', text) or ',' in text:
        search_type = "and"
    # If 'atau' is present, it explicitly sets to 'or' (overriding 'and' if both are present)
    if re.search(r'\b(atau|or)\b', text):
        search_type = "or"
    
    # Remove common genre-related query words to isolate potential genre names
    cleaned_input = re.sub(r'\b(dan|atau|and|or|dengan|manga|genre|rekomendasi|cari|carikan|berikan|saya|yang|ada|tolong|coba|sih|dong|ya)\b', ' ', text)
    cleaned_input = re.sub(r'[.,+]', ' ', cleaned_input) # Remove punctuation that might be part of genre separators
    cleaned_input = re.sub(r'\s+', ' ', cleaned_input).strip() # Normalize spaces
    
    words = cleaned_input.split()
    matched_genres = []
    suggested_corrections = {} # Store original_typo_word: corrected_genre
    
    valid_genre_keys = list(genre_ids.keys())

    for word in words:
        if word in valid_genre_keys: # Exact match found
            if word not in matched_genres:
                matched_genres.append(word)
        else: # Try fuzzy match for potential typos
            matched = fuzzy_match_genre(word, valid_genre_keys)
            if matched:
                if matched not in matched_genres:
                    matched_genres.append(matched)
                if word != matched: # If it was a fuzzy match but not exact
                    suggested_corrections[word] = matched
            # Words that don't match exactly or fuzzily are ignored for genre search

    return matched_genres, search_type, suggested_corrections

def extract_manga_title(text):
    # Expanded patterns to catch more variations of title queries
    patterns = [
        r"(?:apa itu|tentang apa|itu manga apa|manga|judul|manga apa)\s*([a-zA-Z][a-zA-Z0-9\s\-:!?']+)",
        r"(?:cari manga|carikan manga|info manga|rekomendasikan manga)\s*([a-zA-Z][a-zA-Z0-9\s\-:!?']+)",
        r"(?:rekomendasi manga|manga seperti|mirip dengan|sejenis)\s*([a-zA-Z][a-zA-Z0-9\s\-:!?']+)",
        r"([a-zA-Z][a-zA-Z0-9\s\-:!?']+)\s*(?:sinopsis|genre|karakter|mirip|seperti|itu apa)" # Catches title before keyword
    ]
    
    for pattern in patterns:
        match = re.search(pattern, text, re.IGNORECASE)
        if match:
            title = match.group(1).strip()
            # Aggressive cleaning of common chatbot command words and fillers from the extracted title
            title = re.sub(r'\b(apa|itu|ya|dong|sih|tolong|coba|nya|nya ya|manga|genre|karakter|sinopsis|rekomendasi|mirip|seperti|tentang|komik|buku|bagus|seru|keren|aku|mau|cari|carikan|berikan|ada)\b', '', title, flags=re.IGNORECASE)
            title = re.sub(r'\s+', ' ', title).strip() # Normalize spaces
            
            # Ensure the extracted title is meaningful (more than 2 characters and not just common keywords)
            if len(title) > 2 and not any(word in title.lower().split() for word in genre_ids.keys()):
                return title
    return None

@app.route("/chat", methods=["POST"])
def chat():
    try:
        user_input = request.json.get("message", "").strip()
        if not user_input:
            return jsonify({"reply": "Maaf, saya tidak bisa memahami pesan kosong 😅"})

        user_lower = user_input.lower()

        # --- Help/Bantu command ---
        if re.search(r'\b(help|bantu|cara pakai|bagaimana|apa yang bisa saya lakukan)\b', user_lower):
            help_message = """
            <b>Panduan Penggunaan Bot Manga:</b><br><br>
            Halo! Saya adalah bot rekomendasi manga. Saya bisa bantu Anda mencari informasi atau rekomendasi manga.<br>
            Anda bisa bertanya dengan format berikut:<br>
            <ul>
                <li><b>Sinopsis manga:</b> "Apa sinopsis One Piece?", "Cerita tentang Naruto?"</li>
                <li><b>Genre manga:</b> "Genre Jujutsu Kaisen?", "Kategori Solo Leveling?"</li>
                <li><b>Karakter manga:</b> "Karakter utama Attack on Titan?", "Siapa tokoh di Berserk?"</li>
                <li><b>Rekomendasi mirip:</b> "Rekomendasi manga seperti Chainsaw Man?", "Manga mirip Spy x Family?"</li>
                <li><b>Rekomendasi genre:</b> "Rekomendasi manga genre fantasi", "Cari manga action dan comedy", "Manga shounen atau adventure"</li>
                <li><b>Manga populer:</b> "Top manga", "Manga terbaik", "Manga terpopuler"</li>
                <li><b>Rekomendasi acak:</b> "Rekomendasi manga acak", "Manga bagus dong", "Cari manga apa saja"</li>
                <li><b>Pencarian umum:</b> "Cari manga My Hero Academia", "Manga seru", "Judul manga 'Kingdom'"</li>
            </ul>
            Saya akan berusaha menemukan informasi terbaik untuk Anda! 😊
            """
            return jsonify({"reply": help_message})

        # --- Greeting responses ---
        if re.search(r'\b(halo|hi|hello|hai|assalamualaikum|selamat)\b', user_lower):
            return jsonify({"reply": random.choice([
                "Halo! Saya chatbot rekomendasi manga. Ada yang bisa saya bantu? 😊",
                "Hai! Mau saya bantu cari manga berdasarkan genre atau tahu informasi tentang manga tertentu? Ketik 'bantu' untuk panduan ya! 😊",
                "Selamat datang! Saya bisa bantu dengan rekomendasi manga, sinopsis, genre, dan informasi lainnya. Ketik 'bantu' jika Anda butuh panduan. 😊"
            ])})

        # --- Block inappropriate content ---
        if re.search(r'\b(hentai|porn|sex|dewasa|bokep|mesum)\b', user_lower):
            return jsonify({"reply": "Maaf, saya tidak bisa memberikan rekomendasi untuk genre atau konten tersebut 🙏"})

        # --- Check for specific manga information queries (synopsis, genre, characters, similar) ---
        query_type = detect_manga_query_type(user_input)
        
        if query_type:
            manga_title = extract_manga_title_from_query(user_input, query_type)
            if manga_title:
                if query_type == "synopsis":
                    reply = get_manga_synopsis(manga_title)
                    if reply:
                        return jsonify({"reply": reply})
                    else:
                        return jsonify({"reply": f"Maaf, saya tidak bisa menemukan sinopsis untuk '{manga_title}'. Mohon cek kembali nama manga atau coba judul lain 😅"})
                elif query_type == "genre":
                    reply = get_manga_genre_info(manga_title)
                    if reply:
                        return jsonify({"reply": reply})
                    else:
                        return jsonify({"reply": f"Maaf, saya tidak bisa menemukan informasi genre untuk '{manga_title}'. Mohon cek kembali nama manga atau coba judul lain 😅"})
                elif query_type == "characters":
                    reply = get_manga_characters(manga_title)
                    if reply:
                        return jsonify({"reply": reply})
                    else:
                        return jsonify({"reply": f"Maaf, saya tidak bisa menemukan informasi karakter untuk '{manga_title}'. Mohon cek kembali nama manga atau coba judul lain 😅"})
                elif query_type == "similar":
                    reply = get_manga_recommendations_based_on_title(manga_title)
                    if reply:
                        return jsonify({"reply": reply})
                    else:
                        return jsonify({"reply": f"Maaf, saya tidak bisa menemukan rekomendasi manga yang mirip dengan '{manga_title}'. Coba judul lain atau genre lain 😅"})

        # --- Top manga requests ---
        if re.search(r'\b(top|terbaik|populer|terkenal|terpopuler|best|ranking)\b.*manga', user_lower):
            reply = get_top_manga(5)
            if reply:
                return jsonify({"reply": reply})
            else:
                return jsonify({"reply": "Maaf, saya tidak bisa mengambil daftar manga top saat ini. Silakan coba lagi nanti 😅"})

        # --- Random manga recommendation ---
        if re.search(r'\b(random|acak|saran|rekomendasi)\b.*\b(manga|apa saja|bagus|seru|keren|dong)', user_lower):
            reply = get_random_manga()
            if reply:
                return jsonify({"reply": reply})
            else:
                return jsonify({"reply": "Maaf, saya kesulitan menemukan rekomendasi manga acak saat ini. Coba lagi nanti ya 😅"})

        # --- Parse genre query with typo correction feedback ---
        matched_genres, search_type, suggested_corrections = parse_genre_query(user_input)

        if matched_genres:
            correction_feedback = ""
            if suggested_corrections:
                for original, corrected in suggested_corrections.items():
                    correction_feedback += f"Saya mendeteksi Anda mungkin bermaksud genre '{corrected}' (dari '{original}'). "
                correction_feedback += "Saya akan mencari berdasarkan itu.<br><br>"

            if len(matched_genres) > 1:
                reply = fetch_manga_by_combined_genres(matched_genres, search_type, 5)
                if reply:
                    return jsonify({"reply": correction_feedback + reply})
                else:
                    return jsonify({"reply": f"{correction_feedback}Maaf, saya tidak menemukan manga dengan kombinasi genre '{' dan '.join([g.title() for g in matched_genres])}'. Coba kombinasi genre lain atau satu genre saja 😅"})
            else: # Single genre search
                manga_list = fetch_manga_by_genre_api(matched_genres[0], 5)
                if manga_list:
                    replies = []
                    for i, manga in enumerate(manga_list):
                        synopsis_translated = translate_to_indo(shorten_text(manga['synopsis'], max_words=20))
                        genres_text = ', '.join(manga['genres'][:5])
                        
                        reply_item = f"{i+1}. <b>{manga['title']}</b>"
                        if manga['score'] and manga['score'] > 0:
                            reply_item += f" ⭐ {manga['score']}"
                        reply_item += f"<br>= {synopsis_translated}"
                        if genres_text:
                            reply_item += f"<br><i>Genre: {genres_text}</i>"
                        
                        replies.append(reply_item)
                    
                    if replies:
                        genre_name = matched_genres[0].title()
                        final_reply = f"<b>🎯 Rekomendasi manga genre {genre_name}:</b><br><br>" + "<br><br>".join(replies)
                        return jsonify({"reply": correction_feedback + final_reply})
                    else:
                        return jsonify({"reply": f"{correction_feedback}Maaf, saya tidak menemukan manga untuk genre '{matched_genres[0].title()}'. Mungkin coba genre lain? 😅"})

        # --- General search (fallback if no specific query type or genre matched) ---
        manga_title = extract_manga_title(user_input)
        if manga_title and len(manga_title) > 2: # Ensure a meaningful title was extracted
            reply = search_manga_by_query(manga_title, 3)
            if reply:
                return jsonify({"reply": reply})
            else:
                return jsonify({"reply": f"Maaf, saya tidak bisa menemukan manga dengan judul '{manga_title}'. Mungkin ada kesalahan ketik atau manga tersebut tidak ada di database saya. Coba ketik ulang judulnya ya 😊"})

        # --- Default response if nothing matches ---
        return jsonify({"reply": "Maaf, saya tidak bisa memahami permintaan Anda. Coba tanyakan tentang genre manga, sinopsis, atau rekomendasi manga tertentu. Ketik 'bantu' untuk panduan 😊"})

    except Exception as e:
        print(f"Error in chat: {e}")
        return jsonify({"reply": "Maaf, terjadi kesalahan pada sistem saya. Silakan coba lagi nanti 😅"})

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5002, debug=True)