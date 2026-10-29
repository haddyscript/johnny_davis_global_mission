<?php

namespace Tests\Feature;

use Database\Seeders\NavItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class MinisterialFellowshipNavTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(NavItemSeeder::class);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function globalMissionUrls(): array
    {
        return [
            'mission homepage' => ['https://johnnydavisglobalmission.org/'],
            'mission donate page' => ['https://johnnydavisglobalmission.org/donate'],
            'missions homepage' => ['https://johnnydavisglobalmissions.org/'],
            'missions donate page' => ['https://johnnydavisglobalmissions.org/donate'],
            'missions ministry page' => ['https://johnnydavisglobalmissions.org/ministry'],
        ];
    }

    #[DataProvider('globalMissionUrls')]
    public function test_ministerial_fellowship_is_hidden_on_global_mission_domains(string $url): void
    {
        $response = $this->get($url);

        $response->assertOk();
        $this->assertStringNotContainsString('Ministerial Fellowship', $this->navigationMarkup($response->getContent()));
    }

    public function test_ministerial_fellowship_is_shown_on_ministries_domain(): void
    {
        $response = $this->get('https://johnnydavisministries.org/');

        $response->assertOk();
        $this->assertStringContainsString(
            'href="'.route('ministerial-fellowship').'"',
            $this->navigationMarkup($response->getContent())
        );
    }

    /**
     * Collect the markup of every <nav> menu (desktop, mobile and drawer).
     */
    private function navigationMarkup(string $html): string
    {
        preg_match_all('/<nav\b.*?<\/nav>/s', $html, $matches);

        $this->assertNotEmpty($matches[0]);

        return implode("\n", $matches[0]);
    }
}
