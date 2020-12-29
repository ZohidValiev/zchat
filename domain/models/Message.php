<?php
namespace app\domain\models;

use app\domain\exceptions\DomainNotFoundException;
use yii\db\ActiveQueryInterface;

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
 * @property User $user
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

    public function isCorrect()
    {
        return $this->isCorrect == true;
    }

    /**
     * Метод изменяет сотояние сообщения на некорректное
     * @return void
     */
    public function doIncorrect()
    {
        if ($this->isCorrect()) {
            $this->isCorrect = false;
            $this->update(false);
        }
    }

    /**
     * Метод изменяет сотояние сообщения на корректное
     * @return void
     */
    public function doCorrect()
    {
        if (!$this->isCorrect()) {
            $this->isCorrect = true;
            $this->update(false);
        }
    }

    public static function getAllIncorrectQuery(): ActiveQueryInterface
    {
        return static::find()
            ->with('user')
            ->where([
                'isCorrect' => false,
            ])
            ->orderBy([
                'id' => SORT_DESC,
            ]);
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
        $messages = static::find()
            ->select('[[id]]')
            ->where([
                'isCorrect' => false
            ])
            ->asArray()
            ->all();

        return array_map(function (array $message) {
            return (integer) $message['id'];
        }, $messages);
    }

    public static function getAllGreaterThenId(int $id, string $marker)
    {
        return static::find()
            ->with('user')
            ->andWhere(['>', 'id', $id])
            ->andWhere(['!=', 'marker', $marker])
            ->all();
    }

    public static function getAll()
    {
        return static::find()
            ->with('user')
            ->all();
    }

}
