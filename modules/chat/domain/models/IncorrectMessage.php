<?php

namespace app\modules\chat\domain\models;

use app\modules\chat\domain\exceptions\DomainNotFoundException;
use Yii;

/**
 * This is the model class for table "incorrect_message".
 *
 * @property int $id
 * @property int $messageId
 * @property string $createdAt
 */
class IncorrectMessage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%incorrect_message}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['messageId', 'createdAt'], 'required'],
            [['messageId'], 'integer'],
            [['createdAt'], 'safe'],
            [['messageId'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'messageId' => 'Message ID',
            'createdAt' => 'Created At',
        ];
    }

    public function getMessage()
    {
        return $this->hasOne(Message::class, ['id' => 'messageId']);
    }

    public static function create(Message $message)
    {
        $result = new static();
        $result->messageId = $message->id;
        $result->createdAt = (new \DateTime())->format('Y-m-d H:i:s');

        return $result;
    }

    public static function getAllFieldArray(string $field)
    {

        return static::find()
            ->select("[[$field]]")
            ->asArray()
            ->all();
    }

    public static function getAllQuery(bool $idOrderDesc = true)
    {
        return static::find()
            ->with('message.user')
            ->orderBy([
                'id' => $idOrderDesc ? SORT_DESC : SORT_ASC,
            ]);
    }

    public static function getById($id)
    {
        $result = static::find()
            ->with('message')
            ->where(['id' => $id])
            ->one();

        if ($result == null) {
            throw new DomainNotFoundException('Запрошеннй объект не найдено.');
        }

        return $result;
    }
}
