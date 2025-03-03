<?php

namespace Wiz\FilamentAI\Forms\Actions;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Wiz\FilamentAI\FilamentAI;
use Filament\Notifications\Notification;

class GenerateAudioAction
{
    public function execute($field, $record, $data, array $options = [])
    {
        return Action::make('generateAudioContent')
            ->label('Read Content With AI')
            ->icon('heroicon-s-microphone')
            ->form([

            ])
            ->action(function (array $data) use ($field, $options) {
                if (empty(config('openai.api_key'))) {
                    Notification::make()
                        ->warning()
                        ->title('OpenAI API Key Missing')
                        ->body('Please add your OpenAI API Key to the .env file before proceeding.')
                        ->send();
                    return;
                }

                try {
                    $currentContent = $field->getState();
                    if ($data['use_existing_content']) {
                        $action = $data['existing_content_action'];

                        switch ($action) {
                            case 'refine':
                                $prompt = "Refine the following text: $currentContent";
                                break;
                            case 'expand':
                                $prompt = "Expand on the following text by adding more details, examples, or explanations. Ensure that your response is a continuation of the existing content and forms complete sentences and paragraphs: $currentContent";
                                break;
                            case 'shorten':
                                $prompt = "Shorten the following text while maintaining its key points: $currentContent";
                                break;
                            default:
                                throw new \Exception("Invalid action selected for existing content.");
                        }
                    } else {
                        $prompt = $data['ai_prompt'] ?? null;
                        if (empty($prompt)) {
                            throw new \Exception("Prompt is empty or null. Form data: " . json_encode($data));
                        }
                    }

                    $generatedContent = app(FilamentAI::class)->generateContent($prompt, $options);

                    $textInputContent = $generatedContent;
                    // Remove incomplete sentences
                    $generatedContent = $this->removeIncompleteSentences($generatedContent);

                    if ($data['use_existing_content'] && $data['existing_content_action'] === 'expand') {
                        // Append the new content to the existing content
                        $newContent = $currentContent . "\n\n" . $generatedContent;
                    } elseif ($data['use_existing_content']) {
                        // Replace the existing content for 'refine' and 'shorten' actions
                        $newContent = $generatedContent;
                    } else {
                        // Append the new content to the existing content for non-existing content actions
                        if ($field instanceof RichEditor) {
                            $newContent = $currentContent . "\n\n" . $generatedContent;
                        } elseif ($field instanceof Textarea) {
                            $newContent = $currentContent . "\n" . $generatedContent;
                        } else {
                            $newContent = trim($currentContent . ' ' . $textInputContent);
                        }
                    }

                    // Set the new content
                    $field->state($newContent);

                    // Notify the user of successful content generation
                    Notification::make()
                        ->success()
                        ->title('Content Generated Successfully')
                        ->body('The AI-generated content has been added to the field.')
                        ->send();

                } catch (\Exception $e) {
                    // Notify the user if an error occurs
                    Notification::make()
                        ->danger()
                        ->title('Error Generating Content')
                        ->body('An error occurred while generating content: ' . $e->getMessage())
                        ->send();
                }
            })
            ->modalHeading('Read Content with AI')
            ->modalSubmitActionLabel('Read');
    }

    private function removeIncompleteSentences($content)
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', $content, -1, PREG_SPLIT_NO_EMPTY);
        $lastSentence = end($sentences);

        // Check if the last sentence ends with a period, exclamation mark, or question mark
        if (!preg_match('/[.!?]$/', $lastSentence)) {
            // Remove the last sentence if it's incomplete
            array_pop($sentences);
        }

        return implode(' ', $sentences);
    }
}
