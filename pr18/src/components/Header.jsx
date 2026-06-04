function Header({ count }) {
  return (
    <header style={{
      background: 'rgba(8,8,16,0.92)',
      backdropFilter: 'blur(24px)',
      borderBottom: '1px solid rgba(255,255,255,0.06)',
      padding: '0 40px',
      position: 'sticky', top: 0, zIndex: 100,
    }}>
      <div style={{
        maxWidth: 900, margin: '0 auto',
        display: 'flex', alignItems: 'center',
        justifyContent: 'space-between', height: 64,
      }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
          <div style={{
            width: 32, height: 32, borderRadius: 8,
            background: 'linear-gradient(135deg, #818cf8, #06b6d4)',
            display: 'flex', alignItems: 'center', justifyContent: 'center',
            fontSize: 16,
          }}>📚</div>
          <span style={{
            fontFamily: 'Outfit, sans-serif',
            fontWeight: 800, fontSize: 18,
            color: '#f8fafc', letterSpacing: -0.5,
          }}>BookShelf</span>
        </div>
        <div style={{
          fontFamily: 'JetBrains Mono, monospace',
          fontSize: 11, color: '#818cf8',
          background: 'rgba(129,140,248,0.1)',
          border: '1px solid rgba(129,140,248,0.25)',
          padding: '5px 14px', borderRadius: 20,
          letterSpacing: 1,
        }}>{count} книг</div>
      </div>
    </header>
  )
}

export default Header
