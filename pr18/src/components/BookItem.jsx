import { useState } from 'react'

const API = 'http://localhost:3001/books'

function BookItem({ book, onDelete }) {
  const [deleting, setDeleting] = useState(false)
  const [hovered,  setHovered]  = useState(false)

  const handleDelete = async () => {
    setDeleting(true)
    try {
      await fetch(`${API}/${book.id}`, { method: 'DELETE' })
      onDelete(book.id)
    } catch {
      setDeleting(false)
    }
  }

  return (
    <div
      onMouseEnter={() => setHovered(true)}
      onMouseLeave={() => setHovered(false)}
      style={{
        display: 'flex', alignItems: 'center', gap: 16,
        padding: '16px 20px',
        background: hovered ? 'rgba(255,255,255,0.045)' : 'rgba(255,255,255,0.025)',
        border: '1px solid',
        borderColor: hovered ? 'rgba(129,140,248,0.25)' : 'rgba(255,255,255,0.06)',
        borderRadius: 12, marginBottom: 10,
        transition: 'all 0.2s',
        transform: hovered ? 'translateX(4px)' : 'translateX(0)',
      }}
    >
      <div style={{
        width: 44, height: 44, borderRadius: 10, flexShrink: 0,
        background: 'linear-gradient(135deg, rgba(129,140,248,0.15), rgba(6,182,212,0.15))',
        border: '1px solid rgba(129,140,248,0.2)',
        display: 'flex', alignItems: 'center', justifyContent: 'center',
        fontSize: 20,
      }}>📖</div>

      <div style={{ flex: 1, minWidth: 0 }}>
        <div style={{
          fontFamily: 'Outfit, sans-serif',
          fontWeight: 700, fontSize: 15,
          color: '#f1f5f9', marginBottom: 3,
          whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis',
        }}>{book.title}</div>
        <div style={{
          fontFamily: 'JetBrains Mono, monospace',
          fontSize: 11, color: '#64748b',
        }}>{book.author}</div>
      </div>

      <div style={{
        fontFamily: 'JetBrains Mono, monospace',
        fontSize: 12, color: '#818cf8',
        background: 'rgba(129,140,248,0.08)',
        border: '1px solid rgba(129,140,248,0.2)',
        padding: '4px 10px', borderRadius: 6,
        flexShrink: 0,
      }}>{book.year}</div>

      <button
        onClick={handleDelete}
        disabled={deleting}
        style={{
          background: 'transparent', border: 'none',
          color: hovered ? '#f87171' : '#334155',
          cursor: 'pointer', fontSize: 16,
          transition: 'color 0.2s', padding: '4px 8px',
          opacity: deleting ? 0.4 : 1,
        }}
      >{deleting ? '...' : '✕'}</button>
    </div>
  )
}

export default BookItem
