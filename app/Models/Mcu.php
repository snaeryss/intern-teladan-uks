<?php

namespace App\Models;

use App\Models\McuConclusion\DctkMcuConclusion;
use App\Models\McuConclusion\SdMcuConclusion;
use App\Models\McuConclusion\SmpMcuConclusion;
use App\Models\McuConclusion\SmaMcuConclusion;
use App\Models\McuEyeEar\DctkMcuEyeEar;
use App\Models\McuEyeEar\SdMcuEyeEar;
use App\Models\McuEyeEar\SmpMcuEyeEar;
use App\Models\McuEyeEar\SmaMcuEyeEar;
use App\Models\McuGeneral\DctkMcuGeneral;
use App\Models\McuGeneral\SdMcuGeneral;
use App\Models\McuHygiene\SmpMcuHygiene;
use App\Models\McuHygiene\SmaMcuHygiene;
use App\Models\McuMouth\DctkMcuMouth;
use App\Models\McuMouth\SdMcuMouth;
use App\Models\McuMouth\SmpMcuMouth;
use App\Models\McuMouth\SmaMcuMouth;
use App\Models\McuNutritionalStatus\DctkMcuNutritionalStatus;
use App\Models\McuNutritionalStatus\SdMcuNutritionalStatus;
use App\Models\McuNutritionalStatus\SmpMcuNutritionalStatus;
use App\Models\McuNutritionalStatus\SmaMcuNutritionalStatus;
use App\Models\McuVitals\SmpMcuVitals;
use App\Models\McuVitals\SmaMcuVitals;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mcu extends Model
{
    use HasFactory;

    protected $table = 'mcu';
    protected $primaryKey = 'mcu_id';

    protected $fillable = [
        'code',
        'date',
        'is_finish',
        'student_id',
        'period_id',
        'doctor_id',
        'location_id'
    ];

    protected $casts = [
        'date' => 'date',
        'is_finish' => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class, 'period_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function getNutritionalStatusAttribute()
    {
        if (!$this->relationLoaded('student')) {
            $this->load('student');
        }

        if (!$this->student) {
            return null;
        }

        $groupLevel = $this->student->school_level->getGroupLevel();

        return match ($groupLevel) {
            'dctk' => DctkMcuNutritionalStatus::where('mcu_id', $this->mcu_id)->first(),
            'sd' => SdMcuNutritionalStatus::where('mcu_id', $this->mcu_id)->first(),
            'smp' => SmpMcuNutritionalStatus::where('mcu_id', $this->mcu_id)->first(),
            'sma' => SmaMcuNutritionalStatus::where('mcu_id', $this->mcu_id)->first(),
            default => null,
        };
    }

    public function dctkNutritionalStatus(): HasOne
    {
        return $this->hasOne(DctkMcuNutritionalStatus::class, 'mcu_id', 'mcu_id');
    }

    public function sdNutritionalStatus(): HasOne
    {
        return $this->hasOne(SdMcuNutritionalStatus::class, 'mcu_id', 'mcu_id');
    }

    public function smpNutritionalStatus(): HasOne
    {
        return $this->hasOne(SmpMcuNutritionalStatus::class, 'mcu_id', 'mcu_id');
    }

    public function smaNutritionalStatus(): HasOne
    {
        return $this->hasOne(SmaMcuNutritionalStatus::class, 'mcu_id', 'mcu_id');
    }

    public function loadNutritionalStatus()
    {
        $this->load('student');

        if (!$this->student) {
            return $this;
        }

        $groupLevel = $this->student->school_level->getGroupLevel();

        $relationName = match ($groupLevel) {
            'dctk' => 'dctkNutritionalStatus',
            'sd' => 'sdNutritionalStatus',
            'smp' => 'smpNutritionalStatus',
            'sma' => 'smaNutritionalStatus',
            default => null,
        };

        if ($relationName) {
            $this->load($relationName);
        }

        return $this;
    }

    public function scopeFinished($query)
    {
        return $query->where('is_finish', true);
    }

    public function scopeUnfinished($query)
    {
        return $query->where('is_finish', false);
    }

    public function scopeWithNutritionalStatus($query, $groupLevel = null)
    {
        $query->with('student');

        if ($groupLevel) {
            $relationName = match ($groupLevel) {
                'dctk' => 'dctkNutritionalStatus',
                'sd' => 'sdNutritionalStatus',
                'smp' => 'smpNutritionalStatus',
                'sma' => 'smaNutritionalStatus',
                default => null,
            };

            if ($relationName) {
                $query->with($relationName);
            }
        }

        return $query;
    }

    public function smpVitals(): HasOne
    {
        return $this->hasOne(SmpMcuVitals::class, 'mcu_id', 'mcu_id');
    }

    public function smaVitals(): HasOne
    {
        return $this->hasOne(SmaMcuVitals::class, 'mcu_id', 'mcu_id');
    }

    public function getVitalsAttribute()
    {
        if (!$this->relationLoaded('student')) {
            $this->load('student');
        }

        if (!$this->student) {
            return null;
        }

        $groupLevel = $this->student->school_level->getGroupLevel();

        return match ($groupLevel) {
            'smp' => SmpMcuVitals::where('mcu_id', $this->mcu_id)->first(),
            'sma' => SmaMcuVitals::where('mcu_id', $this->mcu_id)->first(),
            default => null,
        };
    }

    public function loadVitals()
    {
        $this->load('student');

        if (!$this->student) {
            return $this;
        }

        $groupLevel = $this->student->school_level->getGroupLevel();

        $relationName = match ($groupLevel) {
            'smp' => 'smpVitals',
            'sma' => 'smaVitals',
            default => null,
        };

        if ($relationName) {
            $this->load($relationName);
        }

        return $this;
    }

    public function dctkEyeEar(): HasOne
    {
        return $this->hasOne(DctkMcuEyeEar::class, 'mcu_id', 'mcu_id');
    }

    public function sdEyeEar(): HasOne
    {
        return $this->hasOne(SdMcuEyeEar::class, 'mcu_id', 'mcu_id');
    }

    public function smpEyeEar(): HasOne
    {
        return $this->hasOne(SmpMcuEyeEar::class, 'mcu_id', 'mcu_id');
    }

    public function smaEyeEar(): HasOne
    {
        return $this->hasOne(SmaMcuEyeEar::class, 'mcu_id', 'mcu_id');
    }

    public function getEyeEarAttribute()
    {
        if (!$this->relationLoaded('student')) {
            $this->load('student');
        }

        if (!$this->student) {
            return null;
        }

        $groupLevel = $this->student->school_level->getGroupLevel();

        return match ($groupLevel) {
            'dctk' => DctkMcuEyeEar::where('mcu_id', $this->mcu_id)->first(),
            'sd' => SdMcuEyeEar::where('mcu_id', $this->mcu_id)->first(),
            'smp' => SmpMcuEyeEar::where('mcu_id', $this->mcu_id)->first(),
            'sma' => SmaMcuEyeEar::where('mcu_id', $this->mcu_id)->first(),
            default => null,
        };
    }

    public function loadEyeEar()
    {
        $this->load('student');

        if (!$this->student) {
            return $this;
        }

        $groupLevel = $this->student->school_level->getGroupLevel();

        $relationName = match ($groupLevel) {
            'dctk' => 'dctkEyeEar',
            'sd' => 'sdEyeEar',
            'smp' => 'smpEyeEar',
            'sma' => 'smaEyeEar',
            default => null,
        };

        if ($relationName) {
            $this->load($relationName);
        }

        return $this;
    }

    public function dctkGeneral(): HasOne
    {
        return $this->hasOne(DctkMcuGeneral::class, 'mcu_id', 'mcu_id');
    }

    public function sdGeneral(): HasOne
    {
        return $this->hasOne(SdMcuGeneral::class, 'mcu_id', 'mcu_id');
    }

    public function getGeneralAttribute()
    {
        if (!$this->relationLoaded('student')) {
            $this->load('student');
        }

        if (!$this->student) {
            return null;
        }

        $groupLevel = $this->student->school_level->getGroupLevel();

        return match ($groupLevel) {
            'dctk' => DctkMcuGeneral::where('mcu_id', $this->mcu_id)->first(),
            'sd' => SdMcuGeneral::where('mcu_id', $this->mcu_id)->first(),
            default => null,
        };
    }

    public function loadGeneral()
    {
        $this->load('student');

        if (!$this->student) {
            return $this;
        }

        $groupLevel = $this->student->school_level->getGroupLevel();

        $relationName = match ($groupLevel) {
            'dctk' => 'dctkGeneral',
            'sd' => 'sdGeneral',
            default => null,
        };

        if ($relationName) {
            $this->load($relationName);
        }

        return $this;
    }

    public function dctkMouth(): HasOne
    {
        return $this->hasOne(DctkMcuMouth::class, 'mcu_id', 'mcu_id');
    }

    public function sdMouth(): HasOne
    {
        return $this->hasOne(SdMcuMouth::class, 'mcu_id', 'mcu_id');
    }

    public function smpMouth(): HasOne
    {
        return $this->hasOne(SmpMcuMouth::class, 'mcu_id', 'mcu_id');
    }

    public function smaMouth(): HasOne
    {
        return $this->hasOne(SmaMcuMouth::class, 'mcu_id', 'mcu_id');
    }

    public function getMouthAttribute()
    {
        if (!$this->relationLoaded('student')) {
            $this->load('student');
        }

        if (!$this->student) {
            return null;
        }

        $groupLevel = $this->student->school_level->getGroupLevel();

        return match ($groupLevel) {
            'dctk' => DctkMcuMouth::where('mcu_id', $this->mcu_id)->first(),
            'sd' => SdMcuMouth::where('mcu_id', $this->mcu_id)->first(),
            'smp' => SmpMcuMouth::where('mcu_id', $this->mcu_id)->first(),
            'sma' => SmaMcuMouth::where('mcu_id', $this->mcu_id)->first(),
            default => null,
        };
    }

    public function loadMouth()
    {
        $this->load('student');

        if (!$this->student) {
            return $this;
        }

        $groupLevel = $this->student->school_level->getGroupLevel();

        $relationName = match ($groupLevel) {
            'dctk' => 'dctkMouth',
            'sd' => 'sdMouth',
            'smp' => 'smpMouth',
            'sma' => 'smaMouth',
            default => null,
        };

        if ($relationName) {
            $this->load($relationName);
        }

        return $this;
    }

    public function smpHygiene(): HasOne
    {
        return $this->hasOne(SmpMcuHygiene::class, 'mcu_id', 'mcu_id');
    }

    public function smaHygiene(): HasOne
    {
        return $this->hasOne(SmaMcuHygiene::class, 'mcu_id', 'mcu_id');
    }

    public function getHygieneAttribute()
    {
        if (!$this->relationLoaded('student')) {
            $this->load('student');
        }

        if (!$this->student) {
            return null;
        }

        $groupLevel = $this->student->school_level->getGroupLevel();

        return match ($groupLevel) {
            'smp' => SmpMcuHygiene::where('mcu_id', $this->mcu_id)->first(),
            'sma' => SmaMcuHygiene::where('mcu_id', $this->mcu_id)->first(),
            default => null,
        };
    }

    public function loadHygiene()
    {
        $this->load('student');

        if (!$this->student) {
            return $this;
        }

        $groupLevel = $this->student->school_level->getGroupLevel();

        $relationName = match ($groupLevel) {
            'smp' => 'smpHygiene',
            'sma' => 'smaHygiene',
            default => null,
        };

        if ($relationName) {
            $this->load($relationName);
        }

        return $this;
    }

    public function dctkConclusion(): HasOne
    {
        return $this->hasOne(DctkMcuConclusion::class, 'mcu_id', 'mcu_id');
    }

    public function sdConclusion(): HasOne
    {
        return $this->hasOne(SdMcuConclusion::class, 'mcu_id', 'mcu_id');
    }

    public function smpConclusion(): HasOne
    {
        return $this->hasOne(SmpMcuConclusion::class, 'mcu_id', 'mcu_id');
    }

    public function smaConclusion(): HasOne
    {
        return $this->hasOne(SmaMcuConclusion::class, 'mcu_id', 'mcu_id');
    }

    public function getConclusionAttribute()
    {
        if (!$this->relationLoaded('student')) {
            $this->load('student');
        }

        if (!$this->student) {
            return null;
        }

        $groupLevel = $this->student->school_level->getGroupLevel();

        return match ($groupLevel) {
            'dctk' => DctkMcuConclusion::where('mcu_id', $this->mcu_id)->first(),
            'sd' => SdMcuConclusion::where('mcu_id', $this->mcu_id)->first(),
            'smp' => SmpMcuConclusion::where('mcu_id', $this->mcu_id)->first(),
            'sma' => SmaMcuConclusion::where('mcu_id', $this->mcu_id)->first(),
            default => null,
        };
    }

    public function loadConclusion()
    {
        $this->load('student');

        if (!$this->student) {
            return $this;
        }

        $groupLevel = $this->student->school_level->getGroupLevel();

        $relationName = match ($groupLevel) {
            'dctk' => 'dctkConclusion',
            'sd' => 'sdConclusion',
            'smp' => 'smpConclusion',
            'sma' => 'smaConclusion',
            default => null,
        };

        if ($relationName) {
            $this->load($relationName);
        }

        return $this;
    }
}
