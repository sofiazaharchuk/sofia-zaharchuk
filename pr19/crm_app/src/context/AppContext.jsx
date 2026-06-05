import { createContext, useState, useCallback } from 'react'

const API = 'http://localhost:3001/students'
export const AppContext = createContext()

export function AppProvider({ children }) {
  const [students, setStudents] = useState([])
  const [loading,  setLoading]  = useState(false)
  const [error,    setError]    = useState('')

  const fetchStudents = useCallback(async () => {
    setLoading(true); setError('')
    try {
      const res = await fetch(API)
      if (!res.ok) throw new Error()
      setStudents(await res.json())
    } catch {
      setError('Не вдалося завантажити дані. Перевірте json-server на порту 3001.')
    } finally {
      setLoading(false)
    }
  }, [])

  const addStudent = async (data) => {
    const res     = await fetch(API, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    })
    const created = await res.json()
    setStudents(prev => [...prev, created])
    return created
  }

  const deleteStudent = async (id) => {
    await fetch(`${API}/${id}`, { method: 'DELETE' })
    setStudents(prev => prev.filter(s => s.id !== id))
  }

  return (
    <AppContext.Provider value={{ students, loading, error, fetchStudents, addStudent, deleteStudent }}>
      {children}
    </AppContext.Provider>
  )
}
