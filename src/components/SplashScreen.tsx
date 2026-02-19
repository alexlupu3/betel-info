export function SplashScreen() {
  return (
    <div className="fixed inset-0 z-50 flex flex-col items-center justify-center bg-white">
      <div className="flex flex-col items-center gap-6">
        {/* Spinner */}
        <div
          className="h-12 w-12 rounded-full border-4 border-brand-light border-t-brand animate-spin"
          role="status"
          aria-label="Loading"
        />
        <p className="text-sm text-gray-500 tracking-wide">Loading…</p>
      </div>
    </div>
  )
}
