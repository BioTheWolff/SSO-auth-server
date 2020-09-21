<?php declare(strict_types=1);

namespace App\Controllers;

require_once(dirname(__DIR__) . '/../includes/init.php');

use App\Session;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\Uri;
use Firebase\JWT\JWT;
use UnexpectedValueException;
use function array_search;
use function give_render;
use function look_up_param;

class CASController {

    /**
     * Page to retrieve the pubkey (deactivated by default)
     * @param ServerRequestInterface $request
     * @return TextResponse
     */
    public function givePubkey(ServerRequestInterface $request) : ResponseInterface {
        return new TextResponse(PUBLIC_KEY);
    }

    /**
     * Tries to create a JWT and redirects to the broker site
     * @param ServerRequestInterface $request
     * @return RedirectResponse
     */
    public function handleRedirect(ServerRequestInterface $request) : ResponseInterface {
        $query = $request->getUri()->getQuery();

        // No URL
        $res = look_up_param($query, "url");
        if ($res === null) return new HtmlResponse(give_render('sso/fail_no_url'), 400);
        
        $return_uri = new Uri($res);

        $scheme = $return_uri->getScheme();
        $host = $return_uri->getHost();

        // Invalid URL
        if (empty($scheme) || empty($host)) {
            return new HtmlResponse(give_render('sso/fail_invalid_url'), 400);
        }

        // Broker refused because not in list
        if (array_search($host, ACCEPTED_BROKERS) === false) {
            return new RedirectResponse($res . '?status=refused');
        }

        $payload = array(
            "iss" => AUTH_SERVER_HOSTNAME,
            "aud" => $res,
            "iat" => time(),
            "uid" => Session::get_user_value('id'),
            "username" => Session::get_user_value('username'),
            "email" => Session::get_user_value('email'),
        );
        
        $jwt = JWT::encode($payload, PRIVATE_KEY, ALGORITHM);

        return new RedirectResponse($res . "?status=success&token=$jwt");
    }

    /**
     * Verifies a JWT issued by this CAS
     * @param ServerRequestInterface $request
     * @return JsonResponse
     */
    public function verifyToken(ServerRequestInterface $request) : ResponseInterface {
        $params = $request->getQueryParams();
        $token = null;
        $flag = false;

        if (in_array(CAS_VERIFY . '?token', array_keys($params))) {
            $token = $params[CAS_VERIFY . '?token'];
            $flag = true;
        } else if (in_array('?token', array_keys($params))) {
            $token = $params['?token'];
            $flag = true;
        }

        $response = [];
        $status_code = 400;
        if ($flag == true) {
            $response['status'] = 'failure';

            try {

                $result = JWT::decode($token, PUBLIC_KEY, array(ALGORITHM));
                foreach ($result as $k => $v) {
                    $response[$k] = $v;
                }

                if ($result->iss == AUTH_SERVER_HOSTNAME) {
                    $response['status'] = 'valid'; // override the default status "failure"
                    $status_code = 200;
                } else {
                    $response['error'] = 'wrong_issuer';
                }

            } catch (SignatureInvalidException $e) {
                $response['error'] = 'invalid_signature';
            } catch (BeforeValidException $e) {
                $response['error'] = 'before_valid';
            } catch (ExpiredException $e) {
                $response['error'] = 'expired';
            } catch (UnexpectedValueException $e) {
                $response['error'] = "invalid: " . $e->getMessage();
            }

        } else {
            $response['status'] = 'no_token';
        }

        return new JsonResponse($response, $status_code);

    }

}