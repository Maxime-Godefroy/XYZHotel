<?php 

    namespace App\Service;

    use Symfony\Contracts\HttpClient\HttpClientInterface;

    class ExchangeRateService
    {
        private const EXCHANGE_RATES = [
            'USD' => 0.93, // Dollar américain
            'GBP' => 1.17, // Livre sterling
            'JPY' => 0.0063, // Yen
            'CHF' => 1.06, // Franc suisse
        ];

        private HttpClientInterface $httpClient;

        public function __construct(HttpClientInterface $httpClient)
        {
            $this->httpClient = $httpClient;
        }

        public function convertToEuro(float $amount, string $fromCurrency): float
        {
            $fromCurrency = strtoupper($fromCurrency);

            if (!isset(self::EXCHANGE_RATES[$fromCurrency])) {
                return $amount;
            }

            $exchangeRate = self::EXCHANGE_RATES[$fromCurrency];

            return $amount * $exchangeRate;
        }
    }
