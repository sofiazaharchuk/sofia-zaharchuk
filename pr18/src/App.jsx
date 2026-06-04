import { useState, useEffect } from 'react'
import Header from './components/Header'
import BookForm from './components/BookForm'
import BookList from './components/BookList'
import Footer from './components/Footer'

const API = 'http://localhost:3001/books'

function App() {
  const [books,   setBooks]   = useState([])
  const [loading, setLoading] = useState(false)
  const [error,   setError]   = useState('')
  const [search,  setSearch]  = useState('')

  useEffect(() => {
    setLoading(true)
    fetch(API)
      .then(res => {
        if (!res.ok) throw new Error('Помилка сервера')
        return res.json()
      })
      .then(data => { setBooks(data); setError('') })
      .catch(() => setError('Не вдалося завантажити книги. Перевірте чи запущений json-server на порту 3001.'))
      .finally(() => setLoading(false))
  }, [])

  const handleAdd = (book) => setBooks(prev => [...prev, book])

  const handleDelete = (id) => setBooks(prev => prev.filter(b => b.id !== id))

  return (
    <div style={{
      minHeight: '100vh',
      background: 'linear-gradient(160deg, #08080f 0%, #0d0d1a 60%, #08080f 100%)',
      color: '#f1f5f9',
    }}>
      <div style={{
        position: 'fixed', inset: 0, zIndex: 0, pointerEvents: 'none',
        background: `
          radial-gradient(ellipse 55% 45% at 10% 15%, rgba(129,140,248,0.08), transparent 55%),
          radial-gradient(ellipse 45% 40% at 90% 80%, rgba(6,182,212,0.06), transparent 55%)
        `,
      }} />
      <div style={{
        position: 'fixed', inset: 0, zIndex: 0, pointerEvents: 'none',
        backgroundImage: `
          linear-gradient(rgba(129,140,248,0.03) 1px, transparent 1px),
          linear-gradient(90deg, rgba(129,140,248,0.03) 1px, transparent 1px)
        `,
        backgroundSize: '44px 44px',
      }} />

      <Header count={books.length} />

      <main style={{
        position: 'relative', zIndex: 1,
        maxWidth: 900, margin: '0 auto',
        padding: '52px 32px 40px',
      }}>
        <div style={{ marginBottom: 40 }}>
          <div style={{
            fontFamily: 'JetBrains Mono, monospace',
            fontSize: 10, color: '#818cf8',
            letterSpacing: 4, marginBottom: 10,
          }}>// BOOK MANAGER · GET · POST · DELETE</div>
          <h1 style={{
            fontFamily: 'Outfit, sans-serif',
            fontWeight: 800,
            fontSize: 'clamp(2rem, 5vw, 3.5rem)',
            lineHeight: 1.05, letterSpacing: -1.5,
            background: 'linear-gradient(135deg, #f8fafc 0%, #94a3b8 100%)',
            WebkitBackgroundClip: 'text',
            WebkitTextFillColor: 'transparent',
            backgroundClip: 'text',
            marginBottom: 10,
          }}>Моя бібліотека</h1>
        </div>

        <BookForm onAdd={handleAdd} />

        <div style={{ marginBottom: 20 }}>
          <input
            value={search}
            onChange={e => setSearch(e.target.value)}
            placeholder="🔍 Пошук за назвою..."
            style={{
              width: '100%',
              background: 'rgba(255,255,255,0.04)',
              border: '1px solid rgba(255,255,255,0.09)',
              borderRadius: 10, padding: '11px 14px',
              color: '#f1f5f9',
              fontFamily: 'Outfit, sans-serif', fontSize: 14,
              outline: 'none', boxSizing: 'border-box',
            }}
            onFocus={e => e.target.style.borderColor = 'rgba(129,140,248,0.5)'}
            onBlur={e => e.target.style.borderColor = 'rgba(255,255,255,0.09)'}
          />
        </div>

        <BookList
          books={books}
          loading={loading}
          error={error}
          onDelete={handleDelete}
          search={search}
        />
      </main>

      <Footer />
    </div>
  )
}

export default App
