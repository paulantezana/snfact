<?php

require_once __DIR__ . '/HtmlTemplate.php';

class EmailManager
{
    public static function Send($to, $subject, $senderEmail, $senderName, $document, $files = array())
    {
        $invoiceTemplate = HtmlTemplate::Template();
        foreach($document as $key => $value)
        {
            $invoiceTemplate = str_replace('{{'.$key.'}}', $value, $invoiceTemplate);
        }

        // a random hash will be necessary to send mixed content
        $separator = md5(time());
        $separator = "==Multipart_Boundary_x{$separator}x";

        $eol = "\r\n"; // carriage return type (RFC)

        // main header (multipart mandatory)
        $headers = "From: {$senderName} <{$senderEmail}>" . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"{$separator}\"" . $eol;
        $headers .= "Content-Transfer-Encoding: 7bit" . $eol;

        // message
        $body = "--{$separator}" . $eol;
        $body .= "Content-Type: text/html; charset=\"UTF-8\"" . $eol;
        $body .= "Content-Transfer-Encoding: 7bit" . $eol;
        $body .= $invoiceTemplate . $eol;

        // attachment
        if (!empty($files)) {
            for ($i = 0; $i < count($files); $i++) {
                if (is_file($files[$i])) {
                    $fileName = basename($files[$i]);
                    $fileSize = filesize($files[$i]);
                    $fileType = mime_content_type($files[$i]);

                    $fileStream = fopen($files[$i], "rb");
                    $fileContent = fread($fileStream, $fileSize);
                    fclose($fileStream);
                    $fileContentEncoded = chunk_split(base64_encode($fileContent));

                    $body .= "--" . $separator . $eol;
                    $body .= "Content-Type: $fileType; name=\"" . $fileName . "\"" . $eol;
                    $body .= "Content-Transfer-Encoding: base64" . $eol;
                    $body .= "Content-Disposition: attachment; filename=\"" . $fileName . "\"" . $eol;
                    $body .= "X-Attachment-Id: " . rand(1000, 99999) . $eol . $eol;
                    $body .= $fileContentEncoded . $eol;
                }
            }
        }

        // $body .= "--{$separator}--";
        // $returnpath = "-f" . $senderEmail;

        $response = mail($to, $subject, $body, $headers);
        return $response;
    }
}
