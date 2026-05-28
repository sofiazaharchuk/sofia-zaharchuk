import { useState } from 'react'

function TaskItem({ task, onToggle, onDelete }) {
  const [hovered, setHovered] = useState(false)

  return (
    <div
      onMouseEnter={() => setHovered(true)}
      onMouseLeave={() => setHovered(false)}
      style={{
        display: 'flex', alignItems: 'center', gap: 14,
        padding: '14px 18px',
        background: hovered ? 'rgba(255,255,255,0.04)' : 'rgba(255,255,255,0.02)',
        border: '1px solid',
        borderColor: task.completed ? 'rgba(167,139,250,0.2)' : 'rgba(255,255,255,0.06)',
        borderRadius: 12, marginBottom: 10,
        transition: 'all 0.2s',
        transform: hovered ? 'translateX(4px)' : 'translateX(0)',
      }}
    >
      <button onClick={() => onToggle(task.id)} style={{
        width: 22, height: 22, borderRadius: 6,
        border: '2px solid',
        borderColor: task.completed ? '#a78bfa' : 'rgba(255,255,255,0.2)',
        background: task.completed
          ? 'linear-gradient(135deg, #a78bfa, #ec4899)'
          : 'transparent',
        cursor: 'pointer',
        display: 'flex', alignItems: 'center', justifyContent: 'center',
        flexShrink: 0, transition: 'all 0.2s', padding: 0,
        fontSize: 11, color: '#fff',
      }}>
        {task.completed ? '✓' : ''}
      </button>

      <span style={{
        flex: 1,
        fontFamily: 'Outfit, sans-serif', fontSize: 14,
        color: task.completed ? '#475569' : '#e2e8f0',
        textDecoration: task.completed ? 'line-through' : 'none',
        transition: 'all 0.2s',
      }}>{task.title}</span>

      {task.completed && (
        <span style={{
          fontFamily: 'JetBrains Mono, monospace',
          fontSize: 9, letterSpacing: 1, color: '#a78bfa',
          background: 'rgba(167,139,250,0.1)',
          border: '1px solid rgba(167,139,250,0.2)',
          padding: '2px 8px', borderRadius: 4,
        }}>ВИКОНАНО</span>
      )}

      <button onClick={() => onDelete(task.id)} style={{
        background: 'transparent', border: 'none',
        color: hovered ? '#f87171' : '#334155',
        cursor: 'pointer', fontSize: 16,
        padding: '2px 4px', transition: 'color 0.2s', lineHeight: 1,
      }}>✕</button>
    </div>
  )
}

export default TaskItem
