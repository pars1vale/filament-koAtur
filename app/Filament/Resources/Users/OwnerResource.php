<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\OwnerResource\Pages;
use App\Filament\Resources\Users\OwnerResource\RelationManagers;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class OwnerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $navigationGroup = 'Kelola Pengguna';
    protected static ?int $navigationSort = 3;

    public static function isScopedToTenant(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->hasAnyRole(['super_admin', 'owner']) ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()
            ->whereHas('outlets', function ($query) {
                $query->whereKey(Filament::getTenant()->id);
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Select::make('roles')
                    ->label('Peran')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('outlets')
                    ->label('Outlet')
                    ->relationship('outlets', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Peran')
                    ->badge(),
                TextColumn::make('outlets.name')
                    ->label('Outlet')
                    ->badge()
                    ->separator(','),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name')
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListOwners::route('/'),
            'create' => Pages\CreateOwner::route('/create'),
            'edit' => Pages\EditOwner::route('/{record}/edit'),
        ];
    }
}
