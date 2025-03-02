<?php

namespace Wiz\FilamentAI\Forms\Components;

use Filament\Forms\Components\FileUpload;
use Wiz\FilamentAI\Traits\WithAIImage;

class AIFileUpload extends FileUpload
{
    use WithAIImage;
}
