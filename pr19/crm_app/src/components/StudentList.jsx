import { useContext } from 'react'
import { AppContext } from '../context/AppContext'
import StudentCard from './StudentCard'

function StudentList() {
  const { students, loading, error, deleteStudent } = useContext(AppContext)

  if (loading) return (
    <div style={{ textAlign: 'center', padding: '60px 0', color: '#475569', fontFamily: 'Outfit, sans-serif' }}>
      <div style={{ fontSize: 32, marginBottom: 12 }}>⟳</div>Завантаження...
    </div>
  )

  if (error) return (
    <div style={{
      background: 'rgba(248,113,113,0.08)', border: '1px solid rgba(248,113,113,0.25)',
      borderRadius: 12, padding: '20px 24px',
      color: '#f87171', fontFamily: 'Outfit, sans-serif', fontSize: 14,
    }}>⚠ {error}</div>
  )

  if (students.length === 0) return (
    <div style={{ textAlign: 'center', padding: '60px 0', color: '#334155', fontFamily: 'Outfit, sans-serif' }}>
      <div style={{ fontSize: 40, marginBottom: 12 }}>📭</div>Список студентів порожній
    </div>
  )

  return (
    <div style={{
      display: 'grid',
      gridTemplateColumns: 'repeat(auto-fill, minmax(280px, 1fr))',
      gap: 16,
    }}>
      {students.map(s => <StudentCard key={s.id} student={s} onDelete={deleteStudent} />)}
    </div>
  )
}

export default StudentList
