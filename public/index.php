<?php

header('Content-type:application/json');


require __DIR__ . "/../vendor/autoload.php";

try {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        die(json_encode([
            'error' => "Method not allowed"
        ]));
    }

    $prompt = trim($_POST['message'] ?? "");


    if (empty($prompt)) {
        http_response_code(422);
        die(json_encode([
            'error' => ""
        ]));
    }

    $client = OpenAI::client("your api key");

    $maxTokens = 64;

    $result = $client->completions()->create([
        "model" => "text-davinci-003",
        'prompt' => $prompt,
        "temperature" => 0.7,
        "max_tokens" => $maxTokens,
        "top_p" => 1,
        "frequency_penalty" => 0,
        "presence_penalty" => 0,
    ]);


    $content = htmlspecialchars($result['choices'][0]['text']);


    $content = str_replace("\n","<br/>",$content);

    echo json_encode([
        "message" => $content
    ]);

} catch (Throwable $exception) {
    http_response_code(500);
    echo json_encode([
        "error" => ""
    ]);
}




