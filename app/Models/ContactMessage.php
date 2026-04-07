<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'subject',
        'message',
        'is_read',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getSubjectLabelAttribute(): string
    {
        return match ($this->subject) {
            'general'    => 'General Inquiry',
            'donation'   => 'Donation Question',
            'volunteer'  => 'Volunteer Opportunities',
            'partnership'=> 'Church Partnership',
            'disaster'   => 'Disaster Relief',
            'other'      => 'Other',
            default      => $this->subject ?? 'No Subject',
        };
    }
}
