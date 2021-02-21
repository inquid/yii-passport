<?php

namespace Inquid\YiiPassport\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the base model class for table "oauth_personal_access_clients".
 *
 * @property integer $id
 * @property integer $client_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property OauthClients $client
 */
class OauthPersonalAccessClients extends ActiveRecord
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
            [['client_id'], 'required'],
            [['client_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oauth_personal_access_clients';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client Id',
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
