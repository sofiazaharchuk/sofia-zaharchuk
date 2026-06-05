import { Link } from 'react-router-dom'

function NotFound() {
  return (
    <div style={{ textAlign: 'center', padding: '80px 0' }}>
      <div style={{ fontFamily: 'Outfit, sans-serif', fontWeight: 800, fontSize: 96, color: 'rgba(110,231,183,0.15)', lineHeight: 1, marginBottom: 16 }}>404</div>
      <h2 style={{ fontFamily: 'Outfit, sans-serif', fontWeight: 700, fontSize: 24, color: '#f1f5f9', marginBottom: 10 }}>Сторінку не знайдено</h2>
      <p style={{ fontFamily: 'Outfit, sans-serif', fontSize: 14, color: '#475569', marginBottom: 28 }}>Перевірте адресу або поверніться на головну</p>
      <Link to="/" style={{
        background: 'linear-gradient(135deg, #6ee7b7, #3b82f6)',
        borderRadius: 10, padding: '11px 28px', color: '#000',
        fontFamily: 'Outfit, sans-serif', fontWeight: 700, fontSize: 14,
        textDecoration: 'none',
      }}>На головну</Link>
    </div>
  )
}

export default NotFound
