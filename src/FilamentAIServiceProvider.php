<?php

namespace Wiz\FilamentAI;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Wiz\FilamentAI\Forms\Actions\GenerateContentAction;
use Wiz\FilamentAI\Services\OpenAIService;
use Wiz\FilamentAI\Services\ImageGenerationService;

class FilamentAIServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-ai';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews('filament-ai')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(FilamentAI::class, function ($app) {
            return new FilamentAI(
                $app->make(OpenAIService::class),
                $app->make(ImageGenerationService::class)
            );
        });
    }

    public function packageBooted(): void
    {
        $this->registerWithAIMacro(TextInput::class);
        $this->registerWithAIMacro(Textarea::class);
        $this->registerWithAIMacro(RichEditor::class);

    }

    protected function registerWithAIMacro(string $componentClass)
    {
        $componentClass::macro('withAI', function (array $options = []) {
            return $this->hintAction(
                app(GenerateContentAction::class)->execute($this, null, [], $options)
            );
        });
    }
}
