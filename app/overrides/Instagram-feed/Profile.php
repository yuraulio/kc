<?php


namespace Dymantic\InstagramFeed;


use Dymantic\InstagramFeed\Exceptions\AccessTokenRequestException;
use Dymantic\InstagramFeed\Exceptions\RequestTokenException;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Profile extends Model
{
    const CACHE_KEY_BASE = 'dymantic_instagram_feed';
    const CACHE_KEY_BASE_STORIES = 'dymantic_instagram_feed_stories';
    protected $table = 'dymantic_instagram_basic_profiles';

    protected $guarded = [];

    public function cacheKey($type = 'posts')
    {
        if($type == 'posts'){
            return static::CACHE_KEY_BASE . ":" . config('services.instagram.profile');
        }else{
            return static::CACHE_KEY_BASE_STORIES . ":" . config('services.instagram.profile');
        }
        // return static::CACHE_KEY_BASE . ":" . $this->id;

    }

    public static function new(string $username): self
    {
        return self::create(['username' => $username]);
    }

    public static function for(string $username): ?self
    {
        $result = static::where('username', $username)->first();

        if($result == null){
            $result = \Dymantic\InstagramFeed\Profile::new($username);
        }

        return static::where('username', $username)->first();
    }

    public static function usingIdentityToken(string $token): ?self
    {
        return tap(static::where('identity_token', $token)->first(), function($profile) {
            if($profile) {
                $profile->identity_token = null;
                $profile->save();
            }
        });
    }

    public function getInstagramAuthUrl(): string
    {
        $instagram = App::make(Instagram::class);

        if(!$this->identity_token) {
            $this->identity_token = Str::random(16);
            $this->save();
        }

        return $instagram->authUrlForProfile($this);
    }

    public function tokens()
    {
        return $this->hasMany(AccessToken::class);
    }

    /**
     * @param  $request
     * @return AccessToken
     * @throws AccessTokenRequestException
     * @throws RequestTokenException
     */
    public function requestToken($request)
    {
        if ($request->has('error') || !$request->has('code')) {
            throw new RequestTokenException('Unable to get request token');
        }

        $instagram = App::make(Instagram::class);

        try {
            $token_details = $instagram->requestTokenForProfile($this, $request);
            $user_details = $instagram->fetchUserDetails($token_details);
            $token = $instagram->exchangeToken($token_details);
        } catch (Exception $e) {
            throw new AccessTokenRequestException($e->getMessage());
        }

        return $this->setToken(array_merge(['access_token' => $token['access_token']], $user_details));
    }


    public function refreshToken()
    {
        $instagram = App::make(Instagram::class);
        $token = $this->accessToken();
        $new_token = $instagram->refreshToken($token);
        $this->latestToken()->update(['access_code' => $new_token['access_token']]);
    }


    protected function setToken($token_details)
    {
        $this->tokens->each->delete();

        return AccessToken::createFromResponseArray($this, $token_details);
    }

    public function hasInstagramAccess(): bool
    {
        return !! $this->latestToken();
    }

    public function latestToken(): ?AccessToken
    {
        return $this->tokens()->latest()->first();
    }

    public function accessToken()
    {
        return $this->latestToken()->access_code ?? null;
    }

    public function clearToken()
    {
        $this->tokens->each->delete();
    }

    public function feed($limit = 20, $type = "posts"): InstagramFeed
    {

        // if(!$this->latestToken()) {
        //     return InstagramFeed::empty();
        // }
        if (Cache::has($this->cacheKey($type))) {
            return new InstagramFeed($this, Cache::get($this->cacheKey($type)));
        }

        //Cache::forget($this->cacheKey($type));


        $instagram = App::make(Instagram::class);

        try {
            if($type == "posts"){
                $feed = $instagram->fetchMedia($limit);
            }else{
                $feed = $instagram->fetchStories($limit);
            }


            // Remember for 3 hours and fetch again
            Cache::put($this->cacheKey($type), $feed,$seconds = 10800);

            return new InstagramFeed($this, $feed);
        } catch (Exception $e) {
            return InstagramFeed::empty();
        }
    }

    public function refreshFeed($limit = 20): InstagramFeed
    {
        $instagram = App::make(Instagram::class);
        $new_feed = $instagram->fetchMedia($this->latestToken(), $limit);

        Cache::forget($this->cacheKey());
        Cache::forever($this->cacheKey(), $new_feed);

        return $this->feed();
    }

    public function viewData(): array
    {
        $token = $this->tokens->first();
        return [
            'name'         => $this->username,
            'username'     => $token->username ?? '',
            'fullname'     => $token->user_fullname ?? '',
            'avatar'       => $token->user_profile_picture ?? '',
            'has_auth'     => $this->hasInstagramAccess(),
            'get_auth_url' => $this->getInstagramAuthUrl()
        ];
    }
}
