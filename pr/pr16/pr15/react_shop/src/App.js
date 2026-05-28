import React from 'react';
import Header from './components/Header';
import ProductCard from './components/ProductCard';
import Footer from './components/Footer';

const products = [
  {
    id: 1,
    title: 'MacBook Pro 14"',
    price: 89999,
    category: 'Електроніка',
    description: 'Apple M3 Pro · 18GB RAM · 512GB SSD · Liquid Retina XDR',
    badge: 'NEW',
  },
  {
    id: 2,
    title: 'iPhone 15 Pro',
    price: 54999,
    category: 'Електроніка',
    description: 'Apple A17 Pro · 256GB · Titanium · Dynamic Island',
  },
  {
    id: 3,
    title: 'AirPods Pro 2',
    price: 12999,
    category: 'Аксесуари',
    description: 'Active Noise Cancellation · Adaptive Audio · USB-C',
  },
  {
    id: 4,
    title: 'Офісне крісло',
    price: 8999,
    category: 'Меблі',
    description: 'Ергономічне · Поперекова підтримка · Сітчаста спинка',
    badge: 'ХІТ',
  },
  {
    id: 5,
    title: 'Clean Code',
    price: 699,
    category: 'Книги',
    description: 'Robert C. Martin · Принципи гнучкої розробки ПЗ',
  },
  {
    id: 6,
    title: 'Magic Keyboard',
    price: 8999,
    category: 'Аксесуари',
    description: 'Touch ID · Бездротова · USB-C · Алюміній',
  },
];

function App() {
  return (
    <div style={{
      minHeight: '100vh',
      background: 'linear-gradient(160deg, #0a0a0f 0%, #0d1117 50%, #0a0a0f 100%)',
      color: '#f1f5f9',
    }}>

      <div style={{
        position: 'fixed', inset: 0, zIndex: 0, pointerEvents: 'none',
        backgroundImage: `
          radial-gradient(ellipse 60% 50% at 10% 15%, rgba(99,102,241,0.08), transparent 55%),
          radial-gradient(ellipse 50% 40% at 90% 80%, rgba(6,182,212,0.06), transparent 55%)
        `,
      }} />

      <Header
        title="TechStore"
        subtitle="КАТАЛОГ ТОВАРІВ"
        itemCount={products.length}
      />

      <main style={{ position: 'relative', zIndex: 1, maxWidth: 1200, margin: '0 auto', padding: '60px 48px' }}>

        <div style={{ marginBottom: 52 }}>
          <div style={{
            fontFamily: 'DM Mono, monospace',
            fontSize: 11, color: '#6ee7b7',
            letterSpacing: 4, marginBottom: 12,
          }}>// FEATURED PRODUCTS</div>
          <h1 style={{
            fontFamily: 'Syne, sans-serif',
            fontWeight: 800, fontSize: 'clamp(2rem, 5vw, 3.5rem)',
            lineHeight: 1.05, letterSpacing: -2,
            background: 'linear-gradient(135deg, #f8fafc 0%, #94a3b8 100%)',
            WebkitBackgroundClip: 'text',
            WebkitTextFillColor: 'transparent',
            backgroundClip: 'text',
            marginBottom: 12,
          }}>
            Каталог<br/>товарів
          </h1>
          <p style={{
            fontFamily: 'DM Mono, monospace',
            fontSize: 13, color: '#475569', maxWidth: 420, lineHeight: 1.7,
          }}>
            Найкращі товари за найкращими цінами. Вибирайте з нашого асортименту.
          </p>
        </div>

        <div style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fill, minmax(300px, 1fr))',
          gap: 20,
        }}>
          {products.map(product => (
            <ProductCard
              key={product.id}
              title={product.title}
              price={product.price}
              category={product.category}
              description={product.description}
              badge={product.badge}
            />
          ))}
        </div>

      </main>

      <Footer storeName="TechStore" />

    </div>
  );
}

export default App;
