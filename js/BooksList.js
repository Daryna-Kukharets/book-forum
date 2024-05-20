class BooksList extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      books: [],
      ascendingOrder: true,
      initialBooks: [],
      selectedGenre: "",
    };
  }

  componentDidMount() {
    fetch('books.json')
      .then(response => response.json())
      .then(data => {
        this.setState({
          books: data,
          initialBooks: data
        }); // Оновлення стану компонента з отриманими даними
      })
      .catch(error => console.error('Error fetching data:', error));
  }

  handleRowClick = (id) => {
    window.location.href = `bookCard.html?id=${id}`;
  }

  toggleSortOrder = () => {
    this.setState(prevState => ({
      ascendingOrder: !prevState.ascendingOrder,
    }), () => {
      this.sortBooks();
    });
  }

  resetSorting = () => {
    this.setState({
      ascendingOrder: true,
      books: [...this.state.initialBooks] // Встановлюємо книги на основі початкового стану
    });
  }

  sortBooks = () => {
    const { books, ascendingOrder } = this.state;
    const sortedBooks = books.slice().sort((a, b) => {
      if (ascendingOrder) {
        return b.title.localeCompare(a.title);
      } else {
        return a.title.localeCompare(b.title);
      }
    });
    this.setState({ books: sortedBooks });
  }

  filterBooksByGenre = (genre) => {
    const { initialBooks } = this.state;
    if (genre === "") {
      this.setState({ books: initialBooks, selectedGenre: "" });
    } else {
      const filteredBooks = initialBooks.filter(book => book.genre === genre);
      this.setState({ books: filteredBooks, selectedGenre: genre });
    }
  }

  render() {
    return (
      <>
        <div class="books__func">
          <button
            onClick={this.resetSorting}
            class="books__button"
          >
            Скинути налаштування
          </button>
          <button
            onClick={this.toggleSortOrder}
            class="books__button"
          >
            Сортувати за назвою
          </button>
          <div>
            <label htmlFor="genre" class="books__label">Обрати жанр: </label>
            <select
              id="genre"
              value={this.state.selectedGenre}
              onChange={(e) => this.filterBooksByGenre(e.target.value)}
              class="books__select"
            >
              <option value="">Усі</option>
              <option value="Фентезі">Фентезі</option>
              <option value="Сучасна проза">Сучасна проза</option>
              <option value="Кримінальний трилер">Кримінальний трилер</option>
              <option value="Психологічний трилер">Психологічний трилер</option>
              <option value="Історичний детектив">Історичний детектив</option>
            </select>
          </div>
        </div>
      <table class="books__table">
        <thead>
          <tr class="books__thead-tr">
            <th class="books__th">Обкладинка</th>
            <th class="books__th">Автор</th>
            <th class="books__th">Назва</th>
            <th class="books__th">Жанр</th>
            <th class="books__th">Рейтинг</th>
          </tr>
        </thead>
        <tbody>
        {this.state.books.map(book => (
          <tr key={book.id} className="books__tbody-tr"onClick={() => this.handleRowClick(book.id)}>
            <td className="books__td">
              <img
                src={book.image}
                alt="book"
                className="books__th-img"
              />
            </td>
            <td className="books__td">{book.author}</td> {/* Виведення автора */}
            <td className="books__td">{book.title}</td> {/* Виведення назви */}
            <td className="books__td">{book.genre}</td> {/* Жанр, може бути динамічним, якщо він також є в об'єкті JSON */}
            <td className="books__td">{book.rating}</td> {/* Рейтинг, може бути динамічним, якщо він також є в об'єкті JSON */}
          </tr>
        ))}
      </tbody>
      </table>
      </>
      
    );
  }

}