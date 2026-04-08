<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'subject',
        'body',
        'variables',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'variables' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Replace placeholders in any string with sample data.
     */
    public function renderWithSampleData(string $content, array $overrides = []): string
    {
        $replacements = array_merge([
            '{{name}}'    => 'John Doe',
            '{{email}}'   => 'john.doe@example.com',
            '{{date}}'    => now()->format('F j, Y'),
            '{{message}}' => 'This is a sample message for preview purposes.',
        ], $overrides);

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }
}
