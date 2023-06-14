<?php

use Forme\Framework\Auth\Token;
use Illuminate\Database\Capsule\Manager as Capsule;

beforeEach(function () {
    $this->token= new Token();
});

it('returns a uuid token', function () {
    $result = $this->token->get('test');
    expect($result)->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/');
});

it('returns an existing token', function () {
    $initial = $this->token->get('test');
    $result  = $this->token->get('test');
    expect($result)->toBe($initial);
});

it('validates a token', function () {
    $result  = $this->token->get('test');
    expect($this->token->validate($result, 'test'))->toBe(true);
    expect($this->token->validate('invalid-garbage-token', 'test'))->toBe(false);
});

it('invalidates a token after default expiry', function () {
    $result  = $this->token->get('test');
    // expire the token by setting it to 2 hours ago
    $expiry = strtotime('-2 hours', time());
    $dt     = new DateTime();
    $dt->setTimestamp($expiry);
    Capsule::table('forme_auth_tokens')->where('name', '=', 'test')->update(['expiry' => $dt->format('Y-m-d H:i:s')]);
    expect($this->token->validate($result, 'test'))->toBe(false);
});

it('returns a new token after default expiry', function () {
    $result  = $this->token->get('test');
    // expire the token by setting it to 2 hours ago
    $expiry = strtotime('-2 hours', time());
    $dt     = new DateTime();
    $dt->setTimestamp($expiry);
    Capsule::table('forme_auth_tokens')->where('name', '=', 'test')->update(['expiry' => $dt->format('Y-m-d H:i:s')]);
    expect($this->token->get('test'))->not->toBe($result);
});

it('invalidates a token after custom expiry', function () {
    $result  = $this->token->get('test', '+1 week');
    // expire the token by setting it to 1 week ago
    $expiry = strtotime('-1 week', time());
    $dt     = new DateTime();
    $dt->setTimestamp($expiry);
    Capsule::table('forme_auth_tokens')->where('name', '=', 'test')->update(['expiry' => $dt->format('Y-m-d H:i:s')]);
    expect($this->token->validate($result, 'test'))->toBe(false);
});

it('returns a new token after custom expiry', function () {
    $result  = $this->token->get('test', '+1 month');
    // expire the token by setting it to 1 month ago
    $expiry = strtotime('-1 month', time());
    $dt     = new DateTime();
    $dt->setTimestamp($expiry);
    Capsule::table('forme_auth_tokens')->where('name', '=', 'test')->update(['expiry' => $dt->format('Y-m-d H:i:s')]);
    expect($this->token->get('test'))->not->toBe($result);
});

it('destroys all tokens', function () {
    $this->token->get('test');
    $this->token->destroy('test');
    expect(Capsule::table('forme_auth_tokens')->where('name', '=', 'test')->where('deleted_at', '=', null)->count())->toBe(0);
});

it('tells you when the token expires', function () {
    $this->token->get('test');
    expect($this->token->expires('test'))->not->toBe(null);
    expect($this->token->expires('test'))->toBeInstanceOf(DateTime::class);
    expect($this->token->expires('test')->getTimestamp())->toBeGreaterThan(time());
});
