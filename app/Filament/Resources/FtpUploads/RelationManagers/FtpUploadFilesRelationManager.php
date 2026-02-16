<?php

namespace App\Filament\Resources\FtpUploads\RelationManagers;

use App\Jobs\UploadToFtpJob;
use Filament\Actions\Action as ActionsAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
// Removed Bus import as it's no longer used for batching here

class FtpUploadFilesRelationManager extends RelationManager
{
    protected static string $relationship = 'ftpUploadFiles';

    protected static ?string $title = 'Uploaded Files';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('filename_original')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('filename_original')
            ->columns([
                Tables\Columns\TextColumn::make('filename_original')
                    ->label('Original Filename')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('filename_ftp')
                    ->label('FTP Filename')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'PENDING' => 'gray',
                        'PROCESSING' => 'warning',
                        'SUCCESS' => 'success',
                        'FAILED' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('error_message')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ActionsAction::make('upload')
                    ->label('Upload Files')
                    ->form([
                        Forms\Components\FileUpload::make('files')
                            ->label('Select Files (.txt)')
                            ->multiple()
                            ->disk('public')
                            ->directory('temp-ftp-uploads')
                            ->preserveFilenames()
                            ->acceptedFileTypes(['text/plain'])
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->action(function (array $data, RelationManager $livewire): void {
                        $files = $data['files'];
                        if (!is_array($files)) {
                            $files = [$files];
                        }

                        $batchRecord = $livewire->getOwnerRecord();

                        // Ensure we have the latest model instance
                        if (! $batchRecord instanceof \App\Models\FtpUpload) {
                            $batchRecord = \App\Models\FtpUpload::find($batchRecord->id);
                        }

                        $user = auth()->user();

                        // Associate user if not set
                        if (!$batchRecord->user_id && $user) {
                            $batchRecord->update(['user_id' => $user->id]);
                        }

                        foreach ($files as $filePath) {
                            $originalName = basename($filePath);

                            $fileRecord = $batchRecord->ftpUploadFiles()->create([
                                'filename_original' => $originalName,
                                'status' => 'PENDING',
                            ]);

                            $absolutePath = Storage::disk('public')->path($filePath);

                            // Dispatch individual job. Notification logic moved to Job.
                            UploadToFtpJob::dispatch($fileRecord->id, $absolutePath)->onQueue('ftp-upload');
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Files queued for upload')
                            ->success()
                            ->send();
                    })
                    ->modalWidth('lg'),
            ])
            ->actions([
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
