<?php

namespace AVATOR\TurbosmsBundle\Entity;

/**
 * Class TurboSmsSent
 * @package AVATOR\TurbosmsBundle\Entity
 */
class TurboSmsSent
{
    const STATUS_SENT = 1;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $phone = '';

    /**
     * @var string
     */
    private $message_id = '';

    /**
     * @var string
     */
    private $status = '';

    /**
     * @var string
     */
    private $status_message = '';

    /**
     * @var string
     */
    private $message = '';

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime
     */
    private $updated_at;

    public function __construct()
    {
        $this->setStatus(self::STATUS_SENT);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return TurboSmsSent
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set messageId
     *
     * @param string $messageId
     *
     * @return TurboSmsSent
     */
    public function setMessageId($messageId)
    {
        $this->message_id = $messageId;

        return $this;
    }

    /**
     * Get messageId
     *
     * @return string
     */
    public function getMessageId()
    {
        return $this->message_id;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return TurboSmsSent
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set statusMessage
     *
     * @param string $statusMessage
     *
     * @return TurboSmsSent
     */
    public function setStatusMessage($statusMessage)
    {
        $this->status_message = $statusMessage;

        return $this;
    }

    /**
     * Get statusMessage
     *
     * @return string
     */
    public function getStatusMessage()
    {
        return $this->status_message;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return TurboSmsSent
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TurboSmsSent
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return TurboSmsSent
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @ORM\PrePersist
     */
    public function onInsert()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function onUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
    }

}

