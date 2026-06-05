import { useParams, useNavigate, Link } from 'react-router-dom'
import { useContext, useEffect, useState } from 'react'
import { AppContext } from '../context/AppContext'

function StudentDetails() {
  const { id }          = useParams()
  const { deleteStudent } = useContext(AppContext)
  const navigate        = useNavigate()

  const [student, setStudent] = useState(null)
  const [loading, setLoading] = useState(false)
  const [error,   setError]   = useState('')

  useEffect(() => {
    setLoading(true)
    fetch(`http://localhost:3001/students/${id}`)
      .then(res => { if (!res.ok) throw new Error(); return res.json() })
      .then(setStudent)
      .catch(() => setError('Студента не знайдено'))
      .finally(() => setLoading(false))
  }, [id])

  const handleDelete = async () => {
    await deleteStudent(Number(id))
    navigate('/students')
  }

  if (loading) return <div style={{ textAlign: 'center', padding: '60px 0', color: '#475569', fontFamily: 'Outfit, sans-serif' }}>Завантаження...</div>
  if (error)   return (
    <div>
      <div style={{ background: 'rgba(248,113,113,0.08)', border: '1px solid rgba(248,113,113,0.25)', borderRadius: 12, padding: '20px', color: '#f87171', fontFamily: 'Outfit, sans-serif', marginBottom: 16 }}>⚠ {error}</div>
      <Link to="/students" style={{ color: '#6ee7b7', fontFamily: 'Outfit, sans-serif', fontSize: 14 }}>← Назад до списку</Link>
    </div>
  )
  if (!student) return null

  const rows = [
    { label: "Ім'я",      value: student.firstName },
    { label: 'Прізвище',  value: student.lastName  },
    { label: 'Група',     value: student.group      },
    { label: 'Вік',       value: student.age        },
    { label: 'ID',        value: student.id         },
  ]

  return (
    <div style={{ maxWidth: 560 }}>
      <div style={{ marginBottom: 32 }}>
        <div style={{ fontFamily: 'JetBrains Mono, monospace', fontSize: 10, color: '#6ee7b7', letterSpacing: 4, marginBottom: 8 }}>
          GET /students/{id}
        </div>
        <h2 style={{ fontFamily: 'Outfit, sans-serif', fontWeight: 800, fontSize: 28, color: '#f1f5f9', letterSpacing: -0.5 }}>
          {student.firstName} {student.lastName}
        </h2>
      </div>

      <div style={{
        background: 'rgba(255,255,255,0.025)', border: '1px solid rgba(255,255,255,0.06)',
        borderRadius: 16, overflow: 'hidden', marginBottom: 20, position: 'relative',
      }}>
        <div style={{ position: 'absolute', top: 0, left: 0, right: 0, height: 2, background: 'linear-gradient(90deg, #6ee7b7, #3b82f6)' }} />
        {rows.map((r, i) => (
          <div key={r.label} style={{
            display: 'flex', justifyContent: 'space-between', alignItems: 'center',
            padding: '14px 22px',
            borderBottom: i < rows.length - 1 ? '1px solid rgba(255,255,255,0.04)' : 'none',
          }}>
            <span style={{ fontFamily: 'JetBrains Mono, monospace', fontSize: 11, color: '#64748b', letterSpacing: 1 }}>{r.label.toUpperCase()}</span>
            <span style={{ fontFamily: 'Outfit, sans-serif', fontWeight: 600, fontSize: 14, color: '#f1f5f9' }}>{r.value}</span>
          </div>
        ))}
      </div>

      <div style={{ display: 'flex', gap: 10 }}>
        <Link to="/students" style={{
          flex: 1, textAlign: 'center',
          background: 'rgba(255,255,255,0.04)', border: '1px solid rgba(255,255,255,0.08)',
          borderRadius: 10, padding: '11px',
          color: '#94a3b8', fontFamily: 'Outfit, sans-serif', fontWeight: 600, fontSize: 14,
          textDecoration: 'none',
        }}>← Назад</Link>
        <button onClick={handleDelete} style={{
          flex: 1, background: 'rgba(248,113,113,0.1)',
          border: '1px solid rgba(248,113,113,0.25)',
          borderRadius: 10, padding: '11px',
          color: '#f87171', cursor: 'pointer',
          fontFamily: 'Outfit, sans-serif', fontWeight: 600, fontSize: 14,
        }}>Видалити студента</button>
      </div>
    </div>
  )
}

export default StudentDetails
