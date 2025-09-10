<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificateResource\Pages;
use App\Models\Certificate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('event_id')->relationship('event', 'title')->searchable()->preload()->required(),
                Forms\Components\Select::make('student_id')->relationship('student', 'name')->searchable()->preload()->required(),
                Forms\Components\TextInput::make('certificate_id')->required()->maxLength(128),
                Forms\Components\TextInput::make('pdf_url')->required()->url(),
                Forms\Components\DatePicker::make('issued_on'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.title')->label('Event')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('student.name')->label('Student')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('certificate_id')->copyable(),
                Tables\Columns\TextColumn::make('issued_on')->date()->badge()->color('success'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCertificates::route('/'),
            'create' => Pages\CreateCertificate::route('/create'),
            'edit' => Pages\EditCertificate::route('/{record}/edit'),
        ];
    }
}


