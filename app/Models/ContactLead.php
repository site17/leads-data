<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactLead extends Model
{
    use HasFactory;
    protected $table = 'contacts_lead';
    protected $fillable = [
        'contact_id',
        'lead_id',

    ];
    protected $casts = [
        'contact_id' => 'integer',
        'lead_id' => 'integer',
    ];
    // Связь с моделью Contact
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    // Связь с моделью Lead
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
}
