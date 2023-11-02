<?php
class OTP
{
    private $otp;
    public function __construct()
    {
        $this->otp = rand(100000, 999999);
    }

    public function generateOTP()
    {
        return $this->otp;
    }
}
?>
