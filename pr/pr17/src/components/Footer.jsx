function Footer() {
  return (
    <footer style={{
      borderTop: '1px solid rgba(255,255,255,0.06)',
      padding: '24px 40px',
      display: 'flex', justifyContent: 'space-between', alignItems: 'center',
      marginTop: 60,
    }}>
      <span style={{
        fontFamily: 'Outfit, sans-serif',
        fontWeight: 700, fontSize: 14, color: '#334155',
      }}>ShopFlow</span>
      <span style={{
        fontFamily: 'JetBrains Mono, monospace',
        fontSize: 10, color: '#1e293b', letterSpacing: 2,
      }}>© {new Date().getFullYear()} · REACT · VITE · USECONTEXT</span>
    </footer>
  )
}

export default Footer
