import React from 'react';

function Footer({ storeName }) {
  return (
    <footer style={{
      background: '#0a0a0f',
      borderTop: '1px solid rgba(255,255,255,0.06)',
      padding: '28px 48px',
      marginTop: 80,
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'space-between',
    }}>
      <div style={{
        fontFamily: 'Syne, sans-serif',
        fontWeight: 700, fontSize: 14,
        color: '#334155',
      }}>{storeName}</div>
      <div style={{
        fontFamily: 'DM Mono, monospace',
        fontSize: 10, color: '#334155', letterSpacing: 2,
      }}>
        © {new Date().getFullYear()} · REACT · ПРАКТИЧНА РОБОТА
      </div>
    </footer>
  );
}

export default Footer;
