<?php


namespace Inquid\YiiPassport\controllers;

use Inquid\YiiPassport\models\OauthClients;
use sizeg\jwt\Jwt;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;

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
     * {@inheritDoc}
     */
    public function behaviors()
    {
        /*$behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
            'optional' => [
                'login'
            ],
        ];*/

        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'token' => ['post']
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionToken(): string {
        $request = Yii::$app->request;
        $data = json_decode($request->getRawBody(), true)['data']['attributes'];

        $client = OauthClients::find()
            ->where([
                'id' => $data['client_id'],
                'secret' => $data['secret'],
            ])
            ->select(['id'])
            ->one();

        return json_encode([
            "token_type" => "Bearer",
            "expires_in" => $this->tokenExpiresIn,
            "access_token" => $this->getToken($client, $data)
        ]);
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
}
