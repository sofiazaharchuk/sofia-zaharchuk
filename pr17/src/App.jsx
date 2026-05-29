import { CartProvider } from './context/CartContext'
import Header from './components/Header'
import ProductFilter from './components/ProductFilter'
import ProductList from './components/ProductList'
import Cart from './components/Cart'
import Footer from './components/Footer'

function App() {
  return (
    <CartProvider>
      <div style={{
        minHeight: '100vh',
        background: 'linear-gradient(160deg, #08080f 0%, #0d0d1a 60%, #08080f 100%)',
        color: '#f1f5f9',
      }}>
        <div style={{
          position: 'fixed', inset: 0, zIndex: 0, pointerEvents: 'none',
          background: `
            radial-gradient(ellipse 55% 45% at 10% 15%, rgba(245,158,11,0.07), transparent 55%),
            radial-gradient(ellipse 45% 40% at 90% 80%, rgba(239,68,68,0.05), transparent 55%)
          `,
        }} />

        <Header />

        <main style={{
          position: 'relative', zIndex: 1,
          maxWidth: 1200, margin: '0 auto',
          padding: '52px 32px 40px',
        }}>
          <div style={{ marginBottom: 44 }}>
            <div style={{
              fontFamily: 'JetBrains Mono, monospace',
              fontSize: 10, color: '#f59e0b',
              letterSpacing: 4, marginBottom: 10,
            }}>// PRODUCT CATALOG</div>
            <h1 style={{
              fontFamily: 'Outfit, sans-serif',
              fontWeight: 800,
              fontSize: 'clamp(2rem, 5vw, 3.5rem)',
              lineHeight: 1.05, letterSpacing: -1.5,
              background: 'linear-gradient(135deg, #f8fafc 0%, #94a3b8 100%)',
              WebkitBackgroundClip: 'text',
              WebkitTextFillColor: 'transparent',
              backgroundClip: 'text',
              marginBottom: 10,
            }}>Каталог<br/>товарів</h1>
          </div>

          <Cart />
          <ProductFilter />
          <ProductList />
        </main>

        <Footer />
      </div>
    </CartProvider>
  )
}

export default App
