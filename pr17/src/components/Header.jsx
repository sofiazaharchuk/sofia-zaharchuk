import { useContext } from 'react'
import { CartContext } from '../context/CartContext'

function Header() {
  const { cartCount, cartTotal } = useContext(CartContext)

  return (
    <header style={{
      background: 'rgba(8,8,16,0.92)',
      backdropFilter: 'blur(24px)',
      borderBottom: '1px solid rgba(255,255,255,0.06)',
      padding: '0 40px',
      position: 'sticky', top: 0, zIndex: 100,
    }}>
      <div style={{
        maxWidth: 1200, margin: '0 auto',
        display: 'flex', alignItems: 'center',
        justifyContent: 'space-between', height: 64,
      }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
          <div style={{
            width: 32, height: 32, borderRadius: 8,
            background: 'linear-gradient(135deg, #f59e0b, #ef4444)',
            display: 'flex', alignItems: 'center', justifyContent: 'center',
            fontSize: 16,
          }}>🛍</div>
          <span style={{
            fontFamily: 'Outfit, sans-serif',
            fontWeight: 800, fontSize: 18,
            color: '#f8fafc', letterSpacing: -0.5,
          }}>ShopFlow</span>
        </div>

        <div style={{
          display: 'flex', alignItems: 'center', gap: 10,
          background: 'rgba(245,158,11,0.1)',
          border: '1px solid rgba(245,158,11,0.25)',
          borderRadius: 12, padding: '8px 16px',
        }}>
          <span style={{ fontSize: 16 }}>🛒</span>
          <span style={{
            fontFamily: 'Outfit, sans-serif',
            fontWeight: 600, fontSize: 14, color: '#fcd34d',
          }}>{cartCount} товарів</span>
          {cartCount > 0 && (
            <span style={{
              fontFamily: 'JetBrains Mono, monospace',
              fontSize: 12, color: '#f59e0b',
              borderLeft: '1px solid rgba(245,158,11,0.3)',
              paddingLeft: 10, marginLeft: 4,
            }}>{cartTotal.toLocaleString('uk-UA')} ₴</span>
          )}
        </div>
      </div>
    </header>
  )
}

export default Header
