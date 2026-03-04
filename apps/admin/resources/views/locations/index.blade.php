<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Locations') }}
            </h2>
            <a href="{{ route('locations.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Add Location') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status') === 'location-created')
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ __('Location created successfully.') }}
                </div>
            @elseif (session('status') === 'location-updated')
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ __('Location updated successfully.') }}
                </div>
            @elseif (session('status') === 'location-deleted')
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ __('Location deleted successfully.') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                @if ($locations->isEmpty())
                    <div class="p-6 text-center text-gray-500 text-sm">
                        {{ __('No locations yet.') }}
                        <a href="{{ route('locations.create') }}" class="text-indigo-600 hover:underline">{{ __('Add one now.') }}</a>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Title') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Slug') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Colors') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Default') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($locations as $location)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            @if ($location->logo_path)
                                                <img src="{{ $location->logo_path }}" alt="" class="h-8 w-8 rounded object-cover shrink-0">
                                            @endif
                                            <span class="text-sm font-medium text-gray-900">{{ $location->title }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                        {{ $location->slug }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex gap-1">
                                            @foreach ([$location->primary_color, $location->primary_light_color, $location->primary_dark_color, $location->accent_color, $location->accent_light_color, $location->accent_dark_color] as $color)
                                                @if ($color)
                                                    <span class="inline-block h-5 w-5 rounded border border-gray-200" style="background:{{ $color }}" title="{{ $color }}"></span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($location->is_default)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-700">{{ __('Default') }}</span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                        <a href="{{ route('locations.edit', $location) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>

                                        <form method="POST" action="{{ route('locations.destroy', $location) }}" class="inline" onsubmit="return confirm('{{ __('Delete this location? This cannot be undone.') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
