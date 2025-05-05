<?php
class MailHelper {
    private $apiKey;
    private $apiSecret;
    private $senderEmail;
    private $senderName;

    public function __construct() {
        $this->loadConfig();
    }

    private function loadConfig() {
        $this->apiKey = '6d2241bb09a07b0df748fbdf4bfc1d8f';
        $this->apiSecret = '277fb4c6e74821ed495d2a5605ebd737'; // Replace with your generated secret key
        $this->senderEmail = 'asma.abrougui@esprit.tn';
        $this->senderName = 'StartupConnect';
    }

    public function sendStartupConfirmationEmail($email, $startupName) {
        if (!$this->apiSecret) {
            error_log("Mailjet Secret Key not configured");
            return false;
        }

        $url = 'https://api.mailjet.com/v3.1/send';
        
        // Professional HTML email template
        $htmlContent = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Bienvenue sur StartupConnect</title>
        </head>
        <body style="margin: 0; padding: 0; font-family: 'Arial', sans-serif; line-height: 1.6; background-color: #f4f4f4;">
            <table role="presentation" style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 0;">
                        <!-- Header with Logo -->
                        <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #06A3DA;">
                            <tr>
                                <td style="padding: 30px 40px; text-align: center;">
                                    <h1 style="color: white; margin: 0; font-size: 28px;">StartupConnect</h1>
                                </td>
                            </tr>
                        </table>

                        <!-- Main Content -->
                        <table role="presentation" style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff;">
                            <tr>
                                <td style="padding: 40px;">
                                    <h2 style="color: #333333; margin-top: 0; margin-bottom: 20px;">Félicitations! 🎉</h2>
                                    
                                    <p style="color: #666666; margin-bottom: 25px;">
                                        Cher(e) entrepreneur(e),
                                    </p>
                                    
                                    <p style="color: #666666; margin-bottom: 25px;">
                                        Nous sommes ravis de vous accueillir dans l'écosystème StartupConnect. Votre startup <strong style="color: #06A3DA;">$startupName</strong> a été créée avec succès sur notre plateforme.
                                    </p>

                                    <div style="background-color: #f8f9fa; border-left: 4px solid #06A3DA; padding: 20px; margin: 30px 0;">
                                        <h3 style="color: #333333; margin-top: 0; margin-bottom: 15px;">Prochaines étapes :</h3>
                                        <ul style="color: #666666; margin: 0; padding-left: 20px;">
                                            <li style="margin-bottom: 10px;">Enrichir votre profil startup avec des informations détaillées</li>
                                            <li style="margin-bottom: 10px;">Ajouter des visuels percutants (logo, images, présentations)</li>
                                            <li style="margin-bottom: 10px;">Définir vos objectifs et indicateurs de performance</li>
                                            <li style="margin-bottom: 10px;">Explorer les opportunités de networking avec d'autres startups</li>
                                        </ul>
                                    </div>

                                    <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 30px 0;">
                                        <tr>
                                            <td align="center">
                                                <a href="https://startupconnect.com/dashboard" style="background-color: #06A3DA; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Accéder à mon Dashboard</a>
                                            </td>
                                        </tr>
                                    </table>

                                    <p style="color: #666666; margin-bottom: 25px;">
                                        Notre équipe est là pour vous accompagner dans votre développement. N'hésitez pas à nous contacter pour toute question ou assistance.
                                    </p>

                                    <p style="color: #666666; margin-bottom: 25px;">
                                        Cordialement,<br>
                                        <strong style="color: #333333;">L'équipe StartupConnect</strong>
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <!-- Footer -->
                        <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f8f9fa;">
                            <tr>
                                <td style="padding: 30px 40px; text-align: center;">
                                    <p style="color: #666666; margin: 0; font-size: 14px;">
                                        © 2025 StartupConnect. Tous droits réservés.
                                    </p>
                                    <p style="color: #666666; margin: 10px 0 0 0; font-size: 14px;">
                                        123 Rue Tunis, Tunisie, TN<br>
                                        <a href="mailto:contact@startupconnect.com" style="color: #06A3DA; text-decoration: none;">contact@startupconnect.com</a>
                                    </p>
                                    <div style="margin-top: 20px;">
                                        <a href="#" style="text-decoration: none; margin: 0 10px;"><img src="https://example.com/facebook.png" alt="Facebook" style="width: 24px;"></a>
                                        <a href="#" style="text-decoration: none; margin: 0 10px;"><img src="https://example.com/twitter.png" alt="Twitter" style="width: 24px;"></a>
                                        <a href="#" style="text-decoration: none; margin: 0 10px;"><img src="https://example.com/linkedin.png" alt="LinkedIn" style="width: 24px;"></a>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        HTML;

        // Plain text version for email clients that don't support HTML
        $textContent = "Bienvenue sur StartupConnect!\n\n" .
                      "Cher(e) entrepreneur(e),\n\n" .
                      "Nous sommes ravis de vous accueillir dans l'écosystème StartupConnect. " .
                      "Votre startup $startupName a été créée avec succès sur notre plateforme.\n\n" .
                      "Prochaines étapes :\n" .
                      "- Enrichir votre profil startup avec des informations détaillées\n" .
                      "- Ajouter des visuels percutants (logo, images, présentations)\n" .
                      "- Définir vos objectifs et indicateurs de performance\n" .
                      "- Explorer les opportunités de networking avec d'autres startups\n\n" .
                      "Notre équipe est là pour vous accompagner dans votre développement. " .
                      "N'hésitez pas à nous contacter pour toute question ou assistance.\n\n" .
                      "Cordialement,\n" .
                      "L'équipe StartupConnect\n\n" .
                      "© 2025 StartupConnect. Tous droits réservés.\n" .
                      "123 Rue Tunis, Tunisie, TN\n" .
                      "contact@startupconnect.com";

        $data = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $this->senderEmail,
                        'Name' => $this->senderName
                    ],
                    'To' => [
                        [
                            'Email' => $email,
                            'Name' => $startupName
                        ]
                    ],
                    'Subject' => "Bienvenue sur StartupConnect - Votre startup est prête à décoller! 🚀",
                    'TextPart' => $textContent,
                    'HTMLPart' => $htmlContent
                ]
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->apiKey . ':' . $this->apiSecret)
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            error_log("Email sent successfully to $email");
            return true;
        } else {
            error_log("Failed to send email. Response: " . $response);
            return false;
        }
    }

    public function isConfigured() {
        return !empty($this->apiSecret) && !empty($this->apiKey);
    }
}
?>