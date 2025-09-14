@extends('filament::page')

@section('content')
    @php
        /** @var \App\Models\Department $record */
        $record = $this->record ?? null;
        $events = $record?->events()->withCount('registrations')->get() ?? collect();
        $totalParticipants = $events->sum('registrations_count');
    @endphp

    @if ($record)
        <div class="space-y-6">
            <!-- Department Header -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $record->name }}</h1>
                        <p class="text-gray-600 mt-1">Department Overview</p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-blue-600">{{ $events->count() }}</div>
                        <div class="text-sm text-gray-500">Total Events</div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <x-heroicon-o-users class="h-6 w-6 text-green-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Participants</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $totalParticipants }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <x-heroicon-o-calendar class="h-6 w-6 text-blue-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Upcoming Events</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $events->where('start_at', '>', now())->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <x-heroicon-o-check-circle class="h-6 w-6 text-purple-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Completed Events</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $events->where('start_at', '<', now())->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events List -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Events in this Department</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($events as $event)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $event->title }}</h4>
                                    <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <x-heroicon-o-calendar class="h-4 w-4 mr-1" />
                                            {{ $event->start_at?->format('M d, Y H:i') ?? 'TBD' }}
                                        </div>
                                        <div class="flex items-center">
                                            <x-heroicon-o-users class="h-4 w-4 mr-1" />
                                            {{ $event->registrations_count }} participants
                                        </div>
                                        <div class="flex items-center">
                                            <x-heroicon-o-map-pin class="h-4 w-4 mr-1" />
                                            {{ $event->location ?? 'TBD' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    @if($event->start_at && $event->start_at > now())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Upcoming
                                        </span>
                                    @elseif($event->start_at && $event->start_at <= now())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Completed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            TBD
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <x-heroicon-o-calendar class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No events</h3>
                            <p class="mt-1 text-sm text-gray-500">This department doesn't have any events yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    {{ $this->infolist }}
@endsection
