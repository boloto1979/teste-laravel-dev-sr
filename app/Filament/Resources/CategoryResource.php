<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-s-rectangle-stack';
    protected static ?string $navigationLabel = 'Categorias';

    public function getTitle(): string
    {
        return 'Categorias';
    }

    public static function getModelLabel(): string
    {
        return 'Categoria';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Categorias';
    }

    public static function getNavigationLabel(): string
    {
        return 'Categorias';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nome da Categoria'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Nome da Categoria')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Criado em'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('md')
                    ->color('warning')
                    ->modalHeading('Editar Categoria')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_by'] = filament()->auth()->id();
                        return $data;
                    }),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->action(function (Category $record) {
                        if ($record->tickets()->withoutTrashed()->exists()) {
                            Notification::make()
                                ->title('Não é possível excluir esta categoria')
                                ->body('Existem chamados ativos associados a esta categoria.')
                                ->danger()
                                ->send();
                            return;
                        }
                        $record->delete();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalWidth('md')
                    ->modalHeading('Nova Categoria')
                    ->createAnother(false)
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_by'] = filament()->auth()->id();
                        return $data;
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
        ];
    }
}
