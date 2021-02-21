<?php

namespace Inquid\YiiPassport\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the base model class for table "oauth_clients".
 *
 * @property integer $id
 * @property string $user_id
 * @property string $name
 * @property string $secret
 * @property string $redirect
 * @property integer $personal_access_client
 * @property integer $password_client
 * @property integer $revoked
 * @property string $created_at
 * @property string $updated_at
 *
 * @property OauthAccessTokens[] $oauthAccessTokens
 * @property OauthAuthCodes[] $oauthAuthCodes
 * @property OauthPersonalAccessClients[] $oauthPersonalAccessClients
 */
class OauthClients extends ActiveRecord
{
    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'oauthAccessTokens',
            'oauthAuthCodes',
            'oauthPersonalAccessClients'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'secret', 'redirect', 'personal_access_client', 'password_client', 'revoked'], 'required'],
            [['redirect'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id', 'secret'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 255],
            [['personal_access_client', 'password_client', 'revoked'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oauth_clients';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User Id',
            'name' => 'Name',
            'secret' => 'Secret',
            'redirect' => 'Redirect',
            'personal_access_client' => 'Personal Access Client',
            'password_client' => 'Password Client',
            'revoked' => 'Revoked',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getOauthAccessTokens()
    {
        return $this->hasMany(OauthAccessTokens::class, ['client_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOauthAuthCodes()
    {
        return $this->hasMany(OauthAuthCodes::class, ['client_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOauthPersonalAccessClients()
    {
        return $this->hasMany(OauthPersonalAccessClients::class, ['client_id' => 'id']);
    }
}
