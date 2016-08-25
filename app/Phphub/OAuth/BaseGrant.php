<?php

namespace Phphub\OAuth;

use League\OAuth2\Server\Entity\AccessTokenEntity;
use League\OAuth2\Server\Entity\ClientEntity;
use League\OAuth2\Server\Entity\RefreshTokenEntity;
use League\OAuth2\Server\Entity\SessionEntity;
use League\OAuth2\Server\Event\ClientAuthenticationFailedEvent;
use League\OAuth2\Server\Event\UserAuthenticationFailedEvent;
use League\OAuth2\Server\Exception\InvalidClientException;
use League\OAuth2\Server\Exception\InvalidCredentialsException;
use League\OAuth2\Server\Exception\InvalidRequestException;
use League\OAuth2\Server\Exception\ServerErrorException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Util\SecureKey;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseGrant extends AbstractGrant
{
    /**
     * Grant identifier.
     *
     * @var string
     */
    protected $identifier = null;

    /**
     * Response type.
     *
     * @var string
     */
    protected $responseType;

    /**
     * Callback to authenticate a user's name and password.
     *
     * @var callable
     */
    protected $callback;

    /**
     * Access token expires in override.
     *
     * @var int
     */
    protected $accessTokenTTL;

    /**
     * Set the callback to verify a user's username and password.
     *
     * @param callable $callback The callback function
     */
    public function setVerifyCredentialsCallback(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Return the callback function.
     *
     * @return callable
     *
     * @throws
     */
    protected function getVerifyCredentialsCallback()
    {
        if (is_null($this->callback) || ! is_callable($this->callback)) {
            throw new ServerErrorException('Null or non-callable callback set on Password grant');
        }

        return $this->callback;
    }

    /**
     * Complete the password grant.
     *
     * @return array
     *
     * @throws
     */
    public function completeFlow()
    {
        $client = $this->getClient();

        $userId = $this->getUserId($this->server->getRequest(), $this->getVerifyCredentialsCallback());

        if ($userId === false) {
            $this->server->getEventEmitter()->emit(new UserAuthenticationFailedEvent($this->server->getRequest()));
            throw new InvalidCredentialsException();
        }

        // Create a new session
        $session = new SessionEntity($this->server);
        $session->setOwner('user', $userId);
        $session->associateClient($client);

        // Generate an access token
        $accessToken = new AccessTokenEntity($this->server);
        $accessToken->setId(SecureKey::generate());
        $accessToken->setExpireTime($this->getAccessTokenTTL() + time());

        $this->server->getTokenType()->setSession($session);
        $this->server->getTokenType()->setParam('access_token', $accessToken->getId());
        $this->server->getTokenType()->setParam('expires_in', $this->getAccessTokenTTL());

        // Save everything
        $session->save();
        $accessToken->setSession($session);
        $accessToken->save();

        // Associate a refresh token if set
        if ($this->server->hasGrantType('refresh_token')) {
            $refreshToken = new RefreshTokenEntity($this->server);
            $refreshToken->setId(SecureKey::generate());
            $refreshToken->setExpireTime($this->server->getGrantType('refresh_token')->getRefreshTokenTTL() + time());
            $this->server->getTokenType()->setParam('refresh_token', $refreshToken->getId());

            $refreshToken->setAccessToken($accessToken);
            $refreshToken->save();
        }

        return $this->server->getTokenType()->generateResponse();
    }

    /**
     * 根据请求的 client_id 和 client_secret 获取 ClientEntity.
     *
     * @throws InvalidClientException
     * @throws InvalidRequestException
     *
     * @return ClientEntity
     */
    protected function getClient()
    {
        // Get the required params
        $clientId = $this->server->getRequest()->request->get('client_id', $this->server->getRequest()->getUser());
        if (is_null($clientId)) {
            throw new InvalidRequestException('client_id');
        }

        $clientSecret = $this->server->getRequest()->request->get('client_secret',
            $this->server->getRequest()->getPassword());
        if (is_null($clientSecret)) {
            throw new InvalidRequestException('client_secret');
        }

        // Validate client ID and client secret
        $client = $this->server->getClientStorage()->get(
            $clientId,
            $clientSecret,
            null,
            $this->getIdentifier()
        );

        if (($client instanceof ClientEntity) === false) {
            $this->server->getEventEmitter()->emit(new ClientAuthenticationFailedEvent($this->server->getRequest()));
            throw new InvalidClientException();
        }

        return $client;
    }

    /**
     * 获取 UserId.
     *
     * @param $request
     * @param $verifier
     *
     * @return int
     */
    abstract public function getUserId(Request $request, $verifier);
}
