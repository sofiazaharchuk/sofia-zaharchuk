import { useContext } from 'react'
import { CartContext } from '../context/CartContext'

function Cart() {
  const { cart, removeFromCart, cartTotal } = useContext(CartContext)

  if (cart.length === 0) return null

  return (
    <div style={{
      background: 'rgba(245,158,11,0.05)',
      border: '1px solid rgba(245,158,11,0.2)',
      borderRadius: 16, padding: '22px 24px',
      marginBottom: 32,
    }}>
      <div style={{
        fontFamily: 'JetBrains Mono, monospace',
        fontSize: 10, color: '#f59e0b',
        letterSpacing: 3, marginBottom: 16,
      }}>🛒 КОШИК</div>

      {cart.map(item => (
        <div key={item.id} style={{
          display: 'flex', alignItems: 'center',
          justifyContent: 'space-between',
          padding: '10px 0',
          borderBottom: '1px solid rgba(255,255,255,0.04)',
        }}>
          <div>
            <div style={{
              fontFamily: 'Outfit, sans-serif',
              fontWeight: 600, fontSize: 14, color: '#e2e8f0',
              marginBottom: 2,
            }}>{item.title}</div>
            <div style={{
              fontFamily: 'JetBrains Mono, monospace',
              fontSize: 11, color: '#64748b',
            }}>{item.qty} × {item.price.toLocaleString('uk-UA')} ₴</div>
          </div>
          <div style={{ display: 'flex', alignItems: 'center', gap: 14 }}>
            <span style={{
              fontFamily: 'Outfit, sans-serif',
              fontWeight: 700, fontSize: 15, color: '#fbbf24',
            }}>{(item.price * item.qty).toLocaleString('uk-UA')} ₴</span>
            <button onClick={() => removeFromCart(item.id)} style={{
              background: 'transparent', border: 'none',
              color: '#475569', cursor: 'pointer',
              fontSize: 14, transition: 'color 0.2s',
            }}
              onMouseEnter={e => e.target.style.color = '#f87171'}
              onMouseLeave={e => e.target.style.color = '#475569'}
            >✕</button>
          </div>
        </div>
      ))}

      <div style={{
        display: 'flex', justifyContent: 'space-between',
        alignItems: 'center', marginTop: 16,
        paddingTop: 16,
        borderTop: '1px solid rgba(245,158,11,0.2)',
      }}>
        <span style={{
          fontFamily: 'Outfit, sans-serif',
          fontWeight: 600, fontSize: 14, color: '#94a3b8',
        }}>Разом</span>
        <span style={{
          fontFamily: 'Outfit, sans-serif',
          fontWeight: 800, fontSize: 22, color: '#fbbf24',
        }}>{cartTotal.toLocaleString('uk-UA')} ₴</span>
      </div>
    </div>
  )
}

export default Cart
