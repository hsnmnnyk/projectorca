<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- Basic Input Sanitization ---
    $name = filter_var(trim($_POST["name"]), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = filter_var(trim($_POST["message"]), FILTER_SANITIZE_STRING);

    // --- Validate Email ---
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "無効なメールアドレスです。";
        exit;
    }

    // --- Prevent Header Injection ---
    if (strpos($name, "\n") !== false || strpos($name, "\r") !== false ||
        strpos($email, "\n") !== false || strpos($email, "\r") !== false) {
        http_response_code(400);
        echo "不正な入力です。";
        exit;
    }

    // --- Email Configuration ---
    $to = "hsn.mnn.yk@gmail.com"; 
    $subject = "ウェブサイトからのお問い合わせ: " . $name;

    $email_content = "お名前: " . $name . "\n";
    $email_content .= "メールアドレス: " . $email . "\n\n";
    $email_content .= "メッセージ:\n" . $message . "\n";

    $headers = "From: " . $name . " <" . $email . ">\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    if (mail($to, $subject, $email_content, $headers)) {
        header("Location: thank_you.html");
        exit;
    } else {
        http_response_code(500);
        echo "申し訳ありません。メッセージの送信中にエラーが発生しました。";
        exit;
    }

} else {
    http_response_code(405);
    echo "許可されていないメソッドです。";
    exit;
}
?>
