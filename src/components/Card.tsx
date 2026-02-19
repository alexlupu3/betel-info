import type { CardItem } from '../types'

interface Props {
  item: CardItem
  /** When true, switches to a horizontal layout (thumbnail left) on sm+ screens */
  horizontal?: boolean
}

function formatDate(dateStr: string, timeStr?: string): string {
  const date = new Date(dateStr + 'T00:00:00')
  const datePart = date.toLocaleDateString(undefined, {
    weekday: 'short',
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
  if (!timeStr) return datePart
  const [h, m] = timeStr.split(':').map(Number)
  const t = new Date()
  t.setHours(h ?? 0, m ?? 0, 0, 0)
  const timePart = t.toLocaleTimeString(undefined, { hour: '2-digit', minute: '2-digit' })
  return `${datePart} · ${timePart}`
}

export function Card({ item, horizontal = false }: Props) {
  const inner = (
    <div className={`flex overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition-shadow hover:shadow-md ${horizontal ? 'flex-col sm:flex-row' : 'flex-col'}`}>
      {item.thumbnail && (
        <div className={`overflow-hidden flex-shrink-0 ${horizontal ? 'aspect-video w-full sm:aspect-auto sm:w-56' : 'aspect-video w-full'}`}>
          <img
            src={item.thumbnail}
            alt={item.title}
            className="h-full w-full object-cover"
            loading="lazy"
          />
        </div>
      )}
      <div className="flex flex-1 flex-col gap-2 p-4">
        {item.date && (
          <p className="text-xs font-medium text-brand">{formatDate(item.date, item.time)}</p>
        )}
        <h3 className="text-base font-semibold text-gray-900 leading-snug">{item.title}</h3>
        <p className="text-sm text-gray-600 leading-relaxed">{item.description}</p>
        {item.link && (
          <span className="mt-auto pt-2 text-xs font-semibold text-brand hover:text-brand-dark">
            {item.linkText ?? 'Află mai multe'} →
          </span>
        )}
      </div>
    </div>
  )

  if (item.link) {
    return (
      <a href={item.link} target="_blank" rel="noopener noreferrer" className="block focus:outline-none focus-visible:ring-2 focus-visible:ring-brand rounded-2xl">
        {inner}
      </a>
    )
  }

  return inner
}
