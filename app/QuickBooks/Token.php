<?php

namespace App\QuickBooks;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2AccessToken;

class Token extends Model
{
    protected $table = 'quickbooks_tokens';

    protected $fillable = [
        'access_token',
        'access_token_expires_at',
        'realm_id',
        'refresh_token',
        'refresh_token_expires_at',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'access_token_expires_at' => 'datetime',
            'refresh_token_expires_at' => 'datetime',
        ];
    }

    public function getHasValidAccessTokenAttribute(): bool
    {
        return $this->access_token_expires_at && Carbon::now()->lt($this->access_token_expires_at);
    }

    public function getHasValidRefreshTokenAttribute(): bool
    {
        return $this->refresh_token_expires_at && Carbon::now()->lt($this->refresh_token_expires_at);
    }

    public function parseOauthToken(OAuth2AccessToken $oauth_token): self
    {
        $this->access_token = $oauth_token->getAccessToken();
        $this->access_token_expires_at = Carbon::parse($oauth_token->getAccessTokenExpiresAt());
        $this->realm_id = $oauth_token->getRealmID();
        $this->refresh_token = $oauth_token->getRefreshToken();
        $this->refresh_token_expires_at = Carbon::parse($oauth_token->getRefreshTokenExpiresAt());

        return $this;
    }

    public function remove(): self
    {
        $user = $this->user;
        $this->delete();

        return $user->quickBooksToken()->make();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
