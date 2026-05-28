import { useState } from 'react'

function TaskForm({ onAdd }) {
  const [value, setValue] = useState('')
  const [focused, setFocused] = useState(false)

  const handleSubmit = (e) => {
    e.preventDefault()
    if (value.trim() === '') return
    onAdd(value.trim())
    setValue('')
  }

  return (
    <form onSubmit={handleSubmit} style={{ display: 'flex', gap: 10, marginBottom: 32 }}>
      <input
        value={value}
        onChange={(e) => setValue(e.target.value)}
        onFocus={() => setFocused(true)}
        onBlur={() => setFocused(false)}
        placeholder="Додати нову задачу..."
        style={{
          flex: 1,
          background: 'rgba(255,255,255,0.04)',
          border: '1px solid',
          borderColor: focused ? 'rgba(167,139,250,0.5)' : 'rgba(255,255,255,0.08)',
          borderRadius: 12,
          padding: '13px 16px',
          color: '#f1f5f9',
          fontFamily: 'Outfit, sans-serif',
          fontSize: 14, outline: 'none',
          transition: 'all 0.2s',
          boxShadow: focused ? '0 0 0 3px rgba(167,139,250,0.1)' : 'none',
        }}
      />
      <button type="submit" style={{
        background: 'linear-gradient(135deg, #a78bfa, #ec4899)',
        border: 'none', borderRadius: 12,
        padding: '13px 24px', color: '#fff',
        fontFamily: 'Outfit, sans-serif',
        fontWeight: 600, fontSize: 14,
        cursor: 'pointer', whiteSpace: 'nowrap',
      }}>+ Додати</button>
    </form>
  )
}

export default TaskForm
