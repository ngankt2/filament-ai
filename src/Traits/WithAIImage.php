<?php

namespace Wiz\FilamentAI\Traits;

use Filament\Forms\Components\Actions\Action;
use Wiz\FilamentAI\Forms\Actions\GenerateImageAction;

trait WithAIImage
{
    public function imageAI(array $options = [])
    {
        $this->hintAction(
            fn ($component) => app(GenerateImageAction::class)->execute($component, null, [], $options)
        );

        return $this;
    }
}
