<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $email
 * @property string $ip_address
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class CodeDownloadLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
