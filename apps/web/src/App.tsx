import { useContent } from './hooks/useContent'
import { SplashScreen } from './components/SplashScreen'
import { Header } from './components/Header'
import { ContentFeed } from './components/ContentFeed'

export function App() {
  const { pageConfig, contentFeed, state } = useContent()

  if (state === 'loading') {
    return <SplashScreen />
  }

  if (state === 'error' || !pageConfig || !contentFeed) {
    return (
      <div className="fixed inset-0 flex flex-col items-center justify-center gap-4 p-8 text-center">
        <p className="text-2xl">⚠️</p>
        <p className="text-lg font-semibold text-gray-800">Could not load content</p>
        <p className="text-sm text-gray-500">Please check your connection and try again.</p>
        <button
          onClick={() => window.location.reload()}
          className="mt-2 rounded-lg bg-brand px-5 py-2 text-sm font-medium text-white hover:bg-brand-dark transition-colors"
        >
          Retry
        </button>
      </div>
    )
  }

  return (
    <div className="min-h-dvh flex flex-col">
      <Header config={pageConfig} />
      <ContentFeed feed={contentFeed} />
    </div>
  )
}
