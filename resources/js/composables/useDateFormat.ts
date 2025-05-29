export function useDateFormat() {
  const shortDate = (dateString: string | number | Date) => {
    const date = new Date(dateString)
    const now = new Date()
    const diff = now.getTime() - date.getTime()
    const diffHours = Math.floor(diff / (1000 * 60 * 60))
    
    if (diffHours < 1) {
      const minutes = Math.floor(diff / (1000 * 60))
      return minutes ? `${minutes}m` : 'Ahora'
    }
    if (diffHours < 24) return `${diffHours}h`
    if (diffHours < 168) return `${Math.floor(diffHours/24)}d`
    return date.toLocaleDateString('es', { month: 'short', day: 'numeric' })
  }

  return { shortDate }
}