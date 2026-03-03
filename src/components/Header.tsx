import { useState } from 'react'
import type { PageConfig } from '../types'
import { getLocation } from '../hooks/useContent'

const LOCATIONS = [
  { slug: 'betel-centru',   label: 'Centru' },
  { slug: 'betel-manastur', label: 'Mănăștur' },
  { slug: 'betel-vest',     label: 'Vest' },
  { slug: 'betel-est',      label: 'Est' },
]

interface Props {
  config: PageConfig
}

export function Header({ config }: Props) {
  const location = getLocation()
  const [showLocations, setShowLocations] = useState(false)

  function clearLocation() {
    window.location.href = window.location.pathname
  }

  // Signature mixed-type composition:
  // - "Betel Centru"   → BETEL (bold) + CENTRU (condensed italic accent)
  // - "Biserica Betel" → BISERICA (bold) + BETEL (condensed italic accent)
  const betelIdx = config.title.indexOf('Betel')
  const before = betelIdx > 0 ? config.title.slice(0, betelIdx).trim() : ''
  const after = config.title.slice(betelIdx + 5).trim()
  // Title starts with "Betel": bold="Betel", italic=location suffix
  // Title has prefix before "Betel": bold=prefix, italic="Betel"
  const titleStartsWithBetel = betelIdx === 0
  const hasMixedTitle = betelIdx !== -1 && (titleStartsWithBetel ? after.length > 0 : before.length > 0)

  return (
    <header className="flex flex-col items-center gap-5 py-12 px-4 text-center">
      {config.logo && (
        <img
          src={config.logo}
          alt={`${config.title} logo`}
          className="h-16 w-16 rounded-full object-cover"
        />
      )}
      <h1 className="text-4xl sm:text-5xl tracking-tight text-gray-950 uppercase leading-none">
        {hasMixedTitle ? (
          titleStartsWithBetel ? (
            // e.g. "Betel Centru" → BETEL bold + CENTRU italic
            <>
              <span className="font-semibold">Betel</span>
              {''}
              <span className="text-accent" style={{ fontStyle: 'italic', fontWeight: 200 }}>
                {after}
              </span>
            </>
          ) : (
            // e.g. "Biserica Betel Cluj" → BISERICA bold + BETEL italic + rest bold
            <>
              <span className="font-semibold">{before}</span>
              {''}
              <span className="text-accent" style={{ fontStyle: 'italic', fontWeight: 200 }}>
                Betel
              </span>
              {after && <span className="font-semibold"> {after}</span>}
            </>
          )
        ) : (
          <span className="font-semibold">{config.title}</span>
        )}
      </h1>
      {config.description && (
        <p className="max-w-sm text-sm text-gray-500 leading-relaxed">{config.description}</p>
      )}
      {location && (
        <button
          onClick={clearLocation}
          className="text-xs font-medium text-gray-950 border border-gray-950/40 rounded-full px-4 py-1.5 hover:bg-gray-950/5 transition-colors"
        >
          Vezi tot
        </button>
      )}
      {!location && (
        <div className="flex flex-col items-center gap-3">
          <button
            onClick={() => setShowLocations(v => !v)}
            className="text-xs font-medium text-gray-950 border border-gray-950/40 rounded-full px-4 py-1.5 hover:bg-gray-950/5 transition-colors"
          >
            Locații
          </button>
          {showLocations && (
            <div className="flex flex-wrap justify-center gap-2">
              {LOCATIONS.map(loc => (
                <a
                  key={loc.slug}
                  href={`?location=${loc.slug}`}
                  className="text-xs text-gray-500 border border-gray-200 rounded-full px-3 py-1 hover:text-gray-950 hover:border-gray-950/40 transition-colors"
                >
                  {loc.label}
                </a>
              ))}
            </div>
          )}
        </div>
      )}
    </header>
  )
}
