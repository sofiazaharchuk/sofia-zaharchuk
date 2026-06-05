import { Link } from 'react-router-dom'
import { useContext } from 'react'
import { AppContext } from '../context/AppContext'

function Home() {
  const { students } = useContext(AppContext)

  const stats = [
    { label: 'Студентів',  value: students.length,                                     icon: '👥' },
    { label: 'Груп',       value: new Set(students.map(s => s.group)).size,             icon: '🏫' },
    { label: 'Середній вік', value: students.length
        ? (students.reduce((s, i) => s + Number(i.age), 0) / students.length).toFixed(1)
        : 0,                                                                              icon: '📊' },
  ]

  return (
    <div>
      <div style={{ marginBottom: 48 }}>
        <div style={{ fontFamily: 'JetBrains Mono, monospace', fontSize: 10, color: '#6ee7b7', letterSpacing: 4, marginBottom: 10 }}>
          // STUDENT CRM · SPA
        </div>
        <h1 style={{
          fontFamily: 'Outfit, sans-serif', fontWeight: 800,
          fontSize: 'clamp(2rem, 5vw, 3.8rem)',
          lineHeight: 1.05, letterSpacing: -1.5,
          background: 'linear-gradient(135deg, #f8fafc 0%, #94a3b8 100%)',
          WebkitBackgroundClip: 'text', WebkitTextFillColor: 'transparent',
          backgroundClip: 'text', marginBottom: 14,
        }}>Система управління<br/>студентами</h1>
        <p style={{ fontFamily: 'Outfit, sans-serif', fontSize: 15, color: '#475569', maxWidth: 480, lineHeight: 1.7 }}>
          Повноцінний SPA-застосунок для роботи зі студентами. GET, POST, DELETE запити через Fetch API та json-server.
        </p>
        <div style={{ display: 'flex', gap: 12, marginTop: 24 }}>
          <Link to="/students" style={{
            background: 'linear-gradient(135deg, #6ee7b7, #3b82f6)',
            borderRadius: 10, padding: '11px 24px', color: '#000',
            fontFamily: 'Outfit, sans-serif', fontWeight: 700, fontSize: 14,
            textDecoration: 'none',
          }}>Переглянути студентів</Link>
          <Link to="/students/add" style={{
            background: 'transparent',
            border: '1px solid rgba(255,255,255,0.1)',
            borderRadius: 10, padding: '11px 24px', color: '#94a3b8',
            fontFamily: 'Outfit, sans-serif', fontWeight: 600, fontSize: 14,
            textDecoration: 'none',
          }}>Додати студента</Link>
        </div>
      </div>

      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 16 }}>
        {stats.map(s => (
          <div key={s.label} style={{
            background: 'rgba(255,255,255,0.025)',
            border: '1px solid rgba(255,255,255,0.06)',
            borderRadius: 14, padding: '22px',
            textAlign: 'center',
          }}>
            <div style={{ fontSize: 28, marginBottom: 8 }}>{s.icon}</div>
            <div style={{ fontFamily: 'Outfit, sans-serif', fontWeight: 800, fontSize: 32, color: '#6ee7b7', marginBottom: 4 }}>{s.value}</div>
            <div style={{ fontFamily: 'JetBrains Mono, monospace', fontSize: 10, color: '#64748b', letterSpacing: 2 }}>{s.label.toUpperCase()}</div>
          </div>
        ))}
      </div>
    </div>
  )
}

export default Home
