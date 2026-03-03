import type { PosterItem } from '../types'

interface Props {
  item: PosterItem
}

export function Poster({ item }: Props) {
  const inner = (
    <div className="flex flex-col overflow-hidden rounded-xl bg-white">
{/* 9:16 vertical image */}
      <div className="relative w-full" style={{ aspectRatio: '9 / 16' }}>
        <img
          src={item.image}
          alt={item.title}
          className="absolute inset-0 h-full w-full object-cover"
          loading="lazy"
        />
      </div>
      <div className="p-3">
        <p className="text-sm font-semibold text-gray-950 text-center leading-snug">{item.title}</p>
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
