import { useState, useContext } from 'react'
import { useNavigate } from 'react-router-dom'
import { AppContext } from '../context/AppContext'

function AddStudent() {
  const { addStudent } = useContext(AppContext)
  const navigate = useNavigate()

  const [form, setForm]     = useState({ firstName: '', lastName: '', group: '', age: '' })
  const [errors, setErrors] = useState({})
  const [loading, setLoading] = useState(false)
  const [serverError, setServerError] = useState('')

  const validate = () => {
    const e = {}
    if (!form.firstName.trim()) e.firstName = 'Вкажіть імʼя'
    if (!form.lastName.trim())  e.lastName  = 'Вкажіть прізвище'
    if (!form.group.trim())     e.group     = 'Вкажіть групу'
    if (!form.age || isNaN(form.age) || form.age < 16 || form.age > 60)
      e.age = 'Вік від 16 до 60'
    return e
  }

  const handleChange = (e) => {
    setForm(prev => ({ ...prev, [e.target.name]: e.target.value }))
    setErrors(prev => ({ ...prev, [e.target.name]: '' }))
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    const e2 = validate()
    if (Object.keys(e2).length) { setErrors(e2); return }
    setLoading(true); setServerError('')
    try {
      await addStudent({ ...form, age: Number(form.age) })
      navigate('/students')
    } catch {
      setServerError('Помилка сервера. Перевірте json-server.')
    } finally {
      setLoading(false)
    }
  }

  const fields = [
    { name: 'firstName', label: 'ІМʼЯ',      placeholder: "Іван"       },
    { name: 'lastName',  label: 'ПРІЗВИЩЕ',   placeholder: "Петренко"   },
    { name: 'group',     label: 'ГРУПА',      placeholder: "ІПЗ-21"     },
    { name: 'age',       label: 'ВІК',        placeholder: "20"          },
  ]

  const inp = (focused) => ({
    width: '100%', background: 'rgba(255,255,255,0.04)',
    border: '1px solid rgba(255,255,255,0.09)', borderRadius: 10,
    padding: '11px 14px', color: '#f1f5f9',
    fontFamily: 'Outfit, sans-serif', fontSize: 14,
    outline: 'none', boxSizing: 'border-box', transition: 'border-color 0.2s',
  })

  return (
    <div style={{ maxWidth: 560 }}>
      <div style={{ marginBottom: 32 }}>
        <div style={{ fontFamily: 'JetBrains Mono, monospace', fontSize: 10, color: '#6ee7b7', letterSpacing: 4, marginBottom: 8 }}>
          POST /students
        </div>
        <h2 style={{ fontFamily: 'Outfit, sans-serif', fontWeight: 800, fontSize: 28, color: '#f1f5f9', letterSpacing: -0.5 }}>
          Додати студента
        </h2>
      </div>

      <div style={{
        background: 'rgba(110,231,183,0.04)', border: '1px solid rgba(110,231,183,0.12)',
        borderRadius: 16, padding: '28px', position: 'relative', overflow: 'hidden',
      }}>
        <div style={{ position: 'absolute', top: 0, left: 0, right: 0, height: 2, background: 'linear-gradient(90deg, #6ee7b7, #3b82f6)' }} />

        {serverError && (
          <div style={{
            background: 'rgba(248,113,113,0.08)', border: '1px solid rgba(248,113,113,0.2)',
            borderRadius: 8, padding: '10px 14px', color: '#f87171',
            fontFamily: 'Outfit, sans-serif', fontSize: 13, marginBottom: 16,
          }}>⚠ {serverError}</div>
        )}

        <form onSubmit={handleSubmit}>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 14, marginBottom: 14 }}>
            {fields.map(f => (
              <div key={f.name}>
                <label style={{ display: 'block', fontFamily: 'Outfit, sans-serif', fontSize: 11, color: '#64748b', marginBottom: 5, letterSpacing: 1 }}>
                  {f.label}
                </label>
                <input
                  name={f.name}
                  value={form[f.name]}
                  onChange={handleChange}
                  placeholder={f.placeholder}
                  style={{ ...inp(), borderColor: errors[f.name] ? 'rgba(248,113,113,0.5)' : 'rgba(255,255,255,0.09)' }}
                  onFocus={e => e.target.style.borderColor = 'rgba(110,231,183,0.5)'}
                  onBlur={e => e.target.style.borderColor = errors[f.name] ? 'rgba(248,113,113,0.5)' : 'rgba(255,255,255,0.09)'}
                />
                {errors[f.name] && (
                  <div style={{ fontFamily: 'Outfit, sans-serif', fontSize: 11, color: '#f87171', marginTop: 4 }}>
                    {errors[f.name]}
                  </div>
                )}
              </div>
            ))}
          </div>

          <button type="submit" disabled={loading} style={{
            background: loading ? 'rgba(110,231,183,0.3)' : 'linear-gradient(135deg, #6ee7b7, #3b82f6)',
            border: 'none', borderRadius: 10, padding: '12px 28px',
            color: '#000', fontFamily: 'Outfit, sans-serif',
            fontWeight: 700, fontSize: 14,
            cursor: loading ? 'not-allowed' : 'pointer',
          }}>
            {loading ? 'Додаємо...' : '+ Додати студента'}
          </button>
        </form>
      </div>
    </div>
  )
}

export default AddStudent
