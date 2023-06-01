<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Source;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Source::create([
            "title"=>"The New York Times",
            "slug"=>"nytime",
            "url"=>"https://api.nytimes.com/svc/search/v2/articlesearch.json",
            "api_key"=>"uC1woOTQybCAbZW9MrF1BmBF4myAEEYs",
            "adapter"=>"NYTimeAdapter",
        ]);

        Source::create([
            "title"=>"Guardians",
            "slug"=>"guardians",
            "url"=>"https://content.guardianapis.com/search",
            "api_key"=>"c25ad85f-c0a3-4f72-95c2-7cac322d7a9d",
            "adapter"=>"GuardiansAdapter",
        ]);

        Source::create([
            "title"=>"NewsCatcher",
            "slug"=>"newscatcher",
            "url"=>"https://api.newscatcherapi.com/v2/search",
            "api_key"=>"5GLXrO2HaS4NlD_-NlBY0sT1SQM-TsavSWB7aWcSLZM",
            "adapter"=>"NewscratcherAdapter",
        ]);

    }
}
