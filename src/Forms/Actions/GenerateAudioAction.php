<?php

namespace Wiz\FilamentAI\Forms\Actions;

use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Wiz\FilamentAI\FilamentAI;
use Filament\Notifications\Notification;

class GenerateAudioAction
{
    public function execute($field, array $options = [])
    {
        $voices = app(FilamentAI::class)->getListVoices(); // Lấy danh sách voice từ OpenAI hoặc dịch vụ khác
        $voices  = $voices->where('language','Vietnamese');
        return Action::make('generateAudioContent')
            ->label('Read Content With AI')
            ->icon('heroicon-s-microphone')
            ->modalHeading('Read Content with AI')
            ->modalContent(fn (Action $action) => view('filament-ai::voices-list', ['voices' => $voices,'action'=>$action])) // Truyền dữ liệu vào view
            ->registerModalActions([
                Action::make('report')
                    ->label('Generate Report')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        // Xử lý khi nhấn vào report (ví dụ: Dump record)
                        dump($record);
                        // Thực hiện các hành động khác nếu cần
                    }),
            ])
            ->slideOver()
            ->modalSubmitAction(false); // Ẩn nút Submit vì không cần thiết
    }
    public function playVoice($voiceId, $text)
    {
        if (empty($voiceId) || empty($text)) {
            Notification::make()
                ->warning()
                ->title('Missing Data')
                ->body('Please select a voice and enter text before playing.')
                ->send();
            return;
        }

        try {
            // Gọi API của OpenAI hoặc dịch vụ TTS khác
            $audioUrl = app(FilamentAI::class)->getAudioUrl($voiceId, $text);

            // Trả về một script để phát audio
            $this->dispatchBrowserEvent('play-audio', ['audioUrl' => $audioUrl]);

        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error Playing Audio')
                ->body('An error occurred: ' . $e->getMessage())
                ->send();
        }
    }
    public function readWithAI($voiceId, $text)
    {
        if (empty($voiceId) || empty($text)) {
            Notification::make()
                ->warning()
                ->title('Missing Data')
                ->body('Please select a voice and enter text before generating audio.')
                ->send();
            return;
        }

        try {
            // Gọi API OpenAI hoặc dịch vụ khác để tạo giọng nói
            $audioUrl = app(FilamentAI::class)->generateSpeech($voiceId, $text);

            Notification::make()
                ->success()
                ->title('Audio Generated')
                ->body('Click the button below to listen.')
                ->actions([
                    Forms\Components\Actions\Action::make('playGeneratedAudio')
                        ->label('🔊 Play')
                        ->url($audioUrl, true),
                ])
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error Generating Audio')
                ->body('An error occurred: ' . $e->getMessage())
                ->send();
        }
    }

}
