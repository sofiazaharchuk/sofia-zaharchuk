import React, { useState } from 'react';

const categoryColors = {
  'Електроніка': { bg: 'rgba(99,102,241,0.12)', border: 'rgba(99,102,241,0.3)', text: '#a5b4fc' },
  'Аксесуари':   { bg: 'rgba(6,182,212,0.10)',  border: 'rgba(6,182,212,0.3)',  text: '#67e8f9' },
  'Меблі':       { bg: 'rgba(245,158,11,0.10)', border: 'rgba(245,158,11,0.3)', text: '#fcd34d' },
  'Одяг':        { bg: 'rgba(236,72,153,0.10)', border: 'rgba(236,72,153,0.3)', text: '#f9a8d4' },
  'Книги':       { bg: 'rgba(16,185,129,0.10)', border: 'rgba(16,185,129,0.3)', text: '#6ee7b7' },
};

const categoryIcons = {
  'Електроніка': '⚡',
  'Аксесуари':   '🎧',
  'Меблі':       '🪑',
  'Одяг':        '👕',
  'Книги':       '📚',
};

function ProductCard({ title, price, category, description, badge }) {
  const [hovered, setHovered] = useState(false);
  const colors = categoryColors[category] || { bg: 'rgba(255,255,255,0.06)', border: 'rgba(255,255,255,0.15)', text: '#94a3b8' };
  const icon = categoryIcons[category] || '📦';

  return (
    <div
      onMouseEnter={() => setHovered(true)}
      onMouseLeave={() => setHovered(false)}
      style={{
        background: hovered
          ? 'rgba(255,255,255,0.045)'
          : 'rgba(255,255,255,0.025)',
        border: '1px solid',
        borderColor: hovered ? 'rgba(255,255,255,0.12)' : 'rgba(255,255,255,0.06)',
        borderRadius: 18,
        padding: '24px 22px',
        position: 'relative',
        overflow: 'hidden',
        cursor: 'pointer',
        transition: 'all 0.25s ease',
        transform: hovered ? 'translateY(-4px)' : 'translateY(0)',
        boxShadow: hovered
          ? '0 20px 60px rgba(0,0,0,0.5)'
          : '0 4px 20px rgba(0,0,0,0.3)',
      }}
    >
      <div style={{
        position: 'absolute', top: 0, left: 0, right: 0, height: 2,
        background: hovered
          ? 'linear-gradient(90deg, #6ee7b7, #3b82f6)'
          : 'transparent',
        transition: 'all 0.25s ease',
      }} />

      {badge && (
        <div style={{
          position: 'absolute', top: 16, right: 16,
          background: 'linear-gradient(135deg, #f59e0b, #ef4444)',
          color: '#fff', fontFamily: 'DM Mono, monospace',
          fontSize: 9, fontWeight: 500, letterSpacing: 1,
          padding: '3px 10px', borderRadius: 20,
        }}>{badge}</div>
      )}

      <div style={{ fontSize: 36, marginBottom: 14 }}>{icon}</div>

      <div style={{
        display: 'inline-block',
        ...colors,
        fontFamily: 'DM Mono, monospace',
        fontSize: 10, letterSpacing: 2,
        padding: '3px 10px', borderRadius: 5,
        marginBottom: 12,
      }}>
        {category.toUpperCase()}
      </div>

      <div style={{
        fontFamily: 'Syne, sans-serif',
        fontWeight: 700, fontSize: 18,
        color: '#f1f5f9', marginBottom: 8,
        letterSpacing: -0.3, lineHeight: 1.3,
      }}>{title}</div>

      {description && (
        <div style={{
          fontFamily: 'DM Mono, monospace',
          fontSize: 11, color: '#64748b',
          lineHeight: 1.6, marginBottom: 18,
        }}>{description}</div>
      )}

      <div style={{
        display: 'flex', alignItems: 'center',
        justifyContent: 'space-between', marginTop: 'auto',
        paddingTop: description ? 0 : 16,
      }}>
        <div>
          <div style={{ fontFamily: 'DM Mono, monospace', fontSize: 9, color: '#475569', letterSpacing: 1, marginBottom: 2 }}>ЦІНА</div>
          <div style={{
            fontFamily: 'Syne, sans-serif', fontWeight: 800,
            fontSize: 22, color: '#6ee7b7', letterSpacing: -0.5,
          }}>
            {Number(price).toLocaleString('uk-UA')} ₴
          </div>
        </div>
        <button style={{
          background: hovered
            ? 'linear-gradient(135deg, #6ee7b7, #3b82f6)'
            : 'rgba(255,255,255,0.06)',
          border: '1px solid',
          borderColor: hovered ? 'transparent' : 'rgba(255,255,255,0.1)',
          borderRadius: 10, padding: '9px 18px',
          color: hovered ? '#000' : '#94a3b8',
          fontFamily: 'DM Mono, monospace',
          fontSize: 11, fontWeight: 500, letterSpacing: 1,
          cursor: 'pointer',
          transition: 'all 0.25s ease',
        }}>
          КУПИТИ
        </button>
      </div>
    </div>
  );
}

export default ProductCard;
