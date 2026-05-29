import { useContext } from 'react'
import { CartContext } from '../context/CartContext'
import ProductCard from './ProductCard'

function ProductList() {
  const { products } = useContext(CartContext)

  if (products.length === 0) {
    return (
      <div style={{
        textAlign: 'center', padding: '60px 0',
        color: '#334155', fontFamily: 'Outfit, sans-serif', fontSize: 14,
      }}>
        <div style={{ fontSize: 40, marginBottom: 12 }}>📭</div>
        Товарів у цій категорії немає
      </div>
    )
  }

  return (
    <div style={{
      display: 'grid',
      gridTemplateColumns: 'repeat(auto-fill, minmax(260px, 1fr))',
      gap: 16,
    }}>
      {products.map(p => <ProductCard key={p.id} product={p} />)}
    </div>
  )
}

export default ProductList
