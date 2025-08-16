<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_type',
        'parent_id', 
        'file_path',
        'file_name',
        'original_name',
        'file_type',
        'mime_type',
        'file_size',
        'button_text',
        'description',
        'order',
        'is_public',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'order' => 'integer',
        'is_public' => 'boolean',
    ];

    /**
     * Relación polimórfica
     */
    public function parent()
    {
        return $this->morphTo();
    }

    /**
     * Scopes para diferentes tipos de archivos
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('file_type', $type);
    }

    public function scopePdfs($query)
    {
        return $query->where('file_type', 'pdf');
    }

    public function scopeImages($query)
    {
        return $query->whereIn('file_type', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
    }

    public function scopeDocuments($query)
    {
        return $query->whereIn('file_type', ['pdf', 'doc', 'docx', 'txt', 'rtf']);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Accessors
     */
    public function getFileUrlAttribute()
    {
        return asset($this->file_path);
    }

    public function getIsImageAttribute()
    {
        return in_array($this->file_type, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
    }

    public function getIsDocumentAttribute()
    {
        return in_array($this->file_type, ['pdf', 'doc', 'docx', 'txt', 'rtf']);
    }

    public function getFileSizeHumanAttribute()
    {
        if ($this->file_size < 1024) {
            return $this->file_size . ' B';
        } elseif ($this->file_size < 1048576) {
            return round($this->file_size / 1024, 2) . ' KB';
        } else {
            return round($this->file_size / 1048576, 2) . ' MB';
        }
    }

    public function getFileIconAttribute()
    {
        $icons = [
            'pdf' => 'fa-file-pdf',
            'doc' => 'fa-file-word',
            'docx' => 'fa-file-word',
            'txt' => 'fa-file-text',
            'jpg' => 'fa-file-image',
            'jpeg' => 'fa-file-image',
            'png' => 'fa-file-image',
            'gif' => 'fa-file-image',
            'webp' => 'fa-file-image',
            'svg' => 'fa-file-image',
        ];

        return $icons[$this->file_type] ?? 'fa-file';
    }

    /**
     * Boot method para auto-rellenar campos
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($file) {
            if (empty($file->file_type) && !empty($file->file_name)) {
                $file->file_type = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
            }
        });
    }
}