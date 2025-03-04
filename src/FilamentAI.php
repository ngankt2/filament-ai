<?php

namespace Wiz\FilamentAI;

use Wiz\FilamentAI\Services\OpenAIService;
use Wiz\FilamentAI\Services\ImageGenerationService;

class FilamentAI
{

    protected $openAIService;
    protected $imageGenerationService;

    public function __construct(OpenAIService $openAIService, ImageGenerationService $imageGenerationService)
    {
        $this->openAIService = $openAIService;
        $this->imageGenerationService = $imageGenerationService;
    }

    public function generateContent(string $prompt, array $options = [])
    {
        return $this->openAIService->generateContent($prompt, $options);
    }

    public function generateImage(string $prompt, array $options = [])
    {
        return $this->imageGenerationService->generateImage($prompt, $options);
    }

    public function getContentTemplates()
    {
        return config('filament-ai.content_templates', []);
    }

    public function getTemplatesPlaceholders()
    {
        return config('filament-ai.template_placeholders', []);
    }

    public function getImagePrompts()
    {
        return config('filament-ai.image_prompts', []);
    }

    public function getPromptsPlaceholders()
    {
        return config('filament-ai.prompt_placeholders', []);
    }

}
