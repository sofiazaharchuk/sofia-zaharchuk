import { useContext, useEffect } from 'react'
import { AppContext } from '../context/AppContext'
import StudentList from '../components/StudentList'

function Students() {
  const { fetchStudents, students } = useContext(AppContext)

  useEffect(() => { fetchStudents() }, [fetchStudents])

  return (
    <div>
      <div style={{ marginBottom: 32 }}>
        <div style={{ fontFamily: 'JetBrains Mono, monospace', fontSize: 10, color: '#6ee7b7', letterSpacing: 4, marginBottom: 8 }}>
          GET /students
        </div>
        <h2 style={{ fontFamily: 'Outfit, sans-serif', fontWeight: 800, fontSize: 28, color: '#f1f5f9', letterSpacing: -0.5 }}>
          Список студентів
        </h2>
        <p style={{ fontFamily: 'Outfit, sans-serif', fontSize: 13, color: '#475569', marginTop: 4 }}>
          Всього: {students.length} студентів
        </p>
      </div>
      <StudentList />
    </div>
  )
}

export default Students
