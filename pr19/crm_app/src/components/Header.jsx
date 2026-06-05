import { useContext } from 'react'
import { AppContext } from '../context/AppContext'
import Navigation from './Navigation'

function Header() {
  const { students } = useContext(AppContext)

  return (
    <header style={{
      background: 'rgba(8,8,16,0.92)',
      backdropFilter: 'blur(24px)',
      borderBottom: '1px solid rgba(255,255,255,0.06)',
      position: 'sticky', top: 0, zIndex: 100,
      padding: '0 40px',
    }}>
      <div style={{
        maxWidth: 1100, margin: '0 auto',
        display: 'flex', alignItems: 'center',
        justifyContent: 'space-between', height: 64,
      }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
          <div style={{
            width: 32, height: 32, borderRadius: 8,
            background: 'linear-gradient(135deg, #6ee7b7, #3b82f6)',
            display: 'flex', alignItems: 'center', justifyContent: 'center',
            fontSize: 16,
          }}>🎓</div>
          <span style={{
            fontFamily: 'Outfit, sans-serif',
            fontWeight: 800, fontSize: 18,
            color: '#f8fafc', letterSpacing: -0.5,
          }}>StudentCRM</span>
        </div>
        <Navigation />
        <div style={{
          fontFamily: 'JetBrains Mono, monospace',
          fontSize: 11, color: '#6ee7b7',
          background: 'rgba(110,231,183,0.08)',
          border: '1px solid rgba(110,231,183,0.2)',
          padding: '5px 14px', borderRadius: 20,
        }}>{students.length} студентів</div>
      </div>
    </header>
  )
}

export default Header
