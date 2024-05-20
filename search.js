fetch("books.json")
  .then((response) => response.json())
  .then((data) => {
    const books = data;

    function searchBooks(query) {
      const results = books.filter(
        (book) =>
          book.title.toLowerCase().includes(query.toLowerCase()) ||
          book.author.toLowerCase().includes(query.toLowerCase())
      );
    }

    function displaySuggestions(query) {
      const suggestionsContainer = document.getElementById("suggestions");
      suggestionsContainer.innerHTML = "";

      const suggestions = books.filter(
        (book) =>
          book.title.toLowerCase().includes(query.toLowerCase()) ||
          book.author.toLowerCase().includes(query.toLowerCase())
      );
      suggestions.slice(0, 5).forEach((book) => {
        const suggestion = document.createElement("div");
        suggestion.textContent = `${book.title} - ${book.author}`;
        suggestion.classList.add("suggestion");
        suggestion.addEventListener("click", () => {
          window.location.href = `bookCard.html?id=${book.id}`;
        });
        suggestionsContainer.appendChild(suggestion);
      });
    }

    const searchInput = document.getElementById("searchInput");
    const searchButton = document.getElementById("searchButton"); // Додано змінну для кнопки пошуку
    const clearButton = document.getElementById("clearButton");

    searchInput.addEventListener("input", () => {
      const query = searchInput.value.trim();
      if (query.length > 0) {
        displaySuggestions(query);
      } else {
        document.getElementById("suggestions").innerHTML = "";
      }
    });

    searchInput.addEventListener("keydown", (event) => {
      if (event.key === "Enter") {
        const firstSuggestion = document.querySelector("#suggestions div");
        if (firstSuggestion) {
          const suggestionText = firstSuggestion.textContent;
          document.getElementById("searchInput").value = suggestionText;
          const query = suggestionText.split(" - ")[0];
          searchBooks(query);
        }
      }
    });

    const searchForm = document.getElementById("searchForm");
    searchForm.addEventListener("submit", (event) => {
      event.preventDefault();
      const query = searchInput.value.trim();
      searchBooks(query);
    });

    // Додано обробник події для кнопки пошуку
    searchButton.addEventListener("click", () => {
      const query = searchInput.value.trim();
      if (query.length > 0) {
        const [title, author] = query.split(" - "); // Розділити введений рядок на назву та автора
        const book = books.find(
          (book) =>
            book.title.toLowerCase() === title.toLowerCase() &&
            book.author.toLowerCase() === author.toLowerCase()
        );
        console.log(query); // Додайте цей рядок для перевірки

        if (book) {
          window.location.href = `bookCard.html?id=${book.id}`;
        } else {
          searchBooks(query);
        }
      } else {
        alert("Будь ласка, введіть назву книги або ім'я автора для пошуку.");
      }
    });

    clearButton.addEventListener("click", () => {
      searchInput.value = ""; // Очищаємо поле вводу
      document.getElementById("suggestions").innerHTML = ""; // Очищаємо список пропозицій
    });
  })
  .catch((error) => {
    console.error("Помилка під час отримання даних про книги:", error);
  });
