<?php

namespace Wiz\FilamentAI\Forms\Components;

use Filament\Forms\Components\RichEditor;
use Wiz\FilamentAI\Traits\WithAIContent;
use Filament\Forms\Components\Actions\Action;

class AITextField extends RichEditor
{
    use WithAIContent;
}
