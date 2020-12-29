<?php
namespace app\domain\models;

use app\domain\exceptions\DomainNotFoundException;
use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property int $role
 * @property string|null $marker
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const ROLE_GUEST = 1;
    const ROLE_USER = 2;
    const ROLE_ADMIN = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'role'], 'required'],
            [['role'], 'integer'],
            [['role'], 'in', 'range' => static::getRoles()],
            [['username'], 'string', 'max' => 25],
            [['password'], 'string', 'max' => 32],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'accessToken' => 'Access Token',
            'role' => 'Role',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'username',
            'role',
        ];
    }

    public function getUserTokenArray()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'role' => $this->role,
        ];
    }

    public function isGuest(): bool
    {
        return $this->role === static::ROLE_GUEST;
    }

    public function isUser(): bool {
        return $this->role === static::ROLE_USER;
    }

    public function isAdmin(): bool
    {
        return $this->role === static::ROLE_ADMIN;
    }

    public function resetAccessToken(): self
    {
        $this->accessToken = null;

        return $this;
    }

    public function generateAccessToken(): self
    {
        $this->accessToken = Yii::$app->security->generateRandomString();

        return $this;
    }

    public function resetMarker(): self
    {
        $this->marker = null;

        return $this;
    }

    public function generateMarker(): self
    {
        $this->marker = uniqid('', true);

        return $this;
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne([
            'accessToken' => $token,
        ]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Метод позволяет изменить роль пользователя
     * @param int $role
     * @return void
     */
    public function doUpdateRole(int $role)
    {
        if ($this->role != $role) {
            $this->role = $role;
            $this->update(false);
        }
    }

    public static function getRoles()
    {
        return [
            static::ROLE_GUEST,
            static::ROLE_USER,
            static::ROLE_ADMIN,
        ];
    }

    public static function getRoleOptions()
    {
        return [
            static::ROLE_GUEST => 'Гость',
            static::ROLE_USER => 'Пользователь',
            static::ROLE_ADMIN => 'Администратор',
        ];
    }

    public static function findByUsername($username): ?User
    {
        return static::findOne([
            'username' => $username,
        ]);
    }

    public static function getAll($excludedIds = null)
    {
        $query = static::find();

        if ($excludedIds == null) {
            return $query->all();
        }

        if (!is_array($excludedIds)) {
            $excludedIds = (array) $excludedIds;
        }

        return $query
            ->where(['not in', 'id', $excludedIds])
            ->all();
    }

    /**
     * @param int $id
     * @return User
     * @throws DomainNotFoundException
     */
    public static function getById(int $id): User
    {
        $user = static::findOne($id);

        if ($user === null) {
            throw new DomainNotFoundException('Запрошенный пользователь не найден.');
        }

        return $user;
    }
}
