class BookCard extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      book: null,
    };
  }

  componentDidMount() {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');

    if (id) {
      fetch('books.json')
        .then(response => {
          if (!response.ok) {
            throw new Error('Failed to fetch books');
          }
          return response.json();
        })
        .then(data => {
          const book = data.find(book => book.id === parseInt(id));
          if (book) {
            this.setState({ book });
          } else {
            console.error('Book not found');
          }
        })
        .catch(error => console.error('Error fetching data:', error.message));
    } else {
      console.error('Book ID not provided in URL');
    }
  }

  render() {
    const { book } = this.state;
    if (!book) {
      return <div>Loading...</div>;
    }

    return (
      <div class="book-card__container">
        <div class="book-card__grid">
        <img src={book.image} alt="book" class="book-card__img" />
        <div class="book-card__info">
        <h1 class="book-card__title">{book.title}</h1>
            <h2 class="book-card__rating">{book.rating}/5
              <img
                src="./images/icons/star.svg"
                alt="star"
                class="book-card__star"
              ></img>
            </h2>
            <h2 class="book-card__inf">{book.author}</h2>
            <h2 class="book-card__inf"><span class="book-card__inf book-card__inf--weight">Жанр: </span> {book.genre}</h2>
            <h2 class="book-card__inf"><span class="book-card__inf book-card__inf--weight">Рік видання: </span> {book.year}</h2>
          <p class="book-card__abstract">
            {book.abstract}
          </p>
          </div>
          
            </div>
      </div>
    );
  }
}

