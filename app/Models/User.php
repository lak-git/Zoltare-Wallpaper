<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Support\Str;
use DateTimeInterface;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function uploadedWallpapers()
    {
        return $this->hasMany(Wallpaper::class, 'uploaded_by');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function isAdmin(): bool
    {
        return isset($this->role) && $this->role === 'admin';
    }

    /**
     * Override createToken to persist Sanctum tokens using the SQL PersonalAccessToken
     * model. This avoids issues where the MongoDB-backed user model's relations
     * attempt to use the Mongo connection for the token model.
     */
    public function createToken(string $name, array $abilities = ['*'], ?DateTimeInterface $expiresAt = null)
    {
        $plainTextToken = sprintf('%s%s%s', config('sanctum.token_prefix', ''), Str::random(40), hash('crc32b', Str::random(8)));

        $tokenModel = new PersonalAccessToken();
        $tokenModel->name = $name;
        $tokenModel->token = hash('sha256', $plainTextToken);
        $tokenModel->abilities = $abilities;
        $tokenModel->expires_at = $expiresAt;
        // Store polymorphic relation fields explicitly so the SQL model references
        // the MongoDB user by type and id (string).
        $tokenModel->tokenable_type = static::class;
        $tokenModel->tokenable_id = (string) $this->getKey();

        $tokenModel->save();

        return new NewAccessToken($tokenModel, $tokenModel->getKey().'|'.$plainTextToken);
    }
}
