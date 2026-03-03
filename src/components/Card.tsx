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
    <div className={`flex overflow-hidden rounded-xl bg-white ${horizontal ? 'flex-col sm:flex-row' : 'flex-col'}`}>
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
      <div className="flex flex-1 flex-col gap-2 p-5">
        {item.date && (
          <p className="text-xs text-gray-400 uppercase tracking-widest">{formatDate(item.date, item.time)}</p>
        )}
        <h3 className="text-base font-semibold text-gray-950 leading-snug">{item.title}</h3>
        <p className="text-sm text-gray-600 leading-relaxed">{item.description}</p>
        {item.link && (
          <div className="mt-auto pt-3">
            <span className="inline-flex items-center gap-1 px-4 py-1.5 text-xs font-semibold bg-gray-950 text-white rounded-full transition-opacity hover:opacity-70">
              {item.linkText ?? 'Află mai multe'} →
            </span>
          </div>
        )}
      </div>
    </div>
  )

  if (item.link) {
    return (
      <a href={item.link} target="_blank" rel="noopener noreferrer" className="block focus:outline-none focus-visible:ring-2 focus-visible:ring-accent rounded-xl">
        {inner}
      </a>
    )
  }

  return inner
}
