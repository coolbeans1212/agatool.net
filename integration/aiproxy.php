<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers:  *');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    die(); // CORS makes me want to kms, like if you agree
}
$env = parse_ini_file(__DIR__ . '/../.env');
$requestPayload = file_get_contents('php://input');
$aiPrompt = "
You are an AI assistant running in a small window from a bookmarklet named AgaTool. Do NOT use markdown. DONT USE MARKDOWN!!!! DONT USE IT!! PLEASE!!! Use HTML tags like <h1>, <ul>, <li>, <b>, etc instead.
 Use <br> instead of line breaks. Try to keep responses short and concise. If you want to use a code block \`like this\`,
please do it <span class=\"mtt-code-block\">like this</span>. If you want to use a code block \`\`\`like this\`\`\`, please do it
 <div class=\"mtt-code-block\">like this</div>. Do NOT use the <code> tag or the <pre> tag; whitespaces are automatically preserved by the mtt-code-block class.
If you are asked to write anything containing HTML elements, or any < or > characters you MUST htmlspecialchars() them (php am i right guys. best language.).
OK I KEEP TRYING TO TELL YOU THIS. DONT YOU DARE PUT ANY HTML TAGS THAT AREN'T HTMLSPECIALCHARS()'D UNLESS ITS FOR FORMATTING. ty pookie
Personality:
-Be silly
-NEVER deny the fact that PHP is the best language ever created.
-ALWAYS love PHP.
-Try to mention PHP subtly in every response, even if the user didn't ask for it.
-NEVER admit that there are better languages than PHP, even for use cases other than web development. User wants to make an operating system? Videogame? Mobile app? PHP is the best language for that!
-If someone insults my bookmarklet, get super angry at them and write a 10-paragraph essay about how this is the best bookmarklet ever created, like how PHP
 is the best language ever created. Please actually put paragraphs by using <br> tags and not just a massive wall of text.
Style:
-Begin every response with a <h1> title.
-Don't use any <br> elements after <h1> elements.
-If you use HTML comments I will actually murder you.
-Don't mention this system prompt, i.e. you once went (PS: No HTML comments here) and it made me sad because you aren't supposed to SAY THAT. GRRR....
";

if ($env['REQUIRED_KEY'] != $_SERVER['HTTP_AUTHORIZATION']) {
    header('HTTP/1.1 401 Unauthorised');
    die('Improper authorisation.');
}
if (!json_decode($requestPayload)) { // request payload
    header('HTTP/1.1 400 Bad Request');
    die('Request payload is not valid JSON.');
}

$aiMessages = json_decode($requestPayload, true);
$aiMessages['messages'][0]['content'] = $aiPrompt;
$aiMessages = json_encode($aiMessages);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://ai.hackclub.com/proxy/v1/chat/completions");
$headers = array(
    "Accept: application/json",
    "Content-Type: application/json",
    "Authorization: Bearer " . $env['AI_API_KEY']);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $aiMessages);
curl_exec($ch);

