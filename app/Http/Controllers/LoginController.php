<?php
/**
*  Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license.
*  See LICENSE in the project root for license information.
*
*  PHP version 5
*
*  @category Code_Sample
*  @package  php-connect-sample
*  @author   Caitlin Bales <caitlin.bales@microsoft.com>
*  @license  MIT License
*  @link     http://github.com/microsoftgraph/php-connect-sample
*/
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Connect\Constants;
use Illuminate\Support\Facades\Session;

/**
*  Handles login to the application using
*  the PHP League's OAuth2 library
*
*  @class    LoginController
*  @category Code_Sample
*  @package  php-connect-sample
*  @author   Caitlin Bales <caitlin.bales@microsoft.com>
*  @license  MIT License
*  @link     http://github.com/microsoftgraph/php-connect-sample
*/
class LoginController extends Controller
{
	/**
	* Logs the user in to his or her Microsoft account
	*/
	public function oauth()
	{
		//We store user name, id, and tokens in session variables
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		$provider = new \League\OAuth2\Client\Provider\GenericProvider([
			'clientId'                => Constants::CLIENT_ID,
			'clientSecret'            => Constants::CLIENT_SECRET,
			'redirectUri'             => Constants::REDIRECT_URI,
			'urlAuthorize'            => Constants::AUTHORITY_URL . Constants::AUTHORIZE_ENDPOINT,
			'urlAccessToken'          => Constants::AUTHORITY_URL . Constants::TOKEN_ENDPOINT,
			'urlResourceOwnerDetails' => '',
			'scopes'                  => Constants::SCOPES
		]);

		if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['code'])) {
			$authorizationUrl = $provider->getAuthorizationUrl();

			// The OAuth library automaticaly generates a state value that we can
			// validate later. We just save it for now.
			$_SESSION['state'] = $provider->getState();

			header('Location: ' . $authorizationUrl);
			exit();
		} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['code'])) {
			// Validate the OAuth state parameter
			if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['state'])) {
				unset($_SESSION['state']);
				exit('State value does not match the one initially sent');
			}

			// With the authorization code, we can retrieve access tokens and other data.
			try {
				// Get an access token using the authorization code grant
				$accessToken = $provider->getAccessToken('authorization_code', [
					'code'     => $_GET['code']
				]);
				$_SESSION['access_token'] = $accessToken->getToken();

				// The id token is a JWT token that contains information about the user
				// It's a base64 coded string that has a header, payload and signature
				$idToken = $accessToken->getValues()['id_token'];
				$decodedAccessTokenPayload = base64_decode(
					explode('.', $idToken)[1]
				);
				$jsonAccessTokenPayload = json_decode($decodedAccessTokenPayload, true);

				//echo($decodedAccessTokenPayload);
				//echo($jsonAccessTokenPayload['email']);

				// The following user properties are needed in the next page
				$_SESSION['preferred_username'] = $jsonAccessTokenPayload['preferred_username'];
				$_SESSION['given_name'] = $jsonAccessTokenPayload['name'];
				$email = $jsonAccessTokenPayload['email'];
				$int = 100000;
				$cookie_name = "email";
				$cookie_value = $jsonAccessTokenPayload['email'];
				setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day


				$name = $jsonAccessTokenPayload['name'];
				$mail = $jsonAccessTokenPayload['email'];

				function splitAtUpperCase($s) {
					return preg_split('/(?=[A-Z])/', $s, -1, PREG_SPLIT_NO_EMPTY);
				}

				$upper = splitAtUpperCase($name);
				$nom = $upper;

				array_pop($nom);
				$prenom = array_pop($upper);

				$comma_separated = implode("", $upper);
				$comma_nom = implode("", $nom);


				//echo $comma_nom;
				//echo $prenom;

				$nom = trim($comma_nom);
				$prenom = $prenom;

				setcookie('nom', $nom, time() + (86400 * 30 * 30), "/"); // 30 days (en secondes)
				setcookie('prenom', $prenom, time() + (86400 * 30 * 30), "/"); // 30 days
				setcookie('mail', $mail, time() + (86400 * 30 * 30), "/"); // 30 days



				header('Location: https://arthurdufour.com/home');
				exit();
			} catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
				echo 'Something went wrong, couldn\'t get tokens: ' . $e->getMessage();
			}
		}
	}
}
