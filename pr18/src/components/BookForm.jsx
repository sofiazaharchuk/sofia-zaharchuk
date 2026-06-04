import { useState, useRef } from 'react'

const API = 'http://localhost:3001/books'

function BookForm({ onAdd }) {
  const [title,  setTitle]  = useState('')
  const [author, setAuthor] = useState('')
  const [year,   setYear]   = useState('')
  const [error,  setError]  = useState('')
  const [loading, setLoading] = useState(false)
  const titleRef = useRef(null)

  const handleSubmit = async (e) => {
    e.preventDefault()
    if (!title.trim() || !author.trim() || !year.trim()) {
      setError('Усі поля обовʼязкові!')
      return
    }
    if (isNaN(year) || year < 1000 || year > new Date().getFullYear()) {
      setError('Введіть коректний рік')
      return
    }
    setError('')
    setLoading(true)
    try {
      const res = await fetch(API, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ title: title.trim(), author: author.trim(), year: year.trim() }),
      })
      const newBook = await res.json()
      onAdd(newBook)
      setTitle(''); setAuthor(''); setYear('')
      titleRef.current?.focus()
    } catch {
      setError('Помилка сервера. Перевірте чи запущений json-server.')
    } finally {
      setLoading(false)
    }
  }

  const inputStyle = (focused) => ({
    width: '100%',
    background: 'rgba(255,255,255,0.04)',
    border: '1px solid rgba(255,255,255,0.09)',
    borderRadius: 10, padding: '11px 14px',
    color: '#f1f5f9',
    fontFamily: 'Outfit, sans-serif', fontSize: 14,
    outline: 'none', boxSizing: 'border-box',
    transition: 'border-color 0.2s',
  })

  return (
    <div style={{
      background: 'rgba(129,140,248,0.05)',
      border: '1px solid rgba(129,140,248,0.15)',
      borderRadius: 16, padding: '24px',
      marginBottom: 32, position: 'relative', overflow: 'hidden',
    }}>
      <div style={{
        position: 'absolute', top: 0, left: 0, right: 0, height: 2,
        background: 'linear-gradient(90deg, #818cf8, #06b6d4)',
      }} />

      <div style={{
        fontFamily: 'JetBrains Mono, monospace',
        fontSize: 10, color: '#818cf8', letterSpacing: 3, marginBottom: 16,
      }}>// ДОДАТИ КНИГУ</div>

      <form onSubmit={handleSubmit}>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 120px', gap: 10, marginBottom: 10 }}>
          <div>
            <label style={{ display: 'block', fontFamily: 'Outfit, sans-serif', fontSize: 11, color: '#64748b', marginBottom: 5, letterSpacing: 1 }}>НАЗВА</label>
            <input
              ref={titleRef}
              value={title}
              onChange={e => setTitle(e.target.value)}
              placeholder="Назва книги..."
              style={inputStyle()}
              onFocus={e => e.target.style.borderColor = 'rgba(129,140,248,0.5)'}
              onBlur={e => e.target.style.borderColor = 'rgba(255,255,255,0.09)'}
            />
          </div>
          <div>
            <label style={{ display: 'block', fontFamily: 'Outfit, sans-serif', fontSize: 11, color: '#64748b', marginBottom: 5, letterSpacing: 1 }}>АВТОР</label>
            <input
              value={author}
              onChange={e => setAuthor(e.target.value)}
              placeholder="Ім'я автора..."
              style={inputStyle()}
              onFocus={e => e.target.style.borderColor = 'rgba(129,140,248,0.5)'}
              onBlur={e => e.target.style.borderColor = 'rgba(255,255,255,0.09)'}
            />
          </div>
          <div>
            <label style={{ display: 'block', fontFamily: 'Outfit, sans-serif', fontSize: 11, color: '#64748b', marginBottom: 5, letterSpacing: 1 }}>РІК</label>
            <input
              value={year}
              onChange={e => setYear(e.target.value)}
              placeholder="2024"
              style={inputStyle()}
              onFocus={e => e.target.style.borderColor = 'rgba(129,140,248,0.5)'}
              onBlur={e => e.target.style.borderColor = 'rgba(255,255,255,0.09)'}
            />
          </div>
        </div>

        {error && (
          <div style={{
            fontFamily: 'Outfit, sans-serif', fontSize: 13,
            color: '#f87171', marginBottom: 10,
            background: 'rgba(248,113,113,0.08)',
            border: '1px solid rgba(248,113,113,0.2)',
            borderRadius: 8, padding: '8px 14px',
          }}>⚠ {error}</div>
        )}

        <button type="submit" disabled={loading} style={{
          background: loading ? 'rgba(129,140,248,0.3)' : 'linear-gradient(135deg, #818cf8, #06b6d4)',
          border: 'none', borderRadius: 10,
          padding: '11px 28px', color: '#fff',
          fontFamily: 'Outfit, sans-serif',
          fontWeight: 600, fontSize: 14,
          cursor: loading ? 'not-allowed' : 'pointer',
          transition: 'opacity 0.2s',
        }}>
          {loading ? 'Додаємо...' : '+ Додати книгу'}
        </button>
      </form>
    </div>
  )
}

export default BookForm
