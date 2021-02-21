<?php

declare(strict_types=1);

namespace Inquid\YiiPassport\database\factories;

use Faker\Factory;
use Inquid\YiiPassport\models\OauthClients;
use yii\base\Exception;
use yii\base\Security;

/**
 *
 */
class ClientFactory
{
    /**
     *
     * @param array $data
     * @return OauthClients
     * @throws Exception
     */
    public function generate(array $data): OauthClients {
        // use the factory to create a Faker\Generator instance
        $faker = Factory::create();

        $client = new OauthClients();
        $client->load([
            'user_id' => $data['user_id'],
            'name' => $data['name'] ?? $faker->company,
            'secret' => (new Security())->generateRandomString(40),
            'redirect' => $data['url'] ?? $faker->url,
            'personal_access_client' => $data['personal_access_client'] ?? false,
            'password_client' => $data['password_client'] ?? false,
            'revoked' => $data['revoked'] ?? false,
        ]);

        return $client;
    }
}
