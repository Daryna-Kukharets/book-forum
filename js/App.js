class App extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      name: '',
      apppVersion: ''
    }
  }
  
  
  render() {
    return (
      <BooksList />
    )
  }
}