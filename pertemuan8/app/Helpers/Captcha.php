<?php

class Captcha
{
    private const WIDTH = 180;
    private const HEIGHT = 60;
    private const LENGTH = 6;
    private const EXPIRES_IN = 300;

    private const CHARACTERS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    public static function generate(): void
    {
        self::ensureGdAvailable();

        $code = self::generateCode();

        $_SESSION['captcha'] = [
            'hash' => password_hash(strtoupper($code), PASSWORD_DEFAULT),
            'expires_at' => time() + self::EXPIRES_IN,
        ];

        $image = imagecreatetruecolor(self::WIDTH, self::HEIGHT);

        $background = imagecolorallocate($image, 38, 139, 210);
        $textColor = imagecolorallocate($image, 255, 255, 255);
        $noiseColor = imagecolorallocate($image, 145, 205, 245);
        $borderColor = imagecolorallocate($image, 20, 95, 160);

        imagefill($image, 0, 0, $background);

        // self::drawNoiseLines($image, $noiseColor);
        // self::drawNoiseDots($image, $noiseColor);
        self::drawCode($image, $code, $textColor);
        imagerectangle(
            $image,
            0,
            0,
            self::WIDTH - 1,
            self::HEIGHT - 1,
            $borderColor
        );

        header('Content-Type: image/png');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        imagepng($image);
        imagedestroy($image);
        exit;
    }

    public static function validate(string $input): bool
    {
        $captcha = $_SESSION['captcha'] ?? null;

        if (!is_array($captcha)) {
            return false;
        }

        $hash = $captcha['hash'] ?? '';
        $expiresAt = $captcha['expires_at'] ?? 0;

        // CAPTCHA selalu dihapus setelah dicoba sehingga hanya bisa digunakan sekali.
        unset($_SESSION['captcha']);

        if (
            !is_string($hash) ||
            !is_int($expiresAt) ||
            time() > $expiresAt
        ) {
            return false;
        }

        $input = strtoupper(trim($input));

        if ($input === '' || strlen($input) !== self::LENGTH) {
            return false;
        }

        return password_verify($input, $hash);
    }

    private static function generateCode(): string
    {
        $code = '';

        for ($i = 0; $i < self::LENGTH; $i++) {
            $index = random_int(0, strlen(self::CHARACTERS) - 1);
            $code .= self::CHARACTERS[$index];
        }

        return $code;
    }

    private static function drawNoiseLines($image, $color): void
    {
        for ($i = 0; $i < 5; $i++) {
            imageline(
                $image,
                random_int(0, self::WIDTH),
                random_int(0, self::HEIGHT),
                random_int(0, self::WIDTH),
                random_int(0, self::HEIGHT),
                $color
            );
        }
    }

    private static function drawNoiseDots($image, $color): void
    {
        for ($i = 0; $i < 40; $i++) {
            imagesetpixel(
                $image,
                random_int(0, self::WIDTH - 1),
                random_int(0, self::HEIGHT - 1),
                $color
            );
        }
    }

    private static function drawCode($image, string $code, $color): void
    {
        $font = 5;
        $characterWidth = 25;
        $startX = 15;

        for ($i = 0; $i < strlen($code); $i++) {
            $x = $startX + ($i * $characterWidth);
            $y = random_int(18, 30);

            imagestring(
                $image,
                $font,
                $x,
                $y,
                $code[$i],
                $color
            );
        }
    }

    private static function ensureGdAvailable(): void
    {
        if (!extension_loaded('gd')) {
            http_response_code(500);
            exit('PHP GD extension is required to generate CAPTCHA.');
        }
    }
}
