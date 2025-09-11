<?php

namespace App\Filament\Widgets;

use App\Enums\EventStatus;
use App\Models\Attendance;
use App\Models\Certificate;
use App\Models\Department;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class AdminStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected int | string | array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $startDate = $this->filters['start_date'] ?? null;
        $endDate = $this->filters['end_date'] ?? null;
        $departmentId = $this->filters['department_id'] ?? null;

        $eventsQuery = Event::query();
        $registrationsQuery = Registration::query();
        $attendanceQuery = Attendance::query();
        $certificatesQuery = Certificate::query();

        if ($startDate) {
            $eventsQuery->where('start_at', '>=', $startDate);
            $registrationsQuery->whereHas('event', fn($query) => $query->where('start_at', '>=', $startDate));
            $attendanceQuery->where('checked_in_at', '>=', $startDate);
            $certificatesQuery->where('issued_on', '>=', $startDate);
        }
        if ($endDate) {
            $eventsQuery->where('start_at', '<=', $endDate);
            $registrationsQuery->whereHas('event', fn($query) => $query->where('start_at', '<=', $endDate));
            $attendanceQuery->where('checked_in_at', '<=', $endDate);
            $certificatesQuery->where('issued_on', '<=', $endDate);
        }

        if ($departmentId) {
            $eventsQuery->where('department_id', $departmentId);
            $registrationsQuery->whereHas('event', fn($query) => $query->where('department_id', $departmentId));
            $attendanceQuery->whereHas('registration.event', fn($query) => $query->where('department_id', $departmentId));
            $certificatesQuery->whereHas('event', fn($query) => $query->where('department_id', $departmentId));
        }

        $totalEvents = $eventsQuery->count();
        $upcomingEvents = $eventsQuery->where('start_at', '>=', now())->where('status', EventStatus::Approved->value)->count();
        $totalRegistrations = $registrationsQuery->count();
        $totalAttendance = $attendanceQuery->whereNotNull('checked_in_at')->count();
        $totalCertificates = $certificatesQuery->count();
        $totalUsers = User::count();
        $adminUsers = User::role(['super_admin', 'staff_admin'])->count();
        $organizers = User::role('staff_organizer')->count();

        $eventStatusCounts = Event::query()
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $registrationsByDate = Registration::query()
            ->whereHas('event', fn($query) => $query->when($startDate, fn($q) => $q->where('start_at', '>=', $startDate))
                ->when($endDate, fn($q) => $q->where('start_at', '<=', $endDate)))
            ->select(DB::raw('DATE(events.start_at) as date'), DB::raw('count(*) as count'))
            ->join('events', 'registrations.event_id', '=', 'events.id')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $certificatesByDepartment = Certificate::query()
            ->whereHas('event', fn($query) => $query->when($departmentId, fn($q) => $q->where('department_id', $departmentId)))
            ->select('events.department_id', DB::raw('count(*) as count'))
            ->join('events', 'certificates.event_id', '=', 'events.id')
            ->groupBy('events.department_id')
            ->pluck('count', 'events.department_id')
            ->mapWithKeys(fn($count, $deptId) => [Department::find($deptId)->name ?? 'Unknown' => $count])
            ->toArray();

        $previousTotalEvents = Event::query()
            ->when($startDate, fn($q) => $q->where('start_at', '>=', \Carbon\Carbon::parse($startDate)->subMonth()))
            ->when($endDate, fn($q) => $q->where('start_at', '<=', \Carbon\Carbon::parse($endDate)->subMonth()))
            ->when($departmentId, fn($q) => $q->where('department_id', $departmentId))
            ->count();

        $eventChange = $previousTotalEvents > 0 ? (($totalEvents - $previousTotalEvents) / $previousTotalEvents) * 100 : 0;

        return [
            Stat::make('🔐 Admin Users', number_format($adminUsers))
                ->description('Super admin & staff admin')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('red')
                ->extraAttributes([
                    'class' => 'relative overflow-hidden'
                ]),
            Stat::make('👤 Total Users', number_format($totalUsers))
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('indigo')
                ->extraAttributes([
                    'class' => 'relative overflow-hidden'
                ]),


            Stat::make('📅 Total Events', number_format($totalEvents))
                ->description('Events organized')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('blue')
                ->chart(array_values($eventStatusCounts))
                ->extraAttributes([
                    'class' => 'relative overflow-hidden'
                ]),

            Stat::make('⏰ Upcoming Events', number_format($upcomingEvents))
                ->description('Approved & upcoming')
                ->descriptionIcon('heroicon-m-clock')
                ->color('green')
                ->extraAttributes([
                    'class' => 'relative overflow-hidden'
                ]),

            Stat::make('👥 Total Registrations', number_format($totalRegistrations))
                ->description('Event registrations')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('amber')
                ->chart(array_values($registrationsByDate))
                ->extraAttributes([
                    'class' => 'relative overflow-hidden'
                ]),

            Stat::make('✅ Total Attendance', number_format($totalAttendance))
                ->description('Checked-in participants')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('emerald')
                ->extraAttributes([
                    'class' => 'relative overflow-hidden'
                ]),

            Stat::make('🏆 Certificates Issued', number_format($totalCertificates))
                ->description('Certificates awarded')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('purple')
                ->extraAttributes([
                    'class' => 'relative overflow-hidden'
                ]),



            Stat::make('🎯 Organizers', number_format($organizers))
                ->description('Staff organizers')
                ->descriptionIcon('heroicon-m-user-circle')
                ->color('cyan')
                ->extraAttributes([
                    'class' => 'relative overflow-hidden'
                ]),
        ];
    }

    public function getFilters(): ?array
    {
        return [
            'start_date' => [
                'label' => 'Start Date',
                'type' => 'date',
            ],
            'end_date' => [
                'label' => 'End Date',
                'type' => 'date',
            ],
            'department_id' => [
                'label' => 'Department',
                'type' => 'select',
                'options' => Department::pluck('name', 'id')->toArray(),
                'placeholder' => 'Select a department',
            ],
        ];
    }
}
