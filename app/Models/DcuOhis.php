<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DcuOhis extends Model
{
    use HasFactory;

    protected $table = 'dcu_ohis';

    protected $fillable = [
        'dcu_id',

        'di_1_1', 'di_1_2', 'di_1_3',
        'di_2_1', 'di_2_2', 'di_2_3',

        'ci_1_1', 'ci_1_2', 'ci_1_3',
        'ci_2_1', 'ci_2_2', 'ci_2_3',

        'di_score',
        'ci_score',
        'ohis_score',
        'ohis_status',
        'notes',
    ];

    protected $casts = [
        'di_1_1' => 'decimal:1', 'di_1_2' => 'decimal:1', 'di_1_3' => 'decimal:1',
        'di_2_1' => 'decimal:1', 'di_2_2' => 'decimal:1', 'di_2_3' => 'decimal:1',
        'ci_1_1' => 'decimal:1', 'ci_1_2' => 'decimal:1', 'ci_1_3' => 'decimal:1',
        'ci_2_1' => 'decimal:1', 'ci_2_2' => 'decimal:1', 'ci_2_3' => 'decimal:1',
        'di_score' => 'decimal:2',
        'ci_score' => 'decimal:2',
        'ohis_score' => 'decimal:2',
    ];

    public function dcu(): BelongsTo
    {
        return $this->belongsTo(Dcu::class, 'dcu_id');
    }

    public function calculateDiScore(): float
    {
        $total = ($this->di_1_1 ?? 0) + ($this->di_1_2 ?? 0) + ($this->di_1_3 ?? 0) +
                 ($this->di_2_1 ?? 0) + ($this->di_2_2 ?? 0) + ($this->di_2_3 ?? 0);
        
        return round($total / 6, 2);
    }

    public function calculateCiScore(): float
    {
        $total = ($this->ci_1_1 ?? 0) + ($this->ci_1_2 ?? 0) + ($this->ci_1_3 ?? 0) +
                 ($this->ci_2_1 ?? 0) + ($this->ci_2_2 ?? 0) + ($this->ci_2_3 ?? 0);
        
        return round($total / 6, 2);
    }

    public function calculateOhisScore(): array
    {
        $diScore = $this->calculateDiScore();
        $ciScore = $this->calculateCiScore();
        $ohisScore = round($diScore + $ciScore, 2);

        $status = match(true) {
            $ohisScore <= 1.2 => 'Baik',
            $ohisScore <= 3.0 => 'Sedang',
            default => 'Buruk',
        };

        return [
            'di_score' => $diScore,
            'ci_score' => $ciScore,
            'ohis_score' => $ohisScore,
            'ohis_status' => $status,
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $scores = $model->calculateOhisScore();
            $model->di_score = $scores['di_score'];
            $model->ci_score = $scores['ci_score'];
            $model->ohis_score = $scores['ohis_score'];
            $model->ohis_status = $scores['ohis_status'];
        });
    }
}