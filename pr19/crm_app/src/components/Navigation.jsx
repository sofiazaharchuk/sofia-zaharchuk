import { NavLink } from 'react-router-dom'

const links = [
  { to: '/',              label: 'Головна' },
  { to: '/students',      label: 'Студенти' },
  { to: '/students/add',  label: '+ Додати' },
]

function Navigation() {
  return (
    <nav style={{ display: 'flex', gap: 4 }}>
      {links.map(link => (
        <NavLink
          key={link.to}
          to={link.to}
          end={link.to === '/'}
          style={({ isActive }) => ({
            fontFamily: 'Outfit, sans-serif',
            fontWeight: 500, fontSize: 14,
            color: isActive ? '#6ee7b7' : '#64748b',
            textDecoration: 'none',
            padding: '6px 14px', borderRadius: 8,
            background: isActive ? 'rgba(110,231,183,0.08)' : 'transparent',
            border: '1px solid',
            borderColor: isActive ? 'rgba(110,231,183,0.25)' : 'transparent',
            transition: 'all 0.2s',
          })}
        >{link.label}</NavLink>
      ))}
    </nav>
  )
}

export default Navigation
