<?php

namespace App\QuickBooks;

use Exception;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\ReportService\ReportService;

class Client
{
    protected array $configs;
    protected ?DataService $data_service = null;
    protected ?ReportService $report_service = null;
    protected Token $token;

    public function __construct(array $configs, Token $token)
    {
        $this->configs = $configs;
        $this->setToken($token);
    }

    public function authorizationUri(): string
    {
        return $this->getDataService()
                    ->getOAuth2LoginHelper()
                    ->getAuthorizationCodeURL();
    }

    public function configureLogging(): DataService
    {
        try {
            if ($this->configs['logging']['enabled'] && dir($this->configs['logging']['location'])) {
                $this->data_service->setLogLocation($this->configs['logging']['location']);
                return $this->data_service->enableLog();
            }
        } catch (Exception $e) {
        }

        return $this->data_service->disableLog();
    }

    public function deleteToken(): self
    {
        $this->setToken($this->token->remove());
        return $this;
    }

    public function exchangeCodeForToken(string $code, int $realm_id): self
    {
        $oauth_token = $this->getDataService()
                            ->getOAuth2LoginHelper()
                            ->exchangeAuthorizationCodeForToken($code, $realm_id);

        $this->getDataService()->updateOAuth2Token($oauth_token);

        $this->token->parseOauthToken($oauth_token)->save();

        return $this;
    }

    public function getDataService(): DataService
    {
        if (!$this->hasValidAccessToken() || !isset($this->data_service)) {
            $this->data_service = $this->makeDataService();
            $this->configureLogging();
        }

        return $this->data_service;
    }

    public function getReportService(): ReportService
    {
        if (!$this->hasValidAccessToken() || !isset($this->report_service)) {
            $this->report_service = new ReportService(
                $this->getDataService()->getServiceContext()
            );
        }

        return $this->report_service;
    }

    public function hasValidAccessToken(): bool
    {
        return $this->token->hasValidAccessToken;
    }

    public function hasValidRefreshToken(): bool
    {
        return $this->token->hasValidRefreshToken;
    }

    protected function makeDataService(): DataService
    {
        $existing_keys = [
            'auth_mode'    => null,
            'baseUrl'      => null,
            'ClientID'     => null,
            'ClientSecret' => null,
        ];

        if ($this->hasValidAccessToken()) {
            return DataService::Configure(
                array_merge(
                    array_intersect_key($this->parseDataConfigs(), $existing_keys),
                    [
                        'accessTokenKey'  => $this->token->access_token,
                        'QBORealmID'      => $this->token->realm_id,
                        'refreshTokenKey' => $this->token->refresh_token,
                    ]
                )
            );
        }

        if ($this->hasValidRefreshToken()) {
            $data_service = DataService::Configure(
                array_merge(
                    array_intersect_key($this->parseDataConfigs(), $existing_keys),
                    [
                        'QBORealmID'      => $this->token->realm_id,
                        'refreshTokenKey' => $this->token->refresh_token,
                    ]
                )
            );

            $oauth_token = $data_service->getOAuth2LoginHelper()->refreshToken();
            $data_service->updateOAuth2Token($oauth_token);
            $this->token->parseOauthToken($oauth_token)->save();

            return $data_service;
        }

        return DataService::Configure($this->parseDataConfigs());
    }

    protected function parseDataConfigs(): array
    {
        return [
            'auth_mode'    => $this->configs['data_service']['auth_mode'],
            'baseUrl'      => $this->configs['data_service']['base_url'],
            'ClientID'     => $this->configs['data_service']['client_id'],
            'ClientSecret' => $this->configs['data_service']['client_secret'],
            'RedirectURI'  => route('quickbooks.token'),
            'scope'        => $this->configs['data_service']['scope'],
        ];
    }

    public function setToken(Token $token): self
    {
        $this->token = $token;
        $this->data_service = null;

        return $this;
    }
}
