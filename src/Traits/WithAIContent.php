<?php

namespace Wiz\FilamentAI\Traits;

use Filament\Forms\Components\Actions\Action;
use Wiz\FilamentAI\Forms\Actions\GenerateContentAction;

trait WithAIContent
{
    public function withAI(array $options = [])
    {
        $this->hintAction(
            app(GenerateContentAction::class)->execute($this, null, [], $options)
        );

        return $this;
    }
}
