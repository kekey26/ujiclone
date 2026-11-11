<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Alat extends Model
{
    protected $table = 'alat';
    protected $fillable = [
        'kategori_id', 'nama_alat', 'stok', 'status_kondisi', 'deskripsi', 'gambar'
    ];
    
    protected function casts(): array
    {
        return [
            'stok' => 'integer',
        ];
    }

    public function getGambarUrlAttribute(): ?string
    {
        $path = $this->attributes['gambar'] ?? null;

        if (!$path) {
            return null;
        }

        $path = preg_replace('#^(?:public/)?storage/#', '', $path);

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        $legacyPath = public_path('storage/' . $path);

        if (is_file($legacyPath)) {
            return asset('storage/' . $path);
        }

        $filename = pathinfo($path, PATHINFO_FILENAME);
        foreach (['png', 'jpg', 'jpeg', 'webp'] as $extension) {
            $equipmentImage = "images/foto-alat/{$filename}.{$extension}";
            if (is_file(public_path($equipmentImage))) {
                return asset($equipmentImage);
            }
        }

        return null;
    }

    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0);
    }

    public function kategori(): BelongsTo {
        return $this->belongsTo(Kategori::class);
    }

    public function peminjaman(): HasMany {
        return $this->hasMany(DetailPinjam::class);
    }
}
