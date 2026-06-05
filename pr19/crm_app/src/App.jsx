import { Routes, Route } from 'react-router-dom'
import { AppProvider } from './context/AppContext'
import Header from './components/Header'
import Footer from './components/Footer'
import Home from './pages/Home'
import Students from './pages/Students'
import AddStudent from './pages/AddStudent'
import StudentDetails from './pages/StudentDetails'
import NotFound from './pages/NotFound'

function App() {
  return (
    <AppProvider>
      <div style={{
        minHeight: '100vh',
        background: 'linear-gradient(160deg, #08080f 0%, #0d0d1a 60%, #08080f 100%)',
        color: '#f1f5f9',
      }}>
        <div style={{
          position: 'fixed', inset: 0, zIndex: 0, pointerEvents: 'none',
          background: `
            radial-gradient(ellipse 55% 45% at 10% 15%, rgba(110,231,183,0.07), transparent 55%),
            radial-gradient(ellipse 45% 40% at 90% 80%, rgba(59,130,246,0.05), transparent 55%)
          `,
        }} />
        <div style={{
          position: 'fixed', inset: 0, zIndex: 0, pointerEvents: 'none',
          backgroundImage: `
            linear-gradient(rgba(110,231,183,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(110,231,183,0.03) 1px, transparent 1px)
          `,
          backgroundSize: '44px 44px',
        }} />

        <Header />

        <main style={{ position: 'relative', zIndex: 1, maxWidth: 1100, margin: '0 auto', padding: '52px 32px 40px' }}>
          <Routes>
            <Route path="/"               element={<Home />}           />
            <Route path="/students"       element={<Students />}       />
            <Route path="/students/add"   element={<AddStudent />}     />
            <Route path="/students/:id"   element={<StudentDetails />} />
            <Route path="*"               element={<NotFound />}       />
          </Routes>
        </main>

        <Footer />
      </div>
    </AppProvider>
  )
}

export default App
