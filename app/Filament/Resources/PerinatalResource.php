<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerinatalResource\Pages;
use App\Filament\Resources\PerinatalResource\RelationManagers;
use App\Models\Estado;
use App\Models\Perinatal;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PerinatalResource extends Resource
{
    protected static ?string $model = Perinatal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Atendimentos';

    protected static ?string $navigationLabel = 'Perinatal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Dados do Paciente')
                    ->schema([
                        Forms\Components\Select::make('paciente_id')
                            ->searchable()
                            ->relationship(name: 'paciente', titleAttribute: 'nome')
                            ->createOptionForm([
                                Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('nome')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\DatePicker::make('data_nascimento')
                                            ->label('Data de Nascimento')
                                            ->required(),
                                        Forms\Components\Textarea::make('endereco')
                                            ->label('Endereço')
                                            ->required()
                                            ->columnSpanFull(),
                                        Forms\Components\Select::make('estado_id')
                                            ->searchable()
                                            ->label('Estado')
                                            ->required()
                                            ->options(Estado::all()->pluck('nome', 'id')->toArray())
                                            ->reactive(),
                                        Forms\Components\Select::make('cidade_id')
                                            ->searchable()
                                            ->label('Cidade')
                                            ->required()
                                            ->options(function (callable $get) {
                                                $estado = Estado::find($get('estado_id'));
                                                if (!$estado) {
                                                    return Estado::all()->pluck('nome', 'id');
                                                }
                                                return $estado->cidade->pluck('nome', 'id');
                                            })
                                            ->reactive(),
                                        Forms\Components\TextInput::make('profissão')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('telefone')
                                            ->tel()
                                            ->required()
                                            ->mask('(99)99999-9999')
                                            ->maxLength(255),
                                        Forms\Components\Radio::make('cor')
                                            ->required()
                                            ->inline()
                                            ->options([
                                                '1' => 'Branca',
                                                '2' => 'Preta',
                                                '3' => 'Parda',
                                                '4' => 'Amarela',
                                                '5' => 'Indígena',
                                                '6' => 'Não Declarar',
                                            ])->columnSpanFull(),
                                        Forms\Components\Textarea::make('obs')
                                            ->label('Observações')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Forms\Components\TextInput::make('peso')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('altura')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('dum')
                            ->label('DUM'),
                        Forms\Components\DatePicker::make('dpp')
                            ->label('DPP'),
                        Forms\Components\DatePicker::make('dpp_eco')
                            ->label('DPP eco'),

                    ]),
                Fieldset::make('Gestações')
                    ->schema([
                        Grid::make('4')
                            ->schema([
                                Forms\Components\TextInput::make('parto')
                                    ->numeric(),
                                Forms\Components\TextInput::make('gravidez_planejada')
                                    ->numeric()
                                    ->label('Gravidez Planejada'),

                                Forms\Components\Toggle::make('bebe_2500')
                                    ->inline(false)
                                    ->label('Bebê < 2.500g'),

                                Forms\Components\Toggle::make('bebe_4500')
                                    ->inline(false)
                                    ->label('Bebê > 4.500g'),

                                Forms\Components\Toggle::make('pre_eclampsia')
                                ->inline(false)
                                    ->label('Pré-eclâmpsia'),
                                Forms\Components\TextInput::make('gesta')
                                    ->numeric(),
                                Forms\Components\Toggle::make('gesta_ectopia')
                                ->inline(false)
                                    ->label('Gestação Ectópica'),
                                Forms\Components\TextInput::make('abortos')
                                    ->numeric()
                                    ->maxLength(255),
                                Forms\Components\Toggle::make('abortos_3')
                                ->inline(false)
                                    ->label('3 ou + abortos'),
                                Forms\Components\TextInput::make('parto_vaginal')
                                    ->numeric()
                                    ->label('Parto Vaginal')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('cesarea')
                                    ->numeric()
                                    ->maxLength(255),
                                Forms\Components\Toggle::make('cesarea_previa_2')
                                       ->inline(false)

                                    ->label('2 cesarea prévias'),
                                Forms\Components\TextInput::make('nascido_vivo')
                                    ->numeric()
                                    ->label('Nascidos Vivos')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('nascido_vivo_vivem')
                                    ->numeric()
                                    ->label('Nascido Vivo - Vivem')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('nascido_morto')
                                    ->numeric()
                                    ->label('Nascidos Mortos')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('morto_semana_1')
                                    ->numeric()
                                    ->label('Morreram na 1º Semana')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('morto_depois_semana_1')
                                    ->numeric()
                                    ->label('Morreram depois na 1º Semana')
                                    ->maxLength(255),
                                Forms\Components\Radio::make('final_gesta_anterior_1_ano')
                                    ->label('Final da gestação anterior há 1 ano')
                                    ->options([
                                        '0' => 'Não',
                                        '1' => 'Sim',
                                    ]),
                                Fieldset::make('Vacinas')
                                    ->schema([
                                        Section::make('Vacina Antitetânica (dT)')
                                            ->columns('5')
                                            ->schema([
                                                Forms\Components\radio::make('vacina_dt')
                                                    ->label('')
                                                    ->options([
                                                        '0' => 'Sem informação de imunização',
                                                        '1' => 'Imunizada há menos de 5 anos',
                                                        '2' => 'Imunizada há mais de 5 anos',
                                                    ])->live(),
                                                Forms\Components\DatePicker::make('vacina_dt_data_1')
                                                    ->hidden(fn (Get $get): bool => $get('vacina_dt') === null || $get('vacina_dt') === '0')
                                                    ->label('1º Dose'),
                                                Forms\Components\DatePicker::make('vacina_dt_data_2')
                                                    ->hidden(fn (Get $get): bool => $get('vacina_dt') === null || $get('vacina_dt') === '0')
                                                    ->label('2º Dose'),
                                                Forms\Components\DatePicker::make('vacina_dt_data_3')
                                                    ->hidden(fn (Get $get): bool => $get('vacina_dt') === null || $get('vacina_dt') === '0')
                                                    ->label('3º Dose'),
                                                Forms\Components\DatePicker::make('vacina_dt_reforco')
                                                    ->hidden(fn (Get $get): bool => $get('vacina_dt') === null || $get('vacina_dt') === '0')
                                                    ->label('Reforco'),
                                            ]),
                                        Section::make('Vacina HPV')
                                            ->columns('3')
                                            ->schema([
                                                Forms\Components\radio::make('vacina_hpv')
                                                    ->label('')
                                                    ->options([
                                                        '0' => 'Não',
                                                        '1' => 'Sim',

                                                    ])->live(),
                                                Forms\Components\DatePicker::make('vacina_hpv_data_1')
                                                    ->hidden(fn (Get $get): bool => $get('vacina_hpv') === null || $get('vacina_hpv') === '0')
                                                    ->label('1º Dose'),
                                                Forms\Components\DatePicker::make('vacina_hpv_data_2')
                                                    ->hidden(fn (Get $get): bool => $get('vacina_hpv') === null || $get('vacina_hpv') === '0')
                                                    ->label('2º Dose'),
                                            ]),
                                        Section::make('Vacina Hepatite B')
                                            ->columns('4')
                                            ->schema([
                                                Forms\Components\radio::make('vacina_hepatite_b')
                                                    ->label('')
                                                    ->options([
                                                        '0' => 'Não',
                                                        '1' => 'Sim',

                                                    ])->live(),
                                                Forms\Components\DatePicker::make('vacina_hepatite_b_data_1')
                                                    ->hidden(fn (Get $get): bool => $get('vacina_hepatite_b') === null || $get('vacina_hepatite_b') === '0')
                                                    ->label('1º Dose'),
                                                Forms\Components\DatePicker::make('vacina_hepatite_b_data_2')
                                                    ->hidden(fn (Get $get): bool => $get('vacina_hepatite_b') === null || $get('vacina_hepatite_b') === '0')
                                                    ->label('2º Dose'),
                                                Forms\Components\DatePicker::make('vacina_hepatite_b_data_3')
                                                    ->hidden(fn (Get $get): bool => $get('vacina_hepatite_b') === null || $get('vacina_hepatite_b') === '0')
                                                    ->label('3º Dose'),
                                            ]),
                                        Section::make('Vacina Influenza')
                                            ->columns('2')
                                            ->schema([
                                                Forms\Components\radio::make('vacina_influenza')
                                                    ->label('')
                                                    ->options([
                                                        '0' => 'Não',
                                                        '1' => 'Sim',

                                                    ])->live(),
                                                Forms\Components\DatePicker::make('vacina_influenza_data')
                                                    ->hidden(fn (Get $get): bool => $get('vacina_influenza') === null || $get('vacina_influenza') === '0')
                                                    ->label('Data'),
                                            ]),
                                        Section::make('Vacina Influenza')
                                            ->columns('2')
                                            ->schema([
                                                Forms\Components\radio::make('vacina_dtpa')
                                                    ->label('')
                                                    ->options([
                                                        '0' => 'Não',
                                                        '1' => 'Sim',

                                                    ])->live(),
                                                Forms\Components\DatePicker::make('vacina_dtpa_data')
                                                    ->hidden(fn (Get $get): bool => $get('vacina_dtpa') === null || $get('vacina_dtpa') === '0')
                                                    ->label('Data'),
                                            ]),




                                    ]),



                            ]),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('paciente_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('peso')
                    ->searchable(),
                Tables\Columns\TextColumn::make('altura')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dum')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dpp')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dpp_eco')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gravidez_planejada')
                    ->searchable(),
                Tables\Columns\TextColumn::make('parto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bebe_2500')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bebe_4500')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pre_eclampsia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gesta_previa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gesta_previa_ectopia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('abortos')
                    ->searchable(),
                Tables\Columns\TextColumn::make('abortos_3')
                    ->searchable(),
                Tables\Columns\TextColumn::make('parto_vaginal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cesarea')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cesarea_previa_2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nascido_vivo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nascido_vivo_vivem')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nascido_morto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('morto_semana_1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('morto_depois_semana_1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('final_gesta_anterior_1_ano')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vacina_dt')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vacina_dt_data_1')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vacina_dt_data_2')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vacina_dt_data_3')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vacina_dt_reforco')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vacina_hpv')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vacina_hpv_data_1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vacina_hpv_data_2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vacina_hepatite_b')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vacina_hepatite_b_data_1')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vacina_hepatite_b_data_2')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vacina_hepatite_b_data_3')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListPerinatals::route('/'),
            'create' => Pages\CreatePerinatal::route('/create'),
            'edit' => Pages\EditPerinatal::route('/{record}/edit'),
        ];
    }
}