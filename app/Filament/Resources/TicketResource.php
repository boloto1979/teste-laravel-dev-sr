<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Models\Ticket;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;
    protected static ?string $navigationIcon = 'heroicon-s-phone-arrow-up-right';
    protected static ?string $navigationLabel = 'Chamados';

    public function getTitle(): string
    {
        return 'Chamados';
    }

    public static function getModelLabel(): string
    {
        return 'Chamado';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Chamados';
    }

    public static function getNavigationLabel(): string
    {
        return 'Chamados';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->label('Categoria')
                                    ->placeholder('Selecione uma categoria'),
                                Select::make('status')
                                    ->options([
                                        Ticket::STATUS_ABERTO => 'Aberto',
                                        Ticket::STATUS_EM_PROGRESSO => 'Em Progresso',
                                        Ticket::STATUS_RESOLVIDO => 'Resolvido',
                                    ])
                                    ->default(Ticket::STATUS_ABERTO)
                                    ->required()
                                    ->label('Status'),
                            ]),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->label('Título')
                            ->placeholder('Digite o título do chamado'),
                        Textarea::make('description')
                            ->required()
                            ->maxLength(65535)
                            ->label('Descrição')
                            ->placeholder('Descreva o chamado em detalhes')
                            ->rows(5),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Título'),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable()
                    ->label('Categoria'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Ticket::STATUS_ABERTO => 'gray',
                        Ticket::STATUS_EM_PROGRESSO => 'warning',
                        Ticket::STATUS_RESOLVIDO => 'success',
                    })
                    ->label('Status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Criado em'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        Ticket::STATUS_ABERTO => 'Aberto',
                        Ticket::STATUS_EM_PROGRESSO => 'Em Progresso',
                        Ticket::STATUS_RESOLVIDO => 'Resolvido',
                    ])
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('md')
                    ->color('warning')
                    ->modalHeading('Editar Chamado')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_by'] = filament()->auth()->id();
                        return $data;
                    }),
                Tables\Actions\Action::make('resolve')
                    ->action(fn (Ticket $record) => $record->markAsResolved())
                    ->requiresConfirmation()
                    ->modalHeading('Concluir Chamado')
                    ->modalDescription('Tem certeza que deseja concluir este chamado?')
                    ->modalSubmitActionLabel('Sim, concluir')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->label('Concluir')
                    ->visible(fn (Ticket $record) => !$record->trashed() && $record->status !== Ticket::STATUS_RESOLVIDO),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalWidth('md')
                    ->modalHeading('Novo Chamado')
                    ->createAnother(false)
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_by'] = filament()->auth()->id();
                        $data['status'] = Ticket::STATUS_ABERTO;
                        return $data;
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
        ];
    }
}
