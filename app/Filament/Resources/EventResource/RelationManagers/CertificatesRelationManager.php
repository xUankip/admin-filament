<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CertificatesRelationManager extends RelationManager
{
    protected static string $relationship = 'certificates';

    protected static ?string $recordTitleAttribute = 'certificate_number';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->label('Student')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('certificate_number')
                    ->label('Certificate Number')
                    ->default(fn () => 'CERT-' . strtoupper(Str::random(8)))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('certificate_id')
                    ->label('Certificate ID')
                    ->default(fn () => 'ID-' . strtoupper(Str::random(6)))
                    ->maxLength(255),

                Forms\Components\DatePicker::make('issued_on')
                    ->label('Issue Date')
                    ->default(now())
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'issued' => 'Issued',
                        'revoked' => 'Revoked',
                        'expired' => 'Expired',
                    ])
                    ->required()
                    ->default('draft'),

                Forms\Components\Textarea::make('notes')
                    ->maxLength(500),

                Forms\Components\TextInput::make('template')
                    ->label('Template Used')
                    ->maxLength(255),

                Forms\Components\TextInput::make('pdf_path')
                    ->label('PDF File Path')
                    ->maxLength(500),

                Forms\Components\Toggle::make('is_digital')
                    ->label('Digital Certificate')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('certificate_number')
            ->columns([
                Tables\Columns\TextColumn::make('certificate_number')
                    ->label('Certificate #')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('certificate_id')
                    ->label('Certificate ID')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('student.email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('student.detail.department.name')
                    ->label('Department')
                    ->sortable(),

                Tables\Columns\TextColumn::make('issued_on')
                    ->label('Issue Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'issued' => 'success',
                        'revoked' => 'danger',
                        'expired' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_digital')
                    ->boolean()
                    ->label('Digital'),

                Tables\Columns\TextColumn::make('template')
                    ->limit(20),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'issued' => 'Issued',
                        'revoked' => 'Revoked',
                        'expired' => 'Expired',
                    ]),

                Tables\Filters\TernaryFilter::make('is_digital')
                    ->label('Digital Certificate'),

                Tables\Filters\Filter::make('issued_on')
                    ->form([
                        Forms\Components\DatePicker::make('issued_from'),
                        Forms\Components\DatePicker::make('issued_until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['issued_from'], fn ($query, $date) => $query->whereDate('issued_on', '>=', $date))
                            ->when($data['issued_until'], fn ($query, $date) => $query->whereDate('issued_on', '<=', $date));
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),

                Tables\Actions\Action::make('bulk_issue')
                    ->label('Bulk Issue from Attendees')
                    ->icon('heroicon-o-document-plus')
                    ->color('info')
                    ->action(function () {
                        $event = $this->getOwnerRecord();
                        $attendedRegistrations = $event->registrations()->where('status', 'attended')->get();

                        foreach ($attendedRegistrations as $registration) {
                            // Check if certificate already exists for this student and event
                            $existingCert = $event->certificates()
                                ->where('student_id', $registration->user_id)
                                ->first();

                            if (!$existingCert) {
                                $event->certificates()->create([
                                    'student_id' => $registration->user_id,
                                    'certificate_number' => 'CERT-' . strtoupper(Str::random(8)),
                                    'certificate_id' => 'ID-' . strtoupper(Str::random(6)), // Thêm certificate_id
                                    'issued_on' => now(),
                                    'status' => 'issued',
                                    'is_digital' => true,
                                    'pdf_url' => '', // Thêm default value
                                ]);
                            }
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Issue Certificates to All Attendees')
                    ->modalDescription('This will create certificates for all users with "attended" status who don\'t already have certificates.')
                    ->modalSubmitActionLabel('Issue Certificates'),

                // ... (rest of the actions with certificate_id and pdf_url added)
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->url(fn ($record) => $record->pdf_path ? asset('storage/' . $record->pdf_path) : null)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => !empty($record->pdf_path)),

                Tables\Actions\Action::make('revoke')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function ($record) {
                        $record->update(['status' => 'revoked']);
                    })
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'issued'),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulk_issue_certificates')
                        ->label('Issue Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->status === 'draft') {
                                    $record->update([
                                        'status' => 'issued',
                                        'issued_on' => now()
                                    ]);
                                }
                            }
                        })
                        ->requiresConfirmation(),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
