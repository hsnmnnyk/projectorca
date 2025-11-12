<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"]), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST["message"]), ENT_QUOTES, 'UTF-8');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "無効なメールアドレスです。";
        exit;
    }

    $mail = new PHPMailer(true);
    try {
        // --- Gmail SMTP設定 ---
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hsn.mnn.yk@gmail.com'; // あなたのGmail
        $mail->Password   = 'thbn nefb wkqc xytk'; // Gmailのアプリパスワード
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // --- エンコード設定 ---
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        // --- 管理者へのメール ---
        $mail->setFrom('hsn.mnn.yk@gmail.com', 'Project Orca Japan（仮）');
        $mail->addAddress('hsn.mnn.yk@gmail.com'); // 管理者宛て

        $mail->Subject = "【Project Orca】お問い合わせ: " . $name;
        $mail->Body    = "お名前: {$name}\nメール: {$email}\n\nメッセージ:\n{$message}";
        $mail->send();

        // --- 自動返信メール（ユーザー宛て） ---
        $userMail = new PHPMailer(true);
        $userMail->isSMTP();
        $userMail->Host       = 'smtp.gmail.com';
        $userMail->SMTPAuth   = true;
        $userMail->Username   = 'hsn.mnn.yk@gmail.com';
        $userMail->Password   = 'thbn nefb wkqc xytk';
        $userMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $userMail->Port       = 587;

        $userMail->CharSet = 'UTF-8';
        $userMail->Encoding = 'base64';

        $userMail->setFrom('hsn.mnn.yk@gmail.com', 'Project Orca Japan（仮）');
        $userMail->addAddress($email, $name);

        $userMail->Subject = "【Project Orca】お問い合わせありがとうございます";
        $userMail->Body = <<<EOT
{$name} 様

この度は Project Orca Japan（仮）へお問い合わせいただき、ありがとうございます。
以下の内容でメッセージを受け付けました。

------------------------------
お名前: {$name}
メール: {$email}

メッセージ:
{$message}
------------------------------

担当者より折り返しご連絡いたしますので、しばらくお待ちください。

Project Orca Japan（仮）
EOT;

        $userMail->send();

        header("Location: thank_you.html");
        exit;

    } catch (Exception $e) {
        echo "メール送信に失敗しました: {$mail->ErrorInfo}";
    }
} else {
    echo "許可されていないアクセスです。";
}
?>
