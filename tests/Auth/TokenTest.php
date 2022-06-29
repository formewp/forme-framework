<?php
use Illuminate\Database\Capsule\Manager as Capsule;

beforeEach(function () {
    // set up in memory database
    $this->capsule = new Capsule();
    $this->capsule->addConnection([
        'driver'        => 'sqlite',
        'database'      => ':memory:',
        'charset'       => 'utf8',
        'collation'     => 'utf8_unicode_ci',
    ]);
    $this->capsule->setAsGlobal();
    $this->capsule->bootEloquent();
    // run the auth tokens migration (todo make this less brittle)
    $this->capsule->schema()->create('forme_auth_tokens', function ($table) {
        $table->increments('id');
        $table->string('name');
        $table->string('token');
        $table->datetime('expiry');
    });
    $this->token= new \Forme\Framework\Auth\Token();
});

test('returns a uuid token', function () {
    $result = $this->token->get('test');
    expect($result)->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/');
});

test('returns an existing token', function () {
    $initial = $this->token->get('test');
    $result  = $this->token->get('test');
    expect($result)->toBe($initial);
});

test('validates a token', function () {
    $result  = $this->token->get('test');
    expect($this->token->validate($result, 'test'))->toBe(true);
    expect($this->token->validate('invalid-garbage-token', 'test'))->toBe(false);
});

test('invalidates a token after expiry', function () {
    $result  = $this->token->get('test');
    // expire the token by setting it to 2 hours ago
    $expiry = strtotime('-2 hours', time());
    $dt     = new DateTime();
    $dt->setTimestamp($expiry);
    Capsule::table('forme_auth_tokens')->where('name', '=', 'test')->update(['expiry' => $dt->format('Y-m-d H:i:s')]);
    expect($this->token->validate($result, 'test'))->toBe(false);
});

test('returns a new token after expiry', function () {
    $result  = $this->token->get('test');
    // expire the token by setting it to 2 hours ago
    $expiry = strtotime('-2 hours', time());
    $dt     = new DateTime();
    $dt->setTimestamp($expiry);
    Capsule::table('forme_auth_tokens')->where('name', '=', 'test')->update(['expiry' => $dt->format('Y-m-d H:i:s')]);
    expect($this->token->get('test'))->not->toBe($result);
});
