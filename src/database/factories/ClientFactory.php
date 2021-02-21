<?php

namespace Inquid\YiiPassport\database\factories;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Inquid\YiiPassport\models\OauthClients;

/**
 *
 */
class ClientFactory
{



    /**
     *
     */
    public function generate(array $data): array {
        $client = new OauthClients();
        $client->load([
            'user_id' => null,
            'name' => $this->faker->company,
            'secret' => Str::random(40),
            'redirect' => $this->faker->url,
            'personal_access_client' => false,
            'password_client' => false,
            'revoked' => false,
        ]);

        return $client;
    }
}
