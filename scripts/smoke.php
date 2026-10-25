<?php

/**
 * End-to-end smoke test against a running instance.
 * Run with: ./dev.sh smoke
 */
$base = getenv('APP_URL') ?: 'http://127.0.0.1:8000';
$failures = 0;

/**
 * @return array{status: int, body: string}
 */
function request(string $method, string $url, ?array $payload = null): array
{
    $headers = "Accept: application/json\r\nX-Requested-With: XMLHttpRequest\r\n";

    if ($payload !== null) {
        $headers .= "Content-Type: application/json\r\n";
    }

    $context = stream_context_create([
        'http' => [
            'method' => $method,
            'header' => $headers,
            'content' => $payload === null ? '' : json_encode($payload),
            'ignore_errors' => true,
            'timeout' => 10,
        ],
    ]);

    $body = file_get_contents($url, false, $context);

    return [
        'status' => (int) (explode(' ', $http_response_header[0] ?? 'HTTP/1.1 0')[1] ?? 0),
        'body' => $body === false ? '' : $body,
    ];
}

function check(string $label, bool $ok, string $detail = ''): void
{
    global $failures;

    if ($ok) {
        echo "  ok   {$label}\n";

        return;
    }

    $failures++;
    echo "  FAIL {$label}".($detail !== '' ? " — {$detail}" : '')."\n";
}

echo "Smoke test: {$base}\n";

$page = request('GET', $base.'/');
check('game page renders', $page['status'] === 200 && str_contains($page['body'], 'id="app"'), "status {$page['status']}");

$state = request('GET', $base.'/api/game/state');
$stateJson = json_decode($state['body'], true) ?: [];
check('state endpoint returns a player', $state['status'] === 200 && ($stateJson['player']['name'] ?? null) !== null);
check('state endpoint returns enemies', count($stateJson['enemies'] ?? []) > 0);

$chat = request('POST', $base.'/api/agent/chat', ['message' => 'What enemies are out there?']);
$chatJson = json_decode($chat['body'], true) ?: [];
check(
    'helper calls enemies_get',
    $chat['status'] === 200 && ($chatJson['tool_calls'][0]['name'] ?? null) === 'enemies_get',
    $chat['body'],
);
check('helper reply is non-empty', trim($chatJson['reply'] ?? '') !== '');

$invalid = request('POST', $base.'/api/agent/chat', ['message' => '']);
check('empty message is rejected', $invalid['status'] === 422);

if ($failures > 0) {
    echo "\n{$failures} smoke check(s) failed.\n";
    exit(1);
}

echo "All smoke checks passed.\n";
