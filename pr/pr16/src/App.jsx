import { useState, useEffect } from 'react'
import Header from './components/Header'
import TaskForm from './components/TaskForm'
import TaskList from './components/TaskList'

const FILTERS = [
  { key: 'all',    label: 'Всі' },
  { key: 'active', label: 'Активні' },
  { key: 'done',   label: 'Виконані' },
]

function App() {
  const [tasks, setTasks] = useState([
    { id: 1, title: 'Вивчити React hooks',           completed: false },
    { id: 2, title: 'Зробити практичну роботу',      completed: false },
    { id: 3, title: 'Задеплоїти проєкт на хостинг',  completed: true  },
  ])
  const [filter, setFilter] = useState('all')

  useEffect(() => {
    const total = tasks.length
    const done  = tasks.filter(t => t.completed).length
    console.log(`Задач всього: ${total}, виконано: ${done}`)
  }, [tasks])

  const handleAdd = (title) => {
    setTasks(prev => [...prev, { id: Date.now(), title, completed: false }])
  }

  const handleToggle = (id) => {
    setTasks(prev => prev.map(t => t.id === id ? { ...t, completed: !t.completed } : t))
  }

  const handleDelete = (id) => {
    setTasks(prev => prev.filter(t => t.id !== id))
  }

  const handleClearDone = () => {
    setTasks(prev => prev.filter(t => !t.completed))
  }

  const total     = tasks.length
  const completed = tasks.filter(t => t.completed).length
  const active    = total - completed

  return (
    <div style={{
      minHeight: '100vh',
      background: 'linear-gradient(160deg, #0a0a12 0%, #0d0d1a 60%, #0a0a12 100%)',
      color: '#f1f5f9',
    }}>
      <div style={{
        position: 'fixed', inset: 0, zIndex: 0, pointerEvents: 'none',
        background: `
          radial-gradient(ellipse 55% 45% at 15% 20%, rgba(167,139,250,0.08), transparent 55%),
          radial-gradient(ellipse 45% 40% at 85% 75%, rgba(236,72,153,0.06), transparent 55%)
        `,
      }} />

      <Header total={total} completed={completed} />

      <main style={{
        position: 'relative', zIndex: 1,
        maxWidth: 720, margin: '0 auto',
        padding: '52px 24px 80px',
      }}>
        <div style={{ marginBottom: 40 }}>
          <div style={{
            fontFamily: 'JetBrains Mono, monospace',
            fontSize: 10, color: '#a78bfa',
            letterSpacing: 4, marginBottom: 10,
          }}>// TASK MANAGER</div>
          <h1 style={{
            fontFamily: 'Outfit, sans-serif',
            fontWeight: 800,
            fontSize: 'clamp(2rem, 5vw, 3.2rem)',
            lineHeight: 1.05, letterSpacing: -1.5,
            background: 'linear-gradient(135deg, #f8fafc 0%, #94a3b8 100%)',
            WebkitBackgroundClip: 'text',
            WebkitTextFillColor: 'transparent',
            backgroundClip: 'text',
            marginBottom: 10,
          }}>Мої задачі</h1>
          <div style={{
            display: 'flex', gap: 20,
            fontFamily: 'JetBrains Mono, monospace',
            fontSize: 11, color: '#475569',
          }}>
            <span>Всього: <strong style={{ color: '#94a3b8' }}>{total}</strong></span>
            <span>Активних: <strong style={{ color: '#a78bfa' }}>{active}</strong></span>
            <span>Виконано: <strong style={{ color: '#6ee7b7' }}>{completed}</strong></span>
          </div>
        </div>

        <TaskForm onAdd={handleAdd} />

        <div style={{
          display: 'flex', alignItems: 'center',
          justifyContent: 'space-between', marginBottom: 16,
        }}>
          <div style={{ display: 'flex', gap: 6 }}>
            {FILTERS.map(f => (
              <button
                key={f.key}
                onClick={() => setFilter(f.key)}
                style={{
                  background: filter === f.key ? 'rgba(167,139,250,0.15)' : 'transparent',
                  border: '1px solid',
                  borderColor: filter === f.key ? 'rgba(167,139,250,0.4)' : 'rgba(255,255,255,0.08)',
                  borderRadius: 8, padding: '6px 14px',
                  color: filter === f.key ? '#a78bfa' : '#64748b',
                  fontFamily: 'Outfit, sans-serif',
                  fontWeight: 500, fontSize: 13,
                  cursor: 'pointer', transition: 'all 0.2s',
                }}
              >{f.label}</button>
            ))}
          </div>

          {completed > 0 && (
            <button onClick={handleClearDone} style={{
              background: 'transparent', border: 'none',
              color: '#475569',
              fontFamily: 'JetBrains Mono, monospace',
              fontSize: 11, letterSpacing: 0.5,
              cursor: 'pointer', transition: 'color 0.2s',
            }}
              onMouseEnter={e => e.target.style.color = '#f87171'}
              onMouseLeave={e => e.target.style.color = '#475569'}
            >Очистити виконані ✕</button>
          )}
        </div>

        <TaskList
          tasks={tasks}
          onToggle={handleToggle}
          onDelete={handleDelete}
          filter={filter}
        />
      </main>
    </div>
  )
}

export default App
