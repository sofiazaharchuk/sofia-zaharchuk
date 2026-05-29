import { useState, useContext } from 'react'
import { CartContext } from '../context/CartContext'

const catColors = {
  'Електроніка': { c: '#818cf8', bg: 'rgba(129,140,248,0.1)', b: 'rgba(129,140,248,0.25)' },
  'Аксесуари':   { c: '#34d399', bg: 'rgba(52,211,153,0.1)',  b: 'rgba(52,211,153,0.25)'  },
  'Меблі':       { c: '#fbbf24', bg: 'rgba(251,191,36,0.1)',  b: 'rgba(251,191,36,0.25)'  },
  'Книги':       { c: '#f472b6', bg: 'rgba(244,114,182,0.1)', b: 'rgba(244,114,182,0.25)' },
}
const catIcons = { 'Електроніка': '⚡', 'Аксесуари': '🎧', 'Меблі': '🪑', 'Книги': '📚' }

function ProductCard({ product }) {
  const { addToCart, cart } = useContext(CartContext)
  const [hovered, setHovered] = useState(false)
  const col   = catColors[product.category] || { c: '#94a3b8', bg: 'rgba(148,163,184,0.1)', b: 'rgba(148,163,184,0.2)' }
  const icon  = catIcons[product.category] || '📦'
  const inCart = cart.find(i => i.id === product.id)

  return (
    <div
      onMouseEnter={() => setHovered(true)}
      onMouseLeave={() => setHovered(false)}
      style={{
        background: hovered ? 'rgba(255,255,255,0.045)' : 'rgba(255,255,255,0.025)',
        border: '1px solid',
        borderColor: inCart ? col.b : (hovered ? 'rgba(255,255,255,0.1)' : 'rgba(255,255,255,0.06)'),
        borderRadius: 16, padding: '22px 20px',
        position: 'relative', overflow: 'hidden',
        transition: 'all 0.25s',
        transform: hovered ? 'translateY(-4px)' : 'translateY(0)',
        boxShadow: hovered ? '0 20px 50px rgba(0,0,0,0.5)' : '0 4px 16px rgba(0,0,0,0.3)',
      }}
    >
      <div style={{
        position: 'absolute', top: 0, left: 0, right: 0, height: 2,
        background: hovered ? `linear-gradient(90deg, ${col.c}, transparent)` : 'transparent',
        transition: 'all 0.25s',
      }} />

      {inCart && (
        <div style={{
          position: 'absolute', top: 14, right: 14,
          background: col.bg, border: `1px solid ${col.b}`,
          color: col.c, fontFamily: 'JetBrains Mono, monospace',
          fontSize: 9, letterSpacing: 1,
          padding: '2px 8px', borderRadius: 4,
        }}>×{inCart.qty}</div>
      )}

      <div style={{ fontSize: 32, marginBottom: 12 }}>{icon}</div>

      <div style={{
        display: 'inline-block',
        background: col.bg, border: `1px solid ${col.b}`,
        color: col.c, fontFamily: 'JetBrains Mono, monospace',
        fontSize: 9, letterSpacing: 2,
        padding: '2px 8px', borderRadius: 4, marginBottom: 10,
      }}>{product.category.toUpperCase()}</div>

      <div style={{
        fontFamily: 'Outfit, sans-serif',
        fontWeight: 700, fontSize: 16,
        color: '#f1f5f9', marginBottom: 16,
        lineHeight: 1.3,
      }}>{product.title}</div>

      <div style={{
        display: 'flex', alignItems: 'center',
        justifyContent: 'space-between',
      }}>
        <div style={{
          fontFamily: 'Outfit, sans-serif',
          fontWeight: 800, fontSize: 20,
          color: '#fbbf24',
        }}>{product.price.toLocaleString('uk-UA')} ₴</div>

        <button
          onClick={() => addToCart(product)}
          style={{
            background: hovered
              ? `linear-gradient(135deg, ${col.c}, #f59e0b)`
              : 'rgba(255,255,255,0.06)',
            border: '1px solid',
            borderColor: hovered ? 'transparent' : 'rgba(255,255,255,0.1)',
            borderRadius: 8, padding: '8px 16px',
            color: hovered ? '#000' : '#94a3b8',
            fontFamily: 'Outfit, sans-serif',
            fontWeight: 600, fontSize: 12,
            cursor: 'pointer', transition: 'all 0.25s',
            letterSpacing: 0.5,
          }}
        >{inCart ? '+ Ще' : 'Купити'}</button>
      </div>
    </div>
  )
}

export default ProductCard
