import BookItem from './BookItem'

function BookList({ books, loading, error, onDelete, search }) {
  if (loading) return (
    <div style={{
      textAlign: 'center', padding: '60px 0',
      fontFamily: 'Outfit, sans-serif', color: '#475569',
    }}>
      <div style={{ fontSize: 32, marginBottom: 12, animation: 'spin 1s linear infinite', display: 'inline-block' }}>⟳</div>
      <div>Завантаження...</div>
    </div>
  )

  if (error) return (
    <div style={{
      background: 'rgba(248,113,113,0.08)',
      border: '1px solid rgba(248,113,113,0.25)',
      borderRadius: 12, padding: '20px 24px',
      color: '#f87171', fontFamily: 'Outfit, sans-serif', fontSize: 14,
    }}>⚠ {error}</div>
  )

  const filtered = search.trim()
    ? books.filter(b => b.title.toLowerCase().includes(search.toLowerCase()))
    : books

  if (filtered.length === 0) return (
    <div style={{
      textAlign: 'center', padding: '60px 0',
      color: '#334155', fontFamily: 'Outfit, sans-serif', fontSize: 14,
    }}>
      <div style={{ fontSize: 40, marginBottom: 12 }}>📭</div>
      {search ? `Книг за запитом "${search}" не знайдено` : 'Список книг порожній'}
    </div>
  )

  return (
    <div>
      {filtered.map(book => (
        <BookItem key={book.id} book={book} onDelete={onDelete} />
      ))}
    </div>
  )
}

export default BookList
