import TaskItem from './TaskItem'

function TaskList({ tasks, onToggle, onDelete, filter }) {
  const filtered = tasks.filter(t => {
    if (filter === 'active') return !t.completed
    if (filter === 'done')   return t.completed
    return true
  })

  if (filtered.length === 0) {
    return (
      <div style={{
        textAlign: 'center', padding: '48px 0',
        color: '#334155',
        fontFamily: 'Outfit, sans-serif', fontSize: 14,
      }}>
        <div style={{ fontSize: 40, marginBottom: 12 }}>
          {filter === 'done' ? '🎉' : '📭'}
        </div>
        {filter === 'done'
          ? 'Ще немає виконаних задач'
          : filter === 'active'
          ? 'Всі задачі виконано!'
          : 'Список порожній. Додайте першу задачу!'}
      </div>
    )
  }

  return (
    <div>
      {filtered.map(task => (
        <TaskItem
          key={task.id}
          task={task}
          onToggle={onToggle}
          onDelete={onDelete}
        />
      ))}
    </div>
  )
}

export default TaskList
