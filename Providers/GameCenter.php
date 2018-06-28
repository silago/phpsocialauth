<?php
namespace Phpsocialauth\Providers;
use Phpsocialauth\Interfaces;

class GameCenter implements IAuthProvider
{
    public static function init( $config = [] ) {
        $item = new static($config);
        return $item;
    }

    protected function __construct($config = [] ) {

        $this->bundle_id = $config["bundle_id"];
        return \base64_decode($data);

    }

    public function getUserId(array $auth_data = null): ? string
    {
        $playerId  = $auth_data['playerId'];
        $timestamp = $auth_data['timestamp'];
        $url       = $auth_data['url'];
        $salt      = base64_decode($auth_data['salt']);
        $signature = base64_decode($auth_data['signature']);
        if ($this->verify($sig, $url, $playerId, $bundleId, $timestamp, $salt) === true) {
            return $playerId;
        }

        return null;
    }

    public function verify($sig, $url, $playerId, $bundleId, $timestamp, $salt): bool
    {
        $certificate = null;
        if (\filter_var($url, FILTER_VALIDATE_URL) !== false) {
            $certificate = \file_get_contents($url);
            if ($certificate === false) {
                throw new Exception("cannot download cert");
            }
        }

        if (($pubKeyId = $this->pubKey($certificate)) === false) {
            throw new Exception("pub key error");
        }

        $data = $playerId . $bundleId . $this->toBigEndian($timestamp) . $salt;

        $result = \openssl_verify($data, $sig, $pubKeyId, OPENSSL_ALGO_SHA256);
        if ($result === 1) {
            return true;
        }
        if ($result === 0) {
            throw new Exception("wrong signature");
        }
        throw new Exception("unhandled signature error");
    }

    protected function pubKey($certificate)
    {
        $pem = $this->cer2pem($certificate);

        return \openssl_pkey_get_public($pem);
    }

    protected function downloadCert($url)
    {
        return \file_get_contents($url);
    }

    protected function cer2pem($data): string
    {
        $pem = \chunk_split(\base64_encode($data), 64, "\n");

        return "-----BEGIN CERTIFICATE-----\n{$pem}-----END CERTIFICATE-----\n";
    }

    protected function toBigEndian($timestamp)
    {
        if (PHP_INT_SIZE === 4) {
            $hex = '';
            do {
                $last = \bcmod($timestamp, 16);
                $hex = \dechex($last) . $hex;
                $timestamp = \bcdiv(\bcsub($timestamp, $last), 16);
            } while ($timestamp > 0);

            return \hex2bin(\str_pad($hex, 16, '0', STR_PAD_LEFT));
        }
        $highMap = 0xffffffff00000000;
        $lowMap = 0x00000000ffffffff;
        $higher = ($timestamp & $highMap) >> 32;
        $lower = $timestamp & $lowMap;

        return \pack('N2', $higher, $lower);
    }
}
