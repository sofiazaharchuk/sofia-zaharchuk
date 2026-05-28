import React from 'react';

function Header({ title, subtitle, itemCount }) {
  return (
    <header style={{
      background: 'linear-gradient(135deg, #0a0a0f 0%, #12121e 100%)',
      borderBottom: '1px solid rgba(255,255,255,0.06)',
      padding: '0 48px',
      position: 'sticky',
      top: 0,
      zIndex: 100,
      backdropFilter: 'blur(20px)',
    }}>
      <div style={{
        maxWidth: 1200,
        margin: '0 auto',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        height: 70,
      }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 14 }}>
          <div style={{
            width: 36, height: 36, borderRadius: 10,
            background: 'linear-gradient(135deg, #6ee7b7, #3b82f6)',
            display: 'flex', alignItems: 'center', justifyContent: 'center',
            fontFamily: 'Syne, sans-serif', fontWeight: 800, fontSize: 14, color: '#000',
          }}>T</div>
          <div>
            <div style={{ fontFamily: 'Syne, sans-serif', fontWeight: 800, fontSize: 18, color: '#f8fafc', letterSpacing: -0.5 }}>{title}</div>
            <div style={{ fontFamily: 'DM Mono, monospace', fontSize: 10, color: '#64748b', letterSpacing: 2 }}>{subtitle}</div>
          </div>
        </div>
        <div style={{
          fontFamily: 'DM Mono, monospace', fontSize: 11,
          color: '#6ee7b7', letterSpacing: 2,
          background: 'rgba(110,231,183,0.08)',
          border: '1px solid rgba(110,231,183,0.2)',
          padding: '5px 14px', borderRadius: 20,
        }}>
          {itemCount} ТОВАРІВ
        </div>
      </div>
    </header>
  );
}

export default Header;
