<?php

namespace app\modules\chat\domain\models;

use app\modules\chat\domain\exceptions\DomainNotFoundException;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property string $content
 * @property int $isCorrect
 * @property int $userId
 * @property int $userRole
 * @property string $createdAt
 * @property string $marker
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%message}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content', 'userId', 'userRole', 'createdAt', 'marker'], 'required'],
            [['isCorrect', 'userId', 'userRole'], 'integer'],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'isCorrect' => 'Is Correct',
            'userId' => 'User ID',
            'userRole' => 'User Role',
            'createdAt' => 'Created At',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'content',
            'isCorrect',
            'userId',
            'userRole',
            'createdAt' => function() {
                return (new \DateTime($this->createdAt))->format('d.m.Y H:i');
            },
            'username' => function() {
                return $this->user->username;
            },
            'marker',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }

    public function getIncorrectMessage()
    {
        return $this->hasOne(IncorrectMessage::class, ['messageId' => 'id']);
    }

    public function isCorrect()
    {
        return $this->isCorrect == true;
    }

    /**
     * Метод изменяет сотояние сообщения на некорректное
     * @return bool
     */
    public function doIncorrect(): bool
    {
        if (!$this->isCorrect()) {
            return false;
        }

        $this->isCorrect = false;
        $this->update(false);

        $incorrectMesasge = IncorrectMessage::create($this);
        $incorrectMesasge->insert(false);
        $incorrectMesasge->populateRelation('message', $this);

        $this->populateRelation('incorrectMesasge', $incorrectMesasge);

        return true;
    }

    /**
     * Метод изменяет сотояние сообщения на корректное
     * @return bool
     */
    public function doCorrect(): bool
    {
        if ($this->isCorrect()) {
            return false;
        }

        /**
         * @var $incorrectMesasge IncorrectMessage
         */
        $incorrectMesasge = $this->incorrectMessage;

        if ($incorrectMesasge != null) {
            $incorrectMesasge->delete();
        }

        $this->isCorrect = true;
        $this->update(false);

        return true;
    }

    public static function getById(int $id)
    {
        $message = static::findOne($id);

        if ($message == null) {
            throw new DomainNotFoundException('Запрошенное сообшение не найдено');
        }

        return $message;
    }

    public static function getAllIncorrectIdsArray()
    {
        $rows = IncorrectMessage::getAllFieldArray('messageId');

        $result = [];
        foreach ($rows as $row) {
            $result[] = (integer) $row['messageId'];
        }

        return $result;
    }

    public static function getAllGreaterThenId(int $id, string $marker, bool $isCorrect = null)
    {
        $query = static::find()
            ->with('user')
            ->andWhere(['>', 'id', $id])
            ->andWhere(['!=', 'marker', $marker]);

        if ($isCorrect == null) {
           return $query->all();
        }

        return $query
            ->andWhere(['isCorrect' => $isCorrect])
            ->all();
    }

    public static function getAll()
    {
        return static::find()
            ->with('user')
            ->all();
    }

    public static function getAllByIsCorrect(bool $isCorrect)
    {
        return static::find()
            ->with('user')
            ->where([
                'isCorrect' => $isCorrect,
            ])
            ->all();
    }
}
