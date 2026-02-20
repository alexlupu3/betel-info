// ---------------------------------------------------------------------------
// Page configuration (page.json)
// ---------------------------------------------------------------------------

export interface Theme {
  /** Primary brand color, e.g. "#3b82f6" */
  primaryColor: string
  /** Lighter variant (used for backgrounds, hover states) */
  primaryLightColor?: string
  /** Darker variant (used for text on light backgrounds) */
  primaryDarkColor?: string
  /** Accent / call-to-action color */
  accentColor?: string
  accentLightColor?: string
  accentDarkColor?: string
}

export interface PageConfig {
  /** URL to the church logo image */
  logo: string
  /** Church name / page title */
  title: string
  /** Short description shown below the title */
  description: string
  theme: Theme
}

// ---------------------------------------------------------------------------
// Content items (content.json)
// ---------------------------------------------------------------------------

/** Location slugs this item should appear on. Null/empty = visible everywhere. */
type LocationFilter = string[] | null | undefined

export interface RichTextItem {
  type: 'richtext'
  /** Markdown string */
  content: string
  locations?: LocationFilter
}

export interface CardItem {
  type: 'card'
  title: string
  description: string
  /** Optional thumbnail — expected 16:9 aspect ratio */
  thumbnail?: string
  /** ISO date string, e.g. "2024-12-25" */
  date?: string
  /** 24h time string, e.g. "18:30" — only meaningful when date is set */
  time?: string
  link?: string
  /** Custom CTA label. Defaults to "Află mai multe" when link is set. */
  linkText?: string
  locations?: LocationFilter
}

export interface PosterItem {
  type: 'poster'
  /** Vertical image — expected 9:16 aspect ratio */
  image: string
  title: string
  link?: string
  locations?: LocationFilter
}

export interface GroupItem {
  type: 'group'
  title: string
  items: Array<CardItem | PosterItem>
  locations?: LocationFilter
}

export type ContentItem = RichTextItem | CardItem | PosterItem | GroupItem

export interface ContentFeed {
  items: ContentItem[]
}
