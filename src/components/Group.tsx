import type { GroupItem } from '../types'
import { Card } from './Card'
import { Poster } from './Poster'
import { isVisible } from '../utils/locationFilter'

interface Props {
  item: GroupItem
}

export function Group({ item }: Props) {
  const visibleItems = item.items.filter(isVisible)
  if (visibleItems.length === 0) return null

  const hasPosters = visibleItems.some(i => i.type === 'poster')
  const hasCards = visibleItems.some(i => i.type === 'card')

  // Mixed groups: posters scroll horizontally, cards in a responsive grid
  // Homogeneous groups: optimal grid for the single type
  const gridClass = hasPosters && !hasCards
    ? 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4'
    : hasCards && !hasPosters
      ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4'
      : 'flex flex-wrap gap-4' // mixed

  return (
    <section className="w-full">
      <h2 className="mb-4 text-lg font-bold text-gray-800 border-l-4 border-brand pl-3">
        {item.title}
      </h2>
      <div className={gridClass}>
        {visibleItems.map((child, idx) =>
          child.type === 'card'
            ? <Card key={idx} item={child} />
            : <Poster key={idx} item={child} />
        )}
      </div>
    </section>
  )
}
