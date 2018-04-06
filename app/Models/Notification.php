<?php

namespace App\Models;

use App\Traits\CreateTimestampable;

class Notification extends Model
{
    use CreateTimestampable;

    /**
     * Valid levels.
     *
     * @const array
     */
    const LEVELS = ['info', 'success', 'warning', 'error'];

    // Meta ========================================================================

    /**
     * The level of the notification.
     *
     * @var string
     */
    protected $level = 'info';

    /**
     * The message of the notification.
     *
     * @var string
     */
    protected $message;

    /**
     * The text for the action.
     *
     * @var string|null
     */
    protected $actionText;

    /**
     * The URL for the action.
     *
     * @var string|null
     */
    protected $actionUrl;

    /**
     * When the notification was read.
     *
     * @var \Carbon\Carbon|null
     */
    protected $readAt;

    // Relationships ===============================================================

    /**
     * The user to be notified.
     *
     * @var User
     */
    protected $user;

    // Gettets =====================================================================

    /**
     * Get the level of the notification.
     *
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * Get the message of the notification.
     *
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Get the text for the action.
     *
     * @return string|null
     */
    public function getActionText(): ?string
    {
        return $this->actionText;
    }

    /**
     * Get the action URL.
     *
     * @return string|null
     */
    public function getActionUrl(): ?string
    {
        return $this->actionUrl;
    }

    /**
     * Get the read date of the notification.
     *
     * @return \Carbon\Carbon|null
     */
    public function getReadAt(): ?\Carbon\Carbon
    {
        return $this->readAt;
    }

    /**
     * Get the user.
     *
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    // Setters =====================================================================

    /**
     * Set the level of the notification.
     *
     * @param  string $level
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setLevel(string $level): self
    {
        if (! in_array($level, static::LEVELS, true))
            throw new \InvalidArgumentException('Invalid notification level');

        $this->level = $level;

        return $this;
    }

    /**
     * Set the message of the notification.
     *
     * @param  string $message
     * @return self
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set the text for the action.
     *
     * @param  string|null $action
     * @return self
     */
    public function setActionText(string $action): self
    {
        $this->actionText = $action;

        return $this;
    }

    /**
     * Set the action URL.
     *
     * @param  string|null $url
     * @return self
     */
    public function setActionUrl(string $url): self
    {
        $this->actionUrl = $url;

        return $this;
    }

    /**
     * Set the read date of the notification.
     *
     * @param  mixed $date
     * @return self
     */
    public function setReadAt($date): self
    {
        $this->readAt = convert_to_date($date, static::DATETIME_FORMAT);

        return $this;
    }

    /**
     * Set the user.
     *
     * @param  User|array|null $user
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setUser($user): self
    {
        if (is_array($user))
            $user = User::make($user);

        if ($user !== null and ! $user instanceof User)
            throw new \InvalidArgumentException('Invalid user');

        $this->user = $user;

        return $this;
    }

    // Transformers ================================================================

    /**
     * Convert the notification to string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return rtrim(sprintf('[%s] %s', $this->getLevel(), $this->getMessage()));
    }

    /**
     * Convert the notification into its array representation.
     *
     * NOTE: By convention array keys must be in snake_case, not in camelCase or StudlyCase.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'user' => ($user = $this->getUser()) ? $user->toArray() : null,
            'level' => $this->getLevel(),
            'message' => $this->getMessage(),
            'action_text' => $this->getActionText(),
            'action_url' => $this->getActionUrl(),
        ] + $this->getCreateTimestampAsArray() + [
            'read_at' => convert_date_to_string($this->getReadAt(), static::DATETIME_FORMAT),
        ];
    }

    // Domain logic ================================================================

    /**
     * Alias of setUser().
     *
     * @param  User $user
     * @return self
     */
    public function to(User $user): self
    {
        return $this->setUser($user);
    }

    /**
     * Alias of setLevel('info')->setMessage().
     *
     * @param  string $message
     * @return self
     */
    public function info(string $message = null): self
    {
        return $this->setLevel('info')->setMessage($message);
    }

    /**
     * Alias of setLevel('success')->setMessage().
     *
     * @param  string $message
     * @return self
     */
    public function success(string $message = null): self
    {
        return $this->setLevel('success')->setMessage($message);
    }

    /**
     * Alias of setLevel('warning')->setMessage().
     *
     * @param  string $message
     * @return self
     */
    public function warning(string $message = null): self
    {
        return $this->setLevel('warning')->setMessage($message);
    }

    /**
     * Alias of setLevel('error')->setMessage().
     *
     * @param  string $message
     * @return self
     */
    public function error(string $message = null): self
    {
        return $this->setLevel('error')->setMessage($message);
    }

    /**
     * Set the action text and URL.
     *
     * @param  string $action
     * @param  string $url
     * @return self
     */
    public function action(string $action, string $url): self
    {
        return $this->setActionText($action)->setActionUrl($url);
    }

    /**
     * Determine whether or not the notification has been read.
     *
     * @return bool
     */
    public function isRead(): bool
    {
        return isset($this->readAt);
    }

    /**
     * Determine whether or not the notification has been read.
     *
     * @return bool
     */
    public function isUnread(): bool
    {
        return ! $this->isRead();
    }

    /**
     * Determine whether or not the notification belongs to a given user.
     *
     * @param  User $user
     * @return bool
     */
    public function belongsTo(User $user): bool
    {
        return $this->getUser() and $user->getId() and $this->getUser()->getId() === $user->getId();
    }
}
