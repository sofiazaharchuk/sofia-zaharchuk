function Header({ total, completed }) {
  const percent = total === 0 ? 0 : Math.round((completed / total) * 100)

  return (
    <header style={{
      background: 'rgba(10,10,18,0.9)',
      backdropFilter: 'blur(24px)',
      borderBottom: '1px solid rgba(255,255,255,0.06)',
      padding: '0 40px',
      position: 'sticky',
      top: 0,
      zIndex: 100,
    }}>
      <div style={{
        maxWidth: 720, margin: '0 auto',
        display: 'flex', alignItems: 'center',
        justifyContent: 'space-between', height: 64,
      }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
          <div style={{
            width: 32, height: 32, borderRadius: 8,
            background: 'linear-gradient(135deg, #a78bfa, #ec4899)',
            display: 'flex', alignItems: 'center', justifyContent: 'center',
            fontSize: 14, color: '#fff', fontWeight: 700,
          }}>✓</div>
          <span style={{
            fontFamily: 'Outfit, sans-serif',
            fontWeight: 800, fontSize: 18,
            color: '#f8fafc', letterSpacing: -0.5,
          }}>TaskFlow</span>
        </div>

        <div style={{ display: 'flex', alignItems: 'center', gap: 16 }}>
          <div>
            <div style={{
              fontFamily: 'JetBrains Mono, monospace',
              fontSize: 10, color: '#64748b', letterSpacing: 2, marginBottom: 4,
            }}>ПРОГРЕС</div>
            <div style={{
              width: 120, height: 4,
              background: 'rgba(255,255,255,0.08)',
              borderRadius: 4, overflow: 'hidden',
            }}>
              <div style={{
                height: '100%', width: `${percent}%`,
                background: 'linear-gradient(90deg, #a78bfa, #ec4899)',
                borderRadius: 4, transition: 'width 0.4s ease',
              }} />
            </div>
          </div>
          <div style={{
            fontFamily: 'Outfit, sans-serif',
            fontWeight: 700, fontSize: 13, color: '#a78bfa',
          }}>{percent}%</div>
        </div>
      </div>
    </header>
  )
}

export default Header
