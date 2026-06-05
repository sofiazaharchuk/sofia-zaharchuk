function Footer() {
  return (
    <footer style={{
      borderTop: '1px solid rgba(255,255,255,0.06)',
      padding: '22px 40px', marginTop: 60,
      display: 'flex', justifyContent: 'space-between', alignItems: 'center',
    }}>
      <span style={{ fontFamily: 'Outfit, sans-serif', fontWeight: 700, fontSize: 14, color: '#475569' }}>StudentCRM</span>
      <span style={{ fontFamily: 'JetBrains Mono, monospace', fontSize: 10, color: '#334155', letterSpacing: 2 }}>
        REACT · VITE · ROUTER · JSON-SERVER
      </span>
    </footer>
  )
}

export default Footer
