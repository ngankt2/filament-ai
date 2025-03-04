<?php

namespace Wiz\FilamentAI\Traits;

use Filament\Forms\Components\Actions\Action;
use Wiz\FilamentAI\Forms\Actions\GenerateAudioAction;
use Wiz\FilamentAI\Forms\Actions\GenerateContentAction;

trait WithAIReadContent
{
    public function withReadAI(array $options = [])
    {
        $this->hintAction(
            app(GenerateAudioAction::class)->execute($this, $options)
        );

        return $this;
    }
}
