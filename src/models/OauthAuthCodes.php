<?php

namespace Inquid\YiiPassport\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the base model class for table "oauth_auth_codes".
 *
 * @property string $id
 * @property string $user_id
 * @property integer $client_id
 * @property string $scopes
 * @property integer $revoked
 * @property string $expires_at
 *
 * @property OauthClients $client
 */
class OauthAuthCodes extends ActiveRecord
{
    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'client'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'client_id', 'revoked'], 'required'],
            [['client_id'], 'integer'],
            [['scopes'], 'string'],
            [['expires_at'], 'safe'],
            [['user_id'], 'string', 'max' => 100],
            [['revoked'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oauth_auth_codes';
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
}
