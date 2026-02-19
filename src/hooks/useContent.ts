import { useEffect, useState } from 'react'
import type { PageConfig, ContentFeed } from '../types'

const BASE_URL = import.meta.env.VITE_CONTENT_BASE_URL ?? ''

function injectTheme(theme: PageConfig['theme']) {
  const root = document.documentElement
  root.style.setProperty('--color-brand', theme.primaryColor)
  root.style.setProperty('--color-brand-light', theme.primaryLightColor ?? lighten(theme.primaryColor))
  root.style.setProperty('--color-brand-dark', theme.primaryDarkColor ?? darken(theme.primaryColor))
  if (theme.accentColor) {
    root.style.setProperty('--color-accent', theme.accentColor)
    root.style.setProperty('--color-accent-light', theme.accentLightColor ?? lighten(theme.accentColor))
    root.style.setProperty('--color-accent-dark', theme.accentDarkColor ?? darken(theme.accentColor))
  }

  // Also update the PWA theme-color meta tag
  const meta = document.querySelector<HTMLMetaElement>('meta[name="theme-color"]')
  if (meta) meta.content = theme.primaryColor
}

/** Simple hex color manipulation helpers */
function hexToRgb(hex: string): [number, number, number] {
  const clean = hex.replace('#', '')
  const int = parseInt(clean.length === 3
    ? clean.split('').map(c => c + c).join('')
    : clean, 16)
  return [(int >> 16) & 255, (int >> 8) & 255, int & 255]
}

function rgbToHex(r: number, g: number, b: number): string {
  return '#' + [r, g, b].map(v => v.toString(16).padStart(2, '0')).join('')
}

function clamp(n: number) { return Math.max(0, Math.min(255, n)) }

function lighten(hex: string, amount = 0.85): string {
  const [r, g, b] = hexToRgb(hex)
  return rgbToHex(
    clamp(Math.round(r + (255 - r) * amount)),
    clamp(Math.round(g + (255 - g) * amount)),
    clamp(Math.round(b + (255 - b) * amount)),
  )
}

function darken(hex: string, amount = 0.25): string {
  const [r, g, b] = hexToRgb(hex)
  return rgbToHex(
    clamp(Math.round(r * (1 - amount))),
    clamp(Math.round(g * (1 - amount))),
    clamp(Math.round(b * (1 - amount))),
  )
}

// ---------------------------------------------------------------------------

export type LoadState = 'loading' | 'ready' | 'error'

export function useContent() {
  const [pageConfig, setPageConfig] = useState<PageConfig | null>(null)
  const [contentFeed, setContentFeed] = useState<ContentFeed | null>(null)
  const [state, setState] = useState<LoadState>('loading')

  useEffect(() => {
    async function load() {
      try {
        const [pageRes, contentRes] = await Promise.all([
          fetch(`${BASE_URL}/page.json`),
          fetch(`${BASE_URL}/content.json`),
        ])

        if (!pageRes.ok || !contentRes.ok) throw new Error('Failed to fetch content')

        const [page, content] = await Promise.all([
          pageRes.json() as Promise<PageConfig>,
          contentRes.json() as Promise<ContentFeed>,
        ])

        injectTheme(page.theme)
        document.title = page.title

        setPageConfig(page)
        setContentFeed(content)
        setState('ready')
      } catch (err) {
        console.error('[betel-info] content load error:', err)
        setState('error')
      }
    }

    void load()
  }, [])

  return { pageConfig, contentFeed, state }
}
