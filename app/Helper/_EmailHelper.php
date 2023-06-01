<?php


namespace App\Helper;


use App\Models\UserToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class _EmailHelper
{
    public function __construct()
    {

    }

    public function generateToken($user, $expired_at)
    {
        $check = UserToken::query()->where([
            'user_id' => $user->id,
        ])->where('expired_at', '>', Carbon::now())->first();
        if ($check) {
            return $check->token;
        }
        $token = UserToken::query()->create([
            'token' => Str::slug(Hash::make($user->email)),
            'user_id' => $user->id,
            'expired_at' => $expired_at
        ]);
        return $token->token;
    }

    public function sendVerification($user)
    {
        $token = $this->generateToken($user, Carbon::now()->addHours(6));
        $data = ["token" => $token];
        return $this->sendEmail($user, $data, 'email', 'Account Verification');
    }

    public function sendResetPassword($user)
    {
        $token = $this->generateToken($user, Carbon::now()->addHours(1));
        $data = ["token" => $token];
        return $this->sendEmail($user, $data, 'reset-email', 'Reset Password');
    }

    public function sendEmail($user, $data, $view, $subject)
    {
        try {
            $mail = new PHPMailer();
            // SMTP configurations
            $mail->isSMTP();
            $mail->Host = 'smtp.dreamhost.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@event.medcon.ae';
            $mail->Password = 'xnCNzMj92^LT';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->setFrom('info@event.medcon.ae', 'LinkMed');
            $mail->Sender = "info@event.medcon.ae";
            $mail->ContentType = "text/html;charset=UTF-8\r\n";
            $mail->Priority = 3;
            $mail->addCustomHeader("MIME-Version: 1.0\r\n");
            $mail->addCustomHeader("X-Mailer: PHP'" . phpversion() . "'\r\n");
            $mail->addAddress($user->email, $user->first_name);
            $mail->addReplyTo('info@event.medcon.ae', 'LinkMed');
            $mail->Subject = 'LinkMed - ' . $subject;

            $mail->isHTML(true);
            // Email body content
            $mail->Body = view($view, $data)->render();
            // Send email
            if ($mail->send()) {
                return true;
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
        return false;
    }

}
