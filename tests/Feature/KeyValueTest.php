<?php

use App\Models\KeyValue;

test('key-values can be stored', function () {
    $this->postJson('/api/object', [
        'key1' => $value1 = 'value1',
        'key2' => $value2 = 2,
        'key3' => $value3 = false,
        'key4' => $value4 = ['value4', 4, true],
        'key5' => $value5 = ['foo' => 'value5', 'bar' => 5, 'baz' => true],
    ])->assertStatus(201);

    $this->assertDatabaseCount('key_values', 5);
    $this->assertDatabaseHas('key_values', ['key' => 'key1', 'value' => json_encode($value1)]);
    $this->assertDatabaseHas('key_values', ['key' => 'key2', 'value' => json_encode($value2)]);
    $this->assertDatabaseHas('key_values', ['key' => 'key3', 'value' => json_encode($value3)]);
    $this->assertDatabaseHas('key_values', ['key' => 'key4', 'value' => json_encode($value4)]);
    $this->assertDatabaseHas('key_values', ['key' => 'key5', 'value' => json_encode($value5)]);
});

test('value can be retrieved by key', function () {
    $twentySecondsAgo = now()->subSeconds(20)->timestamp;
    $tenSecondsAgo = now()->subSeconds(10)->timestamp;
    $fiveSecondsAgo = now()->subSeconds(5)->timestamp;
    $twoSecondsAgo = now()->subSeconds(2)->timestamp;
    $now = now()->timestamp;

    KeyValue::factory()->createMany([
        ['key' => 'key1', 'value' => 'old_value1', 'created_at' => $twentySecondsAgo],
        ['key' => 'key2', 'value' => 'value2', 'created_at' => $fiveSecondsAgo],
        ['key' => 'key1', 'value' => 'new_value1', 'created_at' => $fiveSecondsAgo],
        ['key' => 'key1', 'value' => 'latest_value1', 'created_at' => $now],
    ]);

    $this->getJson('/api/object/key1')
        ->assertStatus(200)
        ->assertExactJson([
            'key' => 'key1',
            'value' => 'latest_value1',
            'timestamp' => $now,
        ]);

    $this->getJson('/api/object/key2')
        ->assertStatus(200)
        ->assertExactJson([
            'key' => 'key2',
            'value' => 'value2',
            'timestamp' => $fiveSecondsAgo,
        ]);

    $this->getJson('/api/object/key3')
        ->assertStatus(404);

    $this->getJson("/api/object/key1?timestamp=$tenSecondsAgo")
        ->assertStatus(200)
        ->assertExactJson([
            'key' => 'key1',
            'value' => 'old_value1',
            'timestamp' => $twentySecondsAgo,
        ]);

    $this->getJson("/api/object/key1?timestamp=$twoSecondsAgo")
        ->assertStatus(200)
        ->assertExactJson([
            'key' => 'key1',
            'value' => 'new_value1',
            'timestamp' => $fiveSecondsAgo,
        ]);
});

test('key-values can be listed', function () {
    KeyValue::factory()->createMany([
        ['key' => 'key1', 'value' => 'value1', 'created_at' => $tenSecondsAgo = now()->subSeconds(10)->timestamp],
        ['key' => 'key2', 'value' => 'value2', 'created_at' => $tenSecondsAgo],
        ['key' => 'key3', 'value' => 'value3', 'created_at' => $tenSecondsAgo],
        ['key' => 'key1', 'value' => 'new_value1', 'created_at' => $fiveSecondsAgo = now()->subSeconds(5)->timestamp],
        ['key' => 'key3', 'value' => 'new_value3', 'created_at' => $now = now()->timestamp],
    ]);

    $this->getJson('/api/object/get_all_records')
        ->assertStatus(200)
        ->assertExactJson([
            [
                'key' => 'key1',
                'value' => 'new_value1',
                'timestamp' => $fiveSecondsAgo,
            ], [
                'key' => 'key2',
                'value' => 'value2',
                'timestamp' => $tenSecondsAgo,
            ], [
                'key' => 'key3',
                'value' => 'new_value3',
                'timestamp' => $now,
            ],
        ]);
});
