<?php

namespace Inquid\YiiPassport\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the base model class for table "oauth_refresh_tokens".
 *
 * @property string $id
 * @property string $access_token_id
 * @property integer $revoked
 * @property string $expires_at
 *
 * @property OauthAccessTokens $accessToken
 */
class OauthRefreshTokens extends ActiveRecord
{
    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'accessToken'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['access_token_id', 'revoked'], 'required'],
            [['expires_at'], 'safe'],
            [['access_token_id'], 'string', 'max' => 100],
            [['revoked'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oauth_refresh_tokens';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'access_token_id' => 'Access Token Id',
            'revoked' => 'Revoked',
            'expires_at' => 'Expires At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAccessToken()
    {
        return $this->hasOne(OauthAccessTokens::class, ['id' => 'access_token_id']);
    }
}
