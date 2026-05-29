import { useContext, useEffect } from 'react'
import { CartContext } from '../context/CartContext'

function ProductFilter() {
  const { categories, filter, setFilter, filterRef } = useContext(CartContext)

  useEffect(() => {
    if (filterRef.current) filterRef.current.focus()
  }, [])

  return (
    <div style={{ marginBottom: 32 }}>
      <div style={{
        fontFamily: 'JetBrains Mono, monospace',
        fontSize: 10, color: '#64748b',
        letterSpacing: 3, marginBottom: 12,
      }}>ФІЛЬТР ЗА КАТЕГОРІЄЮ</div>
      <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
        {categories.map((cat, i) => (
          <button
            key={cat}
            ref={i === 0 ? filterRef : null}
            onClick={() => setFilter(cat)}
            style={{
              background: filter === cat
                ? 'linear-gradient(135deg, #f59e0b, #ef4444)'
                : 'rgba(255,255,255,0.04)',
              border: '1px solid',
              borderColor: filter === cat ? 'transparent' : 'rgba(255,255,255,0.08)',
              borderRadius: 10, padding: '8px 18px',
              color: filter === cat ? '#fff' : '#64748b',
              fontFamily: 'Outfit, sans-serif',
              fontWeight: 500, fontSize: 13,
              cursor: 'pointer', transition: 'all 0.2s',
            }}
          >{cat}</button>
        ))}
      </div>
    </div>
  )
}

export default ProductFilter
