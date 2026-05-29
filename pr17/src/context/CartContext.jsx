import { createContext, useState, useRef } from 'react'

export const CartContext = createContext()

const PRODUCTS = [
  { id: 1, title: 'MacBook Pro 14"',      category: 'Електроніка', price: 89999 },
  { id: 2, title: 'iPhone 15 Pro',         category: 'Електроніка', price: 54999 },
  { id: 3, title: 'AirPods Pro',           category: 'Аксесуари',   price: 12999 },
  { id: 4, title: 'Magic Keyboard',        category: 'Аксесуари',   price: 8999  },
  { id: 5, title: 'Офісне крісло Comfort', category: 'Меблі',       price: 8999  },
  { id: 6, title: 'Стіл IKEA Bekant',      category: 'Меблі',       price: 12499 },
  { id: 7, title: 'Clean Code',            category: 'Книги',        price: 699   },
  { id: 8, title: 'The Pragmatic Programmer', category: 'Книги',    price: 849   },
]

export function CartProvider({ children }) {
  const [cart, setCart]         = useState([])
  const [filter, setFilter]     = useState('Всі')
  const filterRef               = useRef(null)

  const addToCart = (product) => {
    setCart(prev => {
      const exists = prev.find(i => i.id === product.id)
      if (exists) return prev.map(i => i.id === product.id ? { ...i, qty: i.qty + 1 } : i)
      return [...prev, { ...product, qty: 1 }]
    })
  }

  const removeFromCart = (id) => setCart(prev => prev.filter(i => i.id !== id))

  const cartCount = cart.reduce((sum, i) => sum + i.qty, 0)
  const cartTotal = cart.reduce((sum, i) => sum + i.price * i.qty, 0)

  const categories = ['Всі', ...new Set(PRODUCTS.map(p => p.category))]

  const filtered = filter === 'Всі'
    ? PRODUCTS
    : PRODUCTS.filter(p => p.category === filter)

  return (
    <CartContext.Provider value={{
      products: filtered,
      cart, addToCart, removeFromCart,
      cartCount, cartTotal,
      filter, setFilter,
      categories, filterRef,
    }}>
      {children}
    </CartContext.Provider>
  )
}
