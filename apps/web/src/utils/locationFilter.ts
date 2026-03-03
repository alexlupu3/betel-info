import { getLocation } from '../hooks/useContent'

export function isVisible(item: { locations?: string[] | null }): boolean {
  const { locations } = item
  if (!locations || locations.length === 0) return true
  const current = getLocation()
  if (!current) return true
  return locations.includes(current)
}
