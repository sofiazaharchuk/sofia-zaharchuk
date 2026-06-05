import { useState } from 'react'
import { Link } from 'react-router-dom'

function StudentCard({ student, onDelete }) {
  const [hovered, setHovered] = useState(false)

  return (
    <div
      onMouseEnter={() => setHovered(true)}
      onMouseLeave={() => setHovered(false)}
      style={{
        background: hovered ? 'rgba(255,255,255,0.045)' : 'rgba(255,255,255,0.025)',
        border: '1px solid',
        borderColor: hovered ? 'rgba(110,231,183,0.25)' : 'rgba(255,255,255,0.06)',
        borderRadius: 14, padding: '20px',
        transition: 'all 0.2s',
        transform: hovered ? 'translateY(-3px)' : 'translateY(0)',
        position: 'relative', overflow: 'hidden',
      }}
    >
      <div style={{
        position: 'absolute', top: 0, left: 0, right: 0, height: 2,
        background: hovered ? 'linear-gradient(90deg, #6ee7b7, #3b82f6)' : 'transparent',
        transition: 'all 0.2s',
      }} />

      <div style={{ display: 'flex', alignItems: 'center', gap: 14, marginBottom: 14 }}>
        <div style={{
          width: 44, height: 44, borderRadius: 12, flexShrink: 0,
          background: 'linear-gradient(135deg, rgba(110,231,183,0.2), rgba(59,130,246,0.2))',
          border: '1px solid rgba(110,231,183,0.2)',
          display: 'flex', alignItems: 'center', justifyContent: 'center',
          fontFamily: 'Outfit, sans-serif', fontWeight: 800,
          fontSize: 16, color: '#6ee7b7',
        }}>
          {student.firstName[0]}{student.lastName[0]}
        </div>
        <div>
          <div style={{
            fontFamily: 'Outfit, sans-serif',
            fontWeight: 700, fontSize: 16, color: '#f1f5f9',
          }}>{student.firstName} {student.lastName}</div>
          <div style={{
            fontFamily: 'JetBrains Mono, monospace',
            fontSize: 11, color: '#64748b',
          }}>Вік: {student.age}</div>
        </div>
      </div>

      <div style={{
        display: 'inline-block',
        background: 'rgba(59,130,246,0.1)',
        border: '1px solid rgba(59,130,246,0.25)',
        color: '#93c5fd',
        fontFamily: 'JetBrains Mono, monospace',
        fontSize: 10, letterSpacing: 1,
        padding: '3px 10px', borderRadius: 5,
        marginBottom: 16,
      }}>{student.group}</div>

      <div style={{ display: 'flex', gap: 8 }}>
        <Link
          to={`/students/${student.id}`}
          style={{
            flex: 1, textAlign: 'center',
            background: 'rgba(110,231,183,0.08)',
            border: '1px solid rgba(110,231,183,0.2)',
            borderRadius: 8, padding: '8px',
            color: '#6ee7b7',
            fontFamily: 'Outfit, sans-serif',
            fontWeight: 500, fontSize: 13,
            textDecoration: 'none', transition: 'all 0.2s',
          }}
        >Детальніше</Link>
        <button
          onClick={() => onDelete(student.id)}
          style={{
            background: 'rgba(248,113,113,0.08)',
            border: '1px solid rgba(248,113,113,0.2)',
            borderRadius: 8, padding: '8px 14px',
            color: '#f87171', cursor: 'pointer',
            fontFamily: 'Outfit, sans-serif',
            fontWeight: 500, fontSize: 13,
            transition: 'all 0.2s',
          }}
        >Видалити</button>
      </div>
    </div>
  )
}

export default StudentCard
