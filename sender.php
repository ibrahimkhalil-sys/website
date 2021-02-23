<?php
#ini_set('display_errors', 1);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

mb_language("japanese");
mb_internal_encoding("UTF-8");

const FORM_URL    = 'https://wondertree.co.jp/recruit/entry';
const ERROR_URL   = 'https://wondertree.co.jp/recruit/entry?send=error';
const SUCCESS_URL = 'https://wondertree.co.jp/recruit/entry?send=success';

$ini = parse_ini_file('/recruit/config.ini');

$params = [
  "job"                => $_POST['job'],
  "location"           => $_POST['location'],
  "lastName"           => $_POST['lastName'],
  "firstName"          => $_POST['firstName'],
  "lastNameKana"       => $_POST['lastNameKana'],
  "firstNameKana"      => $_POST['firstNameKana'],
  "gendar"             => $_POST['gendar'],
  "year"               => $_POST['year'],
  "month"              => $_POST['month'],
  "date"               => $_POST['date'],
  "postalCode1"        => $_POST['postalCode1'],
  "postalCode2"        => $_POST['postalCode2'],
  "prefectures"        => $_POST['prefectures'],
  "addressFirst"       => $_POST['addressFirst'],
  "addressSecond"      => $_POST['addressSecond'],
  "phone1"             => $_POST['phone1'],
  "phone2"             => $_POST['phone2'],
  "phone3"             => $_POST['phone3'],
  "mail"               => $_POST['mail'],
  "work-information"   => $_POST['work-information'],
  "application-reason" => $_POST['application-reason'],
  "_checkPrivacy" => $_POST['_checkPrivacy'],
];

// パラメータチェック
$errorParams = "";
foreach($params as $key => $value) {
  if(empty($value)) {
    $errorParams = $errorParams . "&" . $key;
  }
}
if ($errorParams !== "") {
  header('Location: ' . ERROR_URL . $errorParams);
  exit;
}

// リファラチェック
if(strpos($_SERVER['HTTP_REFERER'], FORM_URL) !== 0) {
  header('Location: '.ERROR_URL . "&REFERER_ERROR");
  exit;
}

if(! sendBackup($params)) {
  header('Location: '.ERROR_URL . "&SEND_USER_ERROR");
  exit;
}

if(! sendCompany($params)) {
  header('Location: '.ERROR_URL . "&SEND_WT_ERROR");
  exit;
}

header('Location: '.SUCCESS_URL);
exit;

function sendCompany($params) {
  $from    = 'no-reply@wondertree.co.jp';
  $to      = 'recruit@wondertree.co.jp';
  $subject = '【エントリー】recruitサイトからのエントリー';
  $body    = "応募職種:{$params["job"]}\n"
            ."希望勤務地:{$params["location"]}\n"
            ."氏名:{$params["lastName"]}　{$params["firstName"]}（{$params["lastNameKana"]}　{$params["firstNameKana"]}）\n"
            ."性別:{$params["gendar"]}\n"
            ."生年月日:{$params["year"]}/{$params["month"]}/{$params["date"]}\n"
            ."郵便番号:〒{$params["postalCode1"]}-{$params["postalCode2"]}\n"
            ."住所:{$params["prefectures"]} {$params["addressFirst"]}{$params["addressSecond"]}\n"
            ."電話番号:{$params["phone1"]}-{$params["phone2"]}-{$params["phone3"]}\n"
            ."メールアドレス:{$params["mail"]}\n"
            ."職務経歴:\n"
            ."{$params["work-information"]}\n"
            ."志望理由・自己PR:\n"
            ."{$params["application-reason"]}\n";
  return sendMail($to, $from, [], $subject, $body);
}

function sendBackup($params) {
  $from    = 'no-reply@wondertree.co.jp';
  $to      = $params['mail'];
  $subject = '【エントリー】株式会社ワンダーツリー_recruitサイト';
  $body    = "{$params["lastName"]} {$params["firstName"]} 様\n\n"
            . "この度は株式会社ワンダーツリーへエントリーをいただき、誠にありがとうございます。\n"
            . "\n"
            . "エントリーを受け付けました。\n"
            . "選考結果は1週間～10日前後のお時間でご連絡させていただきます。\n"
            . "今しばらくお待ちください。\n"
            . "\n"
            . "=========ご応募内容==========\n"
            ."応募職種:{$params["job"]}\n"
            ."希望勤務地:{$params["location"]}\n"
            ."氏名:{$params["lastName"]}　{$params["firstName"]}（{$params["lastNameKana"]}　{$params["firstNameKana"]}）\n"
            ."性別:{$params["gendar"]}\n"
            ."生年月日:{$params["year"]}/{$params["month"]}/{$params["date"]}\n"
            ."郵便番号:〒{$params["postalCode1"]}-{$params["postalCode2"]}\n"
            ."住所:{$params["prefectures"]} {$params["addressFirst"]}{$params["addressSecond"]}\n"
            ."電話番号:{$params["phone1"]}-{$params["phone2"]}-{$params["phone3"]}\n"
            ."メールアドレス:{$params["mail"]}\n"
            ."職務経歴:\n"
            ."{$params["work-information"]}\n"
            ."志望理由・自己PR:\n"
            ."{$params["application-reason"]}\n";
  return sendMail($to, $from, [], $subject, $body);
}

function sendMail($to, $from, $cc, $subject, $body) {

  $mailer = new PHPMailer();
  $mailer->IsSMTP();
  $mailer->Host = 'wondertree.sakura.ne.jp';
  $mailer->Encoding = "7bit";
  $mailer->CharSet = 'ISO-2022-JP';
  $mailer->SMTPAuth = TRUE;
  $mailer->Username = 'applications@wondertree.co.jp';
  $mailer->Password = 'H2e3gyMnxmTrbT2FKZrRYpMt';
  // $mailer->SMTPSecure = 'tls';
  $mailer->Port = 587;
  // $mailer->SMTPDebug = 2;//2は詳細デバッグ1は簡易デバッグ
  
  $mailer->AddAddress($to);
  $mailer->From     = $from;
  $mailer->FromName = mb_encode_mimeheader(mb_convert_encoding('株式会社ワンダーツリー',"JIS","UTF-8"));
  $mailer->Subject  = mb_encode_mimeheader(mb_convert_encoding($subject,"JIS","UTF-8"));
  $mailer->Body     = mb_convert_encoding($body,"JIS","UTF-8");
  foreach($cc as $address) {
    $mailer->AddCC($address);
  }
  
  // メール送信
  return $mailer->Send();
}
?>