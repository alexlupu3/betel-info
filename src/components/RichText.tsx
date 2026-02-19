import ReactMarkdown from 'react-markdown'
import type { RichTextItem } from '../types'

interface Props {
  item: RichTextItem
}

export function RichText({ item }: Props) {
  return (
    <div className="prose w-full">
      <ReactMarkdown>{item.content}</ReactMarkdown>
    </div>
  )
}
