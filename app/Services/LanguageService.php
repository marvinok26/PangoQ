<?php

namespace App\Services;

use Illuminate\Support\Facades\App;

class LanguageService
{
    public function getAvailableLanguages(): array
    {
        return config('app.available_locales', ['en']);
    }
    
    public function getCurrentLanguage(): string
    {
        return App::getLocale();
    }
    
    public function setLanguage(string $locale): void
    {
        if (in_array($locale, $this->getAvailableLanguages())) {
            App::setLocale($locale);
            session()->put('locale', $locale);
        }
    }
    
    public function getLanguageName(string $locale): string
    {
        $languages = [
            'en' => 'English',
            'es' => 'Español',
            'fr' => 'Français',
            'de' => 'Deutsch',
            // Add more languages as needed
        ];
        
        return $languages[$locale] ?? $locale;
    }
}