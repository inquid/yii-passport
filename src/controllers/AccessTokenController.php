<?php


namespace Inquid\YiiPassport\controllers;

use Inquid\YiiPassport\models\OauthClients;
use Lcobucci\JWT\Token;
use sizeg\jwt\Jwt;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\base\Exception;
use yii\di\Instance;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Request;
use yii\web\Response;

/**
 *
 */
class AccessTokenController extends Controller
{
    public $userAuthClass;

    public $userAuthId = 'id';

    const ALGORITHM = 'HS256';
    const ISSUER = 'https://inquid.dev';
    const PERMITTED = 'https://inquid.dev';

    /** @var int Lifetime of the token */
    public $tokenExpiresIn = 3600;

    /** @var string Token ID */
    public $tokenId = '4f1g23a12aa';

    /**
     * @var string Authorization header schema, default 'Bearer'
     */
    public $schema = 'Bearer';

    /**
     * @var Jwt|string|array the [[Jwt]] object or the application component ID of the [[Jwt]].
     */
    public $jwt = 'jwt';

    /**
     * {@inheritDoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
            'optional' => [
                'token  '
            ],
        ];

        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'token' => ['post']
                ],
            ],
        ];
    }

    public function init()
    {
        parent::init();

        $this->userAuthClass = Yii::$app->user->identityClass;

        $this->jwt = Instance::ensure($this->jwt, Jwt::class);
    }

    /**
     * {@inheritDoc}
     */
    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return parent::beforeAction($action);
    }

    /**
     * @return array
     */
    public function actionToken(): array {
        $request = Yii::$app->request;
        $data = json_decode($request->getRawBody(), true);

        $client = OauthClients::find()
            ->where([
                'id' => $data['client_id'],
                'secret' => $data['client_secret'],
            ])
            ->select(['id'])
            ->one();

        if ($client === null) {
            throw new Exception('Error processing your request, please check your credentials');
        }

        if ($data['grant_type'] !== 'client_credentials' && $this->authenticateWithClientRequest($request, $client)) {
            throw new Exception('Invalid Client Request');
        }

        $response = [
            "token_type" => "Bearer",
            "expires_in" => $this->tokenExpiresIn,
            "access_token" => $this->getToken($client, $data),
        ];

        if ($data['grant_type'] === 'password') {
            $response[] = ['refresh_token' => uniqid()];
        }

        return $response;
    }

    /**
     * @param Request $request
     * @param OauthClients $client
     * @return bool|null
     */
    public function authenticateWithClientRequest(Request $request, OauthClients $client)
    {
        $authHeader = $request->getHeaders()->get('Authorization');
        if ($authHeader !== null && preg_match('/^' . $this->schema . '\s+(.*?)$/', $authHeader, $matches)) {
            /** @var Token $token */
            $token = $this->loadToken($matches[1]);

            if ($token === null) {
                return null;
            }

            $client_id = $token->getClaim('client_id');

            return $client_id === $client->id;
        }

        return null;
    }

    /**
     * @param OauthClients $client
     * @param array $data
     * @return string
     */
    protected function getToken(OauthClients $client, array $data = []): string
    {
        /** @var Jwt $jwt */
        $jwt = Yii::$app->jwt;
        $signer = $jwt->getSigner(self::ALGORITHM);
        $key = $jwt->getKey();
        $now = time();

        $builder = $jwt->getBuilder()
            ->issuedBy(self::ISSUER)
            ->permittedFor(self::PERMITTED)
            ->identifiedBy($this->tokenId, true)
            ->issuedAt($now)
            ->expiresAt($now + $this->tokenExpiresIn);

        $builder->withClaim('client_id', $client->id);
        if ($data['grant_type'] === 'password') {
            $builder->withClaim('user_id', $this->authenticateUser($data)->{$this->userAuthId});
        }

        return $builder->getToken($signer, $key);
    }

    /**
     * @param array $authData
     * @return mixed
     * @throws ForbiddenHttpException
     */
    protected function authenticateUser(array $authData) {
        $condition = null;

        if(isset($authData['username'])) {
            $condition = ['username' => $authData['username']];
        }

        if(isset($authData['email'])) {
            $condition = ['email' => $authData['email']];
        }

        if ($condition === null) {
            return false;
        }

        $user = $this->userAuthClass::find()->where($condition)->one();

        if (
            $user === null ||
            !$user->validatePassword($authData['password'])
        ) {
            return false;
        }

        return $user;
    }

    /**
     * Parses the JWT and returns a token class
     * @param string $token JWT
     * @return Token|null
     */
    public function loadToken($token)
    {
        return $this->jwt->loadToken($token);
    }
}
