import type { PageConfig } from '../types'

interface Props {
  config: PageConfig
}

export function Header({ config }: Props) {
  return (
    <header className="flex flex-col items-center gap-4 py-10 px-4 text-center">
      {config.logo && (
        <img
          src={config.logo}
          alt={`${config.title} logo`}
          className="h-20 w-20 rounded-full object-cover"
        />
      )}
      <h1 className="text-3xl font-bold tracking-tight text-gray-900">{config.title}</h1>
      {config.description && (
        <p className="max-w-xl text-base text-gray-600 leading-relaxed">{config.description}</p>
      )}
    </header>
  )
}
