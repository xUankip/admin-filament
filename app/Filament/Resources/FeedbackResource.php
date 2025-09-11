<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedbackResource\Pages;
use App\Models\Feedback;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('flagged')->label('Flagged'),
                Forms\Components\Textarea::make('comment')->rows(4),
                Forms\Components\TextInput::make('rating')->numeric()->minValue(1)->maxValue(5),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.title')->label('Event')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('rating'),
                Tables\Columns\IconColumn::make('flagged')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('flagged'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggleFlag')
                    ->label('Toggle Flag')
                    ->action(fn($record) => $record->update(['flagged' => ! $record->flagged]))
                    ->icon('heroicon-o-flag'),
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
            'index' => Pages\ListFeedback::route('/'),
            'create' => Pages\CreateFeedback::route('/create'),
            'edit' => Pages\EditFeedback::route('/{record}/edit'),
        ];
    }
}


