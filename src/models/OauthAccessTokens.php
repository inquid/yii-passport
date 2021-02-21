<?php

namespace Inquid\YiiPassport\models;

use mootensai\relation\RelationTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the base model class for table "oauth_access_tokens".
 *
 * @property string $id
 * @property string $user_id
 * @property integer $client_id
 * @property string $name
 * @property string $scopes
 * @property integer $revoked
 * @property string $created_at
 * @property string $updated_at
 * @property string $expires_at
 *
 * @property OauthClients $client
 * @property OauthRefreshTokens[] $oauthRefreshTokens
 */
class OauthAccessTokens extends ActiveRecord
{
    use RelationTrait;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'client',
            'oauthRefreshTokens'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'revoked'], 'required'],
            [['client_id'], 'integer'],
            [['scopes'], 'string'],
            [['created_at', 'updated_at', 'expires_at'], 'safe'],
            [['user_id'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 255],
            [['revoked'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oauth_access_tokens';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User Id',
            'client_id' => 'Client Id',
            'name' => 'Name',
            'scopes' => 'Scopes',
            'revoked' => 'Revoked',
            'expires_at' => 'Expires At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(OauthClients::class, ['id' => 'client_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOauthRefreshTokens()
    {
        return $this->hasMany(OauthRefreshTokens::class, ['access_token_id' => 'id']);
    }
}
