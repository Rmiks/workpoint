<?php

interface socAuthProviderInterface
{

    public function getSignInUrl();

    public function handleAuthentication( array $request );

    public function getUserData();

    public function getProfileImage();

    public function getProfileLink();

    public function getAuthenticationTokenString();

    public function getAccessToken();

    public function getName();

    public function getEmail();

}