import type { ContentFeed as ContentFeedType } from '../types'
import { RichText } from './RichText'
import { Card } from './Card'
import { Poster } from './Poster'
import { Group } from './Group'

interface Props {
  feed: ContentFeedType
}

export function ContentFeed({ feed }: Props) {
  return (
    <main className="w-full max-w-3xl mx-auto px-4 pb-16 flex flex-col gap-8">
      {feed.items.map((item, idx) => {
        switch (item.type) {
          case 'richtext':
            return <RichText key={idx} item={item} />
          case 'card':
            return (
              <div key={idx} className="w-full sm:max-w-[calc((100%-1rem)/2)] lg:max-w-[calc((100%-2rem)/3)]">
                <Card item={item} />
              </div>
            )
          case 'poster':
            return (
              <div key={idx} className="flex justify-center">
                <div className="w-48 sm:w-56">
                  <Poster item={item} />
                </div>
              </div>
            )
          case 'group':
            return <Group key={idx} item={item} />
        }
      })}
    </main>
  )
}
